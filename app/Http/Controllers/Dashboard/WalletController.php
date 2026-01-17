<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DepositPaymentMethod;
use App\Models\CurrencyConversion;
use App\Models\Deposit;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    /**
     * Show the wallet page.
     */
    public function index()
    {
        return view('dashboard.pages.wallet');
    }

    /**
     * Show the deposit page.
     */
    public function deposit()
    {
        $paymentMethods = DepositPaymentMethod::where('is_active', true)
            ->where('allowed_for_deposit', true)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Get user's deposit history
        $deposits = Deposit::where('user_id', auth()->id())
            ->with('paymentMethod')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('dashboard.pages.deposit', compact('paymentMethods', 'deposits'));
    }

    /**
     * Show the deposit confirmation page.
     */
    public function depositConfirm(Request $request)
    {
        $paymentMethodId = $request->query('method_id');
        $amount = $request->query('amount');

        if (!$paymentMethodId || !$amount) {
            return redirect()->route('deposit.index')
                ->with('error', 'Please select a payment method and enter an amount.');
        }

        $paymentMethod = DepositPaymentMethod::findOrFail($paymentMethodId);
        $currencyConversion = CurrencyConversion::where('is_active', true)->first();
        
        // Convert USD to PKR
        $pkrAmount = $currencyConversion ? ($amount * $currencyConversion->rate) : $amount;

        return view('dashboard.pages.deposit-confirm', compact('paymentMethod', 'amount', 'pkrAmount'));
    }

    /**
     * Show the withdrawal page.
     */
    public function withdraw()
    {
        $paymentMethods = DepositPaymentMethod::where('is_active', true)
            ->where('allowed_for_withdrawal', true)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Get user's withdrawal history
        $withdrawals = Withdrawal::where('user_id', auth()->id())
            ->with('paymentMethod')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('dashboard.pages.withdraw', compact('paymentMethods', 'withdrawals'));
    }

    /**
     * Show the withdrawal confirmation page.
     */
    public function withdrawConfirm(Request $request)
    {
        $paymentMethodId = $request->query('method_id');
        $amount = $request->query('amount');

        if (!$paymentMethodId || !$amount) {
            return redirect()->route('withdraw.index')
                ->with('error', 'Please select a payment method and enter an amount.');
        }

        $paymentMethod = DepositPaymentMethod::findOrFail($paymentMethodId);
        
        // Validate amount against limits
        if ($paymentMethod->minimum_withdrawal_amount && $amount < $paymentMethod->minimum_withdrawal_amount) {
            return redirect()->route('withdraw.index')
                ->with('error', 'Amount must be at least $' . number_format($paymentMethod->minimum_withdrawal_amount, 2));
        }

        if ($paymentMethod->maximum_withdrawal_amount && $amount > $paymentMethod->maximum_withdrawal_amount) {
            return redirect()->route('withdraw.index')
                ->with('error', 'Amount cannot exceed $' . number_format($paymentMethod->maximum_withdrawal_amount, 2));
        }

        // Check user balance
        $user = auth()->user();
        if ($user->net_balance < $amount) {
            return redirect()->route('withdraw.index')
                ->with('error', 'Insufficient balance. Your available balance is $' . number_format($user->net_balance, 2));
        }

        return view('dashboard.pages.withdraw-confirm', compact('paymentMethod', 'amount'));
    }

    /**
     * Store a new withdrawal request.
     */
    public function storeWithdrawal(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:deposit_payment_methods,id',
            'amount' => 'required|numeric|min:0.01',
            'account_number' => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255',
        ], [
            'account_number.required' => 'Account number is required.',
            'account_holder_name.required' => 'Account holder name is required.',
        ]);

        try {
            DB::beginTransaction();

            $paymentMethod = DepositPaymentMethod::findOrFail($request->payment_method_id);
            $user = auth()->user();

            // Validate payment method is allowed for withdrawal
            if (!$paymentMethod->is_active || !$paymentMethod->allowed_for_withdrawal) {
                return response()->json([
                    'success' => false,
                    'message' => 'This payment method is not available for withdrawal.',
                ], 422);
            }

            // Validate amount against limits
            if ($paymentMethod->minimum_withdrawal_amount && $request->amount < $paymentMethod->minimum_withdrawal_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Amount must be at least $' . number_format($paymentMethod->minimum_withdrawal_amount, 2),
                ], 422);
            }

            if ($paymentMethod->maximum_withdrawal_amount && $request->amount > $paymentMethod->maximum_withdrawal_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Amount cannot exceed $' . number_format($paymentMethod->maximum_withdrawal_amount, 2),
                ], 422);
            }

            // Check user has sufficient balance
            if ($user->net_balance < $request->amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient balance. Your available balance is $' . number_format($user->net_balance, 2),
                ], 422);
            }

            // Deduct amount from net_balance immediately
            // We need to deduct from the appropriate wallet(s)
            // Since net_balance = fund_wallet + mining_earning + referral_earning
            // We'll deduct from fund_wallet first, then mining_earning, then referral_earning
            $remainingAmount = $request->amount;
            
            if ($user->fund_wallet > 0 && $remainingAmount > 0) {
                $deductFromFund = min($user->fund_wallet, $remainingAmount);
                $user->fund_wallet -= $deductFromFund;
                $remainingAmount -= $deductFromFund;
            }
            
            if ($user->mining_earning > 0 && $remainingAmount > 0) {
                $deductFromMining = min($user->mining_earning, $remainingAmount);
                $user->mining_earning -= $deductFromMining;
                $remainingAmount -= $deductFromMining;
            }
            
            if ($user->referral_earning > 0 && $remainingAmount > 0) {
                $deductFromReferral = min($user->referral_earning, $remainingAmount);
                $user->referral_earning -= $deductFromReferral;
                $remainingAmount -= $deductFromReferral;
            }

            // Update net balance
            $user->updateNetBalance();

            // Create withdrawal record
            $withdrawal = Withdrawal::create([
                'user_id' => $user->id,
                'deposit_payment_method_id' => $request->payment_method_id,
                'amount' => $request->amount,
                'account_holder_name' => $request->account_holder_name,
                'account_number' => $request->account_number,
                'status' => 'pending',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Your withdrawal request has been submitted. After admin approval, the money will be transferred to your account.',
                'redirect' => route('withdraw.index'),
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your withdrawal. Please try again.',
            ], 500);
        }
    }

    /**
     * Store a new deposit request.
     */
    public function storeDeposit(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:deposit_payment_methods,id',
            'amount' => 'required|numeric|min:0.01',
            'pkr_amount' => 'required|numeric|min:0.01',
            'transaction_id' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ], [
            'payment_proof.image' => 'Payment proof must be an image file.',
            'payment_proof.max' => 'Payment proof image must not exceed 5MB.',
            'account_number.required' => 'Account number is required.',
            'account_holder_name.required' => 'Account holder name is required.',
        ]);

        try {
            // Check for duplicate transaction ID for this user
            $existingDeposit = Deposit::where('user_id', auth()->id())
                ->where('transaction_id', $request->transaction_id)
                ->first();

            if ($existingDeposit) {
                return response()->json([
                    'success' => false,
                    'message' => 'A deposit with this transaction ID already exists.',
                ], 422);
            }

            // Handle file upload
            $paymentProofPath = null;
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
                
                // Create directory if it doesn't exist
                $directory = public_path('assets/deposits/payment-proofs');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                // Move the file
                if (!$file->move($directory, $fileName)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to upload payment proof. Please try again.',
                    ], 500);
                }
                
                $paymentProofPath = 'assets/deposits/payment-proofs/' . $fileName;
            }

            // Create deposit record
            $deposit = Deposit::create([
                'user_id' => auth()->id(),
                'deposit_payment_method_id' => $request->payment_method_id,
                'amount' => $request->amount,
                'pkr_amount' => $request->pkr_amount,
                'transaction_id' => $request->transaction_id,
                'account_number' => $request->account_number,
                'account_holder_name' => $request->account_holder_name,
                'payment_proof' => $paymentProofPath,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Your deposit request has been submitted for manual review. You will be notified once it is processed.',
                'redirect' => route('deposit.index'),
            ], 200);

        } catch (\Exception $e) {
            // Delete uploaded file if deposit creation failed
            if (isset($paymentProofPath) && file_exists(public_path($paymentProofPath))) {
                unlink(public_path($paymentProofPath));
            }

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your deposit. Please try again.',
            ], 500);
        }
    }
}


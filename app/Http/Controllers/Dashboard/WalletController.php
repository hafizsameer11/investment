<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DepositPaymentMethod;
use App\Models\CurrencyConversion;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    /**
     * Show the wallet page.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get all user balances
        $balances = [
            'fund_wallet' => $user->fund_wallet ?? 0,
            'mining_earning' => $user->mining_earning ?? 0,
            'referral_earning' => $user->referral_earning ?? 0,
            'net_balance' => $user->net_balance ?? 0,
        ];
        
        // Get all transactions for the user
        $transactions = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Also get deposits and withdrawals that might not have transaction records yet
        // (for backward compatibility with existing data)
        $deposits = Deposit::where('user_id', $user->id)
            ->where('status', 'approved')
            ->with('paymentMethod')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $withdrawals = Withdrawal::where('user_id', $user->id)
            ->where('status', 'approved')
            ->with('paymentMethod')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Combine all transactions into a unified list
        $allTransactions = collect();
        
        // Add transaction records
        foreach ($transactions as $transaction) {
            $allTransactions->push([
                'id' => $transaction->id,
                'type' => $transaction->type,
                'amount' => $transaction->amount,
                'description' => $transaction->description,
                'status' => $transaction->status,
                'created_at' => $transaction->created_at,
                'reference' => $transaction->reference,
            ]);
        }
        
        // Add deposits that don't have transaction records
        foreach ($deposits as $deposit) {
            // Check if transaction already exists for this deposit
            $existingTransaction = Transaction::where('reference_id', $deposit->id)
                ->where('reference_type', Deposit::class)
                ->first();
            
            if (!$existingTransaction) {
                $paymentMethodName = $deposit->paymentMethod ? $deposit->paymentMethod->name : 'Payment Method';
                $allTransactions->push([
                    'id' => 'deposit_' . $deposit->id,
                    'type' => 'deposit',
                    'amount' => $deposit->amount,
                    'description' => 'Deposit via ' . $paymentMethodName,
                    'status' => 'completed',
                    'created_at' => $deposit->approved_at ?? $deposit->created_at,
                    'reference' => $deposit,
                ]);
            }
        }
        
        // Add withdrawals that don't have transaction records
        foreach ($withdrawals as $withdrawal) {
            // Check if transaction already exists for this withdrawal
            $existingTransaction = Transaction::where('reference_id', $withdrawal->id)
                ->where('reference_type', Withdrawal::class)
                ->first();
            
            if (!$existingTransaction) {
                $paymentMethodName = $withdrawal->paymentMethod ? $withdrawal->paymentMethod->name : 'Payment Method';
                $allTransactions->push([
                    'id' => 'withdrawal_' . $withdrawal->id,
                    'type' => 'withdrawal',
                    'amount' => $withdrawal->amount,
                    'description' => 'Withdrawal via ' . $paymentMethodName,
                    'status' => 'completed',
                    'created_at' => $withdrawal->approved_at ?? $withdrawal->created_at,
                    'reference' => $withdrawal,
                ]);
            }
        }
        
        // Sort all transactions by created_at descending
        $allTransactions = $allTransactions->sortByDesc('created_at')->values();
        
        // Calculate total deposits (all-time)
        $totalDeposits = Deposit::where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('amount');
        
        // Calculate total withdrawals (all-time)
        $totalWithdrawals = Withdrawal::where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('amount');
        
        return view('dashboard.pages.wallet', [
            'balances' => $balances,
            'transactions' => $allTransactions,
            'totalDeposits' => $totalDeposits,
            'totalWithdrawals' => $totalWithdrawals,
        ]);
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
        
        // Get active currency conversion rate (fallback to any rate if no active one)
        $currencyConversion = CurrencyConversion::where('is_active', true)->first();
        if (!$currencyConversion) {
            $currencyConversion = CurrencyConversion::first();
        }
        $conversionRate = $currencyConversion ? (float) $currencyConversion->rate : 0;
        
        return view('dashboard.pages.deposit', compact('paymentMethods', 'deposits', 'conversionRate'));
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
        
        // Get active currency conversion rate (fallback to any rate if no active one)
        $currencyConversion = CurrencyConversion::where('is_active', true)->first();
        if (!$currencyConversion) {
            $currencyConversion = CurrencyConversion::first();
        }
        $conversionRate = $currencyConversion ? (float) $currencyConversion->rate : 0;
        
        return view('dashboard.pages.withdraw', compact('paymentMethods', 'withdrawals', 'conversionRate'));
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
            'payment_proof' => 'required|image', // 5MB max
        ], [  
            'account_number.required' => 'Account number is required.',
            'account_holder_name.required' => 'Account holder name is required.',
        ]);

        try {
            DB::beginTransaction();

            // Check for duplicate transaction ID for this user
            $existingDeposit = Deposit::where('user_id', auth()->id())
                ->where('transaction_id', $request->transaction_id)
                ->first();

            if ($existingDeposit) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'A deposit with this transaction ID already exists.',
                ], 422);
            }

            // Handle file upload
            $paymentProofPath = null;
            if ($request->hasFile('payment_proof')) {
                try {
                    $file = $request->file('payment_proof');
                    $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
                    
                    // Create directory if it doesn't exist
                    $directory = public_path('assets/deposits/payment-proofs');
                    
                    // Try to create directory with proper permissions
                    if (!file_exists($directory)) {
                        // Try to create the directory
                        $created = @mkdir($directory, 0755, true);
                        if (!$created) {
                            DB::rollBack();
                            Log::error('Failed to create deposit payment proofs directory', [
                                'directory' => $directory,
                                'user_id' => auth()->id(),
                                'permissions' => substr(sprintf('%o', fileperms(dirname($directory))), -4),
                            ]);
                            return response()->json([
                                'success' => false,
                                'message' => 'Failed to create upload directory. Please contact support.',
                            ], 500);
                        }
                    }
                    
                    // Ensure directory permissions are correct
                    if (file_exists($directory)) {
                        // Try to make it writable if it's not
                        if (!is_writable($directory)) {
                            @chmod($directory, 0755);
                        }
                    }
                    
                    // Check if directory is writable after attempting to fix permissions
                    if (!is_writable($directory)) {
                        DB::rollBack();
                        Log::error('Deposit payment proofs directory is not writable', [
                            'directory' => $directory,
                            'user_id' => auth()->id(),
                            'exists' => file_exists($directory),
                            'is_dir' => is_dir($directory),
                            'permissions' => file_exists($directory) ? substr(sprintf('%o', fileperms($directory)), -4) : 'N/A',
                        ]);
                        return response()->json([
                            'success' => false,
                            'message' => 'Upload directory is not writable. Please contact support.',
                        ], 500);
                    }
                    
                    // Move the file
                    if (!$file->move($directory, $fileName)) {
                        DB::rollBack();
                        Log::error('Failed to move uploaded payment proof file', [
                            'directory' => $directory,
                            'fileName' => $fileName,
                            'user_id' => auth()->id(),
                        ]);
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to upload payment proof. Please try again.',
                        ], 500);
                    }
                    
                    $paymentProofPath = 'assets/deposits/payment-proofs/' . $fileName;
                } catch (\Exception $uploadException) {
                    DB::rollBack();
                    Log::error('Exception during payment proof upload', [
                        'error' => $uploadException->getMessage(),
                        'trace' => $uploadException->getTraceAsString(),
                        'user_id' => auth()->id(),
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to upload payment proof: ' . $uploadException->getMessage(),
                    ], 500);
                }
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

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Your deposit request has been submitted for manual review. You will be notified once it is processed.',
                'redirect' => route('deposit.index'),
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            // Delete uploaded file if validation failed
            if (isset($paymentProofPath) && file_exists(public_path($paymentProofPath))) {
                @unlink(public_path($paymentProofPath));
            }
            
            // Re-throw validation exceptions to return proper validation errors
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the actual error for debugging
            Log::error('Error storing deposit', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id(),
                'request_data' => $request->except(['payment_proof']), // Don't log file data
            ]);

            // Delete uploaded file if deposit creation failed
            if (isset($paymentProofPath) && file_exists(public_path($paymentProofPath))) {
                @unlink(public_path($paymentProofPath));
            }

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your deposit. Please try again. If the problem persists, contact support.',
            ], 500);
        }
    }
}


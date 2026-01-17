<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DepositPaymentMethod;
use App\Models\CurrencyConversion;
use App\Models\Deposit;
use Illuminate\Http\Request;
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
        return view('dashboard.pages.withdraw');
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


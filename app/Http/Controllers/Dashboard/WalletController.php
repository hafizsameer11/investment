<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DepositPaymentMethod;
use App\Models\CurrencyConversion;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Transaction;
use App\Models\CryptoWallet;
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

        // Paginate transactions (5 per page)
        $currentPage = request()->get('page', 1);
        $perPage = 5;
        $total = $allTransactions->count();
        $items = $allTransactions->forPage($currentPage, $perPage)->values();
        $totalPages = (int) ceil($total / $perPage);

        $transactionsData = [
            'data' => $items,
            'current_page' => (int) $currentPage,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => $totalPages,
            'from' => $total > 0 ? (($currentPage - 1) * $perPage + 1) : 0,
            'to' => $total > 0 ? min($currentPage * $perPage, $total) : 0,
        ];
        
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
            'transactionsData' => $transactionsData,
            'totalDeposits' => $totalDeposits,
            'totalWithdrawals' => $totalWithdrawals,
        ]);
    }

    /**
     * Show the deposit page.
     */
    public function deposit()
    {
        // Order payment methods: rast first, then bank, then crypto
        $paymentMethods = DepositPaymentMethod::where('is_active', true)
            ->where('allowed_for_deposit', true)
            ->orderByRaw("CASE 
                WHEN type = 'rast' THEN 1 
                WHEN type = 'bank' THEN 2 
                WHEN type = 'crypto' THEN 3 
                ELSE 4 
            END")
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
        
        // If crypto payment method, redirect to crypto network selection
        if ($paymentMethod->type === 'crypto') {
            return redirect()->route('deposit.crypto.network', [
                'method_id' => $paymentMethodId,
                'amount' => $amount
            ]);
        }
        
        $currencyConversion = CurrencyConversion::where('is_active', true)->first();
        
        // Convert USD to PKR
        $pkrAmount = $currencyConversion ? ($amount * $currencyConversion->rate) : $amount;

        return view('dashboard.pages.deposit-confirm', compact('paymentMethod', 'amount', 'pkrAmount'));
    }

    /**
     * Show crypto deposit network selection page.
     */
    public function cryptoDepositNetwork(Request $request)
    {
        $paymentMethodId = $request->query('method_id');
        $amount = $request->query('amount');

        if (!$paymentMethodId || !$amount) {
            return redirect()->route('deposit.index')
                ->with('error', 'Please select a payment method and enter an amount.');
        }

        $paymentMethod = DepositPaymentMethod::findOrFail($paymentMethodId);
        
        // Get active crypto wallets for deposits
        $cryptoWallets = CryptoWallet::where('is_active', true)
            ->where('allowed_for_deposit', true)
            ->orderBy('network', 'asc')
            ->get();

        if ($cryptoWallets->isEmpty()) {
            return redirect()->route('deposit.index')
                ->with('error', 'No crypto wallets available for deposits.');
        }

        // Get currency conversion for display
        $currencyConversion = CurrencyConversion::where('is_active', true)->first();
        $conversionRate = $currencyConversion ? (float) $currencyConversion->rate : 0;
        $pkrAmount = $conversionRate ? ($amount * $conversionRate) : $amount;

        return view('dashboard.pages.crypto-deposit-network', compact(
            'paymentMethod',
            'amount',
            'pkrAmount',
            'cryptoWallets',
            'conversionRate'
        ));
    }

    /**
     * Show crypto deposit confirmation page with wallet address and QR code.
     */
    public function cryptoDepositConfirm(Request $request)
    {
        $paymentMethodId = $request->query('method_id');
        $amount = $request->query('amount');
        $cryptoWalletId = $request->query('crypto_wallet_id');

        if (!$paymentMethodId || !$amount || !$cryptoWalletId) {
            return redirect()->route('deposit.index')
                ->with('error', 'Please select a payment method, amount, and crypto network.');
        }

        $paymentMethod = DepositPaymentMethod::findOrFail($paymentMethodId);
        $cryptoWallet = CryptoWallet::findOrFail($cryptoWalletId);

        // Validate crypto wallet is active and allowed for deposit
        if (!$cryptoWallet->is_active || !$cryptoWallet->allowed_for_deposit) {
            return redirect()->route('deposit.index')
                ->with('error', 'Selected crypto network is not available for deposits.');
        }

        // Validate amount against limits
        if ($cryptoWallet->minimum_deposit && $amount < $cryptoWallet->minimum_deposit) {
            return redirect()->route('deposit.crypto.network', [
                'method_id' => $paymentMethodId,
                'amount' => $amount
            ])->with('error', 'Amount must be at least $' . number_format($cryptoWallet->minimum_deposit, 2));
        }

        if ($cryptoWallet->maximum_deposit && $amount > $cryptoWallet->maximum_deposit) {
            return redirect()->route('deposit.crypto.network', [
                'method_id' => $paymentMethodId,
                'amount' => $amount
            ])->with('error', 'Amount cannot exceed $' . number_format($cryptoWallet->maximum_deposit, 2));
        }

        // Get currency conversion for display
        $currencyConversion = CurrencyConversion::where('is_active', true)->first();
        $conversionRate = $currencyConversion ? (float) $currencyConversion->rate : 0;
        $pkrAmount = $conversionRate ? ($amount * $conversionRate) : $amount;

        return view('dashboard.pages.crypto-deposit-confirm', compact(
            'paymentMethod',
            'cryptoWallet',
            'amount',
            'pkrAmount',
            'conversionRate'
        ));
    }

    /**
     * Show the withdrawal page.
     */
    public function withdraw()
    {
        // Order payment methods: rast first, then bank, then crypto
        $paymentMethods = DepositPaymentMethod::where('is_active', true)
            ->where('allowed_for_withdrawal', true)
            ->orderByRaw("CASE 
                WHEN type = 'rast' THEN 1 
                WHEN type = 'bank' THEN 2 
                WHEN type = 'crypto' THEN 3 
                ELSE 4 
            END")
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
        
        // Get active crypto wallets for withdrawals (to show minimum withdrawal amounts)
        $cryptoWallets = CryptoWallet::where('is_active', true)
            ->where('allowed_for_withdrawal', true)
            ->orderBy('network', 'asc')
            ->get();
        
        // Check if user has pending withdrawal
        $hasPendingWithdrawal = Withdrawal::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->exists();
        
        return view('dashboard.pages.withdraw', compact('paymentMethods', 'withdrawals', 'conversionRate', 'cryptoWallets', 'hasPendingWithdrawal'));
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
        
        // If crypto payment method, redirect to crypto network selection
        if ($paymentMethod->type === 'crypto') {
            return redirect()->route('withdraw.crypto.network', [
                'method_id' => $paymentMethodId,
                'amount' => $amount
            ]);
        }
        
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
        // Note: Withdrawals can only use mining_earning + referral_earning (NOT fund_wallet)
        $user = auth()->user();
        $totalAvailableBalance = ($user->mining_earning ?? 0) + ($user->referral_earning ?? 0);
        if ($totalAvailableBalance < $amount) {
            return redirect()->route('withdraw.index')
                ->with('error', 'Insufficient balance. Your available withdrawal balance is $' . number_format($totalAvailableBalance, 2) . '. You can only withdraw from mining and referral earnings.');
        }

        // Check if user has pending withdrawal
        $hasPendingWithdrawal = Withdrawal::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        return view('dashboard.pages.withdraw-confirm', compact('paymentMethod', 'amount', 'hasPendingWithdrawal'));
    }

    /**
     * Show crypto withdrawal network selection page.
     */
    public function cryptoWithdrawNetwork(Request $request)
    {
        $paymentMethodId = $request->query('method_id');
        $amount = $request->query('amount');

        if (!$paymentMethodId || !$amount) {
            return redirect()->route('withdraw.index')
                ->with('error', 'Please select a payment method and enter an amount.');
        }

        $paymentMethod = DepositPaymentMethod::findOrFail($paymentMethodId);
        
        // Get active crypto wallets for withdrawals
        $cryptoWallets = CryptoWallet::where('is_active', true)
            ->where('allowed_for_withdrawal', true)
            ->orderBy('network', 'asc')
            ->get();

        if ($cryptoWallets->isEmpty()) {
            return redirect()->route('withdraw.index')
                ->with('error', 'No crypto wallets available for withdrawals.');
        }

        // Check user balance
        // User selects amount, $1 fee is deducted automatically
        // If user selects $5, they need $5 balance, fee is deducted, they receive $4
        $user = auth()->user();
        $totalAvailableBalance = ($user->mining_earning ?? 0) + ($user->referral_earning ?? 0);
        if ($totalAvailableBalance < $amount) {
            return redirect()->route('withdraw.index')
                ->with('error', 'Insufficient balance. Your available withdrawal balance is $' . number_format($totalAvailableBalance, 2) . '. You can only withdraw from mining and referral earnings.');
        }

        return view('dashboard.pages.crypto-withdraw-network', compact(
            'paymentMethod',
            'amount',
            'cryptoWallets'
        ));
    }

    /**
     * Show crypto withdrawal confirmation page with wallet address and QR code.
     */
    public function cryptoWithdrawConfirm(Request $request)
    {
        $paymentMethodId = $request->query('method_id');
        $amount = $request->query('amount');
        $cryptoWalletId = $request->query('crypto_wallet_id');

        if (!$paymentMethodId || !$amount || !$cryptoWalletId) {
            return redirect()->route('withdraw.index')
                ->with('error', 'Please select a payment method, amount, and crypto network.');
        }

        $paymentMethod = DepositPaymentMethod::findOrFail($paymentMethodId);
        $cryptoWallet = CryptoWallet::findOrFail($cryptoWalletId);

        // Validate crypto wallet is active and allowed for withdrawal
        if (!$cryptoWallet->is_active || !$cryptoWallet->allowed_for_withdrawal) {
            return redirect()->route('withdraw.index')
                ->with('error', 'Selected crypto network is not available for withdrawals.');
        }

        // Validate amount against limits
        // User selects amount, $1 fee is deducted automatically
        // If user selects $5, fee is deducted, they receive $4
        // We validate that the amount after fee meets minimum/maximum requirements
        $cryptoFee = 1.00;
        $amountAfterFee = $amount - $cryptoFee;
        
        // Ensure user selects at least $1.01 (so they receive at least $0.01 after fee)
        if ($amount < 1.01) {
            return redirect()->route('withdraw.crypto.network', [
                'method_id' => $paymentMethodId,
                'amount' => $amount
            ])->with('error', 'Amount must be at least $1.01. After $1.00 fee, you will receive $0.01.');
        }
        
        // Validate minimum withdrawal (check amount after fee)
        if ($cryptoWallet->minimum_withdrawal && $amountAfterFee < $cryptoWallet->minimum_withdrawal) {
            return redirect()->route('withdraw.crypto.network', [
                'method_id' => $paymentMethodId,
                'amount' => $amount
            ])->with('error', 'Amount must be at least $' . number_format($cryptoWallet->minimum_withdrawal + $cryptoFee, 2) . '. After $' . number_format($cryptoFee, 2) . ' fee, you will receive $' . number_format($cryptoWallet->minimum_withdrawal, 2) . '.');
        }

        // Validate maximum withdrawal (check amount after fee)
        if ($cryptoWallet->maximum_withdrawal && $amountAfterFee > $cryptoWallet->maximum_withdrawal) {
            return redirect()->route('withdraw.crypto.network', [
                'method_id' => $paymentMethodId,
                'amount' => $amount
            ])->with('error', 'Amount cannot exceed $' . number_format($cryptoWallet->maximum_withdrawal + $cryptoFee, 2) . '. After $' . number_format($cryptoFee, 2) . ' fee, you will receive $' . number_format($cryptoWallet->maximum_withdrawal, 2) . '.');
        }

        // Check user balance (user needs to have the full selected amount)
        $user = auth()->user();
        $totalAvailableBalance = ($user->mining_earning ?? 0) + ($user->referral_earning ?? 0);
        if ($totalAvailableBalance < $amount) {
            return redirect()->route('withdraw.index')
                ->with('error', 'Insufficient balance. Your available withdrawal balance is $' . number_format($totalAvailableBalance, 2) . '. You can only withdraw from mining and referral earnings.');
        }

        // Check if user has pending withdrawal
        $hasPendingWithdrawal = Withdrawal::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        return view('dashboard.pages.crypto-withdraw-confirm', compact(
            'paymentMethod',
            'cryptoWallet',
            'amount',
            'hasPendingWithdrawal'
        ));
    }

    /**
     * Store a new withdrawal request.
     */
    public function storeWithdrawal(Request $request)
    {
        $paymentMethod = DepositPaymentMethod::findOrFail($request->payment_method_id);
        $isCrypto = $paymentMethod->type === 'crypto';

        // Different validation rules for crypto vs regular withdrawals
        if ($isCrypto) {
            $request->validate([
                'payment_method_id' => 'required|exists:deposit_payment_methods,id',
                'amount' => 'required|numeric|min:0.01',
                'crypto_wallet_id' => 'required|exists:crypto_wallets,id',
                'user_wallet_address' => 'required|string|max:255',
            ], [
                'crypto_wallet_id.required' => 'Please select a crypto network.',
                'user_wallet_address.required' => 'Please enter your wallet address for receiving the withdrawal.',
            ]);
        } else {
            // For non-crypto withdrawals, bank_name is only required for bank type
            $isBank = $paymentMethod->type === 'bank';
            $validationRules = [
                'payment_method_id' => 'required|exists:deposit_payment_methods,id',
                'amount' => 'required|numeric|min:0.01',
                'account_number' => 'required|string|max:255',
                'account_holder_name' => 'required|string|max:255',
            ];
            
            $validationMessages = [
                'account_number.required' => 'Account number is required.',
                'account_holder_name.required' => 'Account holder name is required.',
            ];
            
            // Bank name is only required for bank type, optional for rast type
            if ($isBank) {
                $validationRules['bank_name'] = 'required|string|max:255';
                $validationMessages['bank_name.required'] = 'Bank name is required.';
            } else {
                $validationRules['bank_name'] = 'nullable|string|max:255';
            }
            
            $request->validate($validationRules, $validationMessages);
        }

        try {
            DB::beginTransaction();

            $user = auth()->user();

            // Check if user has pending withdrawal
            $pendingWithdrawal = Withdrawal::where('user_id', $user->id)
                ->where('status', 'pending')
                ->exists();

            if ($pendingWithdrawal) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'You have a pending withdrawal request. Please wait for it to be approved or rejected before submitting a new request.',
                ], 422);
            }

            // Validate payment method is allowed for withdrawal
            if (!$paymentMethod->is_active || !$paymentMethod->allowed_for_withdrawal) {
                return response()->json([
                    'success' => false,
                    'message' => 'This payment method is not available for withdrawal.',
                ], 422);
            }

            // For crypto withdrawals, validate crypto wallet
            if ($isCrypto) {
                $cryptoWallet = CryptoWallet::findOrFail($request->crypto_wallet_id);
                
                if (!$cryptoWallet->is_active || !$cryptoWallet->allowed_for_withdrawal) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected crypto network is not available for withdrawals.',
                    ], 422);
                }

                // For crypto withdrawals, $1 fee is deducted automatically from selected amount
                // User selects $5, fee is deducted, they receive $4, we deduct $5 from balance
                $cryptoFee = 1.00;
                $amountAfterFee = $request->amount - $cryptoFee;
                
                // Ensure user selects at least $1.01 (so they receive at least $0.01 after fee)
                if ($request->amount < 1.01) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Amount must be at least $1.01. After $1.00 fee, you will receive $0.01.',
                    ], 422);
                }
                
                // Validate minimum withdrawal (check amount after fee)
                if ($cryptoWallet->minimum_withdrawal && $amountAfterFee < $cryptoWallet->minimum_withdrawal) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Amount must be at least $' . number_format($cryptoWallet->minimum_withdrawal + $cryptoFee, 2) . '. After $' . number_format($cryptoFee, 2) . ' fee, you will receive $' . number_format($cryptoWallet->minimum_withdrawal, 2) . '.',
                    ], 422);
                }

                // Validate maximum withdrawal (check amount after fee)
                if ($cryptoWallet->maximum_withdrawal && $amountAfterFee > $cryptoWallet->maximum_withdrawal) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Amount cannot exceed $' . number_format($cryptoWallet->maximum_withdrawal + $cryptoFee, 2) . '. After $' . number_format($cryptoFee, 2) . ' fee, you will receive $' . number_format($cryptoWallet->maximum_withdrawal, 2) . '.',
                    ], 422);
                }
            } else {
                // Validate amount against payment method limits (non-crypto)
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
            }

            // Calculate total available balance (mining_earning + referral_earning only)
            // Note: Withdrawals can only use mining_earning + referral_earning (NOT fund_wallet)
            $totalAvailableBalance = ($user->mining_earning ?? 0) + ($user->referral_earning ?? 0);
            
            // For crypto withdrawals: User selects amount, $1 fee is deducted automatically
            // User selects $5, we deduct $5 from balance, fee is deducted, they receive $4
            // For non-crypto: Deduct just the requested amount
            $amountToDeduct = $request->amount;
            
            // Check user has sufficient balance
            if ($totalAvailableBalance < $amountToDeduct) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient balance. Your available withdrawal balance is $' . number_format($totalAvailableBalance, 2) . '. You can only withdraw from mining and referral earnings.',
                ], 422);
            }

            // Deduct amount from wallets (only from mining_earning and referral_earning)
            // Deduct proportionally from mining_earning and referral_earning
            $remainingAmount = $amountToDeduct;
            $deductFromMining = 0;
            $deductFromReferral = 0;
            
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

            // Update net balance (mining + referral only, excludes fund_wallet)
            $user->updateNetBalance();
            
            // Save user changes
            $user->save();

            // Create withdrawal record
            // For crypto: store actual amount (after $1 fee deduction)
            // For non-crypto: store requested amount
            $withdrawalAmount = $isCrypto ? ($request->amount - 1.00) : $request->amount;
            
            // Validate withdrawal amount is positive
            if ($withdrawalAmount <= 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid withdrawal amount. Please try again.',
                ], 422);
            }
            
            $withdrawalData = [
                'user_id' => $user->id,
                'deposit_payment_method_id' => $request->payment_method_id,
                'amount' => $withdrawalAmount,
                'deducted_from_mining' => $deductFromMining,
                'deducted_from_referral' => $deductFromReferral,
                'status' => 'pending',
            ];

            if ($isCrypto) {
                $withdrawalData['crypto_wallet_id'] = $request->crypto_wallet_id;
                $withdrawalData['user_wallet_address'] = $request->user_wallet_address;
                $withdrawalData['crypto_network'] = $cryptoWallet->network;
                // Set account fields to null for crypto withdrawals
                $withdrawalData['account_holder_name'] = null;
                $withdrawalData['account_number'] = null;
                $withdrawalData['bank_name'] = null;
            } else {
                $withdrawalData['account_holder_name'] = $request->account_holder_name;
                $withdrawalData['account_number'] = $request->account_number;
                // Bank name is only required for bank type, optional for rast type
                $withdrawalData['bank_name'] = $request->bank_name ?? null;
                // Set crypto fields to null for non-crypto withdrawals
                $withdrawalData['crypto_wallet_id'] = null;
                $withdrawalData['user_wallet_address'] = null;
                $withdrawalData['crypto_network'] = null;
            }

            $withdrawal = Withdrawal::create($withdrawalData);

            DB::commit();

            $successMessage = 'Your withdrawal request has been submitted. After admin approval, the money will be transferred to your account.';
            if ($isCrypto) {
                $successMessage = 'Your withdrawal request for $' . number_format($withdrawalAmount, 2) . ' has been submitted (including $1.00 fee). After admin approval, the money will be transferred to your account.';
            }
            
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect' => route('withdraw.index'),
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the actual error for debugging
            \Log::error('Withdrawal Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your withdrawal. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Store a new deposit request.
     */
    public function storeDeposit(Request $request)
    {
        $paymentMethod = DepositPaymentMethod::findOrFail($request->payment_method_id);
        $isCrypto = $paymentMethod->type === 'crypto';

        // Different validation rules for crypto vs regular deposits
        if ($isCrypto) {
            $request->validate([
                'payment_method_id' => 'required|exists:deposit_payment_methods,id',
                'amount' => 'required|numeric|min:0.01',
                'pkr_amount' => 'required|numeric|min:0.01',
                'crypto_wallet_id' => 'required|exists:crypto_wallets,id',
                'user_wallet_address' => 'required|string|max:255',
            ], [
                'crypto_wallet_id.required' => 'Please select a crypto network.',
                'user_wallet_address.required' => 'Please enter your wallet address.',
            ]);
        } else {
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
        }

        try {
            DB::beginTransaction();

            // For crypto deposits, validate crypto wallet
            if ($isCrypto) {
                $cryptoWallet = CryptoWallet::findOrFail($request->crypto_wallet_id);
                
                if (!$cryptoWallet->is_active || !$cryptoWallet->allowed_for_deposit) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected crypto network is not available for deposits.',
                    ], 422);
                }

                // Validate amount against crypto wallet limits
                if ($cryptoWallet->minimum_deposit && $request->amount < $cryptoWallet->minimum_deposit) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Amount must be at least $' . number_format($cryptoWallet->minimum_deposit, 2),
                    ], 422);
                }

                if ($cryptoWallet->maximum_deposit && $request->amount > $cryptoWallet->maximum_deposit) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Amount cannot exceed $' . number_format($cryptoWallet->maximum_deposit, 2),
                    ], 422);
                }
            } else {
                // Check for duplicate transaction ID for this user (non-crypto)
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
            }

            // Handle file upload (only for non-crypto deposits)
            $paymentProofPath = null;
            if (!$isCrypto && $request->hasFile('payment_proof')) {
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
            $depositData = [
                'user_id' => auth()->id(),
                'deposit_payment_method_id' => $request->payment_method_id,
                'amount' => $request->amount,
                'pkr_amount' => $request->pkr_amount,
                'status' => 'pending',
            ];

            if ($isCrypto) {
                $depositData['crypto_wallet_id'] = $request->crypto_wallet_id;
                $depositData['user_wallet_address'] = $request->user_wallet_address;
                $depositData['crypto_network'] = $cryptoWallet->network;
                // Generate a unique transaction ID for crypto deposits
                $depositData['transaction_id'] = 'CRYPTO-' . strtoupper(Str::random(16));
            } else {
                $depositData['transaction_id'] = $request->transaction_id;
                $depositData['account_number'] = $request->account_number;
                $depositData['account_holder_name'] = $request->account_holder_name;
                $depositData['payment_proof'] = $paymentProofPath;
            }

            $deposit = Deposit::create($depositData);

            DB::commit();

            // Send notification for crypto deposits
            if ($isCrypto) {
                \App\Services\NotificationService::sendCryptoDepositSubmitted($deposit);
            }

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


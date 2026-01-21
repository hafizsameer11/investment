<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionsController extends Controller
{
    /**
     * Show the transactions page.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user balances
        $miningEarning = $user->mining_earning ?? 0;
        $referralEarning = $user->referral_earning ?? 0;
        $totalEarning = $miningEarning + $referralEarning; // Net Balance (mining + referral)
        
        // Calculate total deposits (all-time approved)
        $totalDeposits = Deposit::where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('amount');
        
        // Calculate total withdrawals (all-time approved)
        $totalWithdrawals = Withdrawal::where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('amount');
        
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
        
        // Get user balances for display
        $balances = [
            'fund_wallet' => $user->fund_wallet ?? 0,
            'mining_earning' => $user->mining_earning ?? 0,
            'referral_earning' => $user->referral_earning ?? 0,
            'net_balance' => $user->net_balance ?? 0,
        ];
        
        return view('dashboard.pages.transactions', [
            'transactions' => $allTransactions,
            'balances' => $balances,
            'totalEarning' => $totalEarning,
            'referralEarning' => $referralEarning,
            'totalDeposits' => $totalDeposits,
            'totalWithdrawals' => $totalWithdrawals,
        ]);
    }
}


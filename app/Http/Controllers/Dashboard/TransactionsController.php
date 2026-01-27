<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Transaction;
use App\Models\PendingReferralCommission;
use App\Models\PendingEarningCommission;
use Illuminate\Support\Facades\Auth;

class TransactionsController extends Controller
{
    /**
     * Show the transactions page.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Calculate all-time Total Earnings from transactions (not affected by withdrawals)
        // Sum all mining_earning and referral_earning transactions
        $allTimeMiningEarnings = Transaction::where('user_id', $user->id)
            ->where('type', 'mining_earning')
            ->where('status', 'completed')
            ->sum('amount');
        
        $allTimeReferralEarnings = Transaction::where('user_id', $user->id)
            ->where('type', 'referral_earning')
            ->where('status', 'completed')
            ->sum('amount');
        
        // Fallback: Include claimed commissions that don't have Transaction records yet
        // This handles cases where commissions were claimed before Transaction records were created
        $claimedInvestmentCommissions = PendingReferralCommission::where('referrer_id', $user->id)
            ->where('is_claimed', true)
            ->sum('commission_amount');
        
        $claimedEarningCommissions = PendingEarningCommission::where('referrer_id', $user->id)
            ->where('is_claimed', true)
            ->sum('commission_amount');
        
        // Get commission-related transactions (exclude Victory Rewards which have different descriptions)
        $commissionTransactions = Transaction::where('user_id', $user->id)
            ->where('type', 'referral_earning')
            ->where('status', 'completed')
            ->whereIn('description', [
                'Investment commission earnings claimed',
                'Team earning commission earnings claimed'
            ])
            ->sum('amount');
        
        // Calculate claimed commissions that don't have Transaction records
        // Only subtract commission transactions, not Victory Rewards transactions
        $totalClaimedCommissions = ($claimedInvestmentCommissions ?? 0) + ($claimedEarningCommissions ?? 0);
        $claimedCommissionsWithoutTransactions = max(0, $totalClaimedCommissions - ($commissionTransactions ?? 0));
        
        // Fallback: If no transactions found, use investments' total_profit_earned for mining
        // This handles cases where earnings were claimed but not recorded as transactions
        if ($allTimeMiningEarnings == 0) {
            $allTimeMiningEarnings = \App\Models\Investment::where('user_id', $user->id)
                ->sum('total_profit_earned');
        }
        
        // Calculate all-time Total Earnings (All Time) - not affected by withdrawals
        // Include all referral_earning transactions (Victory Rewards + commissions) plus missing commission transactions
        $totalEarning = ($allTimeMiningEarnings ?? 0) + ($allTimeReferralEarnings ?? 0) + ($claimedCommissionsWithoutTransactions ?? 0);
        $referralEarning = ($allTimeReferralEarnings ?? 0) + ($claimedCommissionsWithoutTransactions ?? 0);
        
        // Get current user balances for display in transaction details
        $miningEarning = $user->mining_earning ?? 0;
        $currentReferralEarning = $user->referral_earning ?? 0;
        
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
        
        // Add claimed investment commissions that don't have transaction records
        // Group by claimed_at timestamp (rounded to minute) to handle batch claims
        $claimedInvestmentCommissionsByTime = PendingReferralCommission::where('referrer_id', $user->id)
            ->where('is_claimed', true)
            ->selectRaw('DATE_FORMAT(claimed_at, "%Y-%m-%d %H:%i:00") as claim_time, SUM(commission_amount) as total_amount, MIN(claimed_at) as first_claimed_at')
            ->groupBy('claim_time')
            ->orderBy('claim_time', 'desc')
            ->get();
        
        foreach ($claimedInvestmentCommissionsByTime as $commissionGroup) {
            // Check if there's already a transaction for this time and amount
            $existingTransaction = Transaction::where('user_id', $user->id)
                ->where('type', 'referral_earning')
                ->where('description', 'Investment commission earnings claimed')
                ->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i:00") = ?', [$commissionGroup->claim_time])
                ->where('amount', $commissionGroup->total_amount)
                ->first();
            
            if (!$existingTransaction) {
                $allTransactions->push([
                    'id' => 'investment_commission_' . str_replace([' ', ':'], ['_', ''], $commissionGroup->claim_time),
                    'type' => 'referral_earning',
                    'amount' => $commissionGroup->total_amount,
                    'description' => 'Investment commission earnings claimed',
                    'status' => 'completed',
                    'created_at' => $commissionGroup->first_claimed_at,
                    'reference' => null,
                ]);
            }
        }
        
        // Add claimed earning commissions that don't have transaction records
        // Group by claimed_at timestamp (rounded to minute) to handle batch claims
        $claimedEarningCommissionsByTime = PendingEarningCommission::where('referrer_id', $user->id)
            ->where('is_claimed', true)
            ->selectRaw('DATE_FORMAT(claimed_at, "%Y-%m-%d %H:%i:00") as claim_time, SUM(commission_amount) as total_amount, MIN(claimed_at) as first_claimed_at')
            ->groupBy('claim_time')
            ->orderBy('claim_time', 'desc')
            ->get();
        
        foreach ($claimedEarningCommissionsByTime as $commissionGroup) {
            // Check if there's already a transaction for this time and amount
            $existingTransaction = Transaction::where('user_id', $user->id)
                ->where('type', 'referral_earning')
                ->where('description', 'Team earning commission earnings claimed')
                ->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i:00") = ?', [$commissionGroup->claim_time])
                ->where('amount', $commissionGroup->total_amount)
                ->first();
            
            if (!$existingTransaction) {
                $allTransactions->push([
                    'id' => 'earning_commission_' . str_replace([' ', ':'], ['_', ''], $commissionGroup->claim_time),
                    'type' => 'referral_earning',
                    'amount' => $commissionGroup->total_amount,
                    'description' => 'Team earning commission earnings claimed',
                    'status' => 'completed',
                    'created_at' => $commissionGroup->first_claimed_at,
                    'reference' => null,
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


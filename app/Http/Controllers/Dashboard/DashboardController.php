<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Services\EarningCommissionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Calculate mining profits for active investments
     * This method can be called on-demand to ensure earnings are always up-to-date
     */
    private function calculateMiningProfits($user)
    {
        try {
            $activeInvestments = Investment::where('user_id', $user->id)
                ->where('status', 'active')
                ->get();

            if ($activeInvestments->isEmpty()) {
                return;
            }

            $now = Carbon::now();
            $userUpdated = false;

            foreach ($activeInvestments as $investment) {
                try {
                    // Determine the starting point for profit calculation
                    // If last_profit_calculated_at is null, use the investment's created_at
                    $lastCalculatedAt = $investment->last_profit_calculated_at ?? $investment->created_at;
                    
                    // Calculate hours elapsed since last calculation (using float for partial hours)
                    $hoursElapsed = $now->diffInSeconds($lastCalculatedAt) / 3600;
                    
                    // Only calculate if at least some time has passed (allow partial hours for real-time updates)
                    if ($hoursElapsed <= 0) {
                        continue;
                    }

                    // Calculate hourly profit: amount * (hourly_rate / 100)
                    $hourlyRate = $investment->hourly_rate ?? 0;
                    
                    if ($hourlyRate <= 0) {
                        continue;
                    }

                    $hourlyProfit = $investment->amount * ($hourlyRate / 100);
                    
                    // Calculate total profit for the time elapsed (supports partial hours)
                    $totalProfitForPeriod = $hourlyProfit * $hoursElapsed;

                    if ($totalProfitForPeriod > 0) {
                        DB::beginTransaction();
                        
                        try {
                            // Add profit to investment's unclaimed_profit (per investment)
                            // DO NOT add to mining_earning yet - user must claim it first
                            $investment->unclaimed_profit = ($investment->unclaimed_profit ?? 0) + $totalProfitForPeriod;
                            
                            // Update investment's total profit earned
                            $investment->total_profit_earned = ($investment->total_profit_earned ?? 0) + $totalProfitForPeriod;
                            $investment->last_profit_calculated_at = $now;
                            
                            // Save changes
                            $investment->save();
                            $userUpdated = true;
                            
                            DB::commit();

                            // Calculate and distribute earning commissions
                            try {
                                EarningCommissionService::calculateAndDistributeCommissions($user, $investment, $totalProfitForPeriod);
                            } catch (\Exception $e) {
                                // Log error but don't fail the profit calculation
                                Log::error('Error calculating earning commissions: ' . $e->getMessage(), [
                                    'user_id' => $user->id,
                                    'investment_id' => $investment->id,
                                    'earning_amount' => $totalProfitForPeriod,
                                ]);
                            }
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Log::error("Error calculating profit for investment ID {$investment->id}: " . $e->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("Error processing investment ID {$investment->id}: " . $e->getMessage());
                }
            }

            // Update user's net balance if any changes were made
            if ($userUpdated) {
                $user->updateNetBalance();
                $user->save();
            }
        } catch (\Exception $e) {
            Log::error('Error in calculateMiningProfits: ' . $e->getMessage());
        }
    }

    /**
     * Show the dashboard home page.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Calculate total invested from investments table (all investments in plans)
        // This shows only the amount invested in mining plans, not deposits
        $totalInvested = Investment::where('user_id', $user->id)
            ->sum('amount');
        
        // Update user's total_invested field to match actual investments
        if ($user->total_invested != $totalInvested) {
            $user->total_invested = $totalInvested;
            $user->save();
        }
        
        // Calculate mining profits on-demand to ensure earnings are up-to-date
        $this->calculateMiningProfits($user);
        
        // Refresh user to get updated values
        $user->refresh();
        
        // Get active investments for Live Earning section
        $activeInvestments = Investment::where('user_id', $user->id)
            ->where('status', 'active')
            ->get();
        
        // Calculate total unclaimed profit
        $totalUnclaimedProfit = $activeInvestments->sum('unclaimed_profit') ?? 0;
        
        // Calculate time until next hour for countdown timer
        $now = Carbon::now();
        $nextHour = $now->copy()->addHour()->startOfHour();
        $secondsUntilNextHour = $now->diffInSeconds($nextHour);
        
        // Format initial countdown (HH:MM:SS)
        $hours = floor($secondsUntilNextHour / 3600);
        $minutes = floor(($secondsUntilNextHour % 3600) / 60);
        $seconds = $secondsUntilNextHour % 60;
        $initialCountdown = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        
        $hasActivePlan = $activeInvestments->count() > 0;
        
        // Calculate daily earnings and investments for the last 7 days
        $chartData = $this->calculateChartData($user);
        
        // Ensure variables are always set
        $totalUnclaimedProfit = $totalUnclaimedProfit ?? 0;
        $secondsUntilNextHour = $secondsUntilNextHour ?? 3600;
        $initialCountdown = $initialCountdown ?? '01:00:00';
        
        return view('dashboard.index', compact('user', 'hasActivePlan', 'totalUnclaimedProfit', 'initialCountdown', 'secondsUntilNextHour', 'chartData'));
    }

    /**
     * Calculate chart data for the last 7 days
     * Returns daily earnings (mining + referral) and investments
     */
    private function calculateChartData($user)
    {
        $now = Carbon::now();
        $days = [];
        $earningsData = [];
        $investmentData = [];
        
        // Get last 7 days (including today)
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $startOfDay = $date->copy()->startOfDay();
            $endOfDay = $date->copy()->endOfDay();
            
            // Get day name (Mon, Tue, etc.)
            $days[] = $date->format('D');
            
            // Calculate cumulative earnings up to this day (mining + referral)
            // Get from transactions table - cumulative total
            $cumulativeMiningEarnings = \App\Models\Transaction::where('user_id', $user->id)
                ->where('type', 'mining_earning')
                ->where('created_at', '<=', $endOfDay)
                ->where('status', 'completed')
                ->sum('amount');
            
            $cumulativeReferralEarnings = \App\Models\Transaction::where('user_id', $user->id)
                ->where('type', 'referral_earning')
                ->where('created_at', '<=', $endOfDay)
                ->where('status', 'completed')
                ->sum('amount');
            
            // If no transactions found, calculate from investments' total_profit_earned
            // This handles cases where earnings were claimed but not recorded as transactions
            if ($cumulativeMiningEarnings == 0) {
                $cumulativeMiningEarnings = Investment::where('user_id', $user->id)
                    ->where('created_at', '<=', $endOfDay)
                    ->sum('total_profit_earned');
            }
            
            // For referral earnings, if no transactions, use current balance as fallback for today
            if ($cumulativeReferralEarnings == 0 && $i == 0) {
                $cumulativeReferralEarnings = $user->referral_earning ?? 0;
            }
            
            $totalEarnings = ($cumulativeMiningEarnings ?? 0) + ($cumulativeReferralEarnings ?? 0);
            
            // Calculate cumulative investments up to this day
            $cumulativeInvestments = Investment::where('user_id', $user->id)
                ->where('created_at', '<=', $endOfDay)
                ->sum('amount');
            
            $earningsData[] = round($totalEarnings, 2);
            $investmentData[] = round($cumulativeInvestments, 2);
        }
        
        return [
            'days' => $days,
            'earnings' => $earningsData,
            'investments' => $investmentData,
        ];
    }

    /**
     * Claim all mining earnings from active investments
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function claimAllEarnings()
    {
        try {
            $user = Auth::user();

            // Get all active investments
            $activeInvestments = Investment::where('user_id', $user->id)
                ->where('status', 'active')
                ->get();

            if ($activeInvestments->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active investments found.',
                ], 422);
            }

            // Calculate total unclaimed profit
            $totalUnclaimedProfit = $activeInvestments->sum('unclaimed_profit') ?? 0;

            if ($totalUnclaimedProfit <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No earnings available to claim.',
                ], 422);
            }

            DB::beginTransaction();

            try {
                // Add unclaimed profit to mining_earning
                $user->mining_earning = ($user->mining_earning ?? 0) + $totalUnclaimedProfit;
                
                // Reset unclaimed_profit for all active investments
                foreach ($activeInvestments as $investment) {
                    if ($investment->unclaimed_profit > 0) {
                        $investment->unclaimed_profit = 0;
                        $investment->last_claimed_at = now();
                        $investment->save();
                    }
                }

                // Save user and update net balance
                $user->save();
                $user->updateNetBalance();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Earnings claimed successfully.',
                    'claimed_amount' => number_format($totalUnclaimedProfit, 2, '.', ''),
                    'mining_earning' => number_format($user->mining_earning ?? 0, 2, '.', ''),
                    'net_balance' => number_format($user->net_balance ?? 0, 2, '.', ''),
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error claiming all earnings: ' . $e->getMessage());
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error claiming all earnings: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to claim earnings. Please try again.',
            ], 500);
        }
    }
}


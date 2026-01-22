<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\MiningPlan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PlansController extends Controller
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

                            // Note: Earning commissions are calculated when the user claims their mining earnings,
                            // not when the earnings are calculated. This ensures commissions only appear after claims.
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
     * Show the investment plans page.
     */
    public function index()
    {
        $plans = MiningPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
        
        $inactivePlans = MiningPlan::where('is_active', false)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        // Get user's active investments for each plan
        $user = auth()->user();
        $userInvestments = [];
        
        if ($user) {
            // Calculate mining profits on-demand to ensure earnings are up-to-date
            $this->calculateMiningProfits($user);
            
            // Refresh user to get updated values
            $user->refresh();
            
            $investments = Investment::where('user_id', $user->id)
                ->where('status', 'active')
                ->with('miningPlan')
                ->get();
            
            foreach ($investments as $investment) {
                $userInvestments[$investment->mining_plan_id] = [
                    'id' => $investment->id,
                    'amount' => $investment->amount,
                    'unclaimed_profit' => $investment->unclaimed_profit ?? 0,
                    'last_claimed_at' => $investment->last_claimed_at,
                ];
            }
        }
        
        return view('dashboard.pages.plans', compact('plans', 'inactivePlans', 'userInvestments'));
    }
}


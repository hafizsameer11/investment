<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the dashboard home page.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Calculate total invested from approved deposits if not set
        if ($user->total_invested == 0) {
            $totalInvested = $user->deposits()
                ->where('status', 'approved')
                ->sum('amount');
            
            if ($totalInvested > 0) {
                $user->total_invested = $totalInvested;
                $user->save();
            }
        }
        
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
        
        // Ensure variables are always set
        $totalUnclaimedProfit = $totalUnclaimedProfit ?? 0;
        $secondsUntilNextHour = $secondsUntilNextHour ?? 3600;
        $initialCountdown = $initialCountdown ?? '01:00:00';
        
        return view('dashboard.index', compact('user', 'hasActivePlan', 'totalUnclaimedProfit', 'initialCountdown', 'secondsUntilNextHour'));
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
                // Reset unclaimed_profit for all active investments
                foreach ($activeInvestments as $investment) {
                    if ($investment->unclaimed_profit > 0) {
                        $investment->unclaimed_profit = 0;
                        $investment->last_claimed_at = now();
                        $investment->save();
                    }
                }

                // Refresh user to get updated mining_earning (already updated by profit calculation)
                $user->refresh();
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


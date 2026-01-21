<?php

namespace App\Services;

use App\Models\User;
use App\Models\Investment;
use App\Models\EarningCommissionStructure;
use App\Models\PendingEarningCommission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EarningCommissionService
{
    /**
     * Calculate and distribute commissions for all referral levels up to 5
     *
     * @param User $investor The user who earned the profit
     * @param Investment $investment The investment record that generated the profit
     * @param float $earningAmount The amount of profit earned
     * @return void
     */
    public static function calculateAndDistributeCommissions(User $investor, Investment $investment, $earningAmount)
    {
        // Only calculate commissions for active investments
        if ($investment->status !== 'active') {
            return;
        }

        // Skip if earning amount is zero or negative
        if ($earningAmount <= 0) {
            return;
        }

        $planId = $investment->mining_plan_id;

        // Traverse up the referral chain up to 5 levels
        $currentUser = $investor;
        $level = 1;
        $maxLevel = 5;

        while ($level <= $maxLevel && $currentUser->referred_by) {
            // Get the referrer
            $referrer = User::where('refer_code', $currentUser->referred_by)->first();

            if (!$referrer) {
                // Referrer not found, break the chain
                break;
            }

            // Get commission rate for this level
            $commissionRate = EarningCommissionStructure::getCommissionRate($level);

            if ($commissionRate !== null && $commissionRate > 0) {
                // Calculate commission amount
                $commissionAmount = ($earningAmount * $commissionRate) / 100;

                // Create pending commission record
                try {
                    PendingEarningCommission::create([
                        'referrer_id' => $referrer->id,
                        'investor_id' => $investor->id,
                        'investment_id' => $investment->id,
                        'mining_plan_id' => $planId,
                        'level' => $level,
                        'earning_amount' => $earningAmount,
                        'commission_rate' => $commissionRate,
                        'commission_amount' => $commissionAmount,
                        'is_claimed' => false,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error creating pending earning commission: ' . $e->getMessage(), [
                        'referrer_id' => $referrer->id,
                        'investor_id' => $investor->id,
                        'investment_id' => $investment->id,
                        'level' => $level,
                    ]);
                }
            }

            // Move up the referral chain
            $currentUser = $referrer;
            $level++;
        }
    }

    /**
     * Get commission rate for a specific level
     *
     * @param int $level
     * @return float|null
     */
    public static function getCommissionRate($level)
    {
        return EarningCommissionStructure::getCommissionRate($level);
    }
}


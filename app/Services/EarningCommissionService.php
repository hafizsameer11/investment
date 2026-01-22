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
     * Calculate and distribute earning commissions for referral users up to level 5
     * 
     * IMPORTANT: This service ONLY processes MINING EARNINGS from investments.
     * It calculates commissions based on the mining profits earned by referred users.
     * 
     * Commission Structure:
     * - Level 1: Direct referral (user who directly referred the investor)
     * - Level 2: Referral of referral (if level 1 user referred another user)
     * - Level 3-5: Continues up the referral chain
     * 
     * @param User $investor The user who earned the mining profit
     * @param Investment $investment The investment record that generated the mining profit
     * @param float $earningAmount The amount of mining profit earned (must be from mining earnings only)
     * @return void
     */
    public static function calculateAndDistributeCommissions(User $investor, Investment $investment, $earningAmount)
    {
        // Only calculate commissions for active investments
        if ($investment->status !== 'active') {
            return;
        }

        // Ensure this is a mining investment (has a mining plan)
        if (!$investment->mining_plan_id) {
            Log::warning('EarningCommissionService: Investment does not have a mining plan', [
                'investment_id' => $investment->id,
                'user_id' => $investor->id,
            ]);
            return;
        }

        // Skip if earning amount is zero or negative
        if ($earningAmount <= 0) {
            return;
        }

        $planId = $investment->mining_plan_id;

        // Traverse up the referral chain up to 5 levels
        // Level 1 = direct referrer, Level 2-5 = indirect referrers
        $currentUser = $investor;
        $level = 1;
        $maxLevel = 5; // Maximum referral level for earning commissions

        while ($level <= $maxLevel && $currentUser->referred_by) {
            // Get the referrer at this level
            $referrer = User::where('refer_code', $currentUser->referred_by)->first();

            if (!$referrer) {
                // Referrer not found, break the chain
                break;
            }

            // Get commission rate for this level and plan (with fallback to global rate)
            $commissionRate = EarningCommissionStructure::getCommissionRate($level, $planId);

            if ($commissionRate !== null && $commissionRate > 0) {
                // Calculate commission amount based on mining earnings
                $commissionAmount = ($earningAmount * $commissionRate) / 100;

                // Create pending commission record for the referrer
                try {
                    PendingEarningCommission::create([
                        'referrer_id' => $referrer->id,
                        'investor_id' => $investor->id,
                        'investment_id' => $investment->id,
                        'mining_plan_id' => $planId,
                        'level' => $level,
                        'earning_amount' => $earningAmount, // The mining earnings amount
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
                        'earning_amount' => $earningAmount,
                    ]);
                }
            }

            // Move up the referral chain to the next level
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


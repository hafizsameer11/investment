<?php

namespace App\Services;

use App\Models\User;
use App\Models\Investment;
use App\Models\InvestmentCommissionStructure;
use App\Models\PendingReferralCommission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReferralCommissionService
{
    /**
     * Calculate and distribute commissions for all referral levels up to 5
     *
     * @param User $investor The user who made the investment
     * @param Investment $investment The investment record
     * @param float|null $amountOverride Optional amount to use instead of investment amount (for additional investments)
     * @return void
     */
    public static function calculateAndDistributeCommissions(User $investor, Investment $investment, $amountOverride = null)
    {
        // Only calculate commissions for active investments
        if ($investment->status !== 'active') {
            return;
        }

        $planId = $investment->mining_plan_id;
        $investmentAmount = $amountOverride ?? $investment->amount;

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

            // Get commission rate for this level and plan
            $commissionRate = InvestmentCommissionStructure::getCommissionRate($level, $planId);

            if ($commissionRate !== null && $commissionRate > 0) {
                // Calculate commission amount
                $commissionAmount = ($investmentAmount * $commissionRate) / 100;

                // Create pending commission record
                try {
                    PendingReferralCommission::create([
                        'referrer_id' => $referrer->id,
                        'investor_id' => $investor->id,
                        'investment_id' => $investment->id,
                        'mining_plan_id' => $planId,
                        'level' => $level,
                        'investment_amount' => $investmentAmount,
                        'commission_rate' => $commissionRate,
                        'commission_amount' => $commissionAmount,
                        'is_claimed' => false,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error creating pending referral commission: ' . $e->getMessage(), [
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
     * Get commission rate for a specific level and plan
     * Falls back to global rate if plan-specific rate doesn't exist
     *
     * @param int $level
     * @param int|null $planId
     * @return float|null
     */
    public static function getCommissionRate($level, $planId = null)
    {
        return InvestmentCommissionStructure::getCommissionRate($level, $planId);
    }
}


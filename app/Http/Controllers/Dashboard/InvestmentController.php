<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\MiningPlan;
use App\Services\RewardLevelService;
use App\Services\ReferralCommissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvestmentController extends Controller
{
    /**
     * Show investment modal data for a specific plan
     *
     * @param int $planId
     * @return \Illuminate\Http\JsonResponse
     */
    public function showModal($planId)
    {
        try {
            $user = auth()->user();
            $plan = MiningPlan::findOrFail($planId);

            // Calculate balances
            $fundBalance = $user->fund_wallet ?? 0;
            $earningBalance = ($user->referral_earning ?? 0) + ($user->mining_earning ?? 0);

            return response()->json([
                'success' => true,
                'plan' => [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'subtitle' => $plan->subtitle,
                    'min_investment' => $plan->min_investment,
                    'max_investment' => $plan->max_investment,
                    'hourly_rate' => $plan->hourly_rate,
                ],
                'balances' => [
                    'fund_balance' => number_format($fundBalance, 2, '.', ''),
                    'earning_balance' => number_format($earningBalance, 2, '.', ''),
                ],
                'can_invest_from_fund' => $fundBalance >= $plan->min_investment,
                'can_invest_from_earning' => $earningBalance >= $plan->min_investment,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching investment modal data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load investment data.',
            ], 500);
        }
    }

    /**
     * Store a new investment
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $user = auth()->user();

            // Validate request
            $validated = $request->validate([
                'plan_id' => 'required|exists:mining_plans,id',
                'amount' => 'required|numeric|min:0.01',
                'source_balance' => 'required|in:fund_wallet,earning_balance',
            ]);

            $plan = MiningPlan::findOrFail($validated['plan_id']);
            $amount = $validated['amount'];
            $sourceBalance = $validated['source_balance'];

            // Check if user already has an active investment for this plan
            $existingInvestment = Investment::where('user_id', $user->id)
                ->where('mining_plan_id', $plan->id)
                ->where('status', 'active')
                ->first();

            if ($existingInvestment) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have an active investment for this plan. Please use "Manage Active Plan" to add additional funds.',
                ], 422);
            }

            // Validate amount is within plan range
            if ($amount < $plan->min_investment || $amount > $plan->max_investment) {
                return response()->json([
                    'success' => false,
                    'message' => "Investment amount must be between $" . number_format($plan->min_investment, 2) . " and $" . number_format($plan->max_investment, 2) . ".",
                ], 422);
            }

            // Calculate balances
            $fundBalance = $user->fund_wallet ?? 0;
            $earningBalance = ($user->referral_earning ?? 0) + ($user->mining_earning ?? 0);

            // Validate selected balance has sufficient funds
            if ($sourceBalance === 'fund_wallet') {
                if ($fundBalance < $amount) {
                    $minRequired = $plan->min_investment;
                    return response()->json([
                        'success' => false,
                        'message' => "Please deposit at least $" . number_format($minRequired, 2) . " to buy this plan.",
                    ], 422);
                }
            } else {
                if ($earningBalance < $amount) {
                    $minRequired = $plan->min_investment;
                    return response()->json([
                        'success' => false,
                        'message' => "Please deposit at least $" . number_format($minRequired, 2) . " to buy this plan.",
                    ], 422);
                }
            }

            // Create investment using database transaction
            DB::beginTransaction();

            try {
                // Deduct from selected balance
                if ($sourceBalance === 'fund_wallet') {
                    $user->fund_wallet = $user->fund_wallet - $amount;
                    $user->save();
                } else {
                    // Deduct from earning balance proportionally
                    $referralEarning = $user->referral_earning ?? 0;
                    $miningEarning = $user->mining_earning ?? 0;
                    $totalEarning = $referralEarning + $miningEarning;

                    if ($totalEarning > 0) {
                        // Calculate proportional deduction
                        $referralRatio = $referralEarning / $totalEarning;
                        $miningRatio = $miningEarning / $totalEarning;

                        $deductFromReferral = $amount * $referralRatio;
                        $deductFromMining = $amount * $miningRatio;

                        // Adjust if rounding causes issues
                        $totalDeducted = $deductFromReferral + $deductFromMining;
                        if (abs($totalDeducted - $amount) > 0.01) {
                            $difference = $amount - $totalDeducted;
                            if ($miningEarning >= $deductFromMining + $difference) {
                                $deductFromMining += $difference;
                            } else {
                                $deductFromReferral += $difference;
                            }
                        }

                        // Deduct from both balances
                        $user->referral_earning = max(0, $referralEarning - $deductFromReferral);
                        $user->mining_earning = max(0, $miningEarning - $deductFromMining);
                    } else {
                        // If no earning balance, this shouldn't happen due to validation, but handle it
                        throw new \Exception('Insufficient earning balance');
                    }
                    $user->save();
                }

                // Update total invested
                $user->total_invested = ($user->total_invested ?? 0) + $amount;
                $user->updateNetBalance();

                // Create investment record
                $investment = Investment::create([
                    'user_id' => $user->id,
                    'mining_plan_id' => $plan->id,
                    'amount' => $amount,
                    'source_balance' => $sourceBalance,
                    'hourly_rate' => $plan->hourly_rate ?? 0,
                    'status' => 'active',
                    'total_profit_earned' => 0,
                ]);

                DB::commit();

                // Calculate and distribute referral commissions
                try {
                    ReferralCommissionService::calculateAndDistributeCommissions($user, $investment);
                } catch (\Exception $e) {
                    // Log error but don't fail the investment creation
                    Log::error('Error calculating referral commissions: ' . $e->getMessage(), [
                        'user_id' => $user->id,
                        'investment_id' => $investment->id,
                    ]);
                }

                // Process reward levels for referrer if user has a referrer
                if ($user->referred_by) {
                    $referrer = \App\Models\User::where('refer_code', $user->referred_by)->first();
                    if ($referrer) {
                        // Process reward levels asynchronously to avoid blocking the response
                        // Using dispatch after response or queue if available
                        try {
                            RewardLevelService::processRewardLevels($referrer, $amount);
                        } catch (\Exception $e) {
                            // Log error but don't fail the investment creation
                            Log::error('Error processing reward levels for referrer: ' . $e->getMessage(), [
                                'referrer_id' => $referrer->id,
                                'investment_amount' => $amount,
                            ]);
                        }
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Investment created successfully.',
                    'investment' => [
                        'id' => $investment->id,
                        'amount' => $investment->amount,
                        'plan_name' => $plan->name,
                    ],
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error creating investment: ' . $e->getMessage());
                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error storing investment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create investment. Please try again.',
            ], 500);
        }
    }

    /**
     * Show manage active plan modal data
     *
     * @param int $planId
     * @return \Illuminate\Http\JsonResponse
     */
    public function showManageModal($planId)
    {
        try {
            $user = auth()->user();
            $plan = MiningPlan::findOrFail($planId);

            // Find user's active investment for this plan
            $investment = Investment::where('user_id', $user->id)
                ->where('mining_plan_id', $planId)
                ->where('status', 'active')
                ->first();

            if (!$investment) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active investment found for this plan.',
                ], 404);
            }

            // Calculate balances
            $fundBalance = $user->fund_wallet ?? 0;
            $earningBalance = ($user->referral_earning ?? 0) + ($user->mining_earning ?? 0);

            // Calculate max additional investment (plan max - existing investment)
            $maxAdditional = max(0, $plan->max_investment - $investment->amount);

            return response()->json([
                'success' => true,
                'investment' => [
                    'id' => $investment->id,
                    'amount' => number_format($investment->amount, 2, '.', ''),
                    'unclaimed_profit' => number_format($investment->unclaimed_profit ?? 0, 2, '.', ''),
                ],
                'plan' => [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'subtitle' => $plan->subtitle,
                    'min_investment' => $plan->min_investment,
                    'max_investment' => $plan->max_investment,
                    'hourly_rate' => $plan->hourly_rate,
                ],
                'balances' => [
                    'fund_balance' => number_format($fundBalance, 2, '.', ''),
                    'earning_balance' => number_format($earningBalance, 2, '.', ''),
                ],
                'max_additional' => number_format($maxAdditional, 2, '.', ''),
                'can_invest_from_fund' => $fundBalance >= $plan->min_investment,
                'can_invest_from_earning' => $earningBalance >= $plan->min_investment,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching manage modal data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load manage plan data.',
            ], 500);
        }
    }

    /**
     * Show claim earnings modal data
     *
     * @param int $investmentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function showClaimModal($investmentId)
    {
        try {
            $user = auth()->user();

            // Find investment and validate ownership
            $investment = Investment::where('id', $investmentId)
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->with('miningPlan')
                ->firstOrFail();

            $plan = $investment->miningPlan;
            $unclaimedProfit = $investment->unclaimed_profit ?? 0;
            $miningEarning = $user->mining_earning ?? 0;

            return response()->json([
                'success' => true,
                'investment' => [
                    'id' => $investment->id,
                    'unclaimed_profit' => number_format($unclaimedProfit, 2, '.', ''),
                ],
                'plan' => [
                    'id' => $plan->id,
                    'name' => $plan->name,
                ],
                'mining_earning' => number_format($miningEarning, 2, '.', ''),
                'has_earnings' => $unclaimedProfit > 0,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Investment not found or you do not have permission.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching claim modal data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load claim earnings data.',
            ], 500);
        }
    }

    /**
     * Claim earnings for an investment
     *
     * @param int $investmentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function claimEarnings($investmentId)
    {
        try {
            $user = auth()->user();

            // Find investment and validate ownership
            $investment = Investment::where('id', $investmentId)
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->firstOrFail();

            $unclaimedProfit = $investment->unclaimed_profit ?? 0;

            if ($unclaimedProfit <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No earnings available to claim.',
                ], 422);
            }

            DB::beginTransaction();

            try {
                // Mark earnings as claimed (keep in mining_earning, just reset unclaimed_profit)
                $investment->unclaimed_profit = 0;
                $investment->last_claimed_at = now();
                $investment->save();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Earnings claimed successfully.',
                    'claimed_amount' => number_format($unclaimedProfit, 2, '.', ''),
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error claiming earnings: ' . $e->getMessage());
                throw $e;
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Investment not found or you do not have permission to claim earnings.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error claiming earnings: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to claim earnings. Please try again.',
            ], 500);
        }
    }

    /**
     * Update an existing investment with additional amount
     *
     * @param Request $request
     * @param int $investmentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateInvestment(Request $request, $investmentId)
    {
        try {
            $user = auth()->user();

            // Validate request
            $validated = $request->validate([
                'amount' => 'required|numeric|min:0.01',
                'source_balance' => 'required|in:fund_wallet,earning_balance',
            ]);

            // Find investment and validate ownership
            $investment = Investment::where('id', $investmentId)
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->with('miningPlan')
                ->firstOrFail();

            $plan = $investment->miningPlan;
            $additionalAmount = $validated['amount'];
            $sourceBalance = $validated['source_balance'];
            $existingAmount = $investment->amount;
            $newTotal = $existingAmount + $additionalAmount;

            // Validate new total is within plan range
            if ($newTotal < $plan->min_investment || $newTotal > $plan->max_investment) {
                return response()->json([
                    'success' => false,
                    'message' => "Total investment amount must be between $" . number_format($plan->min_investment, 2) . " and $" . number_format($plan->max_investment, 2) . ". Current: $" . number_format($existingAmount, 2) . ", Additional: $" . number_format($additionalAmount, 2) . ".",
                ], 422);
            }

            // Calculate balances
            $fundBalance = $user->fund_wallet ?? 0;
            $earningBalance = ($user->referral_earning ?? 0) + ($user->mining_earning ?? 0);

            // Validate selected balance has sufficient funds
            if ($sourceBalance === 'fund_wallet') {
                if ($fundBalance < $additionalAmount) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient fund balance. Available: $" . number_format($fundBalance, 2) . ", Required: $" . number_format($additionalAmount, 2) . ".",
                    ], 422);
                }
            } else {
                if ($earningBalance < $additionalAmount) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient earning balance. Available: $" . number_format($earningBalance, 2) . ", Required: $" . number_format($additionalAmount, 2) . ".",
                    ], 422);
                }
            }

            // Update investment using database transaction
            DB::beginTransaction();

            try {
                // Deduct from selected balance
                if ($sourceBalance === 'fund_wallet') {
                    $user->fund_wallet = $user->fund_wallet - $additionalAmount;
                    $user->save();
                } else {
                    // Deduct from earning balance proportionally
                    $referralEarning = $user->referral_earning ?? 0;
                    $miningEarning = $user->mining_earning ?? 0;
                    $totalEarning = $referralEarning + $miningEarning;

                    if ($totalEarning > 0) {
                        // Calculate proportional deduction
                        $referralRatio = $referralEarning / $totalEarning;
                        $miningRatio = $miningEarning / $totalEarning;

                        $deductFromReferral = $additionalAmount * $referralRatio;
                        $deductFromMining = $additionalAmount * $miningRatio;

                        // Adjust if rounding causes issues
                        $totalDeducted = $deductFromReferral + $deductFromMining;
                        if (abs($totalDeducted - $additionalAmount) > 0.01) {
                            $difference = $additionalAmount - $totalDeducted;
                            if ($miningEarning >= $deductFromMining + $difference) {
                                $deductFromMining += $difference;
                            } else {
                                $deductFromReferral += $difference;
                            }
                        }

                        // Deduct from both balances
                        $user->referral_earning = max(0, $referralEarning - $deductFromReferral);
                        $user->mining_earning = max(0, $miningEarning - $deductFromMining);
                    } else {
                        throw new \Exception('Insufficient earning balance');
                    }
                    $user->save();
                }

                // Update total invested
                $user->total_invested = ($user->total_invested ?? 0) + $additionalAmount;
                $user->updateNetBalance();

                // Update investment amount (add to existing)
                $investment->amount = $newTotal;
                $investment->save();

                DB::commit();

                // Calculate and distribute referral commissions for additional amount
                try {
                    ReferralCommissionService::calculateAndDistributeCommissions($user, $investment, $additionalAmount);
                } catch (\Exception $e) {
                    // Log error but don't fail the investment update
                    Log::error('Error calculating referral commissions for additional investment: ' . $e->getMessage(), [
                        'user_id' => $user->id,
                        'investment_id' => $investment->id,
                        'additional_amount' => $additionalAmount,
                    ]);
                }

                // Process reward levels for referrer if user has a referrer
                if ($user->referred_by) {
                    $referrer = \App\Models\User::where('refer_code', $user->referred_by)->first();
                    if ($referrer) {
                        // Process reward levels asynchronously to avoid blocking the response
                        try {
                            RewardLevelService::processRewardLevels($referrer, $additionalAmount);
                        } catch (\Exception $e) {
                            // Log error but don't fail the investment update
                            Log::error('Error processing reward levels for referrer: ' . $e->getMessage(), [
                                'referrer_id' => $referrer->id,
                                'investment_amount' => $additionalAmount,
                            ]);
                        }
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Investment updated successfully.',
                    'investment' => [
                        'id' => $investment->id,
                        'amount' => number_format($investment->amount, 2, '.', ''),
                        'plan_name' => $plan->name,
                    ],
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error updating investment: ' . $e->getMessage());
                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Investment not found or you do not have permission to update it.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error updating investment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update investment. Please try again.',
            ], 500);
        }
    }
}

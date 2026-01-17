<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\MiningPlan;
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
}

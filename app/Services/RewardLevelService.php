<?php

namespace App\Services;

use App\Models\User;
use App\Models\RewardLevel;
use App\Models\UserRewardLevel;
use App\Models\Investment;
use App\Models\Transaction;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RewardLevelService
{
    /**
     * Calculate total referral investment for a user
     * Sum of all investments made by direct referrals
     *
     * @param User $user
     * @return float
     */
    public static function calculateTotalReferralInvestment(User $user): float
    {
        // Get all direct referrals
        $directReferrals = $user->directReferrals()->pluck('id');
        
        if ($directReferrals->isEmpty()) {
            return 0;
        }
        
        // Sum all investments from direct referrals
        $totalInvestment = Investment::whereIn('user_id', $directReferrals)
            ->where('status', 'active')
            ->sum('amount');
        
        return (float) $totalInvestment;
    }

    /**
     * Get all reward levels achieved by a user
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getUserAchievedLevels(User $user)
    {
        return UserRewardLevel::where('user_id', $user->id)
            ->with('rewardLevel')
            ->get()
            ->pluck('reward_level_id')
            ->toArray();
    }

    /**
     * Get the next uncompleted reward level for a user
     *
     * @param User $user
     * @return RewardLevel|null
     */
    public static function getNextLevelToComplete(User $user): ?RewardLevel
    {
        $achievedLevelIds = self::getUserAchievedLevels($user);
        
        return RewardLevel::where('is_active', true)
            ->whereNotIn('id', $achievedLevelIds)
            ->orderBy('sort_order')
            ->orderBy('level')
            ->first();
    }

    /**
     * Process reward levels when a new referral investment is made
     * This method handles the sequential completion of reward levels
     *
     * @param User $user The referrer user
     * @param float $newInvestmentAmount The new investment amount from a referral
     * @return array Array of completed levels
     */
    public static function processRewardLevels(User $user, float $newInvestmentAmount): array
    {
        $completedLevels = [];
        
        try {
            DB::beginTransaction();
            
            // Get all active reward levels ordered by sort_order/level
            $rewardLevels = RewardLevel::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('level')
                ->get();
            
            if ($rewardLevels->isEmpty()) {
                DB::rollBack();
                return $completedLevels;
            }
            
            // Get user's achieved levels
            $achievedLevelIds = self::getUserAchievedLevels($user);
            
            // Calculate total referral investment (this includes the new investment)
            $totalReferralInvestment = self::calculateTotalReferralInvestment($user);
            
            // Process each uncompleted level in order
            foreach ($rewardLevels as $level) {
                // Skip if already achieved
                if (in_array($level->id, $achievedLevelIds)) {
                    continue;
                }
                
                // Check if we have enough total investment to complete this level
                if ($totalReferralInvestment >= (float) $level->investment_required) {
                    // Calculate total reward to credit (cumulative)
                    // We sum rewards for this level and all previous levels that weren't achieved yet
                    $rewardToCredit = 0;
                    foreach ($rewardLevels as $l) {
                        if ($l->sort_order <= $level->sort_order) {
                            if (!in_array($l->id, $achievedLevelIds)) {
                                $rewardToCredit += (float) $l->reward_amount;
                            }
                        }
                    }

                    // Mark level as achieved (but not claimed yet)
                    UserRewardLevel::create([
                        'user_id' => $user->id,
                        'reward_level_id' => $level->id,
                        'achieved_at' => now(),
                        'reward_amount_credited' => (float) $level->reward_amount,
                        'is_claimed' => false,
                    ]);
                    
                    // Send notification that level is completed
                    NotificationService::sendRewardLevelCompleted($user, $level);
                    
                    $completedLevels[] = [
                        'level_id' => $level->id,
                        'level_name' => $level->level_name,
                        'reward_amount' => (float) $level->reward_amount,
                    ];
                    
                    // Update achieved levels list for next iteration
                    $achievedLevelIds[] = $level->id;
                } else {
                    // Not enough total investment to complete this level, stop processing
                    break;
                }
            }
            
            DB::commit();
            
            return $completedLevels;
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing reward levels: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'investment_amount' => $newInvestmentAmount,
                'trace' => $e->getTraceAsString(),
            ]);
            
            return $completedLevels;
        }
    }

    /**
     * Check and process reward levels based on current total referral investment
     * This ensures levels are automatically completed when requirements are met
     *
     * @param User $user The user to check levels for
     * @return array Array of newly completed levels
     */
    public static function checkAndProcessRewardLevels(User $user): array
    {
        $completedLevels = [];
        
        try {
            DB::beginTransaction();
            
            // Get all active reward levels ordered by sort_order/level
            $rewardLevels = RewardLevel::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('level')
                ->get();
            
            if ($rewardLevels->isEmpty()) {
                DB::rollBack();
                return $completedLevels;
            }
            
            // Get user's achieved levels
            $achievedLevelIds = self::getUserAchievedLevels($user);
            
            // Calculate total referral investment
            $totalReferralInvestment = self::calculateTotalReferralInvestment($user);
            
            // Process each uncompleted level in order
            foreach ($rewardLevels as $level) {
                // Skip if already achieved
                if (in_array($level->id, $achievedLevelIds)) {
                    continue;
                }
                
                // Check if we have enough total investment to complete this level
                if ($totalReferralInvestment >= (float) $level->investment_required) {
                    // Complete this level
                    $rewardAmount = (float) $level->reward_amount;
                    
                    // Check if already achieved (race condition check)
                    $existingAchievement = UserRewardLevel::where('user_id', $user->id)
                        ->where('reward_level_id', $level->id)
                        ->first();
                    
                    if (!$existingAchievement) {
                        // Mark level as achieved (but not claimed yet)
                        UserRewardLevel::create([
                            'user_id' => $user->id,
                            'reward_level_id' => $level->id,
                            'achieved_at' => now(),
                            'reward_amount_credited' => $rewardAmount,
                            'is_claimed' => false,
                        ]);
                        
                        // Send notification that level is completed
                        NotificationService::sendRewardLevelCompleted($user, $level);
                        
                        $completedLevels[] = [
                            'level_id' => $level->id,
                            'level_name' => $level->level_name,
                            'reward_amount' => $rewardAmount,
                        ];
                    }
                    
                    // Update achieved levels list for next iteration
                    $achievedLevelIds[] = $level->id;
                } else {
                    // Not enough total investment to complete this level, stop processing
                    break;
                }
            }
            
            DB::commit();
            
            return $completedLevels;
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error checking and processing reward levels: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString(),
            ]);
            
            return $completedLevels;
        }
    }

    /**
     * Get user's progress for a specific reward level
     *
     * @param User $user
     * @param RewardLevel $level
     * @return array
     */
    public static function getUserLevelProgress(User $user, RewardLevel $level): array
    {
        $totalReferralInvestment = self::calculateTotalReferralInvestment($user);
        $achievedLevelIds = self::getUserAchievedLevels($user);
        $isAchieved = in_array($level->id, $achievedLevelIds);
        
        // Calculate how much investment has been counted for previous levels
        // Since investment_required is cumulative, we need the highest achieved level's requirement
        $previousLevelsInvestment = 0;
        $rewardLevels = RewardLevel::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('level')
            ->get();
        
        // Find the highest achieved level before this one
        foreach ($rewardLevels as $prevLevel) {
            if ($prevLevel->id == $level->id) {
                break;
            }
            if (in_array($prevLevel->id, $achievedLevelIds)) {
                // Since requirements are cumulative, use the highest one
                $previousLevelsInvestment = max($previousLevelsInvestment, (float) $prevLevel->investment_required);
            }
        }
        
        // Calculate progress for this level
        $targetAmount = (float) $level->investment_required;
        $currentProgress = (float) $totalReferralInvestment;
        
        // remainingNeeded is how much is LEFT to reach the target
        $remainingNeeded = max(0, $targetAmount - $currentProgress);
        
        // progressPercentage is based on the total target
        $progressPercentage = $isAchieved ? 100 : ($targetAmount > 0 ? min(100, ($currentProgress / $targetAmount) * 100) : 0);
        
        // Get claim status if achieved
        $isClaimed = false;
        if ($isAchieved) {
            $userRewardLevel = UserRewardLevel::where('user_id', $user->id)
                ->where('reward_level_id', $level->id)
                ->first();
            $isClaimed = $userRewardLevel ? $userRewardLevel->is_claimed : false;
        }
        
        return [
            'is_achieved' => $isAchieved,
            'is_claimed' => $isClaimed,
            'total_referral_investment' => $totalReferralInvestment,
            'previous_levels_investment' => $previousLevelsInvestment,
            'current_progress' => $currentProgress,
            'remaining_needed' => $remainingNeeded,
            'progress_percentage' => $progressPercentage,
        ];
    }

    /**
     * Claim a reward level - add reward to referral earnings
     *
     * @param User $user
     * @param int $levelId
     * @return array
     */
    public static function claimRewardLevel(User $user, int $levelId): array
    {
        try {
            DB::beginTransaction();
            
            // Get the reward level
            $level = RewardLevel::findOrFail($levelId);
            
            // Check if user has achieved this level
            $userRewardLevel = UserRewardLevel::where('user_id', $user->id)
                ->where('reward_level_id', $levelId)
                ->first();
            
            if (!$userRewardLevel) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'You have not completed this reward level yet.',
                ];
            }
            
            // Check if already claimed
            if ($userRewardLevel->is_claimed) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'This reward has already been claimed.',
                ];
            }
            
            // Claim the reward
            $rewardAmount = (float) $userRewardLevel->reward_amount_credited;
            
            // Update user reward level record
            $userRewardLevel->is_claimed = true;
            $userRewardLevel->claimed_at = now();
            $userRewardLevel->save();
            
            // Add reward to referral earning
            $user->referral_earning += $rewardAmount;
            $user->updateNetBalance();
            
            // Create transaction record for referral earning
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'referral_earning',
                'amount' => $rewardAmount,
                'description' => "Reward claimed for completing {$level->level_name}",
                'reference_id' => $level->id,
                'reference_type' => RewardLevel::class,
                'status' => 'completed',
            ]);
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => "Reward of \${$rewardAmount} has been added to your referral earnings.",
                'reward_amount' => $rewardAmount,
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error claiming reward level: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'level_id' => $levelId,
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'success' => false,
                'message' => 'An error occurred while claiming the reward. Please try again.',
            ];
        }
    }

    /**
     * Get claimable levels for a user (completed but not claimed)
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getClaimableLevels(User $user)
    {
        return UserRewardLevel::where('user_id', $user->id)
            ->where('is_claimed', false)
            ->with('rewardLevel')
            ->get();
    }
}


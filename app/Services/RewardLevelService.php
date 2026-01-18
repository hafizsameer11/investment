<?php

namespace App\Services;

use App\Models\User;
use App\Models\RewardLevel;
use App\Models\UserRewardLevel;
use App\Models\Investment;
use App\Models\Transaction;
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
                
                // Calculate how much investment has already been counted for previous levels
                // Since investment_required is cumulative, we need the highest achieved level's requirement
                $previousLevelsInvestment = 0;
                foreach ($rewardLevels as $prevLevel) {
                    if ($prevLevel->id == $level->id) {
                        break;
                    }
                    if (in_array($prevLevel->id, $achievedLevelIds)) {
                        // Since requirements are cumulative, use the highest one
                        $previousLevelsInvestment = max($previousLevelsInvestment, (float) $prevLevel->investment_required);
                    }
                }
                
                // Calculate remaining investment needed for this level
                // Level 1 needs $10, Level 2 needs $40 total (so $30 more after Level 1)
                $remainingNeeded = (float) $level->investment_required - $previousLevelsInvestment;
                
                // Check if we have enough total investment to complete this level
                // We check if total referral investment meets or exceeds this level's requirement
                if ($totalReferralInvestment >= (float) $level->investment_required) {
                    // Complete this level
                    $rewardAmount = (float) $level->reward_amount;
                    
                    // Mark level as achieved
                    UserRewardLevel::create([
                        'user_id' => $user->id,
                        'reward_level_id' => $level->id,
                        'achieved_at' => now(),
                        'reward_amount_credited' => $rewardAmount,
                    ]);
                    
                    // Add reward to referral earning
                    $user->referral_earning += $rewardAmount;
                    $user->updateNetBalance();
                    
                    // Create transaction record for referral earning
                    Transaction::create([
                        'user_id' => $user->id,
                        'type' => 'referral_earning',
                        'amount' => $rewardAmount,
                        'description' => "Reward for completing {$level->level_name}",
                        'reference_id' => $level->id,
                        'reference_type' => RewardLevel::class,
                        'status' => 'completed',
                    ]);
                    
                    $completedLevels[] = [
                        'level_id' => $level->id,
                        'level_name' => $level->level_name,
                        'reward_amount' => $rewardAmount,
                    ];
                    
                    // Update achieved levels list for next iteration
                    $achievedLevelIds[] = $level->id;
                    
                    // Continue to next level (investment carries forward)
                } else {
                    // Not enough total investment to complete this level, stop processing
                    // This ensures only the current level is being worked on
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
                
                // Calculate how much investment has already been counted for previous levels
                // Since investment_required is cumulative, we need the highest achieved level's requirement
                $previousLevelsInvestment = 0;
                foreach ($rewardLevels as $prevLevel) {
                    if ($prevLevel->id == $level->id) {
                        break;
                    }
                    if (in_array($prevLevel->id, $achievedLevelIds)) {
                        // Since requirements are cumulative, use the highest one
                        $previousLevelsInvestment = max($previousLevelsInvestment, (float) $prevLevel->investment_required);
                    }
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
                        // Mark level as achieved
                        UserRewardLevel::create([
                            'user_id' => $user->id,
                            'reward_level_id' => $level->id,
                            'achieved_at' => now(),
                            'reward_amount_credited' => $rewardAmount,
                        ]);
                        
                        // Add reward to referral earning
                        $user->referral_earning += $rewardAmount;
                        $user->updateNetBalance();
                        
                        // Create transaction record for referral earning
                        Transaction::create([
                            'user_id' => $user->id,
                            'type' => 'referral_earning',
                            'amount' => $rewardAmount,
                            'description' => "Reward for completing {$level->level_name}",
                            'reference_id' => $level->id,
                            'reference_type' => RewardLevel::class,
                            'status' => 'completed',
                        ]);
                        
                        $completedLevels[] = [
                            'level_id' => $level->id,
                            'level_name' => $level->level_name,
                            'reward_amount' => $rewardAmount,
                        ];
                    }
                    
                    // Update achieved levels list for next iteration
                    $achievedLevelIds[] = $level->id;
                    
                    // Continue to next level (investment carries forward)
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
        // remainingNeeded is the difference between this level's requirement and previous levels' requirement
        $remainingNeeded = (float) $level->investment_required - $previousLevelsInvestment;
        $currentProgress = max(0, $totalReferralInvestment - $previousLevelsInvestment);
        $progressPercentage = $isAchieved ? 100 : min(100, ($currentProgress / $remainingNeeded) * 100);
        
        return [
            'is_achieved' => $isAchieved,
            'total_referral_investment' => $totalReferralInvestment,
            'previous_levels_investment' => $previousLevelsInvestment,
            'current_progress' => $currentProgress,
            'remaining_needed' => max(0, $remainingNeeded),
            'progress_percentage' => $progressPercentage,
        ];
    }
}


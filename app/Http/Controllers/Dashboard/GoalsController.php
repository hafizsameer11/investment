<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\RewardLevel;
use App\Services\RewardLevelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalsController extends Controller
{
    /**
     * Show the goals page.
     */
    public function index()
    {
        $user = Auth::user();
        
        // First, check and process any levels that should be completed based on current investment
        // This ensures levels are automatically marked as achieved when requirements are met
        RewardLevelService::checkAndProcessRewardLevels($user);
        
        // Refresh user data to get updated referral_earning
        $user->refresh();
        
        // Fetch all active reward levels ordered by sort_order
        $rewardLevels = RewardLevel::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('level')
            ->get();
        
        // Calculate total referral investment
        $totalReferralInvestment = RewardLevelService::calculateTotalReferralInvestment($user);
        
        // Get achieved level IDs (after processing)
        $achievedLevelIds = RewardLevelService::getUserAchievedLevels($user);
        
        // Get current reward level (highest achieved)
        $currentRewardLevel = $user->getCurrentRewardLevel();
        
        // Find next level to complete (this is the current level being worked on)
        $currentWorkingLevel = RewardLevelService::getNextLevelToComplete($user);
        $currentWorkingLevelId = $currentWorkingLevel ? $currentWorkingLevel->id : null;
        
        // Process all levels with their status
        $levelsWithProgress = $rewardLevels->map(function ($level) use ($user, $achievedLevelIds, $currentWorkingLevelId) {
            $progress = RewardLevelService::getUserLevelProgress($user, $level);
            $isAchieved = in_array($level->id, $achievedLevelIds);
            $isClaimed = $progress['is_claimed'] ?? false;
            // Only mark as current if it's the next uncompleted level (not achieved)
            $isCurrent = !$isAchieved && $currentWorkingLevelId && $level->id == $currentWorkingLevelId;
            
            return [
                'level' => $level,
                'is_achieved' => $isAchieved,
                'is_claimed' => $isClaimed,
                'is_current' => $isCurrent,
                'progress_percentage' => $progress['progress_percentage'],
                'current_progress' => $progress['current_progress'],
                'remaining_needed' => $progress['remaining_needed'],
                'total_referral_investment' => $progress['total_referral_investment'],
            ];
        });
        
        // Find next level to complete for the progress section
        $nextLevel = $currentWorkingLevel;
        $nextLevelProgress = null;
        if ($nextLevel) {
            $nextLevelProgress = RewardLevelService::getUserLevelProgress($user, $nextLevel);
        }
        
        return view('dashboard.pages.goals', [
            'rewardLevels' => $levelsWithProgress,
            'totalReferralInvestment' => $totalReferralInvestment,
            'currentRewardLevel' => $currentRewardLevel,
            'currentWorkingLevel' => $currentWorkingLevel,
            'nextLevel' => $nextLevel,
            'nextLevelProgress' => $nextLevelProgress,
        ]);
    }

    /**
     * Claim a reward for a completed level
     */
    public function claimReward(Request $request, int $levelId)
    {
        $user = Auth::user();
        
        $result = RewardLevelService::claimRewardLevel($user, $levelId);
        
        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'reward_amount' => $result['reward_amount'],
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 400);
        }
    }
}


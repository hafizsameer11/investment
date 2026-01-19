<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\InvestmentCommissionStructure;
use App\Models\EarningCommissionStructure;
use App\Models\PendingReferralCommission;
use App\Models\Investment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReferralsController extends Controller
{
    /**
     * Show the referrals page.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get all referrals recursively
        $allReferrals = $user->getAllReferralsRecursive(5);
        
        // Get filter level from request
        $filterLevel = $request->input('level', 'all');
        
        // Filter by level if specified
        if ($filterLevel !== 'all' && is_numeric($filterLevel)) {
            $allReferrals = $allReferrals->filter(function ($referral) use ($filterLevel) {
                return isset($referral->referral_level) && $referral->referral_level == (int)$filterLevel;
            });
        }
        
        // Enrich each referral with additional data
        $referralsData = $allReferrals->map(function ($referral) use ($user) {
            // Get referrer details
            $referrer = $referral->referrer;
            
            // Calculate invested amount from investments table
            $investedAmount = Investment::where('user_id', $referral->id)
                ->where('status', 'active')
                ->sum('amount');
            
            // Calculate referral earning from pending commissions for this specific referral
            $level = $referral->referral_level ?? 0;
            $referralEarning = PendingReferralCommission::where('referrer_id', $user->id)
                ->where('investor_id', $referral->id)
                ->where('level', $level)
                ->where('is_claimed', false)
                ->sum('commission_amount');
            
            // Get level name from commission structure
            $levelName = 'level' . $level;
            
            return [
                'id' => $referral->id,
                'name' => $referral->name,
                'username' => $referral->username,
                'email' => $referral->email,
                'phone' => $referral->phone,
                'referral_code' => $referral->refer_code,
                'level' => $level,
                'level_name' => $levelName,
                'invested_amount' => $investedAmount,
                'referral_earning' => $referralEarning,
                'created_at' => $referral->created_at,
                'referred_by' => [
                    'name' => $referrer->name ?? 'N/A',
                    'email' => $referrer->email ?? 'N/A',
                    'phone' => $referrer->phone ?? 'N/A',
                ],
            ];
        });
        
        // Sort by created_at descending (newest first)
        $referralsData = $referralsData->sortByDesc('created_at')->values();
        
        // Paginate results
        $perPage = 15;
        $currentPage = $request->input('page', 1);
        $items = $referralsData->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $total = $referralsData->count();
        
        // Create paginator manually
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
        
        // Get commission structures
        $investmentCommissions = InvestmentCommissionStructure::where('is_active', true)
            ->orderBy('level')
            ->get();
        
        $earningCommissions = EarningCommissionStructure::where('is_active', true)
            ->orderBy('level')
            ->get();
        
        // Get referrer info for current user
        $currentUserReferrer = $user->referrer;
        
        // Calculate total referrals count
        $totalReferrals = $allReferrals->count();
        
        // Calculate pending referral earnings (unclaimed)
        $pendingReferralEarnings = PendingReferralCommission::where('referrer_id', $user->id)
            ->where('is_claimed', false)
            ->sum('commission_amount');
        
        // Calculate total claimed referral earnings
        $claimedReferralEarnings = PendingReferralCommission::where('referrer_id', $user->id)
            ->where('is_claimed', true)
            ->sum('commission_amount');
        
        // Get pending commissions breakdown by level
        $pendingCommissionsByLevel = PendingReferralCommission::where('referrer_id', $user->id)
            ->where('is_claimed', false)
            ->select('level', DB::raw('SUM(commission_amount) as total'))
            ->groupBy('level')
            ->orderBy('level')
            ->pluck('total', 'level')
            ->toArray();
        
        return view('dashboard.pages.referrals', [
            'referrals' => $paginator,
            'investmentCommissions' => $investmentCommissions,
            'earningCommissions' => $earningCommissions,
            'currentUserReferrer' => $currentUserReferrer,
            'totalReferrals' => $totalReferrals,
            'pendingReferralEarnings' => $pendingReferralEarnings,
            'claimedReferralEarnings' => $claimedReferralEarnings,
            'pendingCommissionsByLevel' => $pendingCommissionsByLevel,
            'currentLevel' => $filterLevel,
            'user' => $user,
        ]);
    }

    /**
     * Claim all pending referral earnings
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function claimEarnings(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Get all pending commissions for this user
            $pendingCommissions = PendingReferralCommission::where('referrer_id', $user->id)
                ->where('is_claimed', false)
                ->get();
            
            if ($pendingCommissions->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No pending earnings to claim.',
                ], 422);
            }
            
            // Calculate total amount to claim
            $totalAmount = $pendingCommissions->sum('commission_amount');
            
            DB::beginTransaction();
            
            try {
                // Transfer to user's referral_earning field
                $user->referral_earning = ($user->referral_earning ?? 0) + $totalAmount;
                $user->updateNetBalance();
                $user->save();
                
                // Mark all pending commissions as claimed
                $now = now();
                PendingReferralCommission::where('referrer_id', $user->id)
                    ->where('is_claimed', false)
                    ->update([
                        'is_claimed' => true,
                        'claimed_at' => $now,
                    ]);
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Earnings claimed successfully.',
                    'claimed_amount' => number_format($totalAmount, 2, '.', ''),
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error claiming referral earnings: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                ]);
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error in claimEarnings: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to claim earnings. Please try again.',
            ], 500);
        }
    }
}


<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\InvestmentCommissionStructure;
use App\Models\EarningCommissionStructure;
use App\Models\PendingReferralCommission;
use App\Models\PendingEarningCommission;
use App\Models\Investment;
use App\Models\Transaction;
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
            $investmentCommission = PendingReferralCommission::where('referrer_id', $user->id)
                ->where('investor_id', $referral->id)
                ->where('level', $level)
                ->where('is_claimed', false)
                ->sum('commission_amount');
            $earningCommission = PendingEarningCommission::where('referrer_id', $user->id)
                ->where('investor_id', $referral->id)
                ->where('level', $level)
                ->where('is_claimed', false)
                ->sum('commission_amount');
            $referralEarning = $investmentCommission + $earningCommission;
            
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
        $perPage = 5;
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
            ->whereNull('mining_plan_id')
            ->orderBy('level')
            ->get();
        
        // Get referrer info for current user
        $currentUserReferrer = $user->referrer;
        
        // Calculate total referrals count
        $totalReferrals = $allReferrals->count();
        
        // Calculate pending investment commissions (unclaimed)
        $pendingInvestmentCommissions = PendingReferralCommission::where('referrer_id', $user->id)
            ->where('is_claimed', false)
            ->sum('commission_amount');
        
        // Calculate pending earning commissions (unclaimed)
        $pendingEarningCommissions = PendingEarningCommission::where('referrer_id', $user->id)
            ->where('is_claimed', false)
            ->sum('commission_amount');
        
        // Combined pending referral earnings
        $pendingReferralEarnings = $pendingInvestmentCommissions + $pendingEarningCommissions;
        
        // Calculate total claimed investment commissions
        $claimedInvestmentCommissions = PendingReferralCommission::where('referrer_id', $user->id)
            ->where('is_claimed', true)
            ->sum('commission_amount');
        
        // Calculate total claimed earning commissions
        $claimedEarningCommissions = PendingEarningCommission::where('referrer_id', $user->id)
            ->where('is_claimed', true)
            ->sum('commission_amount');
        
        // Combined claimed referral earnings
        $claimedReferralEarnings = $claimedInvestmentCommissions + $claimedEarningCommissions;
        
        // Get pending investment commissions breakdown by level
        $pendingInvestmentCommissionsByLevel = PendingReferralCommission::where('referrer_id', $user->id)
            ->where('is_claimed', false)
            ->select('level', DB::raw('SUM(commission_amount) as total'))
            ->groupBy('level')
            ->orderBy('level')
            ->pluck('total', 'level')
            ->toArray();
        
        // Get pending earning commissions breakdown by level
        $pendingEarningCommissionsByLevel = PendingEarningCommission::where('referrer_id', $user->id)
            ->where('is_claimed', false)
            ->select('level', DB::raw('SUM(commission_amount) as total'))
            ->groupBy('level')
            ->orderBy('level')
            ->pluck('total', 'level')
            ->toArray();
        
        // Combine both breakdowns by level
        $pendingCommissionsByLevel = [];
        for ($i = 1; $i <= 5; $i++) {
            $pendingCommissionsByLevel[$i] = ($pendingInvestmentCommissionsByLevel[$i] ?? 0) + ($pendingEarningCommissionsByLevel[$i] ?? 0);
        }
        
        // Return JSON for AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'referrals' => $paginator->items(),
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                    'has_more_pages' => $paginator->hasMorePages(),
                    'previous_page_url' => $paginator->previousPageUrl(),
                    'next_page_url' => $paginator->nextPageUrl(),
                    'url_range' => $paginator->getUrlRange(1, $paginator->lastPage()),
                ],
                'current_level' => $filterLevel,
            ]);
        }
        
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
     * Show the referral earnings claim page (only the wallet section)
     */
    public function claimEarningsPage()
    {
        $user = Auth::user();
        
        // Calculate pending investment commissions (unclaimed)
        $pendingInvestmentCommissions = PendingReferralCommission::where('referrer_id', $user->id)
            ->where('is_claimed', false)
            ->sum('commission_amount');
        
        // Calculate pending earning commissions (unclaimed)
        $pendingEarningCommissions = PendingEarningCommission::where('referrer_id', $user->id)
            ->where('is_claimed', false)
            ->sum('commission_amount');
        
        // Get pending investment commissions breakdown by level
        $pendingInvestmentCommissionsByLevel = PendingReferralCommission::where('referrer_id', $user->id)
            ->where('is_claimed', false)
            ->select('level', DB::raw('SUM(commission_amount) as total'))
            ->groupBy('level')
            ->orderBy('level')
            ->pluck('total', 'level')
            ->toArray();
        
        // Get pending earning commissions breakdown by level
        $pendingEarningCommissionsByLevel = PendingEarningCommission::where('referrer_id', $user->id)
            ->where('is_claimed', false)
            ->select('level', DB::raw('SUM(commission_amount) as total'))
            ->groupBy('level')
            ->orderBy('level')
            ->pluck('total', 'level')
            ->toArray();
        
        return view('dashboard.pages.referral-earnings-claim', [
            'pendingInvestmentCommissions' => $pendingInvestmentCommissions,
            'pendingEarningCommissions' => $pendingEarningCommissions,
            'pendingInvestmentCommissionsByLevel' => $pendingInvestmentCommissionsByLevel,
            'pendingEarningCommissionsByLevel' => $pendingEarningCommissionsByLevel,
            'user' => $user,
        ]);
    }

    /**
     * Claim pending referral earnings (investment or earning commissions separately)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function claimEarnings(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Validate the type parameter
            $type = $request->input('type'); // 'investment' or 'earning'
            
            if (!in_array($type, ['investment', 'earning'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid claim type. Must be "investment" or "earning".',
                ], 422);
            }
            
            $amount = 0;
            $now = now();
            
            DB::beginTransaction();
            
            try {
                if ($type === 'investment') {
                    // Get all pending investment commissions for this user
                    $pendingInvestmentCommissions = PendingReferralCommission::where('referrer_id', $user->id)
                        ->where('is_claimed', false)
                        ->get();
                    
                    $amount = $pendingInvestmentCommissions->sum('commission_amount');
                    
                    if ($amount <= 0) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'No pending investment commission earnings to claim.',
                        ], 422);
                    }
                    
                    // Transfer to user's referral_earning field
                    $user->referral_earning = ($user->referral_earning ?? 0) + $amount;
                    $user->updateNetBalance();
                    $user->save();
                    
                    // Mark all pending investment commissions as claimed
                    if ($pendingInvestmentCommissions->isNotEmpty()) {
                        PendingReferralCommission::where('referrer_id', $user->id)
                            ->where('is_claimed', false)
                            ->update([
                                'is_claimed' => true,
                                'claimed_at' => $now,
                            ]);
                    }
                    
                    // Create transaction record for referral earning
                    Transaction::create([
                        'user_id' => $user->id,
                        'type' => 'referral_earning',
                        'amount' => $amount,
                        'description' => 'Investment commission earnings claimed',
                        'status' => 'completed',
                    ]);
                } else { // earning
                    // Get all pending earning commissions for this user
                    $pendingEarningCommissions = PendingEarningCommission::where('referrer_id', $user->id)
                        ->where('is_claimed', false)
                        ->get();
                    
                    $amount = $pendingEarningCommissions->sum('commission_amount');
                    
                    // Minimum amount required to claim earning commissions is $0.2
                    $minimumAmount = 0.2;
                    
                    if ($amount < $minimumAmount) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Minimum $' . number_format($minimumAmount, 2) . ' required to claim earning commission earnings. Current balance: $' . number_format($amount, 2) . '.',
                        ], 422);
                    }
                    
                    // Transfer to user's referral_earning field
                    $user->referral_earning = ($user->referral_earning ?? 0) + $amount;
                    $user->updateNetBalance();
                    $user->save();
                    
                    // Mark all pending earning commissions as claimed
                    if ($pendingEarningCommissions->isNotEmpty()) {
                        PendingEarningCommission::where('referrer_id', $user->id)
                            ->where('is_claimed', false)
                            ->update([
                                'is_claimed' => true,
                                'claimed_at' => $now,
                            ]);
                    }
                    
                    // Create transaction record for referral earning
                    Transaction::create([
                        'user_id' => $user->id,
                        'type' => 'referral_earning',
                        'amount' => $amount,
                        'description' => 'Team earning commission earnings claimed',
                        'status' => 'completed',
                    ]);
                }
                
                DB::commit();
                
                $typeLabel = $type === 'investment' ? 'Investment commission' : 'Earning commission';
                
                return response()->json([
                    'success' => true,
                    'message' => $typeLabel . ' earnings claimed successfully.',
                    'claimed_amount' => number_format($amount, 2, '.', ''),
                    'type' => $type,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error claiming referral earnings: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'type' => $type,
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


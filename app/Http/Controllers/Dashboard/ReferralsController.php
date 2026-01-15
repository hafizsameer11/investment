<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\InvestmentCommissionStructure;
use App\Models\EarningCommissionStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            
            // Calculate invested amount (placeholder - to be implemented when investment system is ready)
            $investedAmount = 0; // TODO: Calculate from investments table
            
            // Calculate referral earning (placeholder - to be implemented when earnings system is ready)
            $referralEarning = 0; // TODO: Calculate from earnings/commissions table
            
            // Get level name from commission structure
            $level = $referral->referral_level ?? 0;
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
        
        // Calculate total referral earnings (placeholder)
        $totalReferralEarnings = 0; // TODO: Calculate from earnings/commissions table
        
        return view('dashboard.pages.referrals', [
            'referrals' => $paginator,
            'investmentCommissions' => $investmentCommissions,
            'earningCommissions' => $earningCommissions,
            'currentUserReferrer' => $currentUserReferrer,
            'totalReferrals' => $totalReferrals,
            'totalReferralEarnings' => $totalReferralEarnings,
            'currentLevel' => $filterLevel,
            'user' => $user,
        ]);
    }
}


<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\MiningPlan;

class PlansController extends Controller
{
    /**
     * Show the investment plans page.
     */
    public function index()
    {
        $plans = MiningPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
        
        $inactivePlans = MiningPlan::where('is_active', false)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        // Get user's active investments for each plan
        $user = auth()->user();
        $userInvestments = [];
        
        if ($user) {
            $investments = Investment::where('user_id', $user->id)
                ->where('status', 'active')
                ->with('miningPlan')
                ->get();
            
            foreach ($investments as $investment) {
                $userInvestments[$investment->mining_plan_id] = [
                    'id' => $investment->id,
                    'amount' => $investment->amount,
                    'unclaimed_profit' => $investment->unclaimed_profit ?? 0,
                    'last_claimed_at' => $investment->last_claimed_at,
                ];
            }
        }
        
        return view('dashboard.pages.plans', compact('plans', 'inactivePlans', 'userInvestments'));
    }
}


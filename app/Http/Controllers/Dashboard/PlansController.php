<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
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
        
        return view('dashboard.pages.plans', compact('plans', 'inactivePlans'));
    }
}


<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CurrencyConversion;

class SettingsController extends Controller
{
    /**
     * Show the settings page.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Calculate total earnings (mining + referral)
        $miningEarning = $user->mining_earning ?? 0;
        $referralEarning = $user->referral_earning ?? 0;
        $totalEarningsUSD = $miningEarning + $referralEarning;
        
        // Get active currency conversion rate (fallback to any rate if no active one)
        $currencyConversion = CurrencyConversion::where('is_active', true)->first();
        if (!$currencyConversion) {
            $currencyConversion = CurrencyConversion::first();
        }
        $conversionRate = $currencyConversion ? (float) $currencyConversion->rate : 0;
        
        // Calculate PKR earnings
        $totalEarningsPKR = $totalEarningsUSD * $conversionRate;
        
        return view('dashboard.pages.settings', [
            'miningEarning' => $miningEarning,
            'referralEarning' => $referralEarning,
            'totalEarningsUSD' => $totalEarningsUSD,
            'totalEarningsPKR' => $totalEarningsPKR,
            'conversionRate' => $conversionRate,
        ]);
    }
}


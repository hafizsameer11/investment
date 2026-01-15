<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the dashboard home page.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Calculate total invested from approved deposits if not set
        if ($user->total_invested == 0) {
            $totalInvested = $user->deposits()
                ->where('status', 'approved')
                ->sum('amount');
            
            if ($totalInvested > 0) {
                $user->total_invested = $totalInvested;
                $user->save();
            }
        }
        
        return view('dashboard.index', compact('user'));
    }
}


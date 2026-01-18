<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Investment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(){
        // Get total users count
        $totalUsers = User::where('role', '!=', 'admin')->count();
        
        // Get this month users count
        $thisMonthUsers = User::where('role', '!=', 'admin')
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();
        
        // Get last month users count
        $lastMonthUsers = User::where('role', '!=', 'admin')
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->count();
        
        // Get top 5 users by referral count
        $topReferrers = User::where('role', '!=', 'admin')
            ->withCount('directReferrals')
            ->orderBy('direct_referrals_count', 'desc')
            ->limit(5)
            ->get();
        
        return view('admin.index', compact('totalUsers', 'thisMonthUsers', 'lastMonthUsers', 'topReferrers'));
    }

    public function getFinancialData(Request $request)
    {
        $period = $request->input('period', 'monthly'); // weekly, monthly, yearly
        
        $data = [];
        
        if ($period === 'weekly') {
            // Get last 12 weeks
            $startDate = Carbon::now()->subWeeks(11)->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
            
            $current = $startDate->copy();
            while ($current <= $endDate) {
                $weekStart = $current->copy()->startOfWeek();
                $weekEnd = $current->copy()->endOfWeek();
                
                $deposit = Deposit::where('status', 'approved')
                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->sum('amount') ?? 0;
                
                $withdrawal = Withdrawal::where('status', 'approved')
                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->sum('amount') ?? 0;
                
                $revenue = Investment::whereBetween('created_at', [$weekStart, $weekEnd])
                    ->sum('amount') ?? 0;
                
                $data[] = [
                    'period' => 'Week ' . $weekStart->format('W'),
                    'deposit' => (float) $deposit,
                    'withdrawal' => (float) $withdrawal,
                    'revenue' => (float) $revenue,
                ];
                
                $current->addWeek();
            }
        } elseif ($period === 'monthly') {
            // Get last 12 months
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthStart = $date->copy()->startOfMonth();
                $monthEnd = $date->copy()->endOfMonth();
                
                $deposit = Deposit::where('status', 'approved')
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->sum('amount') ?? 0;
                
                $withdrawal = Withdrawal::where('status', 'approved')
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->sum('amount') ?? 0;
                
                $revenue = Investment::whereBetween('created_at', [$monthStart, $monthEnd])
                    ->sum('amount') ?? 0;
                
                $data[] = [
                    'period' => $date->format('M'),
                    'deposit' => (float) $deposit,
                    'withdrawal' => (float) $withdrawal,
                    'revenue' => (float) $revenue,
                ];
            }
        } else { // yearly
            // Get last 12 years
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subYears($i);
                $yearStart = $date->copy()->startOfYear();
                $yearEnd = $date->copy()->endOfYear();
                
                $deposit = Deposit::where('status', 'approved')
                    ->whereBetween('created_at', [$yearStart, $yearEnd])
                    ->sum('amount') ?? 0;
                
                $withdrawal = Withdrawal::where('status', 'approved')
                    ->whereBetween('created_at', [$yearStart, $yearEnd])
                    ->sum('amount') ?? 0;
                
                $revenue = Investment::whereBetween('created_at', [$yearStart, $yearEnd])
                    ->sum('amount') ?? 0;
                
                $data[] = [
                    'period' => $date->format('Y'),
                    'deposit' => (float) $deposit,
                    'withdrawal' => (float) $withdrawal,
                    'revenue' => (float) $revenue,
                ];
            }
        }
        
        return response()->json($data);
    }
    public function form(){
        return view('admin.pages.form');
    }
    public function table(){
        return view('admin.pages.table');
    }
}

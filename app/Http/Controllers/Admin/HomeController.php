<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Investment;
use App\Models\Transaction;
use App\Models\Chat;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(){
        // User Statistics
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $thisMonthUsers = User::where('role', '!=', 'admin')
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();
        $lastMonthUsers = User::where('role', '!=', 'admin')
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->count();
        $userGrowth = $lastMonthUsers > 0 ? (($thisMonthUsers - $lastMonthUsers) / $lastMonthUsers) * 100 : 0;
        
        // Revenue Statistics
        $totalRevenue = Investment::sum('amount') ?? 0;
        $totalDeposits = Deposit::where('status', 'approved')->sum('amount') ?? 0;
        $totalWithdrawals = Withdrawal::where('status', 'approved')->sum('amount') ?? 0;
        $platformBalance = $totalDeposits - $totalWithdrawals;
        
        // Pending Statistics
        $pendingDepositsCount = Deposit::where('status', 'pending')->count();
        $pendingDepositsAmount = Deposit::where('status', 'pending')->sum('amount') ?? 0;
        $pendingWithdrawalsCount = Withdrawal::where('status', 'pending')->count();
        $pendingWithdrawalsAmount = Withdrawal::where('status', 'pending')->sum('amount') ?? 0;
        
        // Investment Statistics
        $totalInvestments = Investment::count();
        $activeInvestmentsCount = Investment::where('status', 'active')->count();
        $activeInvestmentsAmount = Investment::where('status', 'active')->sum('amount') ?? 0;
        $completedInvestments = Investment::where('status', 'completed')->count();
        $cancelledInvestments = Investment::where('status', 'cancelled')->count();
        
        // Earnings Statistics
        $totalMiningEarnings = User::where('role', '!=', 'admin')->sum('mining_earning') ?? 0;
        $totalReferralEarnings = User::where('role', '!=', 'admin')->sum('referral_earning') ?? 0;
        $totalEarningsPaid = $totalMiningEarnings + $totalReferralEarnings;
        
        // Calculate from transactions for more accuracy
        $totalMiningEarningsFromTransactions = Transaction::where('type', 'mining_earning')
            ->where('status', 'completed')
            ->sum('amount') ?? 0;
        $totalReferralEarningsFromTransactions = Transaction::where('type', 'referral_earning')
            ->where('status', 'completed')
            ->sum('amount') ?? 0;
        
        // Use transactions if available, otherwise use user balances
        if ($totalMiningEarningsFromTransactions > 0) {
            $totalMiningEarnings = $totalMiningEarningsFromTransactions;
        }
        if ($totalReferralEarningsFromTransactions > 0) {
            $totalReferralEarnings = $totalReferralEarningsFromTransactions;
        }
        $totalEarningsPaid = $totalMiningEarnings + $totalReferralEarnings;
        
        // Platform Profit
        $platformProfit = $totalRevenue - $totalEarningsPaid;
        
        // KPI Calculations
        $activeUsers = User::where('role', '!=', 'admin')
            ->whereHas('investments', function($query) {
                $query->where('status', 'active');
            })
            ->count();
        $conversionRate = $totalUsers > 0 ? ($activeUsers / $totalUsers) * 100 : 0;
        $averageInvestment = $totalInvestments > 0 ? $totalRevenue / $totalInvestments : 0;
        $depositCount = Deposit::where('status', 'approved')->count();
        $averageDeposit = $depositCount > 0 ? $totalDeposits / $depositCount : 0;
        $usersWithReferrals = User::where('role', '!=', 'admin')
            ->has('directReferrals')
            ->count();
        $referralRate = $totalUsers > 0 ? ($usersWithReferrals / $totalUsers) * 100 : 0;
        
        // Active users in last 30 days (users who made transactions or investments)
        $activeUsersLast30Days = User::where('role', '!=', 'admin')
            ->where(function($query) {
                $query->whereHas('investments', function($q) {
                    $q->where('created_at', '>=', Carbon::now()->subDays(30));
                })
                ->orWhereHas('transactions', function($q) {
                    $q->where('created_at', '>=', Carbon::now()->subDays(30));
                });
            })
            ->count();
        $userRetention = $totalUsers > 0 ? ($activeUsersLast30Days / $totalUsers) * 100 : 0;
        
        // Today's Activity
        $todayUsers = User::where('role', '!=', 'admin')
            ->whereDate('created_at', Carbon::today())
            ->count();
        $todayDeposits = Deposit::whereDate('created_at', Carbon::today())
            ->where('status', 'approved')
            ->sum('amount') ?? 0;
        $todayWithdrawals = Withdrawal::whereDate('created_at', Carbon::today())
            ->where('status', 'approved')
            ->sum('amount') ?? 0;
        $todayInvestments = Investment::whereDate('created_at', Carbon::today())
            ->sum('amount') ?? 0;
        
        // Recent Activity Data
        $recentUsers = User::where('role', '!=', 'admin')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $recentDeposits = Deposit::with(['user', 'paymentMethod'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $recentWithdrawals = Withdrawal::with(['user', 'paymentMethod'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $recentInvestments = Investment::with(['user', 'miningPlan'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Top 5 users by referral count
        $topReferrers = User::where('role', '!=', 'admin')
            ->withCount('directReferrals')
            ->orderBy('direct_referrals_count', 'desc')
            ->limit(5)
            ->get();
        
        // Top investors
        $topInvestors = User::where('role', '!=', 'admin')
            ->select('users.id', 'users.name', 'users.email', 'users.username', 'users.created_at', 
                     DB::raw('COALESCE(SUM(investments.amount), 0) as total_invested'))
            ->leftJoin('investments', 'users.id', '=', 'investments.user_id')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.username', 'users.created_at')
            ->orderBy('total_invested', 'desc')
            ->limit(5)
            ->get();
        
        // Top earners
        $topEarners = User::where('role', '!=', 'admin')
            ->orderByRaw('(COALESCE(mining_earning, 0) + COALESCE(referral_earning, 0)) DESC')
            ->limit(5)
            ->get();
        
        return view('admin.index', compact(
            'totalUsers', 'thisMonthUsers', 'lastMonthUsers', 'userGrowth',
            'totalRevenue', 'totalDeposits', 'totalWithdrawals', 'platformBalance',
            'pendingDepositsCount', 'pendingDepositsAmount',
            'pendingWithdrawalsCount', 'pendingWithdrawalsAmount',
            'totalInvestments', 'activeInvestmentsCount', 'activeInvestmentsAmount',
            'completedInvestments', 'cancelledInvestments',
            'totalMiningEarnings', 'totalReferralEarnings', 'totalEarningsPaid',
            'platformProfit', 'conversionRate', 'averageInvestment', 'averageDeposit',
            'referralRate', 'userRetention', 'activeUsers',
            'todayUsers', 'todayDeposits', 'todayWithdrawals', 'todayInvestments',
            'recentUsers', 'recentDeposits', 'recentWithdrawals', 'recentInvestments',
            'topReferrers', 'topInvestors', 'topEarners'
        ));
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
                
                $netRevenue = $deposit - $withdrawal;
                
                $data[] = [
                    'period' => 'Week ' . $weekStart->format('W'),
                    'deposit' => (float) $deposit,
                    'withdrawal' => (float) $withdrawal,
                    'revenue' => (float) $revenue,
                    'netRevenue' => (float) $netRevenue,
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
                
                $netRevenue = $deposit - $withdrawal;
                
                $data[] = [
                    'period' => $date->format('M'),
                    'deposit' => (float) $deposit,
                    'withdrawal' => (float) $withdrawal,
                    'revenue' => (float) $revenue,
                    'netRevenue' => (float) $netRevenue,
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
                
                $netRevenue = $deposit - $withdrawal;
                
                $data[] = [
                    'period' => $date->format('Y'),
                    'deposit' => (float) $deposit,
                    'withdrawal' => (float) $withdrawal,
                    'revenue' => (float) $revenue,
                    'netRevenue' => (float) $netRevenue,
                ];
            }
        }
        
        return response()->json($data);
    }
    
    public function getUserGrowthData(Request $request)
    {
        $period = $request->input('period', 'monthly');
        $data = [];
        
        if ($period === 'weekly') {
            $startDate = Carbon::now()->subWeeks(11)->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
            $current = $startDate->copy();
            
            while ($current <= $endDate) {
                $weekStart = $current->copy()->startOfWeek();
                $weekEnd = $current->copy()->endOfWeek();
                
                $users = User::where('role', '!=', 'admin')
                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->count();
                
                $data[] = [
                    'period' => 'Week ' . $weekStart->format('W'),
                    'users' => $users,
                ];
                
                $current->addWeek();
            }
        } elseif ($period === 'monthly') {
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthStart = $date->copy()->startOfMonth();
                $monthEnd = $date->copy()->endOfMonth();
                
                $users = User::where('role', '!=', 'admin')
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->count();
                
                $data[] = [
                    'period' => $date->format('M Y'),
                    'users' => $users,
                ];
            }
        } else {
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subYears($i);
                $yearStart = $date->copy()->startOfYear();
                $yearEnd = $date->copy()->endOfYear();
                
                $users = User::where('role', '!=', 'admin')
                    ->whereBetween('created_at', [$yearStart, $yearEnd])
                    ->count();
                
                $data[] = [
                    'period' => $date->format('Y'),
                    'users' => $users,
                ];
            }
        }
        
        return response()->json($data);
    }
    
    public function getInvestmentPerformanceData(Request $request)
    {
        $period = $request->input('period', 'monthly');
        $data = [];
        
        if ($period === 'weekly') {
            $startDate = Carbon::now()->subWeeks(11)->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
            $current = $startDate->copy();
            
            while ($current <= $endDate) {
                $weekStart = $current->copy()->startOfWeek();
                $weekEnd = $current->copy()->endOfWeek();
                
                $active = Investment::where('status', 'active')
                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->sum('amount') ?? 0;
                $completed = Investment::where('status', 'completed')
                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->sum('amount') ?? 0;
                $cancelled = Investment::where('status', 'cancelled')
                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->sum('amount') ?? 0;
                
                $data[] = [
                    'period' => 'Week ' . $weekStart->format('W'),
                    'active' => (float) $active,
                    'completed' => (float) $completed,
                    'cancelled' => (float) $cancelled,
                ];
                
                $current->addWeek();
            }
        } elseif ($period === 'monthly') {
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthStart = $date->copy()->startOfMonth();
                $monthEnd = $date->copy()->endOfMonth();
                
                $active = Investment::where('status', 'active')
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->sum('amount') ?? 0;
                $completed = Investment::where('status', 'completed')
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->sum('amount') ?? 0;
                $cancelled = Investment::where('status', 'cancelled')
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->sum('amount') ?? 0;
                
                $data[] = [
                    'period' => $date->format('M'),
                    'active' => (float) $active,
                    'completed' => (float) $completed,
                    'cancelled' => (float) $cancelled,
                ];
            }
        } else {
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subYears($i);
                $yearStart = $date->copy()->startOfYear();
                $yearEnd = $date->copy()->endOfYear();
                
                $active = Investment::where('status', 'active')
                    ->whereBetween('created_at', [$yearStart, $yearEnd])
                    ->sum('amount') ?? 0;
                $completed = Investment::where('status', 'completed')
                    ->whereBetween('created_at', [$yearStart, $yearEnd])
                    ->sum('amount') ?? 0;
                $cancelled = Investment::where('status', 'cancelled')
                    ->whereBetween('created_at', [$yearStart, $yearEnd])
                    ->sum('amount') ?? 0;
                
                $data[] = [
                    'period' => $date->format('Y'),
                    'active' => (float) $active,
                    'completed' => (float) $completed,
                    'cancelled' => (float) $cancelled,
                ];
            }
        }
        
        return response()->json($data);
    }
    
    public function getEarningsBreakdownData()
    {
        $miningEarnings = Transaction::where('type', 'mining_earning')
            ->where('status', 'completed')
            ->sum('amount') ?? 0;
        
        $referralEarnings = Transaction::where('type', 'referral_earning')
            ->where('status', 'completed')
            ->sum('amount') ?? 0;
        
        // Fallback to user balances if transactions are empty
        if ($miningEarnings == 0) {
            $miningEarnings = User::where('role', '!=', 'admin')->sum('mining_earning') ?? 0;
        }
        if ($referralEarnings == 0) {
            $referralEarnings = User::where('role', '!=', 'admin')->sum('referral_earning') ?? 0;
        }
        
        $total = $miningEarnings + $referralEarnings;
        
        return response()->json([
            [
                'label' => 'Mining Earnings',
                'value' => (float) $miningEarnings,
                'percentage' => $total > 0 ? round(($miningEarnings / $total) * 100, 2) : 0,
            ],
            [
                'label' => 'Referral Earnings',
                'value' => (float) $referralEarnings,
                'percentage' => $total > 0 ? round(($referralEarnings / $total) * 100, 2) : 0,
            ],
        ]);
    }
    
    public function getTransactionActivityData(Request $request)
    {
        $period = $request->input('period', 'monthly');
        $data = [];
        
        if ($period === 'weekly') {
            $startDate = Carbon::now()->subWeeks(11)->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
            $current = $startDate->copy();
            
            while ($current <= $endDate) {
                $weekStart = $current->copy()->startOfWeek();
                $weekEnd = $current->copy()->endOfWeek();
                
                $deposits = Deposit::where('status', 'approved')
                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->sum('amount') ?? 0;
                $withdrawals = Withdrawal::where('status', 'approved')
                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->sum('amount') ?? 0;
                $miningEarnings = Transaction::where('type', 'mining_earning')
                    ->where('status', 'completed')
                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->sum('amount') ?? 0;
                $referralEarnings = Transaction::where('type', 'referral_earning')
                    ->where('status', 'completed')
                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->sum('amount') ?? 0;
                
                $data[] = [
                    'period' => 'Week ' . $weekStart->format('W'),
                    'deposits' => (float) $deposits,
                    'withdrawals' => (float) $withdrawals,
                    'miningEarnings' => (float) $miningEarnings,
                    'referralEarnings' => (float) $referralEarnings,
                ];
                
                $current->addWeek();
            }
        } elseif ($period === 'monthly') {
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthStart = $date->copy()->startOfMonth();
                $monthEnd = $date->copy()->endOfMonth();
                
                $deposits = Deposit::where('status', 'approved')
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->sum('amount') ?? 0;
                $withdrawals = Withdrawal::where('status', 'approved')
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->sum('amount') ?? 0;
                $miningEarnings = Transaction::where('type', 'mining_earning')
                    ->where('status', 'completed')
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->sum('amount') ?? 0;
                $referralEarnings = Transaction::where('type', 'referral_earning')
                    ->where('status', 'completed')
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->sum('amount') ?? 0;
                
                $data[] = [
                    'period' => $date->format('M'),
                    'deposits' => (float) $deposits,
                    'withdrawals' => (float) $withdrawals,
                    'miningEarnings' => (float) $miningEarnings,
                    'referralEarnings' => (float) $referralEarnings,
                ];
            }
        } else {
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subYears($i);
                $yearStart = $date->copy()->startOfYear();
                $yearEnd = $date->copy()->endOfYear();
                
                $deposits = Deposit::where('status', 'approved')
                    ->whereBetween('created_at', [$yearStart, $yearEnd])
                    ->sum('amount') ?? 0;
                $withdrawals = Withdrawal::where('status', 'approved')
                    ->whereBetween('created_at', [$yearStart, $yearEnd])
                    ->sum('amount') ?? 0;
                $miningEarnings = Transaction::where('type', 'mining_earning')
                    ->where('status', 'completed')
                    ->whereBetween('created_at', [$yearStart, $yearEnd])
                    ->sum('amount') ?? 0;
                $referralEarnings = Transaction::where('type', 'referral_earning')
                    ->where('status', 'completed')
                    ->whereBetween('created_at', [$yearStart, $yearEnd])
                    ->sum('amount') ?? 0;
                
                $data[] = [
                    'period' => $date->format('Y'),
                    'deposits' => (float) $deposits,
                    'withdrawals' => (float) $withdrawals,
                    'miningEarnings' => (float) $miningEarnings,
                    'referralEarnings' => (float) $referralEarnings,
                ];
            }
        }
        
        return response()->json($data);
    }
    public function getPendingCounts()
    {
        $pendingDepositsCount = Deposit::where('status', 'pending')->count();
        $pendingWithdrawalsCount = Withdrawal::where('status', 'pending')->count();
        
        // Get unread chats count
        $unreadChatsCount = Chat::where('status', 'pending')
            ->orWhere(function ($query) {
                $query->where('status', 'active')
                    ->whereHas('messages', function ($q) {
                        $q->where('sender_type', 'user')
                            ->where('is_read', false);
                    });
            })
            ->count();
        
        return response()->json([
            'success' => true,
            'pending_deposits_count' => $pendingDepositsCount,
            'pending_withdrawals_count' => $pendingWithdrawalsCount,
            'unread_chats_count' => $unreadChatsCount,
        ]);
    }
    
    public function getAdminNotifications()
    {
        $notifications = [];
        
        // Get pending deposits
        $pendingDepositsCount = Deposit::where('status', 'pending')->count();
        if ($pendingDepositsCount > 0) {
            $pendingDepositsAmount = Deposit::where('status', 'pending')->sum('amount') ?? 0;
            $notifications[] = [
                'type' => 'deposit',
                'icon' => 'mdi-cash-multiple',
                'icon_bg' => 'bg-primary',
                'title' => 'Pending Deposits',
                'message' => "{$pendingDepositsCount} pending deposit(s) totaling $" . number_format($pendingDepositsAmount, 2) . " require approval",
                'count' => $pendingDepositsCount,
                'url' => route('admin.deposits.index'),
                'time' => Carbon::now()->diffForHumans(),
            ];
        }
        
        // Get pending withdrawals
        $pendingWithdrawalsCount = Withdrawal::where('status', 'pending')->count();
        if ($pendingWithdrawalsCount > 0) {
            $pendingWithdrawalsAmount = Withdrawal::where('status', 'pending')->sum('amount') ?? 0;
            $notifications[] = [
                'type' => 'withdrawal',
                'icon' => 'mdi-bank',
                'icon_bg' => 'bg-warning',
                'title' => 'Pending Withdrawals',
                'message' => "{$pendingWithdrawalsCount} pending withdrawal(s) totaling $" . number_format($pendingWithdrawalsAmount, 2) . " require approval",
                'count' => $pendingWithdrawalsCount,
                'url' => route('admin.withdrawals.index'),
                'time' => Carbon::now()->diffForHumans(),
            ];
        }
        
        // Get new/unread chats
        $unreadChatsCount = Chat::where('status', 'pending')
            ->orWhere(function ($query) {
                $query->where('status', 'active')
                    ->whereHas('messages', function ($q) {
                        $q->where('sender_type', 'user')
                            ->where('is_read', false);
                    });
            })
            ->count();
        
        if ($unreadChatsCount > 0) {
            $notifications[] = [
                'type' => 'chat',
                'icon' => 'mdi-message-text',
                'icon_bg' => 'bg-success',
                'title' => 'New Chats',
                'message' => "{$unreadChatsCount} new chat(s) require your attention",
                'count' => $unreadChatsCount,
                'url' => route('admin.chats.index'),
                'time' => Carbon::now()->diffForHumans(),
            ];
        }
        
        $totalCount = $pendingDepositsCount + $pendingWithdrawalsCount + $unreadChatsCount;
        
        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'total_count' => $totalCount,
        ]);
    }
    
    public function form(){
        return view('admin.pages.form');
    }
    public function table(){
        return view('admin.pages.table');
    }
}

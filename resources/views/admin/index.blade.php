@extends('admin.layouts.main')
@section('content')
<div class="page-content-wrapper">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Dashboard Overview</h4>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- end page title -->

        <!-- Pending Actions Alert Section -->
        @if($pendingDepositsCount > 0 || $pendingWithdrawalsCount > 0)
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading"><i class="mdi mdi-alert-circle"></i> Pending Actions Required</h5>
                    <div class="row mt-3">
                        @if($pendingDepositsCount > 0)
                        <div class="col-md-6 mb-2">
                            <strong>Pending Deposits:</strong> {{ $pendingDepositsCount }} deposit(s) totaling ${{ number_format($pendingDepositsAmount, 2) }}
                            <a href="{{ route('admin.deposits.index') }}" class="btn btn-sm btn-primary ml-2">Review</a>
                        </div>
                        @endif
                        @if($pendingWithdrawalsCount > 0)
                        <div class="col-md-6 mb-2">
                            <strong>Pending Withdrawals:</strong> {{ $pendingWithdrawalsCount }} withdrawal(s) totaling ${{ number_format($pendingWithdrawalsAmount, 2) }}
                            <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-sm btn-primary ml-2">Review</a>
                        </div>
                        @endif
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Stats Summary (Today's Activity) -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="header-title mb-3"><i class="mdi mdi-calendar-today"></i> Today's Activity</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center p-3 border rounded">
                                    <h4 class="mb-0 text-primary">{{ $todayUsers }}</h4>
                                    <p class="mb-0 text-muted">New Users</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 border rounded">
                                    <h4 class="mb-0 text-success">${{ number_format($todayDeposits, 2) }}</h4>
                                    <p class="mb-0 text-muted">Deposits</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 border rounded">
                                    <h4 class="mb-0 text-danger">${{ number_format($todayWithdrawals, 2) }}</h4>
                                    <p class="mb-0 text-muted">Withdrawals</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 border rounded">
                                    <h4 class="mb-0 text-info">${{ number_format($todayInvestments, 2) }}</h4>
                                    <p class="mb-0 text-muted">Investments</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Statistics Cards -->
        <div class="row">
            <!-- Total Users -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="col-3 align-self-center">
                                <div class="round round-lg align-self-center bg-primary">
                                    <i class="mdi mdi-account-multiple text-white"></i>
                                </div>
                            </div>
                            <div class="col-9 align-self-center text-right">
                                <div class="m-l-10">
                                    <h5 class="mt-0 mb-1">{{ number_format($totalUsers) }}</h5>
                                    <p class="mb-0 text-muted">Total Users</p>
                                    @if($userGrowth != 0)
                                    <small class="text-{{ $userGrowth > 0 ? 'success' : 'danger' }}">
                                        <i class="mdi mdi-arrow-{{ $userGrowth > 0 ? 'up' : 'down' }}"></i> {{ number_format(abs($userGrowth), 1) }}%
                                    </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="col-3 align-self-center">
                                <div class="round round-lg align-self-center bg-success">
                                    <i class="mdi mdi-chart-line text-white"></i>
                                </div>
                            </div>
                            <div class="col-9 align-self-center text-right">
                                <div class="m-l-10">
                                    <h5 class="mt-0 mb-1">${{ number_format($totalRevenue, 2) }}</h5>
                                    <p class="mb-0 text-muted">Total Revenue</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Deposits -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="col-3 align-self-center">
                                <div class="round round-lg align-self-center bg-info">
                                    <i class="mdi mdi-arrow-down-bold text-white"></i>
                                </div>
                            </div>
                            <div class="col-9 align-self-center text-right">
                                <div class="m-l-10">
                                    <h5 class="mt-0 mb-1">${{ number_format($totalDeposits, 2) }}</h5>
                                    <p class="mb-0 text-muted">Total Deposits</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Withdrawals -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="col-3 align-self-center">
                                <div class="round round-lg align-self-center bg-warning">
                                    <i class="mdi mdi-arrow-up-bold text-white"></i>
                                </div>
                            </div>
                            <div class="col-9 align-self-center text-right">
                                <div class="m-l-10">
                                    <h5 class="mt-0 mb-1">${{ number_format($totalWithdrawals, 2) }}</h5>
                                    <p class="mb-0 text-muted">Total Withdrawals</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Deposits -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="col-3 align-self-center">
                                <div class="round round-lg align-self-center bg-danger d-flex align-items-center justify-content-center">
                                    <i class="mdi mdi-arrow-down-bold text-white" style="font-size: 24px;"></i>
                                </div>
                            </div>
                            <div class="col-9 align-self-center text-right">
                                <div class="m-l-10">
                                    <h5 class="mt-0 mb-1">{{ $pendingDepositsCount }}</h5>
                                    <p class="mb-0 text-muted">Pending Deposits</p>
                                    <small class="text-muted">${{ number_format($pendingDepositsAmount, 2) }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Withdrawals -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="col-3 align-self-center">
                                <div class="round round-lg align-self-center bg-danger d-flex align-items-center justify-content-center">
                                    <i class="mdi mdi-arrow-up-bold text-white" style="font-size: 24px;"></i>
                                </div>
                            </div>
                            <div class="col-9 align-self-center text-right">
                                <div class="m-l-10">
                                    <h5 class="mt-0 mb-1">{{ $pendingWithdrawalsCount }}</h5>
                                    <p class="mb-0 text-muted">Pending Withdrawl</p>
                                    <small class="text-muted">${{ number_format($pendingWithdrawalsAmount, 2) }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Investments -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="col-3 align-self-center">
                                <div class="round round-lg align-self-center bg-purple">
                                    <i class="mdi mdi-chart-areaspline text-white"></i>
                                </div>
                            </div>
                            <div class="col-9 align-self-center text-right">
                                <div class="m-l-10">
                                    <h5 class="mt-0 mb-1">{{ $activeInvestmentsCount }}</h5>
                                    <p class="mb-0 text-muted">Active Investment</p>
                                    <small class="text-muted">${{ number_format($activeInvestmentsAmount, 2) }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Earnings Paid -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="col-3 align-self-center">
                                <div class="round round-lg align-self-center bg-success">
                                    <i class="mdi mdi-cash-multiple text-white"></i>
                                </div>
                            </div>
                            <div class="col-9 align-self-center text-right">
                                <div class="m-l-10">
                                    <h5 class="mt-0 mb-1">${{ number_format($totalEarningsPaid, 2) }}</h5>
                                    <p class="mb-0 text-muted">Total Earnings Paid</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Platform Balance -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="col-3 align-self-center">
                                <div class="round round-lg align-self-center bg-info">
                                    <i class="mdi mdi-wallet text-white"></i>
                                </div>
                            </div>
                            <div class="col-9 align-self-center text-right">
                                <div class="m-l-10">
                                    <h5 class="mt-0 mb-1">${{ number_format($platformBalance, 2) }}</h5>
                                    <p class="mb-0 text-muted">Platform Balance</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Platform Profit -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="col-3 align-self-center">
                                <div class="round round-lg align-self-center bg-success">
                                    <i class="mdi mdi-trending-up text-white"></i>
                                </div>
                            </div>
                            <div class="col-9 align-self-center text-right">
                                <div class="m-l-10">
                                    <h5 class="mt-0 mb-1">${{ number_format($platformProfit, 2) }}</h5>
                                    <p class="mb-0 text-muted">Platform Profit</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

        <!-- Key Performance Indicators -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="header-title mb-4"><i class="mdi mdi-chart-donut"></i> Key Performance Indicators</h5>
                        <div class="row">
                            <div class="col-md-2 text-center">
                                <h3 class="mb-0 text-primary">{{ number_format($conversionRate, 1) }}%</h3>
                                <p class="text-muted mb-0">Conversion Rate</p>
                            </div>
                            <div class="col-md-2 text-center">
                                <h3 class="mb-0 text-info">${{ number_format($averageInvestment, 2) }}</h3>
                                <p class="text-muted mb-0">Avg Investment</p>
                            </div>
                            <div class="col-md-2 text-center">
                                <h3 class="mb-0 text-success">${{ number_format($averageDeposit, 2) }}</h3>
                                <p class="text-muted mb-0">Avg Deposit</p>
                            </div>
                            <div class="col-md-2 text-center">
                                <h3 class="mb-0 text-warning">{{ number_format($referralRate, 1) }}%</h3>
                                <p class="text-muted mb-0">Referral Rate</p>
                            </div>
                            <div class="col-md-2 text-center">
                                <h3 class="mb-0 text-danger">{{ number_format($userRetention, 1) }}%</h3>
                                <p class="text-muted mb-0">User Retention</p>
                            </div>
                            <div class="col-md-2 text-center">
                                <h3 class="mb-0 text-purple">{{ $activeUsers }}</h3>
                                <p class="text-muted mb-0">Active Users</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row">
            <!-- Revenue & Financial Trends Chart -->
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mt-0 header-title mb-0">Revenue & Financial Trends</h4>
                            <div class="btn-group" role="group" aria-label="Time period">
                                <button type="button" class="btn btn-sm btn-outline-primary financial-period-btn" data-period="weekly">Weekly</button>
                                <button type="button" class="btn btn-sm btn-primary financial-period-btn active" data-period="monthly">Monthly</button>
                                <button type="button" class="btn btn-sm btn-outline-primary financial-period-btn" data-period="yearly">Yearly</button>
                            </div>
                        </div>
                        <div id="financial-chart" class="morris-chart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <!-- User Growth Chart -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mt-0 header-title mb-0">User Growth</h4>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary user-growth-period-btn" data-period="weekly">Weekly</button>
                                <button type="button" class="btn btn-sm btn-primary user-growth-period-btn active" data-period="monthly">Monthly</button>
                                <button type="button" class="btn btn-sm btn-outline-primary user-growth-period-btn" data-period="yearly">Yearly</button>
                            </div>
                        </div>
                        <div id="user-growth-chart" class="morris-chart" style="height: 250px;"></div>
                    </div>
                </div>
            </div>

            <!-- Investment Performance Chart -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mt-0 header-title mb-0">Investment Performance</h4>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary investment-period-btn" data-period="weekly">Weekly</button>
                                <button type="button" class="btn btn-sm btn-primary investment-period-btn active" data-period="monthly">Monthly</button>
                                <button type="button" class="btn btn-sm btn-outline-primary investment-period-btn" data-period="yearly">Yearly</button>
                            </div>
                        </div>
                        <div id="investment-performance-chart" class="morris-chart" style="height: 250px;"></div>
                    </div>
                </div>
            </div>

            <!-- Earnings Breakdown Chart -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mt-0 header-title mb-3">Earnings Breakdown</h4>
                        <div id="earnings-breakdown-chart" class="morris-chart" style="height: 250px;"></div>
                        <div class="mt-3 text-center">
                            <span class="badge badge-primary mr-2">Mining: ${{ number_format($totalMiningEarnings, 2) }}</span>
                            <span class="badge badge-success">Referral: ${{ number_format($totalReferralEarnings, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Activity Chart -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mt-0 header-title mb-0">Transaction Activity</h4>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary transaction-period-btn" data-period="weekly">Weekly</button>
                                <button type="button" class="btn btn-sm btn-primary transaction-period-btn active" data-period="monthly">Monthly</button>
                                <button type="button" class="btn btn-sm btn-outline-primary transaction-period-btn" data-period="yearly">Yearly</button>
                            </div>
                        </div>
                        <div id="transaction-activity-chart" class="morris-chart" style="height: 250px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

        <!-- Recent Activity Tables -->
        <div class="row">
            <!-- Recent Users -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="header-title mb-0">Recent Users</h5>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Join Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentUsers as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-2">
                                                    <div class="rounded-circle bg-soft-primary text-primary d-inline-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-weight: bold;">
                                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $user->name ?? 'N/A' }}</h6>
                                                    <small class="text-muted">{{ $user->username ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><small>{{ $user->email ?? 'N/A' }}</small></td>
                                        <td><small>{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</small></td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <p class="text-muted mb-0">No users found.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Deposits -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="header-title mb-0">Recent Deposits</h5>
                            <a href="{{ route('admin.deposits.index') }}" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentDeposits as $deposit)
                                    <tr>
                                        <td>
                                            <small>{{ $deposit->user->name ?? 'N/A' }}</small>
                                        </td>
                                        <td><strong>${{ number_format($deposit->amount ?? 0, 2) }}</strong></td>
                                        <td>
                                            @if($deposit->status == 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @elseif($deposit->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @else
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td><small>{{ $deposit->created_at ? $deposit->created_at->format('M d, Y') : 'N/A' }}</small></td>
                                        <td>
                                            <a href="{{ route('admin.deposits.show', $deposit->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <p class="text-muted mb-0">No deposits found.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Withdrawals -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="header-title mb-0">Recent Withdrawals</h5>
                            <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentWithdrawals as $withdrawal)
                                    <tr>
                                        <td>
                                            <small>{{ $withdrawal->user->name ?? 'N/A' }}</small>
                                        </td>
                                        <td><strong>${{ number_format($withdrawal->amount ?? 0, 2) }}</strong></td>
                                        <td>
                                            @if($withdrawal->status == 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @elseif($withdrawal->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @else
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td><small>{{ $withdrawal->created_at ? $withdrawal->created_at->format('M d, Y') : 'N/A' }}</small></td>
                                        <td>
                                            <a href="{{ route('admin.withdrawals.show', $withdrawal->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <p class="text-muted mb-0">No withdrawals found.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Investments -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="header-title mb-0">Recent Investments</h5>
                            <a href="#" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Plan</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentInvestments as $investment)
                                    <tr>
                                        <td>
                                            <small>{{ $investment->user->name ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <small>{{ $investment->miningPlan->name ?? 'N/A' }}</small>
                                        </td>
                                        <td><strong>${{ number_format($investment->amount ?? 0, 2) }}</strong></td>
                                        <td>
                                            @if($investment->status == 'active')
                                                <span class="badge badge-success">Active</span>
                                            @elseif($investment->status == 'completed')
                                                <span class="badge badge-info">Completed</span>
                                            @else
                                                <span class="badge badge-danger">Cancelled</span>
                                            @endif
                                        </td>
                                        <td><small>{{ $investment->created_at ? $investment->created_at->format('M d, Y') : 'N/A' }}</small></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <p class="text-muted mb-0">No investments found.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

        <!-- Top Performers -->
        <div class="row">
            <!-- Top Referrers -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="header-title mb-4 mt-0">Top 5 Referrers</h5>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Referrals</th>
                                        <th>Earnings</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topReferrers as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-2">
                                                    <div class="rounded-circle bg-soft-primary text-primary d-inline-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-weight: bold;">
                                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $user->name ?? 'N/A' }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">{{ $user->direct_referrals_count ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <strong>${{ number_format($user->referral_earning ?? 0, 2) }}</strong>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4">
                                            <p class="text-muted mb-0">No referrers found.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Investors -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="header-title mb-4 mt-0">Top 5 Investors</h5>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Total Invested</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topInvestors as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-2">
                                                    <div class="rounded-circle bg-soft-success text-success d-inline-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-weight: bold;">
                                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $user->name ?? 'N/A' }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>${{ number_format($user->total_invested ?? 0, 2) }}</strong>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-4">
                                            <p class="text-muted mb-0">No investors found.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Earners -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="header-title mb-4 mt-0">Top 5 Earners</h5>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Total Earnings</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topEarners as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-2">
                                                    <div class="rounded-circle bg-soft-warning text-warning d-inline-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-weight: bold;">
                                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $user->name ?? 'N/A' }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>${{ number_format(($user->mining_earning ?? 0) + ($user->referral_earning ?? 0), 2) }}</strong>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-4">
                                            <p class="text-muted mb-0">No earners found.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

    </div><!-- container -->
</div> <!-- Page content Wrapper -->
@endsection

@push('styles')
<style>
    .round-lg {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bg-purple {
        background-color: #6f42c1 !important;
    }
    .text-purple {
        color: #6f42c1 !important;
    }
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Financial Chart
    let financialChart = null;
    let currentFinancialPeriod = 'monthly';

    function loadFinancialChart(period) {
        currentFinancialPeriod = period;
        $('.financial-period-btn').removeClass('active btn-primary').addClass('btn-outline-primary');
        $(`.financial-period-btn[data-period="${period}"]`).removeClass('btn-outline-primary').addClass('active btn-primary');

        $('#financial-chart').html('<div class="text-center p-5"><i class="mdi mdi-loading mdi-spin"></i> Loading...</div>');

        $.ajax({
            url: '{{ route("admin.financial-data") }}',
            method: 'GET',
            data: { period: period },
            success: function(data) {
                if (financialChart) {
                    financialChart.setData(formatFinancialData(data));
                } else {
                    financialChart = Morris.Line({
                        element: 'financial-chart',
                        data: formatFinancialData(data),
                        xkey: 'period',
                        ykeys: ['deposit', 'withdrawal', 'revenue', 'netRevenue'],
                        labels: ['Deposits', 'Withdrawals', 'Revenue', 'Net Revenue'],
                        lineColors: ['#5b73e8', '#f1b44c', '#34c38f', '#f46a6a'],
                        pointSize: 4,
                        lineWidth: 2,
                        hideHover: 'auto',
                        resize: true,
                        gridLineColor: '#eef0f2',
                        gridTextColor: '#666',
                        yLabelFormat: function(y) {
                            return '$' + y.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        },
                        xLabelAngle: period === 'yearly' ? 0 : 45
                    });
                }
            },
            error: function(xhr, status, error) {
                $('#financial-chart').html('<div class="text-center p-5 text-danger">Error loading chart data.</div>');
            }
        });
    }

    function formatFinancialData(data) {
        return data.map(function(item) {
            return {
                period: item.period,
                deposit: parseFloat(item.deposit) || 0,
                withdrawal: parseFloat(item.withdrawal) || 0,
                revenue: parseFloat(item.revenue) || 0,
                netRevenue: parseFloat(item.netRevenue) || 0
            };
        });
    }

    $('.financial-period-btn').on('click', function() {
        loadFinancialChart($(this).data('period'));
    });
    loadFinancialChart('monthly');

    // User Growth Chart
    let userGrowthChart = null;
    function loadUserGrowthChart(period) {
        $('.user-growth-period-btn').removeClass('active btn-primary').addClass('btn-outline-primary');
        $(`.user-growth-period-btn[data-period="${period}"]`).removeClass('btn-outline-primary').addClass('active btn-primary');

        $('#user-growth-chart').html('<div class="text-center p-5"><i class="mdi mdi-loading mdi-spin"></i> Loading...</div>');

        $.ajax({
            url: '{{ route("admin.user-growth-data") }}',
            method: 'GET',
            data: { period: period },
            success: function(data) {
                if (userGrowthChart) {
                    userGrowthChart.setData(data.map(function(item) {
                        return { period: item.period, users: parseInt(item.users) || 0 };
                    }));
                } else {
                    userGrowthChart = Morris.Area({
                        element: 'user-growth-chart',
                        data: data.map(function(item) {
                            return { period: item.period, users: parseInt(item.users) || 0 };
                        }),
                        xkey: 'period',
                        ykeys: ['users'],
                        labels: ['Users'],
                        lineColors: ['#5b73e8'],
                        fillOpacity: 0.3,
                        pointSize: 4,
                        lineWidth: 2,
                        hideHover: 'auto',
                        resize: true,
                        gridLineColor: '#eef0f2',
                        gridTextColor: '#666',
                        xLabelAngle: period === 'yearly' ? 0 : 45
                    });
                }
            },
            error: function() {
                $('#user-growth-chart').html('<div class="text-center p-5 text-danger">Error loading chart data.</div>');
            }
        });
    }

    $('.user-growth-period-btn').on('click', function() {
        loadUserGrowthChart($(this).data('period'));
    });
    loadUserGrowthChart('monthly');

    // Investment Performance Chart
    let investmentChart = null;
    function loadInvestmentChart(period) {
        $('.investment-period-btn').removeClass('active btn-primary').addClass('btn-outline-primary');
        $(`.investment-period-btn[data-period="${period}"]`).removeClass('btn-outline-primary').addClass('active btn-primary');

        $('#investment-performance-chart').html('<div class="text-center p-5"><i class="mdi mdi-loading mdi-spin"></i> Loading...</div>');

        $.ajax({
            url: '{{ route("admin.investment-performance-data") }}',
            method: 'GET',
            data: { period: period },
            success: function(data) {
                if (investmentChart) {
                    investmentChart.setData(data.map(function(item) {
                        return {
                            period: item.period,
                            active: parseFloat(item.active) || 0,
                            completed: parseFloat(item.completed) || 0,
                            cancelled: parseFloat(item.cancelled) || 0
                        };
                    }));
                } else {
                    investmentChart = Morris.Bar({
                        element: 'investment-performance-chart',
                        data: data.map(function(item) {
                            return {
                                period: item.period,
                                active: parseFloat(item.active) || 0,
                                completed: parseFloat(item.completed) || 0,
                                cancelled: parseFloat(item.cancelled) || 0
                            };
                        }),
                        xkey: 'period',
                        ykeys: ['active', 'completed', 'cancelled'],
                        labels: ['Active', 'Completed', 'Cancelled'],
                        barColors: ['#34c38f', '#5b73e8', '#f46a6a'],
                        hideHover: 'auto',
                        resize: true,
                        gridLineColor: '#eef0f2',
                        gridTextColor: '#666',
                        xLabelAngle: period === 'yearly' ? 0 : 45,
                        yLabelFormat: function(y) {
                            return '$' + y.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        }
                    });
                }
            },
            error: function() {
                $('#investment-performance-chart').html('<div class="text-center p-5 text-danger">Error loading chart data.</div>');
            }
        });
    }

    $('.investment-period-btn').on('click', function() {
        loadInvestmentChart($(this).data('period'));
    });
    loadInvestmentChart('monthly');

    // Earnings Breakdown Chart
    $('#earnings-breakdown-chart').html('<div class="text-center p-5"><i class="mdi mdi-loading mdi-spin"></i> Loading...</div>');

    $.ajax({
        url: '{{ route("admin.earnings-breakdown-data") }}',
        method: 'GET',
        success: function(data) {
            if (data && data.length > 0) {
                Morris.Donut({
                    element: 'earnings-breakdown-chart',
                    data: data.map(function(item) {
                        return {
                            label: item.label,
                            value: parseFloat(item.value) || 0
                        };
                    }),
                    colors: ['#5b73e8', '#34c38f'],
                    formatter: function(y) {
                        return '$' + y.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                });
            } else {
                $('#earnings-breakdown-chart').html('<div class="text-center p-5 text-muted">No data available.</div>');
            }
        },
        error: function() {
            $('#earnings-breakdown-chart').html('<div class="text-center p-5 text-danger">Error loading chart data.</div>');
        }
    });

    // Transaction Activity Chart
    let transactionChart = null;
    function loadTransactionChart(period) {
        $('.transaction-period-btn').removeClass('active btn-primary').addClass('btn-outline-primary');
        $(`.transaction-period-btn[data-period="${period}"]`).removeClass('btn-outline-primary').addClass('active btn-primary');

        $('#transaction-activity-chart').html('<div class="text-center p-5"><i class="mdi mdi-loading mdi-spin"></i> Loading...</div>');

        $.ajax({
            url: '{{ route("admin.transaction-activity-data") }}',
            method: 'GET',
            data: { period: period },
            success: function(data) {
                if (transactionChart) {
                    transactionChart.setData(data.map(function(item) {
                        return {
                            period: item.period,
                            deposits: parseFloat(item.deposits) || 0,
                            withdrawals: parseFloat(item.withdrawals) || 0,
                            miningEarnings: parseFloat(item.miningEarnings) || 0,
                            referralEarnings: parseFloat(item.referralEarnings) || 0
                        };
                    }));
                } else {
                    transactionChart = Morris.Area({
                        element: 'transaction-activity-chart',
                        data: data.map(function(item) {
                            return {
                                period: item.period,
                                deposits: parseFloat(item.deposits) || 0,
                                withdrawals: parseFloat(item.withdrawals) || 0,
                                miningEarnings: parseFloat(item.miningEarnings) || 0,
                                referralEarnings: parseFloat(item.referralEarnings) || 0
                            };
                        }),
                        xkey: 'period',
                        ykeys: ['deposits', 'withdrawals', 'miningEarnings', 'referralEarnings'],
                        labels: ['Deposits', 'Withdrawals', 'Mining Earnings', 'Referral Earnings'],
                        lineColors: ['#5b73e8', '#f46a6a', '#34c38f', '#f1b44c'],
                        fillOpacity: 0.3,
                        pointSize: 4,
                        lineWidth: 2,
                        hideHover: 'auto',
                        resize: true,
                        gridLineColor: '#eef0f2',
                        gridTextColor: '#666',
                        xLabelAngle: period === 'yearly' ? 0 : 45,
                        yLabelFormat: function(y) {
                            return '$' + y.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        }
                    });
                }
            },
            error: function() {
                $('#transaction-activity-chart').html('<div class="text-center p-5 text-danger">Error loading chart data.</div>');
            }
        });
    }

    $('.transaction-period-btn').on('click', function() {
        loadTransactionChart($(this).data('period'));
    });
    loadTransactionChart('monthly');
});
</script>
@endpush

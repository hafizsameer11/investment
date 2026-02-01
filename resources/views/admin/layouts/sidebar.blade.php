<body class="fixed-left">

    <!-- Loader -->
    {{-- <div id="preloader"><div id="status"><div class="spinner"></div></div></div> --}}

    <!-- Begin page -->
    <div id="wrapper">

        <!-- ========== Left Sidebar Start ========== -->
        <div class="left side-menu">
            <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
                <i class="ion-close"></i>
            </button>

            <!-- LOGO -->
            <div class="topbar-left">
                <div class="text-center">
                    <a href="{{ route('admin.index') }}" class="logo" style="text-decoration: none; padding: 15px 10px; display: flex; align-items: center; justify-content: center;">
                        <div class="logo-icon-wrapper" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ asset('assets/dashboard/images/meta/logo-2.png') }}" alt="logo" style="width: 42px; height: 42px; object-fit: contain;">
                        </div>
                    </a>
                </div>
            </div>

            <div class="sidebar-inner niceScrollleft">

                <!-- User Info -->
                {{-- @auth
                <div class="user-info p-3 text-center border-bottom">
                    <div class="text-white">
                        <i class="mdi mdi-account-circle"></i>
                        <span class="ml-2">{{ auth()->user()->name ?? 'Admin' }}</span>
                    </div>
                    <small class="text-muted">{{ auth()->user()->email ?? '' }}</small>
                </div>
                @endauth --}}

                <div id="sidebar-menu">
                    @php
                        $currentRoute = Route::currentRouteName();
                        $currentPath = request()->path();
                    @endphp
                    <ul>
                        {{-- <li class="menu-title">Main</li> --}}

                        <li>
                            <a href="{{ route('admin.index') }}" class="waves-effect">
                                <i class="mdi mdi-view-dashboard"></i>
                                <span> Dashboard 
                                    {{-- <span class="badge badge-pill badge-primary float-right">7</span> --}}
                                    </span>
                            </a>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-cash-multiple"></i>
                                <span> Invest Commission </span> <span class="float-right"><i
                                        class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{ route('admin.investment-commission.index') }}">All Commission</a></li>
                                <li><a href="{{ route('admin.investment-commission.create') }}">Add Commission</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-trending-up"></i>
                                <span> Earn Commission </span> <span class="float-right"><i
                                        class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{ route('admin.earning-commission.index') }}">All Commission</a></li>
                                <li><a href="{{ route('admin.earning-commission.create') }}">Add Commission</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-diamond"></i> <span>
                                    Mining Plans </span> <span class="float-right"><i
                                        class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{ route('admin.mining-plan.index') }}">All Plans</a></li>
                                <li><a href="{{ route('admin.mining-plan.create') }}">Add Plan</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-trophy"></i> <span>
                                    Reward Levels </span> <span class="float-right"><i
                                        class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{ route('admin.reward-level.index') }}">All Reward Levels</a></li>
                                <li><a href="{{ route('admin.reward-level.create') }}">Add Reward Level</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-account-multiple"></i> <span>
                                    Users </span> <span class="float-right"><i
                                        class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{ route('admin.users.index') }}">All Users</a></li>
                                <li><a href="{{ route('admin.users.create') }}">Add User</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-credit-card"></i> <span>
                                    Payment Methods </span> <span class="float-right"><i
                                        class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{ route('admin.deposit-payment-method.index') }}">All Methods</a></li>
                                <li><a href="{{ route('admin.deposit-payment-method.create') }}">Add Method</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-wallet"></i> <span>
                                    Crypto Wallets </span> <span class="float-right"><i
                                        class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{ route('admin.crypto-wallet.index') }}">All Crypto Wallets</a></li>
                                <li><a href="{{ route('admin.crypto-wallet.create') }}">Add Crypto Wallet</a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="{{ route('admin.deposits.index') }}" class="waves-effect">
                                <i class="mdi mdi-cash-multiple"></i>
                                <span> Deposits 
                                    @php
                                        $isOnDepositsPage = $currentRoute === 'admin.deposits.index' || strpos($currentPath, 'admin/deposits') !== false;
                                        $showDepositsBadge = isset($pendingDepositsCount) && $pendingDepositsCount > 0 && !$isOnDepositsPage;
                                    @endphp
                                    @if($showDepositsBadge)
                                        <span class="badge badge-pill badge-danger float-right" id="pending-deposits-badge">{{ $pendingDepositsCount }}</span>
                                    @else
                                        <span class="badge badge-pill badge-danger float-right" id="pending-deposits-badge" style="display: none;">0</span>
                                    @endif
                                </span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.withdrawals.index') }}" class="waves-effect">
                                <i class="mdi mdi-bank"></i>
                                <span> Withdrawals 
                                    @php
                                        $isOnWithdrawalsPage = $currentRoute === 'admin.withdrawals.index' || strpos($currentPath, 'admin/withdrawals') !== false;
                                        $showWithdrawalsBadge = isset($pendingWithdrawalsCount) && $pendingWithdrawalsCount > 0 && !$isOnWithdrawalsPage;
                                    @endphp
                                    @if($showWithdrawalsBadge)
                                        <span class="badge badge-pill badge-danger float-right" id="pending-withdrawals-badge">{{ $pendingWithdrawalsCount }}</span>
                                    @else
                                        <span class="badge badge-pill badge-danger float-right" id="pending-withdrawals-badge" style="display: none;">0</span>
                                    @endif
                                </span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.currency-conversion.index') }}" class="waves-effect">
                                <i class="mdi mdi-currency-usd"></i>
                                <span>Currency Conversion</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.notifications.create') }}" class="waves-effect">
                                <i class="mdi mdi-bell-outline"></i>
                                <span>Send Notification</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.chats.index') }}" class="waves-effect">
                                <i class="mdi mdi-message-text"></i>
                                <span>Chats
                                    @php
                                        $isOnChatsPage = $currentRoute === 'admin.chats.index' || strpos($currentPath, 'admin/chats') !== false;
                                        $showChatsBadge = isset($unreadChatsCount) && $unreadChatsCount > 0 && !$isOnChatsPage;
                                    @endphp
                                    @if($showChatsBadge)
                                        <span class="badge badge-pill badge-primary float-right" id="unread-chats-badge">{{ $unreadChatsCount }}</span>
                                    @else
                                        <span class="badge badge-pill badge-primary float-right" id="unread-chats-badge" style="display: none;">0</span>
                                    @endif
                                </span>
                            </a>
                        </li>

                        {{-- <li>
                            <a href="calendar.html" class="waves-effect"><i class="mdi mdi-calendar-clock"></i><span>
                                    Calendar </span></a>
                        </li>

                        <li class="menu-title">Components</li> --}}

                    </ul>
                </div>
                <div class="clearfix"></div>
            </div> <!-- end sidebarinner -->
        </div>
        <!-- Left Sidebar End -->

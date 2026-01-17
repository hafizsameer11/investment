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
                    <!--<a href="index.html" class="logo"><i class="mdi mdi-assistant"></i>Zoter</a>-->
                    <a href="index.html" class="logo">
                        <img src="assets/images/logo-lg.png" alt="" class="logo-large">
                    </a>
                </div>
            </div>

            <div class="sidebar-inner niceScrollleft">

                <!-- User Info -->
                @auth
                <div class="user-info p-3 text-center border-bottom">
                    <div class="text-white">
                        <i class="mdi mdi-account-circle"></i>
                        <span class="ml-2">{{ auth()->user()->name ?? 'Admin' }}</span>
                    </div>
                    <small class="text-muted">{{ auth()->user()->email ?? '' }}</small>
                </div>
                @endauth

                <div id="sidebar-menu">
                    <ul>
                        <li class="menu-title">Main</li>

                        <li>
                            <a href="{{ route('admin.index') }}" class="waves-effect">
                                <i class="mdi mdi-view-dashboard"></i>
                                <span> Dashboard <span
                                        class="badge badge-pill badge-primary float-right">7</span></span>
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

                        <li>
                            <a href="{{ route('admin.deposits.index') }}" class="waves-effect">
                                <i class="mdi mdi-cash-multiple"></i>
                                <span> Deposits </span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.withdrawals.index') }}" class="waves-effect">
                                <i class="mdi mdi-cash-refund"></i>
                                <span> Withdrawals </span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.currency-conversion.index') }}" class="waves-effect">
                                <i class="mdi mdi-currency-usd"></i>
                                <span>Currency Conversion</span>
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

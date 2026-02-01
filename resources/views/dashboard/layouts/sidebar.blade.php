<aside class="dashboard-sidebar" id="dashboardSidebar">
    <div class="sidebar-content">
        <!-- Logo -->
        <div class="sidebar-logo">
            <div class="logo">
                <div class="logo-icon-wrapper">
                    <img class="logo-icon" src="{{ asset('assets/dashboard/images/meta/logo-2.png') }}" alt="logo" style="width: 24px; height: 24px; object-fit: contain;">
                </div>
                <span class="logo-text">Core Mining</span>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="sidebar-nav">
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('dashboard.index') }}" class="nav-link">
                        <span class="nav-icon-wrapper">
                            <i class="fas fa-th-large"></i>
                        </span>
                        <span class="nav-text">Quick View</span>
                        <span class="nav-indicator"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('wallet.index') }}" class="nav-link">
                        <span class="nav-icon-wrapper">
                            <i class="fas fa-coins"></i>
                        </span>
                        <span class="nav-text">Core Wallet</span>
                        <span class="nav-indicator"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('deposit.index') }}" class="nav-link">
                        <span class="nav-icon-wrapper">
                            <i class="fas fa-money-bill-wave"></i>
                        </span>
                        <span class="nav-text">Add Money</span>
                        <span class="nav-indicator"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('plans.index') }}" class="nav-link">
                        <span class="nav-icon-wrapper">
                            <i class="fas fa-gem"></i>
                        </span>
                        <span class="nav-text">Mining Plan</span>
                        <span class="nav-indicator"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('goals.index') }}" class="nav-link">
                        <span class="nav-icon-wrapper">
                            <i class="fas fa-bullseye"></i>
                        </span>
                        <span class="nav-text">Victory Rewards</span>
                        <span class="nav-indicator"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('referrals.index') }}" class="nav-link">
                        <span class="nav-icon-wrapper">
                            <i class="fas fa-user-plus"></i>
                        </span>
                        <span class="nav-text">Invite System</span>
                        <span class="nav-indicator"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('transactions.index') }}" class="nav-link">
                        <span class="nav-icon-wrapper">
                            <i class="fas fa-exchange-alt"></i>
                        </span>
                        <span class="nav-text">Financial Records</span>
                        <span class="nav-indicator"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('profile.index') }}" class="nav-link">
                        <span class="nav-icon-wrapper">
                            <i class="fas fa-user"></i>
                        </span>
                        <span class="nav-text">Profile & Password</span>
                        <span class="nav-indicator"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('support.index') }}" class="nav-link">
                        <span class="nav-icon-wrapper">
                            <i class="fas fa-headset"></i>
                        </span>
                        <span class="nav-text">Technical Support</span>
                        <span class="nav-indicator"></span>
                    </a>
                </li>
                <li class="nav-item logout-item">
                    <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link logout-link">
                        <span class="nav-icon-wrapper">
                            <i class="fas fa-sign-out-alt"></i>
                        </span>
                        <span class="nav-text">Logout</span>
                        <span class="nav-indicator"></span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

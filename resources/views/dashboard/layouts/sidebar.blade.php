<aside class="dashboard-sidebar" id="dashboardSidebar">
    <div class="sidebar-content">
        <!-- Logo -->
        <div class="sidebar-logo">
            <div class="logo">
                <div class="logo-icon-wrapper">
                    <svg class="logo-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Mining Pickaxe Icon -->
                        <path d="M12 2L8 6L10 8L6 12L8 14L12 10L16 14L18 12L14 8L16 6L12 2Z" fill="url(#coreMiningGradient)" stroke="#FFB21E" stroke-width="1.5" stroke-linejoin="round"/>
                        <!-- Mining Blocks -->
                        <rect x="4" y="16" width="4" height="4" rx="1" fill="#FFB21E" opacity="0.6"/>
                        <rect x="10" y="18" width="4" height="4" rx="1" fill="#FF8A1D" opacity="0.6"/>
                        <rect x="16" y="16" width="4" height="4" rx="1" fill="#FFB21E" opacity="0.6"/>
                        <!-- Glow Effect -->
                        <circle cx="12" cy="8" r="8" fill="url(#coreMiningGlow)" opacity="0.3"/>
                        <defs>
                            <linearGradient id="coreMiningGradient" x1="12" y1="2" x2="12" y2="14" gradientUnits="userSpaceOnUse">
                                <stop offset="0%" stop-color="#FFB21E"/>
                                <stop offset="100%" stop-color="#FF8A1D"/>
                            </linearGradient>
                            <radialGradient id="coreMiningGlow" cx="50%" cy="50%">
                                <stop offset="0%" stop-color="#FFB21E" stop-opacity="0.8"/>
                                <stop offset="100%" stop-color="#FFB21E" stop-opacity="0"/>
                            </radialGradient>
                        </defs>
                    </svg>
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
                        <span class="nav-text">Goals</span>
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
                        <span class="nav-text">Transactions</span>
                        <span class="nav-indicator"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('profile.index') }}" class="nav-link">
                        <span class="nav-icon-wrapper">
                            <i class="fas fa-user"></i>
                        </span>
                        <span class="nav-text">Profile</span>
                        <span class="nav-indicator"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('support.index') }}" class="nav-link">
                        <span class="nav-icon-wrapper">
                            <i class="fas fa-headset"></i>
                        </span>
                        <span class="nav-text">Help & Support</span>
                        <span class="nav-indicator"></span>
                    </a>
                </li>
                <li class="nav-item logout-item">
                    <a href="{{ route('login') }}" class="nav-link logout-link">
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

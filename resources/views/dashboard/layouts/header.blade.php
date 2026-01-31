@php
    $currentRoute = Route::currentRouteName();
    $isHomePage = $currentRoute === 'dashboard.index' || request()->path() === 'user/dashboard';
    
    // Page title mapping (matches mobile navigation and desktop sidebar)
    $pageTitles = [
        'wallet.index' => 'Core Wallet',
        'deposit.index' => 'Add Money',
        'deposit.confirm' => 'Add Money',
        'withdraw.index' => 'Get Money',
        'plans.index' => 'Mining Plan',
        'goals.index' => 'Victory Rewards',
        'targets.index' => 'Targets',
        'referrals.index' => 'Invite System',
        'referrals.claim-earnings' => 'Referral Earning',
        'transactions.index' => 'Financial Records',
        'notifications.index' => 'Notifications',
        'profile.index' => 'Profile',
        'support.index' => 'Technical Support',
        'withdraw-security.index' => 'Get Money',
        'settings.index' => 'Account Studio',
    ];
    
    $pageTitle = $pageTitles[$currentRoute] ?? 'Dashboard';
@endphp

<div class="dashboard-header">
    <div class="header-content">
        <div class="header-left">
            <button class="sidebar-toggle" id="sidebarToggle" onclick="toggleMobileSidebar(event)">
                <i class="fas fa-bars"></i>
            </button>
            <!-- Header Logo (shown on desktop) -->
            <div class="header-logo">
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
            
            <!-- Mobile Navigation (shown only on mobile for non-home pages) -->
            <div class="mobile-nav-header {{ $isHomePage ? 'mobile-nav-home' : '' }}">
                <button class="mobile-back-btn" onclick="goBack()">
                    <svg class="mobile-back-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <h2 class="mobile-page-title">{{ $pageTitle }}</h2>
            </div>
            
            <!-- Mobile User Profile (shown only on mobile for home page) -->
            @if($isHomePage && Auth::check())
            <div class="mobile-user-profile mobile-user-profile-visible">
                <div class="mobile-user-avatar">
                    <img src="{{ Auth::user()->profile_photo ? asset(Auth::user()->profile_photo) : asset('assets/dashboard/images/logo/logo.jpeg') }}" alt="User Avatar">
                </div>
                <div class="mobile-user-info">
                    <div class="mobile-user-name">{{ Auth::user()->name ?? 'User' }}</div>
                    <div class="mobile-user-email">{{ Auth::user()->email ?? '' }}</div>
                </div>
            </div>
            @endif
        </div>

        @if(session()->has('impersonator_id'))
            <div class="header-right" style="margin-right: 12px;">
                <form method="POST" action="{{ route('impersonation.stop') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn" style="background: #ef4444; color: #fff; border: none; padding: 8px 12px; border-radius: 10px; font-weight: 700; cursor: pointer;">
                        Stop Impersonation
                    </button>
                </form>
            </div>
        @endif

        <div class="header-right">
            <!-- Notifications -->
            <div class="notification-wrapper">
                <div class="notification-icon" id="notificationIcon">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                </div>
                
                <!-- Notification Dropdown Panel -->
                <div class="notification-panel" id="notificationPanel">
                    <div class="notification-panel-header">
                        <h3 class="notification-panel-title">Notifications</h3>
                    </div>
                    <div class="notification-panel-body" id="notificationPanelBody">
                        <div class="notification-loading" style="text-align: center; padding: 1rem; color: var(--text-secondary);">
                            <i class="fas fa-spinner fa-spin"></i> Loading...
                        </div>
                    </div>
                    <div class="notification-panel-footer">
                        <a href="{{ route('notifications.index') }}" class="notification-see-all">See all notification</a>
                    </div>
                </div>
            </div>

            <!-- User Profile (shown on desktop) -->
            @auth
            <div class="user-profile">
                <div class="user-avatar">
                    <img src="{{ Auth::user()->profile_photo ? asset(Auth::user()->profile_photo) : asset('assets/dashboard/images/logo/logo.jpeg') }}" alt="User Avatar">
                </div>
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name ?? 'User' }}</div>
                    <div class="user-email">{{ Auth::user()->email ?? '' }}</div>
                </div>
            </div>
            @endauth
        </div>
    </div>
</div>

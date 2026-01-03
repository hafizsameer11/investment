<!-- Mobile Bottom Navigation -->
<nav class="mobile-bottom-nav" id="mobileBottomNav">
    <a href="{{ route('dashboard.index') }}" class="mobile-nav-item" data-route="dashboard.index">
        <div class="mobile-nav-icon">
            <i class="fas fa-home"></i>
        </div>
        <span class="mobile-nav-label">Dashboard</span>
    </a>
    
    <a href="{{ route('wallet.index') }}" class="mobile-nav-item" data-route="wallet.index">
        <div class="mobile-nav-icon">
            <i class="fas fa-wallet"></i>
        </div>
        <span class="mobile-nav-label">Wallet</span>
    </a>
    
    <a href="{{ route('referrals.index') }}" class="mobile-nav-item" data-route="referrals.index">
        <div class="mobile-nav-icon">
            <i class="fas fa-users"></i>
        </div>
        <span class="mobile-nav-label">Referral</span>
    </a>
    
    <a href="{{ route('plans.index') }}" class="mobile-nav-item" data-route="plans.index">
        <div class="mobile-nav-icon">
            <i class="fas fa-chart-line"></i>
        </div>
        <span class="mobile-nav-label">Investment</span>
    </a>
    
    <a href="{{ route('settings.index') }}" class="mobile-nav-item" data-route="settings.index">
        <div class="mobile-nav-icon">
            <i class="fas fa-cog"></i>
        </div>
        <span class="mobile-nav-label">Settings</span>
    </a>
</nav>


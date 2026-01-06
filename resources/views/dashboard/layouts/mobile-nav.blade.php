<!-- Mobile Bottom Navigation -->
<nav class="mobile-bottom-nav" id="mobileBottomNav">
    <a href="{{ route('dashboard.index') }}" class="mobile-nav-item" data-route="dashboard.index">
        <div class="mobile-nav-icon">
            <i class="fas fa-th-large"></i>
        </div>
        <span class="mobile-nav-label">Quick View</span>
    </a>
    
    <a href="{{ route('wallet.index') }}" class="mobile-nav-item" data-route="wallet.index">
        <div class="mobile-nav-icon">
            <i class="fas fa-coins"></i>
        </div>
        <span class="mobile-nav-label">Core Wallet</span>
    </a>
    
    <a href="{{ route('referrals.index') }}" class="mobile-nav-item" data-route="referrals.index">
        <div class="mobile-nav-icon">
            <i class="fas fa-user-plus"></i>
        </div>
        <span class="mobile-nav-label">Invite System</span>
    </a>
    
    <a href="{{ route('plans.index') }}" class="mobile-nav-item" data-route="plans.index">
        <div class="mobile-nav-icon">
            <i class="fas fa-gem"></i>
        </div>
        <span class="mobile-nav-label">Mining Plan</span>
    </a>
    
    <a href="{{ route('settings.index') }}" class="mobile-nav-item" data-route="settings.index">
        <div class="mobile-nav-icon">
            <i class="fas fa-user-cog"></i>
        </div>
        <span class="mobile-nav-label">Account Studio</span>
    </a>
</nav>


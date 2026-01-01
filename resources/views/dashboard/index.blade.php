@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('dashboard/css/dashboard.css') }}">
<style>
    .mining-dashboard {
        padding: 2rem;
    }
    
    .mining-hero-section {
        margin-bottom: 2.5rem;
    }
    
    .mining-hero-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-md);
    }
    
    .mining-hero-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(0, 255, 136, 0.1) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 0.3; }
        50% { opacity: 0.6; }
    }
    
    .mining-logo-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
        position: relative;
        z-index: 1;
    }

    /* Ensure navigation button is always clickable on dashboard page */
    @media (max-width: 768px) {
        .sidebar-toggle {
            z-index: 10002 !important;
            position: relative !important;
            pointer-events: auto !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 44px !important;
            min-height: 44px !important;
            background: transparent !important;
            border: none !important;
            -webkit-tap-highlight-color: rgba(0, 255, 136, 0.3) !important;
        }

        .sidebar-toggle:active,
        .sidebar-toggle:focus {
            outline: 2px solid rgba(0, 255, 136, 0.5) !important;
            outline-offset: 2px !important;
        }

        .sidebar-toggle i {
            pointer-events: none !important;
            z-index: 0 !important;
        }

        .header-left {
            z-index: 10002 !important;
            position: relative !important;
            pointer-events: auto !important;
        }

        .dashboard-header {
            z-index: 10001 !important;
            position: sticky !important;
        }

        .header-content {
            z-index: 10002 !important;
            position: relative !important;
        }

        .mining-hero-card,
        .mining-logo-header,
        .mining-stats-grid,
        .mining-dashboard {
            z-index: 0 !important;
            position: relative !important;
        }

        /* Ensure nothing blocks the header */
        .content-area {
            position: relative;
            z-index: 0;
        }

        /* Prevent any overlay from blocking */
        .mining-hero-card::before {
            z-index: -1 !important;
        }
    }
    
    .mining-logo-large {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, rgba(0, 255, 136, 0.2) 0%, rgba(0, 217, 119, 0.1) 100%);
        border: 2px solid rgba(0, 255, 136, 0.4);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 30px rgba(0, 255, 136, 0.3);
    }
    
    .mining-logo-large svg {
        width: 50px;
        height: 50px;
        filter: drop-shadow(0 0 10px rgba(0, 255, 136, 0.8));
    }
    
    .mining-brand-info {
        flex: 1;
    }
    
    .mining-brand-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #00FF88 0%, #00D977 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 0.5rem 0;
        letter-spacing: -1px;
        text-shadow: 0 0 20px rgba(0, 255, 136, 0.3);
    }
    
    .mining-brand-subtitle {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0;
        font-weight: 400;
    }
    
    .mining-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
        position: relative;
        z-index: 1;
    }
    
    .mining-stat-card {
        background: rgba(0, 255, 136, 0.05);
        border: 1px solid rgba(0, 255, 136, 0.2);
        border-radius: 12px;
        padding: 1.5rem;
        transition: var(--transition);
    }
    
    .mining-stat-card:hover {
        background: rgba(0, 255, 136, 0.1);
        border-color: rgba(0, 255, 136, 0.4);
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0, 255, 136, 0.2);
    }
    
    .mining-stat-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .mining-stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        font-variant-numeric: tabular-nums;
    }
    
    .mining-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }
    
    .mining-action-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 2rem;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }
    
    .mining-action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .mining-action-card:hover::before {
        transform: scaleX(1);
    }
    
    .mining-action-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 20px rgba(0, 255, 136, 0.15);
        transform: translateY(-4px);
    }
    
    .mining-action-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, rgba(0, 255, 136, 0.2) 0%, rgba(0, 217, 119, 0.1) 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(0, 255, 136, 0.3);
    }
    
    .mining-action-icon i {
        font-size: 1.75rem;
        color: var(--primary-color);
    }
    
    .mining-action-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }
    
    .mining-action-desc {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }
    
    .mining-action-btn {
        width: 100%;
        padding: 0.875rem 1.5rem;
        background: linear-gradient(135deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
        border: none;
        border-radius: 8px;
        color: #000;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .mining-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0, 255, 136, 0.4);
    }
    
    .mining-overview-section {
        margin-bottom: 2.5rem;
    }
    
    .mining-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .mining-section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }
    
    .mining-section-subtitle {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0.25rem 0 0 0;
    }
    
    .mining-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    
    .mining-overview-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 1.75rem;
        transition: var(--transition);
    }
    
    .mining-overview-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 20px rgba(0, 255, 136, 0.1);
    }
    
    .mining-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }
    
    .mining-card-title {
        font-size: 0.875rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0;
    }
    
    .mining-card-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0.5rem 0;
        font-variant-numeric: tabular-nums;
    }
    
    .mining-card-change {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }
    
    .mining-card-change.positive {
        color: var(--primary-color);
    }
    
    .mining-activity-section {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .mining-activity-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .mining-activity-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }
    
    .mining-empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-secondary);
    }
    
    .mining-empty-icon {
        font-size: 3rem;
        color: var(--text-muted);
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .mining-empty-text {
        font-size: 0.875rem;
        margin: 0;
    }
    
    @media (max-width: 768px) {
        .mining-dashboard {
            padding: 1rem;
        }
        
        .mining-brand-title {
            font-size: 1.75rem;
        }
        
        .mining-logo-large {
            width: 60px;
            height: 60px;
        }
        
        .mining-logo-large svg {
            width: 35px;
            height: 35px;
        }
        
        .mining-stats-grid {
            grid-template-columns: 1fr;
        }
        
        .mining-actions-grid {
            grid-template-columns: 1fr;
        }
        
        .mining-cards-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="mining-dashboard">
    <!-- Hero Section with Logo -->
    <div class="mining-hero-section">
        <div class="mining-hero-card">
            <div class="mining-logo-header">
                <div class="mining-logo-large">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L8 6L10 8L6 12L8 14L12 10L16 14L18 12L14 8L16 6L12 2Z" fill="url(#coreMiningGradient)" stroke="#00FF88" stroke-width="1.5" stroke-linejoin="round"/>
                        <rect x="4" y="16" width="4" height="4" rx="1" fill="#00FF88" opacity="0.6"/>
                        <rect x="10" y="18" width="4" height="4" rx="1" fill="#00D977" opacity="0.6"/>
                        <rect x="16" y="16" width="4" height="4" rx="1" fill="#00FF88" opacity="0.6"/>
                        <circle cx="12" cy="8" r="8" fill="url(#coreMiningGlow)" opacity="0.3"/>
                        <defs>
                            <linearGradient id="coreMiningGradient" x1="12" y1="2" x2="12" y2="14" gradientUnits="userSpaceOnUse">
                                <stop offset="0%" stop-color="#00FF88"/>
                                <stop offset="100%" stop-color="#00D977"/>
                            </linearGradient>
                            <radialGradient id="coreMiningGlow" cx="50%" cy="50%">
                                <stop offset="0%" stop-color="#00FF88" stop-opacity="0.8"/>
                                <stop offset="100%" stop-color="#00FF88" stop-opacity="0"/>
                            </radialGradient>
                        </defs>
                    </svg>
                </div>
                <div class="mining-brand-info">
                    <h1 class="mining-brand-title">Core Mining</h1>
                    <p class="mining-brand-subtitle">Advanced Cryptocurrency Mining Platform</p>
                </div>
            </div>
            
            <div class="mining-stats-grid">
                <div class="mining-stat-card">
                    <div class="mining-stat-label">Total Balance</div>
                    <div class="mining-stat-value" id="totalBalance">$0.00</div>
                </div>
                <div class="mining-stat-card">
                    <div class="mining-stat-label">Active Mining</div>
                    <div class="mining-stat-value" id="activeMining">0</div>
                </div>
                <div class="mining-stat-card">
                    <div class="mining-stat-label">Hash Rate</div>
                    <div class="mining-stat-value" id="hashRate">0 H/s</div>
                </div>
                <div class="mining-stat-card">
                    <div class="mining-stat-label">Daily Earnings</div>
                    <div class="mining-stat-value" id="dailyEarnings">$0.00</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mining-actions-grid">
        <div class="mining-action-card">
            <div class="mining-action-icon">
                <i class="fas fa-rocket"></i>
            </div>
            <h3 class="mining-action-title">Start Mining</h3>
            <p class="mining-action-desc">Activate your mining rig and start earning cryptocurrency rewards instantly.</p>
            <button class="mining-action-btn">
                <i class="fas fa-play"></i>
                <span>Begin Mining</span>
            </button>
        </div>
        
        <div class="mining-action-card">
            <div class="mining-action-icon">
                <i class="fas fa-wallet"></i>
            </div>
            <h3 class="mining-action-title">Deposit Funds</h3>
            <p class="mining-action-desc">Add funds to your wallet to increase your mining capacity and earnings.</p>
            <button class="mining-action-btn">
                <i class="fas fa-arrow-up"></i>
                <span>Deposit Now</span>
            </button>
        </div>
        
        <div class="mining-action-card">
            <div class="mining-action-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="mining-action-title">Refer & Earn</h3>
            <p class="mining-action-desc">Invite friends and earn commission on their mining activities.</p>
            <button class="mining-action-btn">
                <i class="fas fa-user-plus"></i>
                <span>Invite Friends</span>
            </button>
        </div>
        
        <div class="mining-action-card">
            <div class="mining-action-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3 class="mining-action-title">View Plans</h3>
            <p class="mining-action-desc">Explore our mining plans and choose the best option for your needs.</p>
            <button class="mining-action-btn">
                <i class="fas fa-eye"></i>
                <span>Browse Plans</span>
            </button>
        </div>
    </div>

    <!-- Mining Overview -->
    <div class="mining-overview-section">
        <div class="mining-section-header">
            <div>
                <h2 class="mining-section-title">Mining Overview</h2>
                <p class="mining-section-subtitle">Track your mining performance and earnings</p>
            </div>
        </div>
        
        <div class="mining-cards-grid">
            <div class="mining-overview-card">
                <div class="mining-card-header">
                    <h4 class="mining-card-title">Total Invested</h4>
                    <i class="fas fa-coins" style="color: var(--primary-color);"></i>
                </div>
                <div class="mining-card-value">$0.00</div>
                <div class="mining-card-change positive">+0.00%</div>
            </div>
            
            <div class="mining-overview-card">
                <div class="mining-card-header">
                    <h4 class="mining-card-title">Total Earnings</h4>
                    <i class="fas fa-chart-line" style="color: var(--primary-color);"></i>
                </div>
                <div class="mining-card-value">$0.00</div>
                <div class="mining-card-change positive">+0.00%</div>
            </div>
            
            <div class="mining-overview-card">
                <div class="mining-card-header">
                    <h4 class="mining-card-title">Referral Bonus</h4>
                    <i class="fas fa-gift" style="color: var(--primary-color);"></i>
                </div>
                <div class="mining-card-value">$0.00</div>
                <div class="mining-card-change positive">+0.00%</div>
            </div>
            
            <div class="mining-overview-card">
                <div class="mining-card-header">
                    <h4 class="mining-card-title">Total Withdrawn</h4>
                    <i class="fas fa-arrow-down" style="color: var(--text-secondary);"></i>
                </div>
                <div class="mining-card-value">$0.00</div>
                <div class="mining-card-change">0.00%</div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="mining-activity-section">
        <div class="mining-activity-header">
            <h2 class="mining-activity-title">Recent Mining Activity</h2>
        </div>
        
        <div class="mining-empty-state">
            <div class="mining-empty-icon">
                <i class="fas fa-chart-area"></i>
            </div>
            <p class="mining-empty-text">No mining activity yet. Start mining to see your transactions here.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('dashboard/js/dashboard.js') }}"></script>
<script>
    // Balance toggle functionality
    let balanceVisible = true;
    const balanceElements = document.querySelectorAll('#totalBalance, #dailyEarnings');
    
    // You can add balance toggle functionality here if needed
</script>
@endpush

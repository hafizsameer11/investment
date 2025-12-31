@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Mining Plans')

@push('styles')
<link rel="stylesheet" href="{{ asset('dashboard/css/plans.css') }}">
<style>
    .plans-new-page {
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Hero Section */
    .plans-hero-new {
        text-align: center;
        margin-bottom: 3rem;
        padding: 3rem 2rem;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
    }

    .plans-hero-new::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #00FF88 0%, #00D977 50%, #00FF88 100%);
        background-size: 200% 100%;
        animation: shimmer 3s linear infinite;
    }

    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .plans-hero-new::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(0, 255, 136, 0.08) 0%, transparent 70%);
        pointer-events: none;
    }

    .plans-hero-content-new {
        position: relative;
        z-index: 1;
    }

    .plans-hero-title-new {
        font-size: 3rem;
        font-weight: 700;
        background: linear-gradient(135deg, #00FF88 0%, #00D977 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 1rem 0;
        letter-spacing: -2px;
    }

    .plans-hero-subtitle-new {
        font-size: 1.125rem;
        color: var(--text-secondary);
        margin: 0 0 2.5rem 0;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    .plans-hero-stats-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        max-width: 900px;
        margin: 0 auto;
    }

    .plans-hero-stat-new {
        padding: 1.5rem;
        background: rgba(0, 255, 136, 0.05);
        border: 1px solid rgba(0, 255, 136, 0.2);
        border-radius: 16px;
        transition: var(--transition);
    }

    .plans-hero-stat-new:hover {
        background: rgba(0, 255, 136, 0.1);
        border-color: rgba(0, 255, 136, 0.4);
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 255, 136, 0.2);
    }

    .plans-hero-stat-value-new {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        text-shadow: 0 0 20px rgba(0, 255, 136, 0.5);
    }

    .plans-hero-stat-label-new {
        font-size: 0.875rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    /* Main Plan Card */
    .plan-main-card-new {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 24px;
        padding: 3rem;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
    }

    .plan-main-card-new::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #00FF88 0%, #00D977 100%);
    }

    .plan-main-card-new::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(0, 255, 136, 0.05) 0%, transparent 70%);
        pointer-events: none;
    }

    .plan-card-content-new {
        position: relative;
        z-index: 1;
    }

    .plan-header-new {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .plan-header-left-new {
        display: flex;
        align-items: center;
        gap: 2rem;
        flex: 1;
    }

    .plan-icon-large-new {
        width: 120px;
        height: 120px;
        border-radius: 24px;
        background: linear-gradient(135deg, rgba(0, 255, 136, 0.2) 0%, rgba(0, 217, 119, 0.1) 100%);
        border: 3px solid rgba(0, 255, 136, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 32px rgba(0, 255, 136, 0.3);
        position: relative;
        overflow: hidden;
    }

    .plan-icon-large-new::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(0, 255, 136, 0.3) 0%, transparent 70%);
        animation: rotate 4s linear infinite;
    }

    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .plan-icon-large-new svg {
        position: relative;
        z-index: 1;
        width: 60px;
        height: 60px;
        filter: drop-shadow(0 0 10px rgba(0, 255, 136, 0.8));
    }

    .plan-title-section-new {
        flex: 1;
    }

    .plan-badge-new {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: linear-gradient(135deg, rgba(0, 255, 136, 0.2) 0%, rgba(0, 217, 119, 0.15) 100%);
        border: 1px solid rgba(0, 255, 136, 0.4);
        border-radius: 20px;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 1rem;
        box-shadow: 0 0 16px rgba(0, 255, 136, 0.3);
    }

    .plan-name-new {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
        letter-spacing: -1px;
    }

    .plan-tagline-new {
        font-size: 1.125rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .plan-security-badge-new {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: rgba(0, 255, 136, 0.08);
        border: 1px solid rgba(0, 255, 136, 0.25);
        border-radius: 16px;
        margin-bottom: 2.5rem;
    }

    .plan-security-icon-new {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(0, 255, 136, 0.2) 0%, rgba(0, 217, 119, 0.1) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 1.25rem;
    }

    .plan-security-text-new {
        flex: 1;
    }

    .plan-security-title-new {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .plan-security-desc-new {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    /* Features Grid */
    .plan-features-grid-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .plan-feature-card-new {
        background: rgba(0, 255, 136, 0.05);
        border: 1px solid rgba(0, 255, 136, 0.2);
        border-radius: 16px;
        padding: 2rem;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .plan-feature-card-new::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .plan-feature-card-new:hover {
        background: rgba(0, 255, 136, 0.1);
        border-color: rgba(0, 255, 136, 0.4);
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 255, 136, 0.2);
    }

    .plan-feature-card-new:hover::before {
        transform: scaleX(1);
    }

    .plan-feature-card-new.highlight {
        background: linear-gradient(135deg, rgba(0, 255, 136, 0.15) 0%, rgba(0, 217, 119, 0.1) 100%);
        border-color: rgba(0, 255, 136, 0.5);
        box-shadow: 0 0 30px rgba(0, 255, 136, 0.3);
    }

    .plan-feature-icon-new {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(0, 255, 136, 0.2) 0%, rgba(0, 217, 119, 0.1) 100%);
        border: 1px solid rgba(0, 255, 136, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: var(--primary-color);
        margin-bottom: 1.25rem;
        transition: var(--transition);
    }

    .plan-feature-card-new:hover .plan-feature-icon-new {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 4px 20px rgba(0, 255, 136, 0.4);
    }

    .plan-feature-card-new.highlight .plan-feature-icon-new {
        background: linear-gradient(135deg, #00FF88 0%, #00D977 100%);
        color: #000;
        box-shadow: 0 4px 20px rgba(0, 255, 136, 0.5);
    }

    .plan-feature-label-new {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
        font-weight: 600;
    }

    .plan-feature-value-new {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-variant-numeric: tabular-nums;
    }

    .plan-feature-card-new.highlight .plan-feature-value-new {
        color: var(--primary-color);
        text-shadow: 0 0 20px rgba(0, 255, 136, 0.5);
    }

    .plan-feature-hint-new {
        font-size: 0.8125rem;
        color: var(--text-secondary);
    }

    /* Calculator Section */
    .plan-calculator-section-new {
        background: rgba(0, 255, 136, 0.05);
        border: 1px solid rgba(0, 255, 136, 0.2);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2.5rem;
    }

    .plan-calculator-header-new {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .plan-calculator-title-new {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .plan-calculator-toggle-new {
        padding: 0.875rem 1.5rem;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .plan-calculator-toggle-new:hover {
        background: rgba(0, 255, 136, 0.1);
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .plan-calculator-grid-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .plan-calculator-item-new {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 1.75rem;
        text-align: center;
    }

    .plan-calculator-label-new {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        margin-bottom: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .plan-calculator-value-new {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        font-variant-numeric: tabular-nums;
    }

    .plan-calculator-note-new {
        font-size: 0.8125rem;
        color: var(--text-secondary);
    }

    /* Action Buttons */
    .plan-actions-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .plan-action-btn-new {
        padding: 1.5rem 2rem;
        border: none;
        border-radius: 16px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        position: relative;
        overflow: hidden;
    }

    .plan-action-btn-new::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .plan-action-btn-new:hover::before {
        width: 300px;
        height: 300px;
    }

    .plan-action-btn-new span,
    .plan-action-btn-new i {
        position: relative;
        z-index: 1;
    }

    .plan-action-primary-new {
        background: linear-gradient(135deg, #00FF88 0%, #00D977 100%);
        color: #000;
        box-shadow: 0 4px 20px rgba(0, 255, 136, 0.4);
    }

    .plan-action-primary-new:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 30px rgba(0, 255, 136, 0.6);
    }

    .plan-action-secondary-new {
        background: var(--card-bg);
        border: 2px solid var(--card-border);
        color: var(--text-primary);
    }

    .plan-action-secondary-new:hover {
        background: rgba(0, 255, 136, 0.1);
        border-color: var(--primary-color);
        box-shadow: 0 0 20px rgba(0, 255, 136, 0.3);
    }

    /* Benefits Section */
    .plan-benefits-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        padding-top: 2rem;
        border-top: 1px solid var(--card-border);
    }

    .plan-benefit-item-new {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: rgba(0, 255, 136, 0.05);
        border: 1px solid rgba(0, 255, 136, 0.15);
        border-radius: 12px;
        transition: var(--transition);
    }

    .plan-benefit-item-new:hover {
        background: rgba(0, 255, 136, 0.1);
        border-color: rgba(0, 255, 136, 0.3);
        transform: translateX(4px);
    }

    .plan-benefit-icon-new {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(0, 255, 136, 0.2) 0%, rgba(0, 217, 119, 0.1) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 1.125rem;
        flex-shrink: 0;
    }

    .plan-benefit-text-new {
        font-size: 0.9375rem;
        color: var(--text-primary);
        font-weight: 500;
    }

    /* Coming Soon Section */
    .plans-coming-soon-new {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 24px;
        padding: 3rem;
        text-align: center;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
    }

    .coming-soon-title-new {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.75rem 0;
    }

    .coming-soon-subtitle-new {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0 0 2.5rem 0;
    }

    .coming-soon-cards-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        max-width: 800px;
        margin: 0 auto;
    }

    .coming-soon-card-new {
        background: rgba(0, 255, 136, 0.05);
        border: 1px solid rgba(0, 255, 136, 0.2);
        border-radius: 20px;
        padding: 2.5rem;
        transition: var(--transition);
        opacity: 0.7;
        position: relative;
        overflow: hidden;
    }

    .coming-soon-card-new::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
        opacity: 0.5;
    }

    .coming-soon-card-new:hover {
        opacity: 1;
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 255, 136, 0.2);
        border-color: rgba(0, 255, 136, 0.4);
    }

    .coming-soon-icon-new {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        background: linear-gradient(135deg, rgba(0, 255, 136, 0.2) 0%, rgba(0, 217, 119, 0.1) 100%);
        border: 2px solid rgba(0, 255, 136, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        color: var(--primary-color);
    }

    .coming-soon-name-new {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
    }

    .coming-soon-status-new {
        font-size: 0.875rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .plans-new-page {
            padding: 1rem;
        }

        .plans-hero-title-new {
            font-size: 2rem;
        }

        .plan-name-new {
            font-size: 1.75rem;
        }

        .plan-icon-large-new {
            width: 80px;
            height: 80px;
        }

        .plan-features-grid-new {
            grid-template-columns: 1fr;
        }

        .plan-actions-new {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="plans-new-page">
    <!-- Hero Section -->
    <div class="plans-hero-new">
        <div class="plans-hero-content-new">
            <h1 class="plans-hero-title-new">Mining Plans</h1>
            <p class="plans-hero-subtitle-new">Choose your mining plan and start earning cryptocurrency rewards with automated 24/7 mining operations</p>
            <div class="plans-hero-stats-new">
                <div class="plans-hero-stat-new">
                    <div class="plans-hero-stat-value-new">3/3.5%</div>
                    <div class="plans-hero-stat-label-new">Daily ROI</div>
                </div>
                <div class="plans-hero-stat-new">
                    <div class="plans-hero-stat-value-new">$2</div>
                    <div class="plans-hero-stat-label-new">Minimum Investment</div>
                </div>
                <div class="plans-hero-stat-new">
                    <div class="plans-hero-stat-value-new">24/7</div>
                    <div class="plans-hero-stat-label-new">Active Mining</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Plan Card -->
    <div class="plan-main-card-new">
        <div class="plan-card-content-new">
            <div class="plan-header-new">
                <div class="plan-header-left-new">
                    <div class="plan-icon-large-new">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L8 6L10 8L6 12L8 14L12 10L16 14L18 12L14 8L16 6L12 2Z" fill="url(#planGradient)" stroke="#00FF88" stroke-width="1.5" stroke-linejoin="round"/>
                            <rect x="4" y="16" width="4" height="4" rx="1" fill="#00FF88" opacity="0.6"/>
                            <rect x="10" y="18" width="4" height="4" rx="1" fill="#00D977" opacity="0.6"/>
                            <rect x="16" y="16" width="4" height="4" rx="1" fill="#00FF88" opacity="0.6"/>
                            <defs>
                                <linearGradient id="planGradient" x1="12" y1="2" x2="12" y2="14" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#00FF88"/>
                                    <stop offset="100%" stop-color="#00D977"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <div class="plan-title-section-new">
                        <div class="plan-badge-new">
                            <i class="fas fa-star"></i>
                            <span>Featured Mining Plan</span>
                        </div>
                        <h2 class="plan-name-new">Lithium</h2>
                        <p class="plan-tagline-new">Advanced Mining Plan for Maximum Returns</p>
                    </div>
                </div>
            </div>

            <!-- Security Badge -->
            <div class="plan-security-badge-new">
                <div class="plan-security-icon-new">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="plan-security-text-new">
                    <div class="plan-security-title-new">Principal Return Guarantee</div>
                    <div class="plan-security-desc-new">Your initial investment will be returned at the end of the plan period</div>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="plan-features-grid-new">
                <div class="plan-feature-card-new">
                    <div class="plan-feature-icon-new">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="plan-feature-label-new">Investment Range</div>
                    <div class="plan-feature-value-new">$2 - $100,000</div>
                    <div class="plan-feature-hint-new">Minimum investment: $2</div>
                </div>

                <div class="plan-feature-card-new highlight">
                    <div class="plan-feature-icon-new">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="plan-feature-label-new">Daily ROI</div>
                    <div class="plan-feature-value-new">3% - 3.5%</div>
                    <div class="plan-feature-hint-new">Fixed daily returns guaranteed</div>
                </div>

                <div class="plan-feature-card-new">
                    <div class="plan-feature-icon-new">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="plan-feature-label-new">Hourly Rate</div>
                    <div class="plan-feature-value-new">0.122%</div>
                    <div class="plan-feature-hint-new">Per hour earnings</div>
                </div>
            </div>

            <!-- Calculator Section -->
            <div class="plan-calculator-section-new">
                <div class="plan-calculator-header-new">
                    <h3 class="plan-calculator-title-new">Estimated Returns</h3>
                    <button class="plan-calculator-toggle-new" id="calculatorToggle">
                        <i class="fas fa-calculator"></i>
                        <span>Open Calculator</span>
                    </button>
                </div>
                <div class="plan-calculator-grid-new">
                    <div class="plan-calculator-item-new">
                        <div class="plan-calculator-label-new">Daily Earnings</div>
                        <div class="plan-calculator-value-new">$0.00</div>
                        <div class="plan-calculator-note-new">Based on $100 investment</div>
                    </div>
                    <div class="plan-calculator-item-new">
                        <div class="plan-calculator-label-new">Monthly Earnings</div>
                        <div class="plan-calculator-value-new">$0.00</div>
                        <div class="plan-calculator-note-new">30 days projection</div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="plan-actions-new">
                <button class="plan-action-btn-new plan-action-primary-new">
                    <i class="fas fa-rocket"></i>
                    <span>Start Mining Now</span>
                </button>
                <button class="plan-action-btn-new plan-action-secondary-new" id="openCalculatorBtn">
                    <i class="fas fa-calculator"></i>
                    <span>Mining Calculator</span>
                </button>
            </div>

            <!-- Benefits Section -->
            <div class="plan-benefits-new">
                <div class="plan-benefit-item-new">
                    <div class="plan-benefit-icon-new">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="plan-benefit-text-new">24/7 automated mining</div>
                </div>
                <div class="plan-benefit-item-new">
                    <div class="plan-benefit-icon-new">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="plan-benefit-text-new">Instant withdrawals</div>
                </div>
                <div class="plan-benefit-item-new">
                    <div class="plan-benefit-icon-new">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="plan-benefit-text-new">Principal protection</div>
                </div>
                <div class="plan-benefit-item-new">
                    <div class="plan-benefit-icon-new">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="plan-benefit-text-new">Real-time mining tracking</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Coming Soon Section -->
    <div class="plans-coming-soon-new">
        <h3 class="coming-soon-title-new">More Plans Coming Soon</h3>
        <p class="coming-soon-subtitle-new">We're working on additional mining plan options for you</p>
        <div class="coming-soon-cards-new">
            <div class="coming-soon-card-new">
                <div class="coming-soon-icon-new">
                    <i class="fas fa-gem"></i>
                </div>
                <div class="coming-soon-name-new">Platinum</div>
                <div class="coming-soon-status-new">Coming Soon</div>
            </div>
            <div class="coming-soon-card-new">
                <div class="coming-soon-icon-new">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="coming-soon-name-new">Diamond</div>
                <div class="coming-soon-status-new">Coming Soon</div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('dashboard/js/plans.js') }}"></script>
@endpush
@endsection

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

    /* Hide info icons on desktop */
    .mining-action-info {
        display: none;
    }

    /* Hide activity toggle on desktop */
    .mining-activity-toggle {
        display: none;
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
            padding: 0.75rem;
        }
        
        .mining-hero-section {
            margin-bottom: 1.5rem;
        }

        .mining-hero-card {
            padding: 1.5rem;
            border-radius: 12px;
        }
        
        .mining-logo-header {
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .mining-brand-title {
            font-size: 1.5rem;
        }
        
        .mining-brand-subtitle {
            font-size: 0.875rem;
        }
        
        .mining-logo-large {
            width: 56px;
            height: 56px;
        }
        
        .mining-logo-large svg {
            width: 32px;
            height: 32px;
        }
        
        .mining-stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .mining-stat-card {
            padding: 1.25rem;
        }

        .mining-stat-label {
            font-size: 0.8125rem;
            margin-bottom: 0.5rem;
        }

        .mining-stat-value {
            font-size: 1.5rem;
        }
        
        .mining-actions-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .mining-action-card {
            padding: 1.5rem;
        }

        .mining-action-icon {
            width: 50px;
            height: 50px;
            margin-bottom: 1rem;
        }

        .mining-action-icon i {
            font-size: 1.5rem;
        }

        .mining-action-title {
            font-size: 1.125rem;
        }

        .mining-action-desc {
            font-size: 0.8125rem;
            margin-bottom: 1rem;
        }

        .mining-action-btn {
            padding: 0.75rem 1.25rem;
            font-size: 0.8125rem;
        }
        
        .mining-cards-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .mining-overview-card {
            padding: 1.25rem;
        }

        .mining-card-value {
            font-size: 1.5rem;
        }

        .mining-section-header {
            margin-bottom: 1rem;
        }

        .mining-section-title {
            font-size: 1.25rem;
        }

        .mining-activity-section {
            padding: 1.5rem;
        }

        .mining-activity-title {
            font-size: 1.125rem;
        }
    }

    /* ============================================
       NATIVE MOBILE APP INTERFACE
       Max-width: 390px (Mobile devices only)
       Flat Design | Card-Based | Production App UX
       ============================================ */
    @media (max-width: 390px) {
        /* Container - Mobile App Width - No Padding */
        .mining-dashboard {
            padding: 0;
            max-width: 100%;
            margin: 0;
            background: var(--bg-primary);
        }

        /* Hero Section - Flat Card Design */
        .mining-hero-section {
            margin-bottom: 0.5rem;
        }

        .mining-hero-card {
            padding: 1rem;
            border-radius: 0;
            border: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            margin: 0;
            background: var(--card-bg);
            box-shadow: none;
        }

        .mining-hero-card::before {
            display: none;
        }

        /* Logo Header - Flat Minimal */
        .mining-logo-header {
            gap: 0.75rem;
            margin-bottom: 1rem;
            align-items: center;
        }

        .mining-logo-large {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: rgba(0, 255, 136, 0.1);
            border: none;
            box-shadow: none;
        }

        .mining-logo-large svg {
            width: 24px;
            height: 24px;
        }

        /* Hide subtitle */
        .mining-brand-subtitle {
            display: none;
        }

        .mining-brand-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin: 0;
            letter-spacing: -0.3px;
            line-height: 1.2;
            color: var(--text-primary);
        }

        /* Stats Grid - Flat Card 1x2 (Reduced from 2x2) */
        .mining-stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
            margin-top: 0;
        }

        .mining-stat-card {
            padding: 0.875rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: none;
        }

        .mining-stat-label {
            font-size: 0.6875rem;
            margin-bottom: 0.375rem;
            line-height: 1.2;
            opacity: 0.6;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .mining-stat-value {
            font-size: 1.125rem;
            font-weight: 600;
            line-height: 1.2;
            color: var(--text-primary);
        }

        /* Action Cards - Icon Above Text, All Inline, No Scroll */
        .mining-actions-grid {
            display: flex;
            flex-direction: row;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
            padding: 0.75rem 1rem;
            overflow: hidden;
            justify-content: space-between;
        }

        .mining-action-card {
            padding: 0.75rem 0.5rem;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            position: relative;
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: none;
            flex: 1;
            min-width: 0;
            cursor: pointer;
            -webkit-tap-highlight-color: rgba(0, 255, 136, 0.2);
        }

        .mining-action-card::before {
            display: none;
        }

        .mining-action-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: rgba(0, 255, 136, 0.1);
            border: none;
            box-shadow: none;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .mining-action-icon i {
            font-size: 1.25rem;
            color: var(--primary-color);
        }

        /* Hide info icon, descriptions, and buttons */
        .mining-action-info {
            display: none !important;
        }

        .mining-action-desc {
            display: none !important;
        }

        .mining-action-btn {
            display: none !important;
        }

        /* Show activity toggle on mobile */
        .mining-activity-toggle {
            display: flex !important;
        }

        .mining-action-title {
            display: none !important;
        }

        .mining-action-btn span {
            font-size: 0.6875rem;
        }

        .mining-action-btn i {
            font-size: 0.6875rem;
        }

        /* Overview Section - Flat Card Design */
        .mining-overview-section {
            margin-bottom: 0.75rem;
            padding: 0 0.5rem;
        }

        .mining-section-header {
            margin-bottom: 0.75rem;
            padding: 0;
        }

        .mining-section-subtitle {
            display: none;
        }

        .mining-section-title {
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
            line-height: 1.2;
            color: var(--text-primary);
        }

        .mining-cards-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
        }

        .mining-overview-card {
            padding: 1rem;
            border-radius: 12px;
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: none;
        }

        .mining-card-header {
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mining-card-title {
            font-size: 0.6875rem;
            margin: 0;
            opacity: 0.6;
            font-weight: 500;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .mining-card-header i {
            font-size: 0.875rem;
            opacity: 0.5;
        }

        .mining-card-value {
            font-size: 1.125rem;
            font-weight: 600;
            margin: 0.25rem 0;
            line-height: 1.2;
            color: var(--text-primary);
        }

        .mining-card-change {
            font-size: 0.6875rem;
            margin: 0;
            opacity: 0.7;
            color: var(--text-secondary);
        }

        /* Activity Section - Flat Card Design */
        .mining-activity-section {
            padding: 1rem;
            border-radius: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            margin: 0;
            background: var(--card-bg);
            box-shadow: none;
        }

        .mining-activity-header {
            margin-bottom: 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mining-activity-title {
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
            line-height: 1.2;
            color: var(--text-primary);
        }

        /* Collapse/Expand Toggle - Flat */
        .mining-activity-toggle {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(0, 255, 136, 0.1);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            -webkit-tap-highlight-color: rgba(0, 255, 136, 0.2);
        }

        .mining-activity-toggle i {
            font-size: 0.75rem;
            color: var(--primary-color);
            transition: transform 0.2s;
        }

        .mining-activity-section.collapsed .mining-activity-content {
            display: none;
        }

        .mining-activity-section.collapsed .mining-activity-toggle i {
            transform: rotate(-90deg);
        }

        /* Default collapsed state on mobile */
        .mining-activity-section {
            transition: all 0.2s ease;
        }

        .mining-empty-state {
            padding: 2rem 1rem;
            text-align: center;
        }

        .mining-empty-icon {
            font-size: 2rem;
            margin-bottom: 0.75rem;
            opacity: 0.4;
            color: var(--text-secondary);
        }

        .mining-empty-text {
            font-size: 0.8125rem;
            line-height: 1.4;
            opacity: 0.6;
            color: var(--text-secondary);
        }

        /* Global Mobile Typography */
        body {
            font-size: 14px;
            line-height: 1.4;
        }

        /* Content Area - No Padding, App-Like */
        .content-area {
            padding: 0;
            padding-bottom: 80px;
            max-width: 100%;
            overflow-x: hidden;
        }

        /* Sticky Header - Flat Design */
        .dashboard-header {
            position: sticky;
            top: 0;
            z-index: 999;
            background: var(--bg-primary);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: none;
        }

        .header-content {
            padding: 0.75rem 1rem;
        }

        /* Touch-friendly buttons */
        button,
        .mining-action-btn,
        .btn {
            min-height: 36px;
            min-width: 44px;
            -webkit-tap-highlight-color: rgba(0, 255, 136, 0.2);
        }

        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            line-height: 1.2;
            margin: 0;
        }

        p {
            margin: 0;
            line-height: 1.4;
        }

        /* Remove all hover effects and transforms */
        .mining-action-card:hover,
        .mining-stat-card:hover,
        .mining-overview-card:hover {
            transform: none;
            box-shadow: none;
            border-color: rgba(255, 255, 255, 0.05);
        }

        /* Remove any table-like appearance */
        table, thead, tbody, tr, td, th {
            display: block;
            width: 100%;
        }

        /* Remove all gradients and shadows - Flat Design */
        .mining-action-card,
        .mining-stat-card,
        .mining-overview-card,
        .mining-hero-card {
            box-shadow: none !important;
        }

        /* Ensure no wide layouts */
        .mining-dashboard,
        .content-area,
        .main-content {
            max-width: 100%;
            overflow-x: hidden;
        }

        /* Tooltip styles for info icons */
        .mining-tooltip {
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            margin-bottom: 0.5rem;
            padding: 0.5rem 0.75rem;
            background: rgba(0, 0, 0, 0.95);
            border: 1px solid rgba(0, 255, 136, 0.2);
            border-radius: 8px;
            font-size: 0.6875rem;
            color: var(--text-primary);
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        }

        .mining-action-info:hover .mining-tooltip,
        .mining-action-info:active .mining-tooltip {
            opacity: 1;
        }

        /* Ensure sections are properly spaced */
        .mining-hero-section + .mining-actions-grid {
            margin-top: 0.5rem;
        }

        .mining-actions-grid + .mining-overview-section {
            margin-top: 0.75rem;
        }

        .mining-overview-section + .mining-activity-section {
            margin-top: 0.75rem;
        }

        /* Remove any desktop-style wide containers */
        .dashboard-page {
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        /* Ensure cards have proper flat appearance */
        .mining-stat-card,
        .mining-action-card,
        .mining-overview-card {
            transition: background-color 0.2s;
        }

        .mining-stat-card:active,
        .mining-action-card:active,
        .mining-overview-card:active {
            background: rgba(255, 255, 255, 0.04);
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
                    <div class="mining-stat-label">Daily Earnings</div>
                    <div class="mining-stat-value" id="dailyEarnings">$0.00</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mining-actions-grid">
        <div class="mining-action-card" data-action="start-mining">
            <div class="mining-action-info" onclick="toggleActionDesc(this)">
                <i class="fas fa-info"></i>
                <span class="mining-tooltip">Tap for details</span>
            </div>
            <div class="mining-action-icon">
                <i class="fas fa-rocket"></i>
            </div>
            <h3 class="mining-action-title">Start Mining</h3>
            <p class="mining-action-desc">Activate mining rig and earn rewards instantly.</p>
            <button class="mining-action-btn">
                <i class="fas fa-play"></i>
                <span>Begin</span>
            </button>
        </div>
        
        <div class="mining-action-card" data-action="deposit">
            <div class="mining-action-info" onclick="toggleActionDesc(this)">
                <i class="fas fa-info"></i>
                <span class="mining-tooltip">Tap for details</span>
            </div>
            <div class="mining-action-icon">
                <i class="fas fa-wallet"></i>
            </div>
            <h3 class="mining-action-title">Deposit</h3>
            <p class="mining-action-desc">Add funds to increase mining capacity.</p>
            <button class="mining-action-btn">
                <i class="fas fa-arrow-up"></i>
                <span>Deposit</span>
            </button>
        </div>
        
        <div class="mining-action-card" data-action="refer">
            <div class="mining-action-info" onclick="toggleActionDesc(this)">
                <i class="fas fa-info"></i>
                <span class="mining-tooltip">Tap for details</span>
            </div>
            <div class="mining-action-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="mining-action-title">Refer & Earn</h3>
            <p class="mining-action-desc">Invite friends and earn commission.</p>
            <button class="mining-action-btn">
                <i class="fas fa-user-plus"></i>
                <span>Invite</span>
            </button>
        </div>
        
        <div class="mining-action-card" data-action="plans">
            <div class="mining-action-info" onclick="toggleActionDesc(this)">
                <i class="fas fa-info"></i>
                <span class="mining-tooltip">Tap for details</span>
            </div>
            <div class="mining-action-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3 class="mining-action-title">View Plans</h3>
            <p class="mining-action-desc">Explore mining plans and options.</p>
            <button class="mining-action-btn">
                <i class="fas fa-eye"></i>
                <span>Browse</span>
            </button>
        </div>
    </div>

    <!-- Mining Overview -->
    <div class="mining-overview-section">
        <div class="mining-section-header">
            <h2 class="mining-section-title">Mining Overview</h2>
        </div>
        
        <div class="mining-cards-grid">
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
                    <h4 class="mining-card-title">Total Invested</h4>
                    <i class="fas fa-coins" style="color: var(--primary-color);"></i>
                </div>
                <div class="mining-card-value">$0.00</div>
                <div class="mining-card-change positive">+0.00%</div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="mining-activity-section collapsed" id="activitySection">
        <div class="mining-activity-header">
            <h2 class="mining-activity-title">Recent Activity</h2>
            <div class="mining-activity-toggle" onclick="toggleActivitySection()">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
        
        <div class="mining-activity-content">
            <div class="mining-empty-state">
                <div class="mining-empty-icon">
                    <i class="fas fa-chart-area"></i>
                </div>
                <p class="mining-empty-text">No activity yet. Start mining to see transactions.</p>
            </div>
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
    
    // Toggle action card description
    function toggleActionDesc(element) {
        const card = element.closest('.mining-action-card');
        if (card) {
            card.classList.toggle('expanded');
        }
    }
    
    // Toggle activity section
    function toggleActivitySection() {
        const section = document.getElementById('activitySection');
        if (section) {
            section.classList.toggle('collapsed');
        }
    }
    
    // Close action descriptions when clicking outside (mobile only)
    if (window.innerWidth <= 390) {
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.mining-action-card') && !e.target.closest('.mining-action-info')) {
                document.querySelectorAll('.mining-action-card.expanded').forEach(card => {
                    card.classList.remove('expanded');
                });
            }
        });
    }
</script>
@endpush



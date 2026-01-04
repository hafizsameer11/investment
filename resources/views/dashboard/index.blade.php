@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('dashboard/css/dashboard.css') }}">
<style>
    .mining-dashboard {
        padding: 2rem;
    }

    .mining-hero-section {
        margin-bottom: 0;
    }

    /* Merged Wallet Card - Balance + Actions */
    .mining-wallet-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        margin-bottom: 1.5rem;
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out;
    }

    /* Balance Section */
    .wallet-balance-section {
        padding: 2rem 1.5rem;
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.08) 0%, rgba(255, 138, 29, 0.05) 100%);
        border-bottom: 1px solid rgba(255, 178, 30, 0.1);
    }

    .balance-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .balance-label {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .balance-label i {
        font-size: 0.875rem;
        color: var(--primary-color);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .balance-label i:hover {
        opacity: 0.8;
        transform: scale(1.1);
    }

    .balance-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .balance-trend-up {
        font-size: 0.875rem;
        color: var(--primary-color);
    }

    .balance-toggle-icon {
        font-size: 0.875rem;
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .balance-toggle-icon:hover {
        color: var(--primary-color);
    }

    .balance-amount-display {
        margin-bottom: 1.25rem;
    }

    .balance-amount-large {
        font-size: 3rem;
        font-weight: 700;
        color: var(--primary-color);
        line-height: 1.2;
        font-variant-numeric: tabular-nums;
        text-shadow: 0 0 20px rgba(255, 178, 30, 0.3);
    }

    .deposit-wallet-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 178, 30, 0.1);
    }

    .deposit-wallet-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .deposit-wallet-amount {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        font-variant-numeric: tabular-nums;
    }

    .deposit-trend-down {
        font-size: 0.875rem;
        color: var(--danger-color);
    }

    /* Action Buttons Section */
    .wallet-actions-grid {
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 1.5rem 1rem;
        background: var(--bg-primary);
        gap: 1rem;
    }

    .wallet-action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        flex: 1;
        max-width: 80px;
    }

    .wallet-action-btn:active {
        transform: scale(0.95);
    }

    .wallet-action-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .wallet-action-btn:hover .wallet-action-icon {
        background: rgba(255, 178, 30, 0.1);
        border-color: rgba(255, 178, 30, 0.3);
        transform: scale(1.05);
    }

    .wallet-action-icon i {
        font-size: 1.5rem;
        color: var(--text-primary);
    }

    .wallet-action-label {
        font-size: 0.8125rem;
        color: var(--text-primary);
        text-align: center;
        font-weight: 500;
        white-space: nowrap;
        display: block;
    }

    @keyframes pulse {
        0%, 100% { opacity: 0.3; }
        50% { opacity: 0.6; }
    }

    /* Modern Animation Keyframes */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes shimmer {
        0% {
            background-position: -1000px 0;
        }
        100% {
            background-position: 1000px 0;
        }
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    @keyframes glow {
        0%, 100% {
            box-shadow: 0 0 20px rgba(255, 178, 30, 0.3);
        }
        50% {
            box-shadow: 0 0 30px rgba(255, 178, 30, 0.6);
        }
    }

    /* Smooth scroll behavior */
    html {
        scroll-behavior: smooth;
    }

    /* Animation classes */
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out forwards;
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .animate-slide-in-right {
        animation: slideInRight 0.6s ease-out forwards;
    }

    .animate-scale-in {
        animation: scaleIn 0.5s ease-out forwards;
    }

    /* Initial hidden state for animations - removed as we're using JavaScript for better control */

    /* Stagger animations handled by JavaScript for better performance */

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
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.2) 0%, rgba(255, 138, 29, 0.1) 100%);
        border: 2px solid rgba(255, 178, 30, 0.4);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 30px rgba(255, 178, 30, 0.3);
        animation: scaleIn 0.8s ease-out 0.2s forwards, float 3s ease-in-out infinite 1s;
        opacity: 0;
        transition: transform 0.3s ease;
    }

    .mining-logo-large:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 0 40px rgba(255, 178, 30, 0.5);
    }

    .mining-logo-large svg {
        width: 50px;
        height: 50px;
        filter: drop-shadow(0 0 10px rgba(255, 178, 30, 0.8));
        transition: transform 0.3s ease;
    }

    .mining-logo-large:hover svg {
        transform: scale(1.1);
    }

    .mining-brand-info {
        flex: 1;
    }

    .mining-brand-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 0.5rem 0;
        letter-spacing: -1px;
        text-shadow: 0 0 20px rgba(255, 178, 30, 0.3);
    }

    .mining-brand-subtitle {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0;
        font-weight: 400;
    }

    /* App-like Stats Row */
    .mining-stats-row {
        display: flex;
        gap: 1rem;
        padding: 1rem 1.5rem;
        background: var(--bg-primary);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .mining-stat-item {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.02);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        transition: all 0.2s ease;
    }

    .mining-stat-item:active {
        background: rgba(255, 255, 255, 0.04);
        transform: scale(0.98);
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(255, 178, 30, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon i {
        font-size: 1.125rem;
        color: var(--primary-color);
    }

    .stat-content {
        flex: 1;
        min-width: 0;
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        font-variant-numeric: tabular-nums;
    }


    .mining-action-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 1rem;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
        flex: 0 0 auto;
        min-width: 80px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        -webkit-tap-highlight-color: rgba(255, 178, 30, 0.2);
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
        background: rgba(255, 255, 255, 0.04);
        border-color: rgba(255, 178, 30, 0.2);
    }

    .mining-action-card:active {
        transform: scale(0.96);
        background: rgba(255, 255, 255, 0.06);
        transition: all 0.1s ease;
    }

    .mining-action-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 178, 30, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .mining-action-card:active .mining-action-icon {
        transform: scale(0.95);
        background: rgba(255, 178, 30, 0.15);
    }

    .mining-action-icon i {
        font-size: 1.5rem;
        color: var(--primary-color);
    }

    .mining-action-title {
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--text-primary);
        text-align: center;
        margin: 0;
        white-space: nowrap;
    }

    .mining-action-desc {
        display: none;
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
        display: none;
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
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .mining-overview-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .mining-overview-card:hover::after {
        transform: scaleX(1);
    }

    .mining-overview-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 8px 25px rgba(255, 178, 30, 0.2);
        transform: translateY(-4px) scale(1.02);
    }

    .mining-overview-card:active {
        transform: translateY(-2px) scale(1.01);
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

    /* App-like Graph Section */
    .mining-graph-section {
        padding: 1.5rem;
        background: var(--bg-primary);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .graph-header-app {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    .graph-title-app {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .graph-legend-app {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .legend-item-app {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8125rem;
        color: var(--text-secondary);
    }

    .legend-dot-app {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .legend-dot-app.earnings {
        background: var(--primary-color);
    }

    .legend-dot-app.investment {
        background: #FF8A1D;
    }

    .graph-wrapper-app {
        position: relative;
        height: 250px;
        width: 100%;
    }

    .graph-wrapper-app canvas {
        max-width: 100%;
        height: auto !important;
    }

    /* Enhanced Desktop Styling */
    @media (min-width: 769px) {
        .mining-wallet-card {
            margin: 0 1.5rem 1.5rem 1.5rem;
        }

        .mining-stats-row {
            margin: 0 1.5rem;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .mining-graph-section {
            margin: 0 1.5rem 1.5rem 1.5rem;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .wallet-action-btn:hover .wallet-action-icon {
        background: rgba(255, 178, 30, 0.15);
        border-color: rgba(255, 178, 30, 0.4);
            transform: scale(1.08);
        }
    }

    .mining-activity-section {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
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

        .mining-graph-wrapper {
            height: 250px;
        }

        .mining-graph-header {
            flex-direction: row;
            justify-content: space-between;
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
            font-size: 1.25rem;
        }

        .mining-brand-subtitle {
            font-size: 0.8125rem;
        }

        .mining-logo-large {
            width: 48px;
            height: 48px;
        }

        .mining-logo-large svg {
            width: 28px;
            height: 28px;
        }

        /* Mobile App View - Reduced Font Sizes */
        .wallet-balance-section {
            padding: 1.25rem 1rem;
        }

        .balance-label {
            font-size: 0.75rem;
        }

        .balance-amount-large {
            font-size: 2rem;
        }

        .deposit-wallet-label {
            font-size: 0.75rem;
        }

        .deposit-wallet-amount {
            font-size: 0.9375rem;
        }

        .deposit-trend-down,
        .balance-trend-up {
            font-size: 0.75rem;
        }

        .wallet-actions-grid {
            padding: 1.25rem 0.75rem;
        }

        .wallet-action-icon {
            width: 48px;
            height: 48px;
        }

        .wallet-action-icon i {
            font-size: 1.25rem;
        }

        .wallet-action-label {
            font-size: 0.75rem;
        }

        .mining-stats-row {
            padding: 0.75rem 1rem;
        }

        .mining-stat-item {
            padding: 0.75rem;
        }

        .stat-icon {
            width: 36px;
            height: 36px;
        }

        .stat-icon i {
            font-size: 1rem;
        }

        .stat-label {
            font-size: 0.6875rem;
        }

        .stat-value {
            font-size: 0.9375rem;
        }

        .graph-header-app {
            margin-bottom: 1rem;
        }

        .graph-title-app {
            font-size: 0.875rem;
        }

        .legend-item-app {
            font-size: 0.75rem;
        }

        .graph-wrapper-app {
            height: 200px;
        }

        .mining-stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .mining-stat-card {
            padding: 1rem;
        }

        .mining-stat-label {
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .mining-stat-value {
            font-size: 1.25rem;
        }

        .mining-actions-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .mining-activity-section {
            padding: 1.25rem;
        }

        .mining-activity-title {
            font-size: 1rem;
        }

        .mining-empty-icon {
            font-size: 2rem;
        }

        .mining-empty-text {
            font-size: 0.8125rem;
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

        /* Merged Wallet Card - Mobile */
        .mining-wallet-card {
            border-radius: 0;
            border-left: none;
            border-right: none;
            margin-bottom: 0;
        }

        .wallet-balance-section {
            padding: 1.5rem 1rem;
        }

        .balance-header-row {
            margin-bottom: 0.75rem;
        }

        .balance-label {
            font-size: 0.6875rem;
        }

        .balance-actions {
            gap: 0.5rem;
        }

        .balance-trend-up,
        .balance-toggle-icon {
            font-size: 0.75rem;
        }

        .balance-amount-large {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .deposit-wallet-info {
            padding-top: 0.75rem;
            gap: 0.375rem;
        }

        .deposit-wallet-label {
            font-size: 0.75rem;
        }

        .deposit-wallet-amount {
            font-size: 0.9375rem;
        }

        .deposit-trend-down {
            font-size: 0.75rem;
        }

        .wallet-actions-grid {
            padding: 1.25rem 0.5rem;
            gap: 0.5rem;
        }

        .wallet-action-icon {
            width: 48px;
            height: 48px;
        }

        .wallet-action-icon i {
            font-size: 1.25rem;
        }

        .wallet-action-label {
            font-size: 0.75rem;
        }

        /* Smaller icons on very small mobile screens */
        @media (max-width: 390px) {
            .wallet-action-icon {
                width: 44px;
                height: 44px;
            }

            .wallet-action-icon i {
                font-size: 1.125rem;
            }

            .wallet-action-label {
                font-size: 0.6875rem;
            }
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
            background: rgba(255, 178, 30, 0.1);
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

        .mining-action-btn span {
            font-size: 0.6875rem;
        }

        .mining-action-btn i {
            font-size: 0.6875rem;
        }

        /* Overview Section - App-like Design */
        .mining-overview-section {
            margin-bottom: 0;
        }

        .mining-stats-row {
            padding: 1rem;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .mining-stat-item {
            padding: 0.875rem;
            border-radius: 10px;
        }

        .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
        }

        .stat-icon i {
            font-size: 1rem;
        }

        .stat-label {
            font-size: 0.6875rem;
        }

        .stat-value {
            font-size: 1.125rem;
        }

        .mining-graph-section {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .graph-header-app {
            margin-bottom: 1rem;
        }

        .graph-title-app {
            font-size: 0.9375rem;
        }

        .graph-legend-app {
            gap: 0.75rem;
        }

        .legend-item-app {
            font-size: 0.75rem;
        }

        .graph-wrapper-app {
            height: 180px;
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
            background: rgba(255, 178, 30, 0.1);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            -webkit-tap-highlight-color: rgba(255, 178, 30, 0.2);
            transition: all 0.2s ease;
        }

        .mining-activity-toggle:active {
            transform: scale(0.9);
            background: rgba(255, 178, 30, 0.2);
        }

        .mining-activity-toggle i {
            font-size: 0.75rem;
            color: var(--primary-color);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .mining-activity-section.collapsed .mining-activity-toggle i {
            transform: rotate(-90deg);
        }

        .mining-activity-section.collapsed .mining-activity-content {
            display: none;
            animation: fadeIn 0.3s ease-out;
        }

        .mining-activity-section:not(.collapsed) .mining-activity-content {
            animation: fadeInUp 0.4s ease-out;
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

        /* Sticky Header - Blended with Page Background */
        .dashboard-header {
            position: sticky;
            top: 0;
            z-index: 999;
            background: var(--bg-primary) !important;
            border-bottom: none;
            box-shadow: none;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
        }

        .header-content {
            padding: 0.75rem 1rem;
            background: transparent;
        }


        /* Touch-friendly buttons */
        button,
        .mining-action-btn,
        .btn {
            min-height: 36px;
            min-width: 44px;
            -webkit-tap-highlight-color: rgba(255, 178, 30, 0.2);
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
            border: 1px solid rgba(255, 178, 30, 0.2);
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

        /* Mining Graph Mobile Styles */
        .mining-graph-container {
            margin-top: 0.75rem;
            padding: 0 0.5rem;
        }

        .mining-graph-card {
            padding: 1rem;
            border-radius: 12px;
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .mining-graph-header {
            margin-bottom: 1rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .mining-graph-title {
            font-size: 0.9375rem;
            font-weight: 600;
        }

        .mining-graph-legend {
            gap: 1rem;
        }

        .legend-text {
            font-size: 0.75rem;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
        }

        .mining-graph-wrapper {
            height: 200px;
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
            transform: scale(0.98);
            transition: all 0.1s ease;
        }

        /* Mobile touch feedback animations */
        .mining-action-card {
            -webkit-tap-highlight-color: rgba(255, 178, 30, 0.2);
        }

        .mining-action-card:active {
            transform: scale(0.96);
            transition: all 0.1s ease;
        }

        .mining-stat-card:active {
            transform: scale(0.98);
        }

        .mining-overview-card:active {
            transform: scale(0.98);
        }

        /* Smooth mobile scroll */
        .mining-dashboard {
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
        }

        /* Mobile loading shimmer effect */
        @keyframes shimmerMobile {
            0% {
                background-position: -200px 0;
            }
            100% {
                background-position: calc(200px + 100%) 0;
            }
        }

        /* Mobile card entrance animations */
        .mining-hero-card {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .mining-stat-card {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .mining-action-card {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .mining-overview-card {
            animation: fadeInUp 0.5s ease-out forwards;
        }

    }
</style>
@endpush

@section('content')
<div class="mining-dashboard">
    <!-- Combined Balance and Actions Card -->
    <div class="mining-wallet-card">
        <!-- Balance Section -->
        <div class="wallet-balance-section">
            <div class="balance-header-row">
                <div class="balance-label">
                    <i class="fas fa-eye"></i>
                    <span>Total Balance</span>
                </div>
                <div class="balance-actions">
                    <i class="fas fa-arrow-up balance-trend-up"></i>
                    <i class="fas fa-eye-slash balance-toggle-icon" id="balanceToggle"></i>
                </div>
            </div>
            <div class="balance-amount-display">
                <span class="balance-amount-large" id="totalBalance">$0.00</span>
            </div>
            <div class="deposit-wallet-info">
                <span class="deposit-wallet-label">Deposit Wallet:</span>
                <span class="deposit-wallet-amount">$0.30</span>
                <i class="fas fa-arrow-down deposit-trend-down"></i>
            </div>
        </div>

        <!-- Action Buttons Section -->
        <div class="wallet-actions-grid">
            <div class="wallet-action-btn" data-action="deposit">
                <div class="wallet-action-icon">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <span class="wallet-action-label">Deposit</span>
            </div>
            <div class="wallet-action-btn" data-action="withdraw">
                <div class="wallet-action-icon">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <span class="wallet-action-label">Withdraw</span>
            </div>
            <div class="wallet-action-btn" data-action="wallet">
                <div class="wallet-action-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <span class="wallet-action-label">Wallet</span>
            </div>
            <div class="wallet-action-btn" data-action="refer">
                <div class="wallet-action-icon">
                    <i class="fas fa-share-square"></i>
                </div>
                <span class="wallet-action-label">Refer</span>
            </div>
        </div>
    </div>

    <!-- Mining Overview - Combined Stats and Graph -->
    <div class="mining-overview-section">
        <div class="mining-stats-row">
            <div class="mining-stat-item">
                <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                <div class="stat-content">
                    <div class="stat-label">Total Earnings</div>
                    <div class="stat-value">$0.00</div>
                </div>
            </div>
            <div class="mining-stat-item">
                <div class="stat-icon"><i class="fas fa-coins"></i></div>
                <div class="stat-content">
                    <div class="stat-label">Total Invested</div>
                    <div class="stat-value">$0.00</div>
                </div>
            </div>
        </div>

        <!-- Mining Graph - App-like Design -->
        <div class="mining-graph-section">
            <div class="graph-header-app">
                <h3 class="graph-title-app">Performance</h3>
                <div class="graph-legend-app">
                    <div class="legend-item-app">
                        <span class="legend-dot-app earnings"></span>
                        <span>Earnings</span>
                    </div>
                    <div class="legend-item-app">
                        <span class="legend-dot-app investment"></span>
                        <span>Investment</span>
                    </div>
                </div>
            </div>
            <div class="graph-wrapper-app">
                <canvas id="miningOverviewChart"></canvas>
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
    const balanceToggle = document.getElementById('balanceToggle');
    const totalBalanceEl = document.getElementById('totalBalance');
    const depositWalletAmount = document.querySelector('.deposit-wallet-amount');

    if (balanceToggle) {
        balanceToggle.addEventListener('click', function() {
            balanceVisible = !balanceVisible;
            if (balanceVisible) {
                // Show balance
                totalBalanceEl.textContent = '$0.00';
                if (depositWalletAmount) {
                    depositWalletAmount.textContent = '$0.30';
                }
                balanceToggle.classList.remove('fa-eye-slash');
                balanceToggle.classList.add('fa-eye');
            } else {
                // Hide balance
                totalBalanceEl.textContent = '••••••';
                if (depositWalletAmount) {
                    depositWalletAmount.textContent = '••••';
                }
                balanceToggle.classList.remove('fa-eye');
                balanceToggle.classList.add('fa-eye-slash');
            }
        });
    }

    // Toggle activity section
    function toggleActivitySection() {
        const section = document.getElementById('activitySection');
        if (section) {
            section.classList.toggle('collapsed');
        }
    }

    // Smooth scroll behavior
    document.documentElement.style.scrollBehavior = 'smooth';

    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all animated elements
    document.addEventListener('DOMContentLoaded', function() {
        // Observe sections for scroll animations
        const sections = document.querySelectorAll('.mining-hero-section, .mining-actions-grid, .mining-overview-section, .mining-activity-section');
        sections.forEach(section => {
            observer.observe(section);
        });

        // Animate stat values on load
        const statValues = document.querySelectorAll('.mining-stat-value, .mining-card-value');
        statValues.forEach((value, index) => {
            setTimeout(() => {
                value.classList.add('animate');
            }, 300 + (index * 100));
        });

        // Add smooth entrance animation to cards
        const cards = document.querySelectorAll('.mining-stat-card, .mining-action-card, .mining-overview-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100 + (index * 50));
        });
    });

    // Initialize Mining Overview Chart
    document.addEventListener('DOMContentLoaded', function() {
        // Wait a bit to ensure DOM is fully ready and Chart.js is loaded
        setTimeout(function() {
            const chartCtx = document.getElementById('miningOverviewChart');
            if (!chartCtx) {
                console.error('Canvas element not found');
                return;
            }

            if (typeof Chart === 'undefined') {
                console.error('Chart.js library not loaded');
                return;
            }

            // Use orange colors only
            const earningsColor = '#FFB21E';
            const investmentColor = '#FF8A1D';
            const isMobile = window.innerWidth <= 390;

            new Chart(chartCtx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [
                        {
                            label: 'Earnings',
                            data: [120, 190, 300, 250, 400, 350, 450],
                            borderColor: earningsColor,
                            backgroundColor: 'rgba(255, 178, 30, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: earningsColor,
                            pointBorderColor: '#000',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Investment',
                            data: [80, 150, 200, 180, 280, 240, 320],
                            borderColor: investmentColor,
                            backgroundColor: 'rgba(255, 138, 29, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: investmentColor,
                            pointBorderColor: '#000',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.9)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                                borderColor: '#FFB21E',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': $' + context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                color: 'rgba(255, 255, 255, 0.6)',
                                font: {
                                    size: isMobile ? 10 : 12
                                }
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                color: 'rgba(255, 255, 255, 0.6)',
                                font: {
                                    size: isMobile ? 10 : 12
                                },
                                callback: function(value) {
                                    return '$' + value;
                                }
                            },
                            beginAtZero: true
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });

            console.log('Chart initialized successfully');
        }, 200);

        // Fallback: Try again after a longer delay if chart didn't initialize
        setTimeout(function() {
            const chartCtx = document.getElementById('miningOverviewChart');
            if (chartCtx && !chartCtx.chart && typeof Chart !== 'undefined') {
                console.log('Retrying chart initialization...');
                const earningsColor = '#FFB21E';
                const investmentColor = '#FF8A1D';
                const isMobile = window.innerWidth <= 390;

                new Chart(chartCtx, {
                    type: 'line',
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [
                            {
                                label: 'Earnings',
                                data: [120, 190, 300, 250, 400, 350, 450],
                                borderColor: earningsColor,
                                backgroundColor: 'rgba(255, 178, 30, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 4,
                                pointBackgroundColor: earningsColor,
                                pointBorderColor: '#000',
                                pointBorderWidth: 2,
                                pointHoverRadius: 6
                            },
                            {
                                label: 'Investment',
                                data: [80, 150, 200, 180, 280, 240, 320],
                                borderColor: investmentColor,
                                backgroundColor: 'rgba(255, 138, 29, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 4,
                                pointBackgroundColor: investmentColor,
                                pointBorderColor: '#000',
                                pointBorderWidth: 2,
                                pointHoverRadius: 6
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.9)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderColor: '#FFB21E',
                                borderWidth: 1,
                                padding: 12,
                                displayColors: true,
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': $' + context.parsed.y.toFixed(2);
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.05)',
                                    drawBorder: false
                                },
                                ticks: {
                                    color: 'rgba(255, 255, 255, 0.6)',
                                    font: {
                                        size: isMobile ? 10 : 12
                                    }
                                }
                            },
                            y: {
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.05)',
                                    drawBorder: false
                                },
                                ticks: {
                                    color: 'rgba(255, 255, 255, 0.6)',
                                    font: {
                                        size: isMobile ? 10 : 12
                                    },
                                    callback: function(value) {
                                        return '$' + value;
                                    }
                                },
                                beginAtZero: true
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        }
                    }
                });
                console.log('Chart initialized on retry');
            }
        }, 1000);
    });
</script>
@endpush



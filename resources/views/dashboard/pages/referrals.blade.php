@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Referrals')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/referrals.css') }}">
<style>
    .referrals-new-page {
        padding: 2rem;
        max-width: 1600px;
        margin: 0 auto;
        width: 100%;
        box-sizing: border-box;
        overflow-x: hidden;
        -webkit-overflow-scrolling: touch;
    }

    /* App-like touch interactions */
    @media (max-width: 768px) {
        * {
            -webkit-tap-highlight-color: rgba(255, 178, 30, 0.1);
        }

        button, a, [role="button"] {
            -webkit-tap-highlight-color: transparent;
        }

        button:active, a:active, [role="button"]:active {
            opacity: 0.8;
        }
    }

    /* Hero Section */
    .referrals-hero-new {
        text-align: center;
        padding: 3rem 2rem;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 24px;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .referrals-hero-new::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #FFB21E 0%, #FF8A1D 50%, #FFB21E 100%);
        background-size: 200% 100%;
        animation: shimmer 3s linear infinite;
    }

    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .referrals-hero-new::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 178, 30, 0.08) 0%, transparent 70%);
        pointer-events: none;
    }

    .referrals-hero-content-new {
        position: relative;
        z-index: 1;
    }

    .referrals-hero-title-new {
        font-size: 3rem;
        font-weight: 700;
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 1rem 0;
        letter-spacing: -2px;
    }

    .referrals-hero-subtitle-new {
        font-size: 1.125rem;
        color: var(--text-secondary);
        margin: 0;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Stats Section */
    .referrals-stats-section-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .referrals-stat-card-new {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
        transition: var(--transition);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .referrals-stat-card-new::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .referrals-stat-card-new:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 32px rgba(255, 178, 30, 0.2);
        border-color: var(--primary-color);
    }

    .referrals-stat-card-new:hover::before {
        transform: scaleX(1);
    }

    .referrals-stat-icon-new {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #000;
        flex-shrink: 0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .referrals-stat-icon-earning-new {
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
    }

    .referrals-stat-icon-users-new {
        background: linear-gradient(135deg, #00AAFF 0%, #0088CC 100%);
    }

    /* Mobile Stats Cards - Improved Design */
    @media (max-width: 768px) {
        .referrals-hero-new {
            display: none !important;
        }

        .referrals-stats-section-new {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            width: 100%;
        }

        .referrals-stat-card-new {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.2s ease;
            box-shadow: none;
            min-height: auto;
            height: auto;
        }

        .referrals-stat-card-new::before {
            display: none;
        }

        .referrals-stat-card-new:hover {
            transform: none;
            box-shadow: none;
            border-color: rgba(255, 255, 255, 0.05);
        }

        .referrals-stat-card-new:active {
            background: rgba(255, 255, 255, 0.04);
            transform: scale(0.98);
        }

        .referrals-stat-icon-new {
            width: 36px;
            height: 36px;
            min-width: 36px;
            border-radius: 8px;
            background: rgba(255, 178, 30, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: none;
        }

        .referrals-stat-icon-new i {
            font-size: 1rem;
            color: var(--primary-color);
        }

        .referrals-stat-icon-earning-new {
            background: rgba(255, 178, 30, 0.1);
        }

        .referrals-stat-icon-earning-new i {
            color: var(--primary-color);
        }

        .referrals-stat-icon-users-new {
            background: rgba(255, 178, 30, 0.1);
        }

        .referrals-stat-icon-users-new i {
            color: var(--primary-color);
        }

        .referrals-stat-content-new {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .referrals-stat-label-new {
            font-size: 0.6875rem;
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 400;
            line-height: 1.3;
            white-space: normal;
            overflow: visible;
            text-overflow: clip;
        }

        .referrals-stat-value-new {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            font-variant-numeric: tabular-nums;
            line-height: 1.2;
            white-space: normal;
            overflow: visible;
            text-overflow: clip;
        }
    }


    @media (max-width: 400px) {
        .referrals-stats-section-new {
            gap: 0.5rem;
        }

        .referrals-stat-card-new {
            padding: 0.875rem;
            min-height: 85px;
            gap: 0.75rem;
        }

        .referrals-stat-icon-new {
            width: 48px;
            height: 48px;
            min-width: 48px;
            font-size: 1.25rem;
        }

        .referrals-stat-content-new {
            /* gap: -0.375rem !important; */
        }

        .referrals-stat-label-new {
            font-size: 0.625rem;
        }

        .referrals-stat-value-new {
            font-size: 1.25rem;
        }
    }

    .referrals-stat-content-new {
        flex: 1;
    }

    .referrals-stat-label-new {
        font-size:0.6875rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
        font-weight: 600;
    }

    .referrals-stat-value-new {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        font-variant-numeric: tabular-nums;
    }

    /* Earning Wallet Card */
    .referrals-wallet-section-new {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 24px;
        padding: 3rem;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .referrals-wallet-section-new::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #FFB21E 0%, #FF8A1D 100%);
    }

    .referrals-wallet-header-new {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .referrals-wallet-icon-new {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.2) 0%, rgba(255, 138, 29, 0.1) 100%);
        border: 2px solid rgba(255, 178, 30, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--primary-color);
        box-shadow: 0 0 30px rgba(255, 178, 30, 0.3);
    }

    .referrals-wallet-title-section-new {
        flex: 1;
    }

    .referrals-wallet-title-new {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .referrals-wallet-subtitle-new {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .referrals-wallet-body-new {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .referrals-balance-display-new {
        text-align: center;
        padding: 2.5rem;
        background: rgba(255, 178, 30, 0.05);
        border: 1px solid rgba(255, 178, 30, 0.2);
        border-radius: 20px;
    }

    .referrals-balance-amount-new {
        display: flex;
        align-items: baseline;
        justify-content: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .referrals-balance-currency-new {
        font-size: 2rem;
        font-weight: 600;
        color: var(--text-primary);
        opacity: 0.7;
    }

    .referrals-balance-value-new {
        font-size: 4rem;
        font-weight: 700;
        color: var(--primary-color);
        font-variant-numeric: tabular-nums;
        text-shadow: 0 0 30px rgba(255, 178, 30, 0.6);
    }

    .referrals-minimum-info-new {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        align-items: center;
    }

    .referrals-minimum-requirement-new {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        background: rgba(255, 178, 30, 0.1);
        border: 1px solid rgba(255, 178, 30, 0.3);
        border-radius: 12px;
        font-size: 0.9375rem;
        color: var(--text-primary);
    }

    .referrals-minimum-requirement-new i {
        color: var(--primary-color);
    }

    .referrals-minimum-needed-new {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .referrals-claim-btn-new {
        padding: 1.25rem 2rem;
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
        border: none;
        border-radius: 16px;
        color: #000;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        box-shadow: 0 4px 20px rgba(255, 178, 30, 0.4);
    }

    .referrals-claim-btn-new:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow: 0 6px 30px rgba(255, 178, 30, 0.6);
    }

    .referrals-claim-btn-new:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .referrals-claim-note-new {
        text-align: center;
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
    }

    /* Mobile Wallet Design - Match Image */
    @media (max-width: 768px) {
        .referrals-wallet-section-new {
            padding: 1.5rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
        }

        .referrals-wallet-section-new::before {
            display: none;
        }

        .referrals-wallet-header-new {
            margin-bottom: 1.5rem;
        }

        .referrals-wallet-icon-new {
            display: none;
        }

        .referrals-wallet-title-new {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 0.5rem 0;
        }

        .referrals-wallet-subtitle-new {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
        }

        .referrals-wallet-body-new {
            gap: 1.25rem;
        }

        .referrals-balance-display-new {
            background: transparent;
            border: none;
            padding: 0;
            text-align: left;
        }

        .referrals-balance-amount-wrapper-new {
            display: flex;
            align-items: baseline;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .referrals-balance-amount-new {
            display: flex;
            align-items: baseline;
            gap: 0;
            margin-bottom: 0;
        }

        .referrals-balance-value-new {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            text-shadow: none;
        }

        .referrals-balance-currency-new {
            display: none;
        }

        .referrals-minimum-badge-new {
            padding: 0.375rem 0.75rem;
            background: #8B4513;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
        }

        .referrals-minimum-info-new {
            display: none;
        }

        .referrals-minimum-requirement-new {
            display: none;
        }

        .referrals-minimum-needed-new {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
            text-align: left;
        }

        .referrals-claim-note-new {
            font-size: 0.875rem;
            color: #F97316;
            text-align: left;
            margin: 0;
        }

        .referrals-claim-btn-new {
            padding: 0.875rem 1.5rem;
            background: #F97316;
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 600;
            font-size: 0.9375rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: none;
            width: 100%;
        }

        .referrals-claim-btn-new:hover:not(:disabled) {
            background: #EA580C;
            transform: none;
            box-shadow: none;
        }

        .referrals-claim-btn-new:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .referrals-claim-btn-new i {
            font-size: 0.875rem;
        }
    }

    /* Referral Tools Section */
    .referrals-tools-section-new {
        margin-bottom: 3rem;
    }

    .referrals-tools-header-new {
        margin-bottom: 2rem;
    }

    .referrals-tools-title-new {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .referrals-tools-subtitle-new {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .referrals-tools-grid-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .referrals-tool-card-new {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
        transition: var(--transition);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
    }

    .referrals-tool-card-new::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .referrals-tool-card-new:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 32px rgba(255, 178, 30, 0.2);
        border-color: var(--primary-color);
    }

    .referrals-tool-card-new:hover::before {
        transform: scaleX(1);
    }

    .referrals-tool-header-new {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .referrals-tool-icon-new {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.2) 0%, rgba(255, 138, 29, 0.1) 100%);
        border: 2px solid rgba(255, 178, 30, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: var(--primary-color);
        box-shadow: 0 0 20px rgba(255, 178, 30, 0.3);
    }

    .referrals-tool-title-new {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .referrals-tool-body-new {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .referrals-tool-input-wrapper-new {
        display: flex;
        gap: 1rem;
    }

    .referrals-tool-input-new {
        flex: 1;
        padding: 1rem 1.25rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        color: var(--text-primary);
        font-size: 0.9375rem;
        font-family: inherit;
        transition: var(--transition);
    }

    .referrals-tool-input-new:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(255, 178, 30, 0.1), 0 4px 16px rgba(255, 178, 30, 0.1);
        background: rgba(255, 255, 255, 0.05);
    }

    .referrals-tool-copy-btn-new {
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
        color: #000;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 16px rgba(255, 178, 30, 0.3);
        flex-shrink: 0;
    }

    .referrals-tool-copy-btn-new:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(255, 178, 30, 0.4);
    }

    .referrals-tool-hint-new {
        text-align: center;
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
    }

    /* Mobile View - Simple Design */
    @media (max-width: 768px) {
        .referrals-tools-header-new {
            display: none;
        }

        .referrals-tools-grid-new {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .referrals-tool-card-new {
            background: transparent;
            border: none;
            border-radius: 0;
            padding: 0;
            box-shadow: none;
        }

        .referrals-tool-card-new::before {
            display: none;
        }

        .referrals-tool-card-new:hover {
            transform: none;
            box-shadow: none;
            border-color: transparent;
        }

        .referrals-tool-header-new {
            margin-bottom: 0.75rem;
        }

        .referrals-tool-icon-new {
            display: none;
        }

        .referrals-tool-title-new {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .referrals-tool-body-new {
            gap: 0.75rem;
        }

        .referrals-tool-hint-new {
            display: none;
        }

        .referrals-tool-input-wrapper-new {
            gap: 0.75rem;
        }

        .referrals-tool-input-new {
            flex: 1;
            padding: 0.875rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
        }

        .referrals-tool-input-new:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: none;
            background: rgba(255, 255, 255, 0.05);
        }

        .referrals-tool-copy-btn-new {
            padding: 0;
            width: 44px;
            height: 44px;
            min-width: 44px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            border-radius: 8px;
            box-shadow: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .referrals-tool-copy-btn-new:hover {
            transform: none;
            box-shadow: none;
            background: rgba(255, 255, 255, 0.08);
        }

        .referrals-tool-copy-btn-new:active {
            transform: scale(0.95);
            background: rgba(255, 255, 255, 0.1);
        }

        .referrals-tool-copy-btn-new i {
            font-size: 1rem;
        }
    }

    /* Referrer Info Section */
    .referrals-referrer-section-new {
        margin-bottom: 3rem;
    }

    .referrals-referrer-card-new {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
    }

    .referrals-referrer-card-new::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
    }

    .referrals-referrer-header-new {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .referrals-referrer-icon-new {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: linear-gradient(135deg, #00AAFF 0%, #0088CC 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: #000;
        box-shadow: 0 4px 20px rgba(0, 170, 255, 0.3);
    }

    .referrals-referrer-title-new {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .referrals-referrer-info-grid-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .referrals-referrer-info-item-new {
        padding: 1.5rem;
        background: rgba(255, 178, 30, 0.05);
        border: 1px solid rgba(255, 178, 30, 0.2);
        border-radius: 16px;
        transition: var(--transition);
    }

    .referrals-referrer-info-item-new:hover {
        background: rgba(255, 178, 30, 0.1);
        border-color: rgba(255, 178, 30, 0.4);
        transform: translateY(-2px);
    }

    .referrals-referrer-info-label-new {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.8125rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
        font-weight: 600;
    }

    .referrals-referrer-info-label-new i {
        color: var(--primary-color);
        font-size: 1rem;
    }

    .referrals-referrer-info-value-new {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Investment Commission Structure Section */
    .referrals-investment-commission-section-new {
        margin-bottom: 3rem;
    }

    /* Investment Commission Cards - Same design for desktop and mobile */
    .referrals-investment-commission-mobile {
        display: none;
    }

    .referrals-investment-commission-desktop {
        display: block;
    }

    .referrals-investment-mobile-grid-new {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        padding: 1rem;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
    }

    /* Mobile Header Inside Card for Investment Commission */
    .referrals-investment-mobile-header-new {
        grid-column: 1 / -1;
        text-align: center;
        padding-bottom: 1rem;
        margin-bottom: 0.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .referrals-investment-mobile-header-title-new {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.375rem 0;
    }

    .referrals-investment-mobile-header-subtitle-new {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .referrals-investment-mobile-card-new {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 1.25rem 1rem;
        text-align: center;
        transition: var(--transition);
    }

    .referrals-investment-mobile-card-new:hover {
        border-color: rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.04);
        transform: translateY(-2px);
    }

    .referrals-investment-mobile-title-new {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .referrals-investment-mobile-label-new {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin: 0 0 0.75rem 0;
    }

    .referrals-investment-mobile-percentage-new {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0 0 0.75rem 0;
    }

    /* Desktop: 5 columns grid */
    @media (min-width: 769px) {
        .referrals-investment-mobile-grid-new {
            grid-template-columns: repeat(5, 1fr);
            gap: 1rem;
            padding: 1.5rem;
        }

        .referrals-investment-mobile-card-new {
            padding: 1.5rem 1.25rem;
        }

        .referrals-investment-mobile-title-new {
            font-size: 1rem;
        }

        .referrals-investment-mobile-label-new {
            font-size: 0.8125rem;
        }

        .referrals-investment-mobile-percentage-new {
            font-size: 2rem;
        }
    }

    .referrals-investment-mobile-footer-new {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .referrals-investment-mobile-footer-new span:last-child {
        color: #EF4444;
        font-weight: 600;
    }

    /* Investment Commission Level Colors */
    .referrals-investment-level-1 .referrals-investment-mobile-percentage-new {
        color: #EF4444;
    }

    .referrals-investment-level-2 .referrals-investment-mobile-percentage-new {
        color: #EF4444;
    }

    .referrals-investment-level-3 .referrals-investment-mobile-percentage-new {
        color: #EF4444;
    }

    .referrals-investment-level-4 .referrals-investment-mobile-percentage-new {
        color: #EF4444;
    }

    .referrals-investment-level-5 .referrals-investment-mobile-percentage-new {
        color: #EF4444;
    }

    /* Commission Structure Section */
    .referrals-commission-section-new {
        margin-bottom: 3rem;
    }

    .referrals-commission-header-new {
        margin-bottom: 2rem;
    }

    .referrals-commission-title-new {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .referrals-commission-subtitle-new {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0;
    }

    /* Mobile Header Inside Card */
    .referrals-commission-mobile-header-new {
        grid-column: 1 / -1;
        text-align: center;
        padding-bottom: 1rem;
        margin-bottom: 0.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .referrals-commission-mobile-title-new {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.375rem 0;
    }

    .referrals-commission-mobile-subtitle-new {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .referrals-commission-grid-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
    }

    .referrals-commission-card-new {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
        transition: var(--transition);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
        text-align: center;
    }

    .referrals-commission-card-new::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .referrals-commission-card-new:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 32px rgba(255, 178, 30, 0.2);
        border-color: var(--primary-color);
    }

    .referrals-commission-card-new:hover::before {
        transform: scaleX(1);
    }

    .referrals-commission-level-badge-new {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(220, 38, 38, 0.15) 100%);
        border: 1px solid rgba(239, 68, 68, 0.4);
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        color: #EF4444;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .referrals-commission-level-icon-new {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.2) 0%, rgba(255, 138, 29, 0.1) 100%);
        border: 3px solid rgba(255, 178, 30, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: var(--primary-color);
        margin: 0 auto 1.5rem;
        box-shadow: 0 0 30px rgba(255, 178, 30, 0.3);
    }

    .referrals-commission-level-name-new {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 1.5rem 0;
    }

    .referrals-commission-rate-new {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .referrals-commission-rate-value-new {
        font-size: 3rem;
        font-weight: 700;
        color: var(--primary-color);
        text-shadow: 0 0 20px rgba(255, 178, 30, 0.5);
    }

    .referrals-commission-rate-label-new {
        font-size: 0.875rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .referrals-commission-earning-label-new {
        position: absolute;
        bottom: 1rem;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.75rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    /* Level-specific colors */
    .referrals-commission-level-1 .referrals-commission-level-icon-new {
        background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        border-color: rgba(239, 68, 68, 0.5);
        color: #000;
        box-shadow: 0 0 30px rgba(239, 68, 68, 0.4);
    }

    .referrals-commission-level-1 .referrals-commission-rate-value-new {
        color: #EF4444;
        text-shadow: 0 0 20px rgba(239, 68, 68, 0.5);
    }

    .referrals-commission-level-2 .referrals-commission-level-icon-new {
        background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        border-color: rgba(239, 68, 68, 0.5);
        color: #000;
        box-shadow: 0 0 30px rgba(239, 68, 68, 0.4);
    }

    .referrals-commission-level-2 .referrals-commission-rate-value-new {
        color: #EF4444;
        text-shadow: 0 0 20px rgba(239, 68, 68, 0.5);
    }

    .referrals-commission-level-3 .referrals-commission-level-icon-new {
        background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        border-color: rgba(239, 68, 68, 0.5);
        color: #000;
        box-shadow: 0 0 30px rgba(239, 68, 68, 0.4);
    }

    .referrals-commission-level-3 .referrals-commission-rate-value-new {
        color: #EF4444;
        text-shadow: 0 0 20px rgba(239, 68, 68, 0.5);
    }

    .referrals-commission-level-4 .referrals-commission-level-icon-new {
        background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        border-color: rgba(239, 68, 68, 0.5);
        color: #000;
        box-shadow: 0 0 30px rgba(239, 68, 68, 0.4);
    }

    .referrals-commission-level-4 .referrals-commission-rate-value-new {
        color: #EF4444;
        text-shadow: 0 0 20px rgba(239, 68, 68, 0.5);
    }

    .referrals-commission-level-5 .referrals-commission-level-icon-new {
        background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        border-color: rgba(239, 68, 68, 0.5);
        color: #000;
        box-shadow: 0 0 30px rgba(239, 68, 68, 0.4);
    }

    .referrals-commission-level-5 .referrals-commission-rate-value-new {
        color: #EF4444;
        text-shadow: 0 0 20px rgba(239, 68, 68, 0.5);
    }

    /* Mobile Icon-Based Commission Structure */
    .referrals-commission-mobile-new {
        display: none;
    }

    /* Show desktop view by default */
    .referrals-commission-desktop {
        display: grid;
    }

    .referrals-commission-mobile-container-new {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        padding: 1rem;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
    }

    .referrals-commission-mobile-item-new {
        width: 100%;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 1.25rem 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        position: relative;
        transition: all 0.2s ease;
        cursor: pointer;
        box-sizing: border-box;
    }

    .referrals-commission-mobile-item-new:active {
        transform: scale(0.96);
        background: rgba(255, 255, 255, 0.05);
    }

    .referrals-commission-mobile-item-new:hover {
        border-color: rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.04);
    }

    .referrals-commission-mobile-icon-wrapper-new {
        position: relative;
        width: 100%;
        display: flex;
        justify-content: center;
    }

    .referrals-commission-mobile-icon-new {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: #000;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
        transition: var(--transition);
    }

    .referrals-commission-mobile-badge-new {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--card-bg);
        border: 2px solid var(--card-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.625rem;
        font-weight: 700;
        color: #EF4444;
        text-transform: uppercase;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    .referrals-commission-mobile-content-new {
        width: 100%;
        text-align: center;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .referrals-commission-mobile-name-new {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        line-height: 1.2;
    }

    .referrals-commission-mobile-rate-new {
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1;
    }

    /* Mobile Level Colors */
    .referrals-commission-mobile-level-1 .referrals-commission-mobile-icon-new {
        background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
    }

    .referrals-commission-mobile-level-1 .referrals-commission-mobile-rate-new {
        color: #EF4444;
        text-shadow: 0 0 15px rgba(239, 68, 68, 0.5);
    }

    .referrals-commission-mobile-level-2 .referrals-commission-mobile-icon-new {
        background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
    }

    .referrals-commission-mobile-level-2 .referrals-commission-mobile-rate-new {
        color: #EF4444;
        text-shadow: 0 0 15px rgba(239, 68, 68, 0.5);
    }

    .referrals-commission-mobile-level-3 .referrals-commission-mobile-icon-new {
        background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
    }

    .referrals-commission-mobile-level-3 .referrals-commission-mobile-rate-new {
        color: #EF4444;
        text-shadow: 0 0 15px rgba(239, 68, 68, 0.5);
    }

    .referrals-commission-mobile-level-4 .referrals-commission-mobile-icon-new {
        background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
    }

    .referrals-commission-mobile-level-4 .referrals-commission-mobile-rate-new {
        color: #EF4444;
        text-shadow: 0 0 15px rgba(239, 68, 68, 0.5);
    }

    .referrals-commission-mobile-level-5 .referrals-commission-mobile-icon-new {
        background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
    }

    .referrals-commission-mobile-level-5 .referrals-commission-mobile-rate-new {
        color: #EF4444;
        text-shadow: 0 0 15px rgba(239, 68, 68, 0.5);
    }

    /* Network Section */
    .referrals-network-section-new {
        margin-bottom: 3rem;
    }

    .referrals-network-header-new {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .referrals-network-title-new {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .referrals-network-subtitle-new {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .referrals-network-filter-new {
        padding: 0.875rem 1.25rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        color: var(--text-primary);
        font-size: 0.9375rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .referrals-network-filter-new:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(255, 178, 30, 0.1);
    }

    .referrals-network-card-new {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
    }

    .referrals-network-table-wrapper-new {
        overflow-x: visible;
        border-radius: 12px;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .referrals-network-table-new {
        width: 100%;
        border-collapse: collapse;
    }

    .referrals-network-table-new thead {
        background: linear-gradient(180deg, rgba(255, 178, 30, 0.1) 0%, rgba(255, 138, 29, 0.05) 100%);
        border-bottom: 2px solid rgba(255, 178, 30, 0.2);
    }

    .referrals-network-table-new th {
        padding: 1.25rem 1.5rem;
        text-align: left;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .referrals-network-table-new td {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .referrals-network-table-new tbody tr {
        transition: var(--transition);
    }

    .referrals-network-table-new tbody tr:hover {
        background: rgba(255, 178, 30, 0.05);
    }

    .referrals-network-empty-new {
        text-align: center;
        padding: 4rem 2rem;
    }

    .referrals-network-empty-content-new {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .referrals-network-empty-icon-mobile {
        display: none;
    }

    .referrals-network-empty-icon-desktop {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .referrals-network-empty-icon-desktop i {
        font-size: 4rem;
        color: var(--text-secondary);
        opacity: 0.3;
    }

    .referrals-network-empty-content-new > i {
        display: none;
    }

    .referrals-network-empty-content-new p {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .referrals-network-empty-content-new span {
        font-size: 0.9375rem;
        color: var(--text-secondary);
    }

    .referrals-network-invite-btn-new {
        padding: 0.875rem 1.75rem;
        background: linear-gradient(135deg, #9333EA 0%, #EC4899 100%);
        border: none;
        border-radius: 12px;
        color: #fff;
        font-weight: 700;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: 0 4px 16px rgba(147, 51, 234, 0.3);
        margin-top: 0.5rem;
    }

    .referrals-network-invite-btn-new:hover {
        background: linear-gradient(135deg, #7C3AED 0%, #DB2777 100%);
        box-shadow: 0 6px 20px rgba(147, 51, 234, 0.4);
        transform: translateY(-2px);
    }

    .referrals-network-invite-btn-new:active {
        transform: translateY(0);
    }

    .referrals-network-invite-btn-new i {
        font-size: 0.875rem;
    }

    /* Rules Section */
    .referrals-rules-section-new {
        margin-top: 2rem;
        margin-bottom: 2rem;
    }

    .referrals-rules-header-new {
        margin-bottom: 2rem;
    }

    .referrals-rules-title-new {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .referrals-rules-subtitle-new {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .referrals-rules-card-new {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
        position: relative;
        overflow: hidden;
    }

    .referrals-rules-card-new::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
    }

    .referrals-rules-list-new {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .referrals-rule-item-new {
        display: flex;
        gap: 1.5rem;
        align-items: flex-start;
        padding: 1.5rem;
        background: rgba(255, 178, 30, 0.05);
        border: 1px solid rgba(255, 178, 30, 0.2);
        border-radius: 16px;
        transition: var(--transition);
    }

    .referrals-rule-item-new:hover {
        background: rgba(255, 178, 30, 0.1);
        border-color: rgba(255, 178, 30, 0.4);
        transform: translateX(4px);
    }

    .referrals-rule-icon-new {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.2) 0%, rgba(255, 138, 29, 0.1) 100%);
        border: 1px solid rgba(255, 178, 30, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: var(--primary-color);
        flex-shrink: 0;
    }

    .referrals-rule-content-new {
        flex: 1;
    }

    .referrals-rule-title-new {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .referrals-rule-text-new {
        font-size: 0.9375rem;
        color: var(--text-secondary);
        margin: 0;
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .referrals-new-page {
            padding: 0.75rem;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }

        /* Hero and stats styles moved to dedicated mobile section above */

        /* Wallet styles moved to dedicated mobile section above */

        .referrals-tools-section-new {
            margin-bottom: 1.5rem;
        }

ls-referrer-section-new {
            margin-bottom: 1.5rem;
        }

        .referrals-referrer-card-new {
            padding: 1.5rem;
            border-radius: 16px;
        }

        .referrals-referrer-card-new::before {
            display: none;
        }

        .referrals-referrer-header-new {
            margin-bottom: 1.25rem;
            display: block;
        }

        .referrals-referrer-icon-new {
            display: none;
        }

        .referrals-referrer-title-new {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 1.25rem 0;
        }

        .referrals-referrer-title-new::after {
            content: ':';
        }

        .referrals-referrer-info-grid-new {
            display: flex;
            flex-direction: column;
            gap: 0.875rem;
        }

        .referrals-referrer-info-item-new {
            padding: 0;
            background: transparent;
            border: none;
            border-radius: 0;
            display: flex;
            align-items: baseline;
            gap: 0;
            flex-wrap: wrap;
        }

        .referrals-referrer-info-item-new:hover {
            background: transparent;
            border: none;
            transform: none;
        }

        .referrals-referrer-info-label-new {
            display: inline-flex;
            align-items: center;
            gap: 0;
        }

        .referrals-referrer-info-label-new i {
            display: none;
        }

        .referrals-referrer-info-label-new span {
            font-size: 0.875rem;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.7);
            text-transform: none;
            letter-spacing: 0;
        }

        .referrals-referrer-info-label-new span::after {
            content: ':';
            margin-left: 0.25rem;
            margin-right: 0.5rem;
        }

        .referrals-referrer-info-value-new {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .referrals-investment-commission-section-new {
            margin-bottom: 1.5rem;
        }

        .referrals-investment-commission-desktop {
            display: none;
        }

        .referrals-investment-commission-mobile {
            display: block;
        }

        /* Hide external header on mobile for Investment Commission */
        .referrals-investment-commission-section-new .referrals-commission-header-new {
            display: none !important;
        }

        .referrals-commission-section-new {
            margin-bottom: 1.5rem;
        }

        /* Hide desktop commission grid on mobile */
        .referrals-commission-desktop {
            display: none !important;
        }

        /* Hide external header on mobile */
        .referrals-commission-header-new {
            display: none !important;
        }

        /* Show mobile commission structure on mobile */
        .referrals-commission-mobile-new {
            display: block !important;
        }

        .referrals-commission-mobile-header-new {
            padding-bottom: 0.875rem;
            margin-bottom: 0.75rem;
        }

        .referrals-commission-mobile-title-new {
            font-size: 0.9375rem;
        }

        .referrals-commission-mobile-subtitle-new {
            font-size: 0.6875rem;
        }

        .referrals-network-section-new {
            margin-bottom: 2rem;
        }

        .referrals-network-header-new {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-top: 0.5rem;
            gap: 1rem;
        }

        .referrals-network-header-new > div {
            flex: 1;
        }

        .referrals-network-subtitle-new {
            display: none;
        }

        .referrals-network-title-new {
            font-size: 1.25rem;
            margin: 0;
        }

        .referrals-network-filter-new {
            padding: 0.625rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 0.875rem;
            min-width: 80px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23fff' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            padding-right: 2.5rem;
        }

        .referrals-network-card-new {
            padding: 1.5rem;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        /* Move header inside card on mobile using CSS */
        .referrals-network-section-new {
            position: relative;
        }

        .referrals-network-header-new {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            right: 1.5rem;
            z-index: 10;
            margin: 0;
            padding: 0;
        }

        .referrals-network-table-wrapper-new {
            padding-top: 4.5rem;
        }

        .referrals-network-empty-icon-desktop {
            display: none !important;
        }

        .referrals-network-empty-new {
            padding: 3rem 1.5rem;
        }

        .referrals-network-empty-content-new {
            gap: 1.5rem;
        }

        .referrals-network-empty-icon-desktop {
            display: none !important;
        }

        .referrals-network-empty-icon-mobile {
            display: flex !important;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            flex-shrink: 0;
        }

        .referrals-network-empty-icon-mobile i {
            font-size: 3.5rem;
            color: rgba(255, 255, 255, 0.7);
            opacity: 1;
            display: block !important;
            visibility: visible !important;
        }

        .referrals-network-empty-content-new > i {
            display: none !important;
        }

        .referrals-network-empty-message-new {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .referrals-network-empty-hint-new {
            display: none;
        }

        .referrals-network-invite-btn-new {
            padding: 0.875rem 1.75rem;
            background: linear-gradient(135deg, #9333EA 0%, #EC4899 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 700;
            font-size: 0.9375rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 16px rgba(147, 51, 234, 0.3);
            margin-top: 0.5rem;
        }

        .referrals-network-invite-btn-new:hover {
            background: linear-gradient(135deg, #7C3AED 0%, #DB2777 100%);
            box-shadow: 0 6px 20px rgba(147, 51, 234, 0.4);
            transform: translateY(-2px);
        }

        .referrals-network-invite-btn-new:active {
            transform: translateY(0);
        }

        .referrals-network-invite-btn-new i {
            font-size: 0.875rem;
        }

        .referrals-network-table-wrapper-new {
            overflow-x: visible;
            width: 100%;
            max-width: 100%;
        }

        .referrals-network-table-new {
            width: 100%;
            min-width: 0;
            display: block;
            border-collapse: separate;
        }

        .referrals-network-table-new thead {
            display: none;
        }

        .referrals-network-table-new tbody {
            display: block;
            width: 100%;
        }

        .referrals-network-table-new tbody tr {
            display: block;
            width: 100%;
            margin-bottom: 1rem;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 1rem;
            box-sizing: border-box;
            position: relative;
            min-height: 100px;
        }

        .referrals-network-table-new tbody tr:last-child {
            margin-bottom: 0;
        }

        .referrals-network-table-new td {
            display: block;
            width: 100%;
            padding: 0;
            border: none;
            box-sizing: border-box;
        }

        .referrals-network-table-new td:first-child {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0;
        }

        .referrals-network-table-new td:first-child .referrals-network-mobile-value {
            display: flex !important;
            flex-direction: column;
            align-items: flex-end;
            text-align: right;
        }

        .referrals-network-table-new td:nth-child(2) {
            display: none;
        }

        .referrals-network-table-new td:last-child {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            width: auto;
            text-align: right;
            padding: 0;
        }

        .referrals-network-table-new td:last-child .desktop-earning {
            display: none;
        }

        .referrals-network-table-new td:first-child .referrals-network-mobile-user-info {
            flex: 1;
        }

        .referrals-network-table-new td:first-child .referrals-network-mobile-value {
            flex-shrink: 0;
            margin-left: 1rem;
        }
    }

    /* Desktop view - hide mobile elements */
    @media (min-width: 769px) {
        .referrals-network-table-new td:first-child {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .referrals-network-table-new td:first-child .referrals-network-mobile-value {
            display: none !important;
        }

        .referrals-network-table-new td:nth-child(2) {
            display: table-cell;
        }

        .referrals-network-table-new td:last-child {
            position: static;
            width: auto;
        }

        .referrals-network-table-new td:last-child .desktop-earning {
            display: block;
        }

        .referrals-network-table-new td:last-child .referrals-network-mobile-date {
            display: none;
        }

        /* Mobile card layout - user info on left */
        .referrals-network-mobile-user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .referrals-network-mobile-user-name {
            font-weight: 700;
            color: var(--text-primary);
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .referrals-network-mobile-user-level {
            font-size: 0.8125rem;
            color: var(--text-secondary);
        }

        /* Mobile card layout - value on right */
        .referrals-network-mobile-value {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            text-align: right;
        }

        .referrals-network-mobile-earning {
            font-weight: 600;
            color: #10B981;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .referrals-network-mobile-invested {
            font-size: 0.8125rem;
            color: var(--text-secondary);
        }

        .referrals-network-mobile-date {
            font-size: 0.8125rem;
            color: var(--text-secondary);
        }

        .referrals-rules-section-new {
            margin-bottom: 2rem;
        }

        .referrals-rules-card-new {
            padding: 1.5rem;
        }

        .referrals-rules-header-new {
            margin-bottom: 1.5rem;
            padding-top: 0.5rem;
        }

        .referrals-rules-subtitle-new {
            display: none;
        }

        .referrals-rules-title-new {
            font-size: 1.25rem;
            margin: 0 0 1.25rem 0;
        }

        .referrals-rules-list-new {
            gap: 1rem;
        }

        .referrals-rule-item-new {
            padding: 0;
            background: transparent;
            border: none;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .referrals-rule-item-new:hover {
            background: transparent;
            transform: none;
        }

        .referrals-rule-icon-new {
            display: none;
        }

        .referrals-rule-content-new {
            flex: 1;
        }

        .referrals-rule-title-new {
            display: none;
        }

        .referrals-rule-text-new {
            font-size: 0.875rem;
            color: var(--text-primary);
            margin: 0;
            line-height: 1.5;
            padding-left: 1.25rem;
            position: relative;
        }

        .referrals-rule-text-new::before {
            content: '•';
            position: absolute;
            left: 0;
            color: var(--text-primary);
            font-size: 1.25rem;
            line-height: 1.2;
        }

        .referrals-rules-warning-new {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            margin-top: 1.25rem;
            padding-top: 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .referrals-rules-warning-new i {
            color: #F97316;
            font-size: 1rem;
            margin-top: 0.125rem;
            flex-shrink: 0;
        }

        .referrals-rules-warning-new span {
            font-size: 0.875rem;
            color: #F97316;
            line-height: 1.5;
        }
    }

    @media (max-width: 480px) {
        .referrals-new-page {
            padding: 0.5rem;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }

        .referrals-hero-new {
            padding: 1.5rem 1rem;
            margin-bottom: 1.25rem;
            border-radius: 18px;
        }

        .referrals-hero-title-new {
            font-size: 1.5rem;
        }

        .referrals-hero-subtitle-new {
            font-size: 0.8125rem;
        }

        .referrals-stats-section-new {
            gap: 0.875rem;
            margin-bottom: 1.25rem;
        }

        .referrals-stat-card-new {
            padding: 1.25rem;
            border-radius: 14px;
        }

        .referrals-stat-icon-new {
            width: 56px;
            height: 56px;
            font-size: 1.375rem;
        }

        .referrals-stat-value-new {
            font-size: 1.75rem;
        }

        .referrals-wallet-section-new {
            padding: 1.25rem 1rem;
            margin-bottom: 1.25rem;
            border-radius: 18px;
        }

        .referrals-wallet-header-new {
            margin-bottom: 1.25rem;
        }

        .referrals-wallet-icon-new {
            width: 56px;
            height: 56px;
            font-size: 1.375rem;
        }

        .referrals-wallet-title-new {
            font-size: 1.375rem;
        }

        .referrals-balance-value-new {
            font-size: 2.25rem;
        }

        .referrals-balance-currency-new {
            font-size: 1.375rem;
        }

        .referrals-claim-btn-new {
            padding: 1rem 1.5rem;
            font-size: 0.9375rem;
            border-radius: 14px;
            -webkit-tap-highlight-color: transparent;
        }

        .referrals-claim-btn-new:active {
            transform: scale(0.98);
        }

        .referrals-tools-grid-new {
            gap: 1.25rem;
        }

        .referrals-referrer-card-new {
            padding: 1.25rem;
            border-radius: 14px;
        }

        .referrals-commission-mobile-header-new {
            padding-bottom: 0.75rem;
            margin-bottom: 0.625rem;
        }

        .referrals-commission-mobile-title-new {
            font-size: 0.875rem;
        }

        .referrals-commission-mobile-subtitle-new {
            font-size: 0.625rem;
        }

        .referrals-commission-mobile-container-new {
            padding: 0.875rem;
            border-radius: 18px;
            gap: 0.625rem;
        }

        .referrals-commission-mobile-item-new {
            padding: 1rem 0.875rem;
            border-radius: 14px;
        }

        .referrals-commission-mobile-icon-new {
            width: 56px;
            height: 56px;
            font-size: 1.5rem;
            border-radius: 14px;
        }

        .referrals-commission-mobile-badge-new {
            width: 28px;
            height: 28px;
            font-size: 0.5625rem;
        }

        .referrals-commission-mobile-name-new {
            font-size: 0.6875rem;
        }

        .referrals-commission-mobile-rate-new {
            font-size: 1.5rem;
        }

        .referrals-network-card-new {
            padding: 0;
        }

        .referrals-rules-card-new {
            padding: 1.5rem;
        }

        .referrals-rule-item-new {
            padding: 1rem;
        }
    }

    @media (max-width: 400px) {
        .referrals-new-page {
            padding: 0.5rem;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }

        .referrals-hero-new {
            padding: 1.25rem 0.875rem;
            margin-bottom: 1rem;
            border-radius: 16px;
        }

        .referrals-hero-title-new {
            font-size: 1.375rem;
            letter-spacing: -0.5px;
        }

        .referrals-hero-subtitle-new {
            font-size: 0.75rem;
        }

        .referrals-stats-section-new {
            gap: 0.75rem;
        }

        .referrals-stat-card-new {
            padding: 1.125rem;
            border-radius: 12px;
        }

        .referrals-stat-icon-new {
            width: 36px;
            height: 36px;
            font-size: 1.25rem;
        }

        .referrals-stat-value-new {
            font-size: 1.125rem;
            margin-top: -10px;
        }

        .referrals-wallet-section-new {
            padding: 1.125rem 0.875rem;
            margin-bottom: 1rem;
            border-radius: 16px;
        }

        .referrals-wallet-icon-new {
            width: 52px;
            height: 52px;
            font-size: 1.25rem;
        }

        .referrals-wallet-title-new {
            font-size: 1.25rem;
        }

        .referrals-balance-value-new {
            font-size: 2rem;
        }

        .referrals-balance-currency-new {
            font-size: 1.25rem;
        }

        .referrals-claim-btn-new {
            padding: 0.9375rem 1.25rem;
            font-size: 0.875rem;
            border-radius: 12px;
        }

        .referrals-tool-card-new {
            padding: 1.125rem;
            border-radius: 12px;
        }

        .referrals-tool-icon-new {
            width: 52px;
            height: 52px;
            font-size: 1.375rem;
        }

        .referrals-tool-title-new {
            font-size: 1.125rem;
        }

        .referrals-referrer-card-new {
            padding: 1.125rem;
            border-radius: 12px;
        }

        .referrals-referrer-icon-new {
            width: 52px;
            height: 52px;
            font-size: 1.375rem;
        }

        .referrals-referrer-title-new {
            font-size: 1.125rem;
        }

        .referrals-commission-mobile-header-new {
            padding-bottom: 0.625rem;
            margin-bottom: 0.5rem;
        }

        .referrals-commission-mobile-title-new {
            font-size: 0.8125rem;
        }

        .referrals-commission-mobile-subtitle-new {
            font-size: 0.5625rem;
        }

        .referrals-commission-mobile-container-new {
            padding: 0.75rem;
            border-radius: 16px;
            gap: 0.5rem;
        }

        .referrals-commission-mobile-item-new {
            padding: 0.875rem 0.75rem;
            border-radius: 12px;
        }

        .referrals-commission-mobile-icon-new {
            width: 52px;
            height: 52px;
            font-size: 1.375rem;
            border-radius: 12px;
        }

        .referrals-commission-mobile-badge-new {
            width: 26px;
            height: 26px;
            font-size: 0.5rem;
        }

        .referrals-commission-mobile-name-new {
            font-size: 0.625rem;
        }

        .referrals-commission-mobile-rate-new {
            font-size: 1.375rem;
        }

        .referrals-rules-card-new {
            padding: 1.25rem;
        }

        .referrals-rule-item-new {
            padding: 0.875rem;
            gap: 1rem;
        }

        .referrals-rule-icon-new {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
    }

    /* Referral Detail Modal Styles */
    .referral-detail-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .referral-detail-modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(2px);
    }

    .referral-detail-modal-content {
        position: relative;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        padding: 1.5rem;
        width: 100%;
        max-width: 450px;
        max-height: 85vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        z-index: 10001;
    }

    .referral-detail-modal-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 1.5rem 0;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .referral-detail-modal-close {
        width: 24px;
        height: 24px;
        border-radius: 0;
        background: transparent;
        border: none;
        color: var(--text-primary);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        font-size: 1.125rem;
        font-weight: 400;
        padding: 0;
        margin: 0;
    }

    .referral-detail-modal-close:hover {
        background: transparent;
        transform: none;
        opacity: 0.7;
    }

    .referral-detail-header {
        text-align: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .referral-detail-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.2) 0%, rgba(255, 138, 29, 0.1) 100%);
        border: 2px solid rgba(255, 178, 30, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    .referral-detail-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.375rem 0;
    }

    .referral-detail-date {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .referral-detail-body {
        display: flex;
        flex-direction: column;
        gap: 0.875rem;
    }

    .referral-detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.375rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .referral-detail-item:last-child {
        border-bottom: none;
    }

    .referral-detail-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .referral-detail-value {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .referral-row-clickable:hover {
        background: rgba(255, 178, 30, 0.05) !important;
    }

    @media (max-width: 768px) {
        .referral-detail-modal {
            padding: 0.75rem;
        }

        .referral-detail-modal-content {
            padding: 1.25rem;
            border-radius: 16px;
            max-height: 85vh;
            max-width: 100%;
            margin-bottom: 70px;
        }

        .referral-detail-modal-title {
            font-size: 1rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.875rem;
        }

        .referral-detail-modal-close {
            width: 24px;
            height: 24px;
            font-size: 1rem;
        }

        .referral-detail-header {
            margin-bottom: 1.25rem;
            padding-bottom: 1.25rem;
        }

        .referral-detail-avatar {
            width: 70px;
            height: 70px;
            font-size: 1.75rem;
            margin-bottom: 0.875rem;
        }

        .referral-detail-name {
            font-size: 1.125rem;
        }

        .referral-detail-date {
            font-size: 0.8125rem;
        }

        .referral-detail-body {
            gap: 0.75rem;
        }

        .referral-detail-item {
            padding: 0.625rem 0;
        }

        .referral-detail-label {
            font-size: 0.6875rem;
        }

        .referral-detail-value {
            font-size: 0.875rem;
        }
    }
</style>
@endpush

@section('content')
<div class="referrals-new-page">
    <!-- Hero Section -->
    <div class="referrals-hero-new">
        <div class="referrals-hero-content-new">
            <h1 class="referrals-hero-title-new">Mining Referral Program</h1>
            <p class="referrals-hero-subtitle-new">Invite friends and earn commissions on their mining activities</p>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="referrals-stats-section-new">
        <div class="referrals-stat-card-new">
            <div class="referrals-stat-icon-new referrals-stat-icon-earning-new">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="referrals-stat-content-new">
                <div class="referrals-stat-label-new">Pending Referral Earnings</div>
                <div class="referrals-stat-value-new">${{ number_format($pendingReferralEarnings ?? 0, 2) }}</div>
            </div>
        </div>

        <div class="referrals-stat-card-new">
            <div class="referrals-stat-icon-new referrals-stat-icon-users-new">
                <i class="fas fa-users"></i>
            </div>
            <div class="referrals-stat-content-new">
                <div class="referrals-stat-label-new">Total Referrals</div>
                <div class="referrals-stat-value-new">{{ $totalReferrals }}</div>
            </div>
        </div>
    </div>

    <!-- Earning Wallet Card -->
    <div class="referrals-wallet-section-new">
        <div class="referrals-wallet-header-new">
            <div class="referrals-wallet-title-section-new">
                <h3 class="referrals-wallet-title-new">Referral Earnings Wallet</h3>
                <p class="referrals-wallet-subtitle-new">Your pending earnings ready to claim</p>
            </div>
        </div>
        <div class="referrals-wallet-body-new">
            <div class="referrals-balance-display-new">
                <div class="referrals-balance-amount-wrapper-new">
                    <span class="referrals-balance-value-new" id="pendingEarningsAmount">${{ number_format($pendingReferralEarnings ?? 0, 2) }}</span>
                    <div class="referrals-minimum-badge-new">Minimum $1</div>
                </div>
                @if(($pendingReferralEarnings ?? 0) < 1)
                <div class="referrals-minimum-needed-new">${{ number_format(1 - ($pendingReferralEarnings ?? 0), 2) }} more needed to claim</div>
                @else
                <div class="referrals-minimum-needed-new" style="color: #10B981;">Ready to claim!</div>
                @endif
            </div>
            @if(isset($pendingCommissionsByLevel) && count($pendingCommissionsByLevel) > 0)
            <div class="referrals-commission-breakdown-new" style="margin: 1.5rem 0; padding: 1rem; background: rgba(255, 255, 255, 0.02); border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.05);">
                <p style="margin: 0 0 0.75rem 0; font-size: 0.875rem; color: var(--text-secondary);">Breakdown by Level:</p>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 0.75rem;">
                    @for($i = 1; $i <= 5; $i++)
                    <div style="text-align: center;">
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Level {{ $i }}</div>
                        <div style="font-weight: 600; color: #FFB21E;">${{ number_format($pendingCommissionsByLevel[$i] ?? 0, 2) }}</div>
                    </div>
                    @endfor
                </div>
            </div>
            @endif
            <p class="referrals-claim-note-new">You can claim referral earnings when balance reaches $1 or more</p>
            <button class="referrals-claim-btn-new" id="claimEarningsBtn" {{ ($pendingReferralEarnings ?? 0) < 1 ? 'disabled' : '' }}>
                <i class="fas fa-gift"></i>
                <span>Claim Earnings</span>
            </button>
        </div>
    </div>

    <!-- Referral Tools Section -->
    <div class="referrals-tools-section-new">
        <div class="referrals-tools-header-new">
            <h2 class="referrals-tools-title-new">Your Referral Tools</h2>
            <p class="referrals-tools-subtitle-new">Share your unique link or code to start earning</p>
        </div>
        <div class="referrals-tools-grid-new">
            <!-- Referral Link Card -->
            <div class="referrals-tool-card-new">
                <div class="referrals-tool-header-new">
                    <div class="referrals-tool-icon-new">
                        <i class="fas fa-link"></i>
                    </div>
                    <h3 class="referrals-tool-title-new">Referral Link</h3>
                </div>
                <div class="referrals-tool-body-new">
                    <div class="referrals-tool-input-wrapper-new">
                        <input type="text" class="referrals-tool-input-new" id="referralLink" value="{{ url('/register?ref=' . $user->refer_code) }}" readonly>
                        <button class="referrals-tool-copy-btn-new" data-copy="referralLink" title="Copy Link">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <p class="referrals-tool-hint-new">Share this link with your friends</p>
                </div>
            </div>

            <!-- Referral Code Card -->
            <div class="referrals-tool-card-new">
                <div class="referrals-tool-header-new">
                    <div class="referrals-tool-icon-new">
                        <i class="fas fa-barcode"></i>
                    </div>
                    <h3 class="referrals-tool-title-new">Referral Code</h3>
                </div>
                <div class="referrals-tool-body-new">
                    <div class="referrals-tool-input-wrapper-new">
                        <input type="text" class="referrals-tool-input-new" id="referralCode" value="{{ $user->refer_code }}" readonly>
                        <button class="referrals-tool-copy-btn-new" data-copy="referralCode" title="Copy Code">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <p class="referrals-tool-hint-new">Use this code during registration</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Referrer Info Section -->
    <div class="referrals-referrer-section-new">
        <div class="referrals-referrer-card-new">
            <div class="referrals-referrer-header-new">
                <div class="referrals-referrer-icon-new">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3 class="referrals-referrer-title-new">Referred By</h3>
            </div>
            <div class="referrals-referrer-info-grid-new">
                @if($currentUserReferrer)
                <div class="referrals-referrer-info-item-new">
                    <div class="referrals-referrer-info-label-new">
                        <i class="fas fa-user"></i>
                        <span>Name</span>
                    </div>
                    <div class="referrals-referrer-info-value-new">{{ $currentUserReferrer->name ?? 'N/A' }}</div>
                </div>
                <div class="referrals-referrer-info-item-new">
                    <div class="referrals-referrer-info-label-new">
                        <i class="fas fa-envelope"></i>
                        <span>Email</span>
                    </div>
                    <div class="referrals-referrer-info-value-new">{{ $currentUserReferrer->email ?? 'N/A' }}</div>
                </div>
                <div class="referrals-referrer-info-item-new">
                    <div class="referrals-referrer-info-label-new">
                        <i class="fas fa-phone"></i>
                        <span>Phone</span>
                    </div>
                    <div class="referrals-referrer-info-value-new">{{ $currentUserReferrer->phone ?? 'N/A' }}</div>
                </div>
                @else
                <div class="referrals-referrer-info-item-new" style="grid-column: 1 / -1;">
                    <div class="referrals-referrer-info-label-new">
                        <i class="fas fa-info-circle"></i>
                        <span>Status</span>
                    </div>
                    <div class="referrals-referrer-info-value-new">Not referred by anyone</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Investment Commission Structure Section -->
    <div class="referrals-investment-commission-section-new">
        <div class="referrals-commission-header-new">
            <h2 class="referrals-commission-title-new">Investment Commission Structure</h2>
            <p class="referrals-commission-subtitle-new">Earn commissions on investments across 5 levels</p>
        </div>

        <!-- Desktop Grid View (Same card design as mobile) -->
        <div class="referrals-investment-commission-desktop">
            <div class="referrals-investment-mobile-grid-new">
                @foreach($investmentCommissions as $commission)
                <div class="referrals-investment-mobile-card-new referrals-investment-level-{{ $commission->level }}">
                    <h3 class="referrals-investment-mobile-title-new">{{ $commission->level_name }}</h3>
                    <p class="referrals-investment-mobile-label-new">Commission Rate</p>
                    <div class="referrals-investment-mobile-percentage-new">{{ number_format($commission->commission_rate, 0) }}%</div>
                    <div class="referrals-investment-mobile-footer-new">
                        <span>Earning</span>
                        <span>Level {{ $commission->level }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Mobile Card-Based View -->
        <div class="referrals-investment-commission-mobile">
            <div class="referrals-investment-mobile-grid-new">
                <!-- Mobile Header Inside Card -->
                <div class="referrals-investment-mobile-header-new">
                    <h2 class="referrals-investment-mobile-header-title-new">Investment Commission Structure</h2>
                    <p class="referrals-investment-mobile-header-subtitle-new">Earn commissions on investments across 5 levels</p>
                </div>

                @foreach($investmentCommissions as $commission)
                <div class="referrals-investment-mobile-card-new referrals-investment-level-{{ $commission->level }}">
                    <h3 class="referrals-investment-mobile-title-new">{{ $commission->level_name }}</h3>
                    <p class="referrals-investment-mobile-label-new">Commission Rate</p>
                    <div class="referrals-investment-mobile-percentage-new">{{ number_format($commission->commission_rate, 0) }}%</div>
                    <div class="referrals-investment-mobile-footer-new">
                        <span>Earning</span>
                        <span>Level {{ $commission->level }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Commission Structure Section -->
    <div class="referrals-commission-section-new">
        <div class="referrals-commission-header-new">
            <h2 class="referrals-commission-title-new">Earning Commission Structure</h2>
            <p class="referrals-commission-subtitle-new">Earn up to 18% commission across 5 levels</p>
        </div>

        <!-- Desktop Grid View -->
        <div class="referrals-commission-grid-new referrals-commission-desktop">
            @foreach($earningCommissions as $commission)
            <div class="referrals-commission-card-new referrals-commission-level-{{ $commission->level }}">
                <div class="referrals-commission-level-badge-new">Level {{ $commission->level }}</div>
                <div class="referrals-commission-level-icon-new">
                    <i class="fas fa-star"></i>
                </div>
                <div class="referrals-commission-level-name-new">{{ $commission->level_name }}</div>
                <div class="referrals-commission-rate-new">
                    <span class="referrals-commission-rate-value-new">{{ number_format($commission->commission_rate, 0) }}%</span>
                    <span class="referrals-commission-rate-label-new">Commission Rate</span>
                </div>
                <div class="referrals-commission-earning-label-new">Earning</div>
            </div>
            @endforeach
        </div>

        <!-- Mobile Icon-Based View -->
        <div class="referrals-commission-mobile-new referrals-commission-mobile">
            <div class="referrals-commission-mobile-container-new">
                <!-- Mobile Header Inside Card -->
                <div class="referrals-commission-mobile-header-new">
                    <h2 class="referrals-commission-mobile-title-new">Earning Commission Structure</h2>
                    <p class="referrals-commission-mobile-subtitle-new">Earn up to 18% commission across 5 levels</p>
                </div>

                @foreach($earningCommissions as $commission)
                <div class="referrals-commission-mobile-item-new referrals-commission-mobile-level-{{ $commission->level }}">
                    <div class="referrals-commission-mobile-icon-wrapper-new">
                        <div class="referrals-commission-mobile-icon-new">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="referrals-commission-mobile-badge-new">L{{ $commission->level }}</div>
                    </div>
                    <div class="referrals-commission-mobile-content-new">
                        <div class="referrals-commission-mobile-name-new">{{ $commission->level_name }}</div>
                        <div class="referrals-commission-mobile-rate-new">{{ number_format($commission->commission_rate, 0) }}%</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Network Table Section -->
    <div class="referrals-network-section-new">
        <div class="referrals-network-header-new">
            <div>
                <h2 class="referrals-network-title-new">Your Network</h2>
                <p class="referrals-network-subtitle-new">View all your referrals and their investments</p>
            </div>
            <select class="referrals-network-filter-new" id="levelFilter" onchange="filterByLevel(this.value)">
                <option value="all" {{ $currentLevel == 'all' ? 'selected' : '' }}>All Levels</option>
                <option value="1" {{ $currentLevel == '1' ? 'selected' : '' }}>Level 1</option>
                <option value="2" {{ $currentLevel == '2' ? 'selected' : '' }}>Level 2</option>
                <option value="3" {{ $currentLevel == '3' ? 'selected' : '' }}>Level 3</option>
                <option value="4" {{ $currentLevel == '4' ? 'selected' : '' }}>Level 4</option>
                <option value="5" {{ $currentLevel == '5' ? 'selected' : '' }}>Level 5</option>
            </select>
        </div>
        <div class="referrals-network-card-new">
            <div class="referrals-network-table-wrapper-new">
                <table class="referrals-network-table-new">
                    <thead>
                        <tr>
                            <th>User Info</th>
                            <th>Invested Amount</th>
                            <th>Referral Earning</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($referrals->count() > 0)
                            @foreach($referrals as $referral)
                            <tr class="referral-row-clickable" data-referral='@json($referral)' style="cursor: pointer;">
                                <td>
                                    <!-- Desktop: Full layout, Mobile: User info on left, Value on right -->
                                    <div class="referrals-network-mobile-user-info" style="display: flex; align-items: center; gap: 1rem;">
                                        <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, rgba(255, 178, 30, 0.2) 0%, rgba(255, 138, 29, 0.1) 100%); display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-weight: 600; flex-shrink: 0;">
                                            {{ strtoupper(substr($referral['name'], 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="referrals-network-mobile-user-name" style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.25rem;">{{ $referral['name'] }}</div>
                                            <div class="referrals-network-mobile-user-level" style="font-size: 0.75rem; color: var(--text-secondary);">{{ $referral['level_name'] }}</div>
                                        </div>
                                    </div>
                                    <!-- Mobile: Value on right -->
                                    <div class="referrals-network-mobile-value">
                                        <div class="referrals-network-mobile-earning">${{ number_format($referral['referral_earning'], 2) }}</div>
                                        <div class="referrals-network-mobile-invested">Invested Amount: ${{ number_format($referral['invested_amount'], 2) }}</div>
                                    </div>
                                </td>
                                <td>
                                    <!-- Desktop: Invested Amount column -->
                                    <div style="font-weight: 600; color: var(--text-primary);">${{ number_format($referral['invested_amount'], 2) }}</div>
                                </td>
                                <td>
                                    <!-- Desktop: Referral Earning column -->
                                    <div class="desktop-earning" style="font-weight: 600; color: #10B981;">${{ number_format($referral['referral_earning'], 2) }}</div>
                                    <!-- Mobile: Date in bottom right (join date only) -->
                                    <div class="referrals-network-mobile-date">{{ $referral['created_at']->format('M d, Y') }}</div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="referrals-network-empty-new">
                                    <div class="referrals-network-empty-content-new">
                                        <div class="referrals-network-empty-icon-new referrals-network-empty-icon-desktop">
                                            <i class="fas fa-inbox"></i>
                                        </div>
                                        <div class="referrals-network-empty-icon-new referrals-network-empty-icon-mobile">
                                            <i class="fas fa-inbox"></i>
                                        </div>
                                        <p class="referrals-network-empty-message-new">No referrals yet</p>
                                        <span class="referrals-network-empty-hint-new">Start sharing your referral link to build your network</span>
                                        <button class="referrals-network-invite-btn-new">
                                            <i class="fas fa-share-alt"></i>
                                            <span>Invite Now</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                
                @if($referrals->hasPages())
                <div style="padding: 1.5rem; display: flex; justify-content: center; align-items: center; gap: 0.5rem; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                    @if($referrals->onFirstPage())
                        <button disabled style="padding: 0.5rem 1rem; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: var(--text-secondary); cursor: not-allowed;">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    @else
                        <a href="{{ $referrals->previousPageUrl() }}" style="padding: 0.5rem 1rem; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: var(--text-primary); text-decoration: none;">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif
                    
                    @foreach($referrals->getUrlRange(1, $referrals->lastPage()) as $page => $url)
                        @if($page == $referrals->currentPage())
                            <span style="padding: 0.5rem 1rem; background: rgba(255, 178, 30, 0.2); border: 1px solid rgba(255, 178, 30, 0.4); border-radius: 8px; color: var(--primary-color); font-weight: 600;">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" style="padding: 0.5rem 1rem; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: var(--text-primary); text-decoration: none;">{{ $page }}</a>
                        @endif
                    @endforeach
                    
                    @if($referrals->hasMorePages())
                        <a href="{{ $referrals->nextPageUrl() }}" style="padding: 0.5rem 1rem; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: var(--text-primary); text-decoration: none;">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <button disabled style="padding: 0.5rem 1rem; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: var(--text-secondary); cursor: not-allowed;">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Referral User Detail Modal -->
    <div id="referralDetailModal" class="referral-detail-modal" style="display: none;">
        <div class="referral-detail-modal-overlay" onclick="closeReferralModal()"></div>
        <div class="referral-detail-modal-content">
            <div class="referral-detail-modal-title">
                <span>Referral User Detail</span>
                <button class="referral-detail-modal-close" onclick="closeReferralModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="referral-detail-header">
                <div class="referral-detail-avatar">
                    <span id="modalUserInitial">U</span>
                </div>
                <h3 class="referral-detail-name" id="modalUserName">User Name</h3>
                <p class="referral-detail-date" id="modalUserDate">Jan 1, 2026</p>
            </div>
            <div class="referral-detail-body">
                <div class="referral-detail-item">
                    <span class="referral-detail-label">Phone Number:</span>
                    <span class="referral-detail-value" id="modalUserPhone">N/A</span>
                </div>
                <div class="referral-detail-item">
                    <span class="referral-detail-label">Level:</span>
                    <span class="referral-detail-value" id="modalUserLevel">N/A</span>
                </div>
                <div class="referral-detail-item">
                    <span class="referral-detail-label">Earning:</span>
                    <span class="referral-detail-value" id="modalUserEarning">$0</span>
                </div>
                <div class="referral-detail-item">
                    <span class="referral-detail-label">Invested Amount:</span>
                    <span class="referral-detail-value" id="modalUserInvested">$0</span>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="{{ asset('assets/dashboard/js/referrals.js') }}"></script>
<script>
    // Copy functionality
    document.querySelectorAll('[data-copy]').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-copy');
            const input = document.getElementById(targetId);
            if (input) {
                input.select();
                document.execCommand('copy');

                // Visual feedback
                const originalHTML = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check"></i>';
                this.style.background = 'linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%)';

                setTimeout(() => {
                    this.innerHTML = originalHTML;
                    this.style.background = '';
                }, 2000);
            }
        });
    });

    // Level filter functionality
    function filterByLevel(level) {
        const url = new URL(window.location.href);
        if (level === 'all') {
            url.searchParams.delete('level');
        } else {
            url.searchParams.set('level', level);
        }
        url.searchParams.delete('page'); // Reset to first page when filtering
        window.location.href = url.toString();
    }

    // Referral detail modal functionality
    document.querySelectorAll('.referral-row-clickable').forEach(row => {
        row.addEventListener('click', function(e) {
            // Don't trigger if clicking on links or buttons
            if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON') {
                return;
            }
            
            const referralData = JSON.parse(this.getAttribute('data-referral'));
            openReferralModal(referralData);
        });
    });

    function openReferralModal(referral) {
        // Set user initial
        const initial = referral.name ? referral.name.charAt(0).toUpperCase() : 'U';
        document.getElementById('modalUserInitial').textContent = initial;
        
        // Set user name
        document.getElementById('modalUserName').textContent = referral.name || 'N/A';
        
        // Set date
        let formattedDate = 'N/A';
        if (referral.created_at) {
            // Handle both string and object dates
            const dateStr = typeof referral.created_at === 'string' ? referral.created_at : referral.created_at.date || referral.created_at;
            const date = new Date(dateStr);
            if (!isNaN(date.getTime())) {
                formattedDate = date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
            }
        }
        document.getElementById('modalUserDate').textContent = formattedDate;
        
        // Set phone (format with + if not already present)
        let phone = referral.phone || 'N/A';
        if (phone !== 'N/A' && phone && !phone.startsWith('+')) {
            // Add + if phone doesn't start with it (assuming it's an international number)
            phone = '+' + phone;
        }
        document.getElementById('modalUserPhone').textContent = phone;
        
        // Set level
        document.getElementById('modalUserLevel').textContent = referral.level_name || 'N/A';
        
        // Set earning
        document.getElementById('modalUserEarning').textContent = '$' + parseFloat(referral.referral_earning || 0).toFixed(2);
        
        // Set invested amount
        document.getElementById('modalUserInvested').textContent = '$' + parseFloat(referral.invested_amount || 0).toFixed(2);
        
        // Show modal
        document.getElementById('referralDetailModal').style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    function closeReferralModal() {
        document.getElementById('referralDetailModal').style.display = 'none';
        document.body.style.overflow = ''; // Restore scrolling
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeReferralModal();
        }
    });

    // Claim Earnings functionality
    document.addEventListener('DOMContentLoaded', function() {
        const claimBtn = document.getElementById('claimEarningsBtn');
        if (claimBtn) {
            claimBtn.addEventListener('click', function() {
                if (this.disabled) {
                    return;
                }

                // Disable button and show loading state
                this.disabled = true;
                const originalHTML = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Claiming...</span>';

                // Make AJAX request
                fetch('{{ route("referrals.claim") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        alert('Success! ' + data.message + ' Amount claimed: $' + data.claimed_amount);
                        
                        // Reload page to update balances
                        window.location.reload();
                    } else {
                        // Show error message
                        alert('Error: ' + (data.message || 'Failed to claim earnings. Please try again.'));
                        
                        // Re-enable button
                        this.disabled = false;
                        this.innerHTML = originalHTML;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                    
                    // Re-enable button
                    this.disabled = false;
                    this.innerHTML = originalHTML;
                });
            });
        }
    });
</script>
@endpush
@endsection



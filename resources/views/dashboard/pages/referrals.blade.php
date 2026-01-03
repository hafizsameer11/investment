@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Referrals')

@push('styles')
<link rel="stylesheet" href="{{ asset('dashboard/css/referrals.css') }}">
<style>
    .referrals-new-page {
        padding: 2rem;
        max-width: 1600px;
        margin: 0 auto;
        width: 100%;
        box-sizing: border-box;
        overflow-x: hidden;
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

    .referrals-stat-content-new {
        flex: 1;
    }

    .referrals-stat-label-new {
        font-size: 0.875rem;
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
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.2) 0%, rgba(255, 138, 29, 0.15) 100%);
        border: 1px solid rgba(255, 178, 30, 0.4);
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--primary-color);
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

    .referrals-network-empty-content-new i {
        font-size: 4rem;
        color: var(--text-secondary);
        opacity: 0.3;
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

    /* Rules Section */
    .referrals-rules-section-new {
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
            padding: 1rem;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .referrals-hero-new {
            padding: 2rem 1.5rem;
            margin-bottom: 2rem;
            border-radius: 16px;
        }

        .referrals-hero-title-new {
            font-size: 2rem;
        }

        .referrals-hero-subtitle-new {
            font-size: 1rem;
        }

        .referrals-stats-section-new {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .referrals-stat-card-new {
            padding: 2rem;
            flex-direction: column;
            text-align: center;
        }

        .referrals-wallet-section-new {
            padding: 2rem 1.5rem;
            margin-bottom: 2rem;
            border-radius: 16px;
        }

        .referrals-wallet-header-new {
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 1.5rem;
        }

        .referrals-balance-value-new {
            font-size: 3rem;
        }

        .referrals-tools-section-new {
            margin-bottom: 2rem;
        }

        .referrals-tools-grid-new {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .referrals-tool-input-wrapper-new {
            flex-direction: column;
        }

        .referrals-tool-copy-btn-new {
            width: 100%;
        }

        .referrals-referrer-section-new {
            margin-bottom: 2rem;
        }

        .referrals-referrer-header-new {
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 1rem;
        }

        .referrals-referrer-info-grid-new {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .referrals-commission-section-new {
            margin-bottom: 2rem;
        }

        .referrals-commission-grid-new {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .referrals-network-section-new {
            margin-bottom: 2rem;
        }

        .referrals-network-header-new {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .referrals-network-filter-new {
            width: 100%;
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
            border-radius: 10px;
            padding: 1rem;
            box-sizing: border-box;
        }

        .referrals-network-table-new tbody tr:last-child {
            margin-bottom: 0;
        }

        .referrals-network-table-new td {
            display: block;
            width: 100%;
            padding: 0.75rem 0;
            border: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            text-align: left;
            font-size: 0.875rem;
            box-sizing: border-box;
        }

        .referrals-network-table-new td:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .referrals-network-table-new td:first-child {
            padding-top: 0;
        }

        .referrals-rules-section-new {
            margin-bottom: 2rem;
        }
    }

    @media (max-width: 480px) {
        .referrals-new-page {
            padding: 0;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .referrals-hero-new {
            padding: 1.5rem 1rem;
            margin-bottom: 1.5rem;
            border-radius: 12px;
        }

        .referrals-hero-title-new {
            font-size: 1.75rem;
        }

        .referrals-hero-subtitle-new {
            font-size: 0.875rem;
        }

        .referrals-stats-section-new {
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .referrals-stat-card-new {
            padding: 1.5rem;
        }

        .referrals-stat-icon-new {
            width: 64px;
            height: 64px;
            font-size: 1.5rem;
        }

        .referrals-stat-value-new {
            font-size: 2rem;
        }

        .referrals-wallet-section-new {
            padding: 1.5rem 1rem;
            margin-bottom: 1.5rem;
            border-radius: 12px;
        }

        .referrals-wallet-header-new {
            margin-bottom: 1.5rem;
        }

        .referrals-wallet-icon-new {
            width: 64px;
            height: 64px;
            font-size: 1.5rem;
        }

        .referrals-wallet-title-new {
            font-size: 1.5rem;
        }

        .referrals-balance-value-new {
            font-size: 2.5rem;
        }

        .referrals-balance-currency-new {
            font-size: 1.5rem;
        }

        .referrals-tools-grid-new {
            gap: 1rem;
        }

        .referrals-tool-card-new {
            padding: 1.5rem;
        }

        .referrals-referrer-card-new {
            padding: 1.5rem;
        }

        .referrals-commission-grid-new {
            gap: 1rem;
        }

        .referrals-commission-card-new {
            padding: 1.5rem;
        }

        .referrals-commission-rate-value-new {
            font-size: 2.5rem;
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
            padding: 0;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .referrals-hero-new {
            padding: 1.25rem 0.875rem;
            margin-bottom: 1.25rem;
            border-radius: 12px;
        }

        .referrals-hero-title-new {
            font-size: 1.5rem;
            letter-spacing: -1px;
        }

        .referrals-hero-subtitle-new {
            font-size: 0.8125rem;
        }

        .referrals-stats-section-new {
            gap: 0.875rem;
        }

        .referrals-stat-card-new {
            padding: 1.25rem;
        }

        .referrals-stat-icon-new {
            width: 56px;
            height: 56px;
            font-size: 1.25rem;
        }

        .referrals-stat-value-new {
            font-size: 1.75rem;
        }

        .referrals-wallet-section-new {
            padding: 1.25rem 0.875rem;
            margin-bottom: 1.25rem;
            border-radius: 12px;
        }

        .referrals-wallet-icon-new {
            width: 56px;
            height: 56px;
            font-size: 1.25rem;
        }

        .referrals-wallet-title-new {
            font-size: 1.375rem;
        }

        .referrals-balance-value-new {
            font-size: 2rem;
        }

        .referrals-balance-currency-new {
            font-size: 1.25rem;
        }

        .referrals-claim-btn-new {
            padding: 1rem 1.5rem;
            font-size: 0.9375rem;
        }

        .referrals-tool-card-new {
            padding: 1.25rem;
        }

        .referrals-tool-icon-new {
            width: 56px;
            height: 56px;
            font-size: 1.5rem;
        }

        .referrals-tool-title-new {
            font-size: 1.25rem;
        }

        .referrals-referrer-card-new {
            padding: 1.25rem;
        }

        .referrals-referrer-icon-new {
            width: 56px;
            height: 56px;
            font-size: 1.5rem;
        }

        .referrals-referrer-title-new {
            font-size: 1.25rem;
        }

        .referrals-commission-card-new {
            padding: 1.25rem;
        }

        .referrals-commission-level-icon-new {
            width: 80px;
            height: 80px;
            font-size: 2rem;
        }

        .referrals-commission-rate-value-new {
            font-size: 2rem;
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
                <div class="referrals-stat-label-new">Total Referral Earning</div>
                <div class="referrals-stat-value-new">$0.00</div>
            </div>
        </div>

        <div class="referrals-stat-card-new">
            <div class="referrals-stat-icon-new referrals-stat-icon-users-new">
                <i class="fas fa-users"></i>
            </div>
            <div class="referrals-stat-content-new">
                <div class="referrals-stat-label-new">Total Referrals</div>
                <div class="referrals-stat-value-new">0</div>
            </div>
        </div>
    </div>

    <!-- Earning Wallet Card -->
    <div class="referrals-wallet-section-new">
        <div class="referrals-wallet-header-new">
            <div class="referrals-wallet-icon-new">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="referrals-wallet-title-section-new">
                <h3 class="referrals-wallet-title-new">Mining Referral Wallet</h3>
                <p class="referrals-wallet-subtitle-new">Available mining referral earnings for withdrawal</p>
            </div>
        </div>
        <div class="referrals-wallet-body-new">
            <div class="referrals-balance-display-new">
                <div class="referrals-balance-amount-new">
                    <span class="referrals-balance-currency-new">$</span>
                    <span class="referrals-balance-value-new">0.00</span>
                </div>
                <div class="referrals-minimum-info-new">
                    <div class="referrals-minimum-requirement-new">
                        <i class="fas fa-info-circle"></i>
                        <span>Minimum withdrawal: <strong>$1.00</strong></span>
                    </div>
                    <div class="referrals-minimum-needed-new">$1.00 more needed to claim</div>
                </div>
            </div>
            <button class="referrals-claim-btn-new" disabled>
                <i class="fas fa-hand-holding-usd"></i>
                <span>Claim Earnings</span>
            </button>
            <p class="referrals-claim-note-new">You can claim referral earnings when balance reaches $1 or more</p>
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
                        <input type="text" class="referrals-tool-input-new" id="referralLink" value="https://licrown.ai/auth/sign-u" readonly>
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
                        <input type="text" class="referrals-tool-input-new" id="referralCode" value="RAMEEZNAZAR2473" readonly>
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
                <div class="referrals-referrer-info-item-new">
                    <div class="referrals-referrer-info-label-new">
                        <i class="fas fa-user"></i>
                        <span>Name</span>
                    </div>
                    <div class="referrals-referrer-info-value-new">Moneymaker</div>
                </div>
                <div class="referrals-referrer-info-item-new">
                    <div class="referrals-referrer-info-label-new">
                        <i class="fas fa-envelope"></i>
                        <span>Email</span>
                    </div>
                    <div class="referrals-referrer-info-value-new">paksameer4@gmail.com</div>
                </div>
                <div class="referrals-referrer-info-item-new">
                    <div class="referrals-referrer-info-label-new">
                        <i class="fas fa-phone"></i>
                        <span>Phone</span>
                    </div>
                    <div class="referrals-referrer-info-value-new">03375453962</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Commission Structure Section -->
    <div class="referrals-commission-section-new">
        <div class="referrals-commission-header-new">
            <h2 class="referrals-commission-title-new">Commission Structure</h2>
            <p class="referrals-commission-subtitle-new">Earn up to 18% commission across 5 levels</p>
        </div>
        <div class="referrals-commission-grid-new">
            <!-- Level 1 -->
            <div class="referrals-commission-card-new">
                <div class="referrals-commission-level-badge-new">Level 1</div>
                <div class="referrals-commission-level-icon-new">
                    <i class="fas fa-star"></i>
                </div>
                <div class="referrals-commission-level-name-new">Direct Referrals</div>
                <div class="referrals-commission-rate-new">
                    <span class="referrals-commission-rate-value-new">6%</span>
                    <span class="referrals-commission-rate-label-new">Commission</span>
                </div>
            </div>

            <!-- Level 2 -->
            <div class="referrals-commission-card-new">
                <div class="referrals-commission-level-badge-new">Level 2</div>
                <div class="referrals-commission-level-icon-new">
                    <i class="fas fa-star"></i>
                </div>
                <div class="referrals-commission-level-name-new">Second Level</div>
                <div class="referrals-commission-rate-new">
                    <span class="referrals-commission-rate-value-new">3%</span>
                    <span class="referrals-commission-rate-label-new">Commission</span>
                </div>
            </div>

            <!-- Level 3 -->
            <div class="referrals-commission-card-new">
                <div class="referrals-commission-level-badge-new">Level 3</div>
                <div class="referrals-commission-level-icon-new">
                    <i class="fas fa-star"></i>
                </div>
                <div class="referrals-commission-level-name-new">Third Level</div>
                <div class="referrals-commission-rate-new">
                    <span class="referrals-commission-rate-value-new">3%</span>
                    <span class="referrals-commission-rate-label-new">Commission</span>
                </div>
            </div>

            <!-- Level 4 -->
            <div class="referrals-commission-card-new">
                <div class="referrals-commission-level-badge-new">Level 4</div>
                <div class="referrals-commission-level-icon-new">
                    <i class="fas fa-star"></i>
                </div>
                <div class="referrals-commission-level-name-new">Fourth Level</div>
                <div class="referrals-commission-rate-new">
                    <span class="referrals-commission-rate-value-new">3%</span>
                    <span class="referrals-commission-rate-label-new">Commission</span>
                </div>
            </div>

            <!-- Level 5 -->
            <div class="referrals-commission-card-new">
                <div class="referrals-commission-level-badge-new">Level 5</div>
                <div class="referrals-commission-level-icon-new">
                    <i class="fas fa-star"></i>
                </div>
                <div class="referrals-commission-level-name-new">Fifth Level</div>
                <div class="referrals-commission-rate-new">
                    <span class="referrals-commission-rate-value-new">3%</span>
                    <span class="referrals-commission-rate-label-new">Commission</span>
                </div>
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
            <select class="referrals-network-filter-new">
                <option value="all">All Levels</option>
                <option value="level1">Level 1</option>
                <option value="level2">Level 2</option>
                <option value="level3">Level 3</option>
                <option value="level4">Level 4</option>
                <option value="level5">Level 5</option>
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
                        <tr>
                            <td colspan="3" class="referrals-network-empty-new">
                                <div class="referrals-network-empty-content-new">
                                    <i class="fas fa-inbox"></i>
                                    <p>No referrals yet</p>
                                    <span>Start sharing your referral link to build your network</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Program Rules Section -->
    <div class="referrals-rules-section-new">
        <div class="referrals-rules-header-new">
            <h2 class="referrals-rules-title-new">Program Rules</h2>
            <p class="referrals-rules-subtitle-new">Important information about the referral program</p>
        </div>
        <div class="referrals-rules-card-new">
            <div class="referrals-rules-list-new">
                <div class="referrals-rule-item-new">
                    <div class="referrals-rule-icon-new">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="referrals-rule-content-new">
                        <h4 class="referrals-rule-title-new">Commission Earnings</h4>
                        <p class="referrals-rule-text-new">After a friend registers, each rebate transaction earns you a corresponding percentage of commission.</p>
                    </div>
                </div>
                <div class="referrals-rule-item-new">
                    <div class="referrals-rule-icon-new">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="referrals-rule-content-new">
                        <h4 class="referrals-rule-title-new">Maximum Commission</h4>
                        <p class="referrals-rule-text-new">The highest rebate ratio is 18%, and the settlement is in USDT. Rebates can be withdrawn to your trading account anytime.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('dashboard/js/referrals.js') }}"></script>
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
</script>
@endpush
@endsection



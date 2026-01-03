@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Transactions')

@push('styles')
<link rel="stylesheet" href="{{ asset('dashboard/css/transactions.css') }}">
<style>
    .transactions-new-page {
        padding: 2rem;
        max-width: 1600px;
        margin: 0 auto;
        width: 100%;
        box-sizing: border-box;
        overflow-x: hidden;
    }

    /* Hero Section */
    .transactions-hero-new {
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

    .transactions-hero-new.transactions-hero-desktop {
        display: block;
    }

    .transactions-hero-new::before {
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

    .transactions-hero-new::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 178, 30, 0.08) 0%, transparent 70%);
        pointer-events: none;
    }

    .transactions-hero-content-new {
        position: relative;
        z-index: 1;
    }

    .transactions-hero-title-new {
        font-size: 3rem;
        font-weight: 700;
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 1rem 0;
        letter-spacing: -2px;
    }

    .transactions-hero-subtitle-new {
        font-size: 1.125rem;
        color: var(--text-secondary);
        margin: 0;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Summary Section */
    .transactions-summary-section-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .transactions-summary-card-new {
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
        gap: 1.5rem;
    }

    .transactions-summary-card-new::before {
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

    .transactions-summary-card-new:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 32px rgba(255, 178, 30, 0.2);
        border-color: var(--primary-color);
    }

    .transactions-summary-card-new:hover::before {
        transform: scaleX(1);
    }

    .transactions-summary-icon-new {
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

    .transactions-summary-icon-earning-new {
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
    }

    .transactions-summary-icon-referral-new {
        background: linear-gradient(135deg, #00AAFF 0%, #0088CC 100%);
    }

    .transactions-summary-icon-deposit-new {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
    }

    .transactions-summary-icon-withdraw-new {
        background: linear-gradient(135deg, #FF4444 0%, #DC2626 100%);
    }

    .transactions-summary-content-new {
        flex: 1;
    }

    .transactions-summary-label-new {
        font-size: 0.875rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
        font-weight: 600;
    }

    .transactions-summary-value-new {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        font-variant-numeric: tabular-nums;
        line-height: 1;
    }

    /* History Section */
    .transactions-history-section-new {
        margin-bottom: 2rem;
    }

    .transactions-history-header-new {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .transactions-history-title-section-new {
        flex: 1;
    }

    .transactions-history-title-new {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .transactions-history-subtitle-new {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .transactions-history-controls-new {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .transactions-search-box-new {
        position: relative;
        display: flex;
        align-items: center;
    }

    .transactions-search-box-new i {
        position: absolute;
        left: 1.25rem;
        color: var(--text-secondary);
        font-size: 0.9375rem;
        z-index: 1;
    }

    .transactions-search-input-new {
        padding: 0.875rem 1.25rem 0.875rem 3rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        color: var(--text-primary);
        font-size: 0.9375rem;
        width: 300px;
        max-width: 100%;
        box-sizing: border-box;
        transition: var(--transition);
    }

    .transactions-search-input-new:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(255, 178, 30, 0.1), 0 4px 16px rgba(255, 178, 30, 0.1);
        background: rgba(255, 255, 255, 0.05);
    }

    .transactions-filter-btn-new {
        padding: 0.875rem 1.25rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        color: var(--text-secondary);
        cursor: pointer;
        transition: var(--transition);
        font-size: 0.9375rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .transactions-filter-btn-new:hover {
        background: rgba(255, 178, 30, 0.1);
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .transactions-date-filter-new {
        padding: 0.875rem 1.25rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        color: var(--text-primary);
        font-size: 0.9375rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .transactions-date-filter-new:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(255, 178, 30, 0.1);
    }

    .transactions-history-card-new {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
        position: relative;
        overflow: hidden;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .transactions-history-card-new::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
    }

    .transactions-table-wrapper-new {
        overflow-x: visible;
        margin-bottom: 1.5rem;
        border-radius: 12px;
        -webkit-overflow-scrolling: touch;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .transactions-table-new {
        width: 100%;
        max-width: 100%;
        border-collapse: collapse;
        box-sizing: border-box;
    }

    .transactions-table-new thead {
        background: linear-gradient(180deg, rgba(255, 178, 30, 0.1) 0%, rgba(255, 138, 29, 0.05) 100%);
        border-bottom: 2px solid rgba(255, 178, 30, 0.2);
    }

    .transactions-table-new th {
        padding: 1.25rem 1.5rem;
        text-align: left;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .transactions-table-new td {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .transactions-table-new tbody tr {
        transition: var(--transition);
    }

    .transactions-table-new tbody tr:hover {
        background: rgba(255, 178, 30, 0.05);
    }

    .transactions-type-cell-new {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .transactions-type-icon-new {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #000;
        flex-shrink: 0;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
        position: relative;
        overflow: hidden;
    }

    .transactions-type-icon-new::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
        animation: icon-shine 3s ease-in-out infinite;
    }

    @keyframes icon-shine {
        0%, 100% { opacity: 0; }
        50% { opacity: 1; }
    }

    .transactions-type-icon-success-new {
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
    }

    .transactions-type-icon-warning-new {
        background: linear-gradient(135deg, #FF6B35 0%, #F7931E 100%);
    }

    .transactions-type-icon-danger-new {
        background: linear-gradient(135deg, #FF4444 0%, #DC2626 100%);
    }

    .transactions-type-icon-info-new {
        background: linear-gradient(135deg, #00AAFF 0%, #0088CC 100%);
    }

    .transactions-type-icon-new i {
        position: relative;
        z-index: 1;
    }

    .transactions-type-info-new {
        flex: 1;
    }

    .transactions-type-name-new {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .transactions-type-date-new {
        font-size: 0.8125rem;
        color: var(--text-secondary);
    }

    .transactions-amount-cell-new {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .transactions-amount-value-new {
        font-size: 1.25rem;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
        line-height: 1;
    }

    .transactions-amount-success-new {
        color: var(--primary-color);
        text-shadow: 0 0 15px rgba(255, 178, 30, 0.5);
    }

    .transactions-amount-danger-new {
        color: #FF4444;
    }

    .transactions-amount-wallet-new {
        font-size: 0.8125rem;
        color: var(--text-secondary);
    }

    /* Hide mobile status on desktop */
    @media (min-width: 401px) {
        .transactions-status-mobile {
            display: none;
        }
    }

    .transactions-status-cell-new {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .transactions-status-badge-new {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        border-radius: 20px;
        font-size: 0.8125rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        width: fit-content;
    }

    .transactions-status-completed-new {
        background: rgba(255, 178, 30, 0.15);
        border: 1px solid rgba(255, 178, 30, 0.3);
        color: var(--primary-color);
        box-shadow: 0 0 12px rgba(255, 178, 30, 0.2);
    }

    .transactions-status-pending-new {
        background: rgba(255, 107, 53, 0.15);
        border: 1px solid rgba(255, 107, 53, 0.3);
        color: #FF6B35;
    }

    .transactions-status-failed-new {
        background: rgba(220, 38, 38, 0.15);
        border: 1px solid rgba(220, 38, 38, 0.3);
        color: #FF4444;
    }

    .transactions-status-badge-new::before {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: currentColor;
        box-shadow: 0 0 8px currentColor;
    }

    .transactions-status-time-new {
        font-size: 0.8125rem;
        color: var(--text-secondary);
    }

    /* Pagination */
    .transactions-pagination-new {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1.5rem;
        padding-top: 2rem;
        border-top: 1px solid var(--card-border);
    }

    .transactions-pagination-btn-new {
        padding: 0.875rem 1.5rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        color: var(--text-secondary);
        cursor: pointer;
        transition: var(--transition);
        font-size: 0.9375rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .transactions-pagination-btn-new:hover:not(:disabled) {
        background: rgba(255, 178, 30, 0.1);
        border-color: var(--primary-color);
        color: var(--primary-color);
        box-shadow: 0 0 20px rgba(255, 178, 30, 0.3);
    }

    .transactions-pagination-btn-new:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .transactions-pagination-info-new {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9375rem;
        color: var(--text-primary);
    }

    .transactions-pagination-current-new {
        font-weight: 700;
        color: var(--primary-color);
        font-size: 1.125rem;
    }

    .transactions-pagination-separator-new {
        color: var(--text-secondary);
    }

    .transactions-pagination-total-new {
        color: var(--text-secondary);
    }

    /* Empty State */
    .transactions-empty-state-new {
        text-align: center;
        padding: 4rem 2rem;
    }

    .transactions-empty-icon-new {
        font-size: 5rem;
        color: var(--text-secondary);
        opacity: 0.3;
        margin-bottom: 1.5rem;
    }

    .transactions-empty-text-new {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .transactions-empty-subtext-new {
        font-size: 0.9375rem;
        color: var(--text-secondary);
    }

    @media (max-width: 1400px) {
        .transactions-new-page {
            max-width: 100%;
            padding: 0 1rem;
        }
    }

    @media (max-width: 1200px) {
        .transactions-summary-section-new {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
    }

    @media (max-width: 992px) {
        .transactions-new-page {
            padding: 1.5rem;
        }

        .transactions-hero-new {
            padding: 2.5rem 2rem;
        }

        .transactions-hero-title-new {
            font-size: 2.5rem;
        }

        .transactions-summary-section-new {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .transactions-summary-card-new {
            padding: 2rem;
        }
    }

    @media (max-width: 768px) {
        .transactions-new-page {
            padding: 0;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: hidden;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.95) 0%, rgba(10, 10, 10, 0.95) 100%);
            min-height: 100vh;
        }

        /* Hide hero section on mobile */
        .transactions-hero-new {
            display: none !important;
        }

        /* Financial Summary Cards - Single Card Container with 2x2 Grid for Mobile */
        .transactions-summary-section-new {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            margin-left: 1rem;
            margin-right: 1rem;
            width: calc(100% - 2rem);
            max-width: calc(100% - 2rem);
            box-sizing: border-box;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .transactions-summary-card-new {
            background: transparent;
            border: none;
            border-radius: 0;
            padding: 0.875rem;
            gap: 0.75rem;
            flex-direction: row;
            text-align: left;
            align-items: center;
            box-shadow: none;
            transition: none;
            position: relative;
        }

        .transactions-summary-card-new::before {
            display: none;
        }

        .transactions-summary-card-new:hover {
            transform: none;
            box-shadow: none;
            border-color: transparent;
        }

        .transactions-summary-icon-new {
            width: 48px;
            height: 48px;
            font-size: 1.25rem;
            border-radius: 50%;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Icon background colors matching the design - Mobile Override */
        .transactions-summary-icon-earning-new {
            background: #00FF88 !important;
        }

        .transactions-summary-icon-referral-new {
            background: linear-gradient(135deg, #00AAFF 0%, #0088CC 100%) !important;
        }

        .transactions-summary-icon-deposit-new {
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%) !important;
        }

        .transactions-summary-icon-withdraw-new {
            background: linear-gradient(135deg, #FF4444 0%, #DC2626 100%) !important;
        }

        .transactions-summary-icon-new i {
            color: #FFFFFF;
            font-weight: 600;
        }

        .transactions-summary-content-new {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .transactions-summary-label-new {
            font-size: 0.75rem;
            margin-bottom: 0;
            color: var(--text-secondary);
            font-weight: 500;
            line-height: 1.3;
            text-transform: none;
            letter-spacing: 0;
        }

        .transactions-summary-value-new {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .transactions-history-section-new {
            margin-bottom: 2rem;
        }

        .transactions-history-header-new {
            flex-direction: column;
            gap: 1.5rem;
            align-items: stretch;
            margin-bottom: 1.5rem;
        }

        .transactions-history-title-section-new {
            text-align: center;
        }

        .transactions-history-title-new {
            font-size: 1.75rem;
        }

        .transactions-history-controls-new {
            flex-direction: column;
            width: 100%;
            gap: 1rem;
        }

        .transactions-search-box-new {
            width: 100%;
        }

        .transactions-search-input-new {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        .transactions-filter-btn-new,
        .transactions-date-filter-new {
            width: 100%;
            justify-content: center;
            padding: 1rem 1.25rem;
            box-sizing: border-box;
        }

        .transactions-history-card-new {
            padding: 1.5rem;
            border-radius: 16px;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        /* Make table responsive on mobile - no horizontal scroll */
        .transactions-table-wrapper-new {
            overflow-x: visible;
            -webkit-overflow-scrolling: touch;
            margin: 0;
            padding: 0;
            width: 100%;
            max-width: 100%;
        }

        .transactions-table-new {
            width: 100%;
            min-width: 0;
            font-size: 0.875rem;
            display: block;
            border-collapse: separate;
        }

        .transactions-table-new thead {
            display: none;
        }

        .transactions-table-new tbody {
            display: block;
            width: 100%;
        }

        .transactions-table-new tbody tr {
            display: block;
            width: 100%;
            margin-bottom: 1rem;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 1rem;
            box-sizing: border-box;
        }

        .transactions-table-new tbody tr:last-child {
            margin-bottom: 0;
        }

        .transactions-table-new td {
            display: block;
            width: 100%;
            padding: 0.75rem 0;
            border: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            text-align: left;
            font-size: 0.875rem;
            box-sizing: border-box;
        }

        .transactions-table-new td:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .transactions-table-new td:first-child {
            padding-top: 0;
        }

        .transactions-type-cell-new,
        .transactions-amount-cell-new,
        .transactions-status-cell-new {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        .transactions-table-new th,
        .transactions-table-new td {
            padding: 1rem 0.75rem;
        }

        .transactions-type-cell-new {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .transactions-type-icon-new {
            width: 48px;
            height: 48px;
            font-size: 1.25rem;
        }

        .transactions-amount-value-new {
            font-size: 1.125rem;
        }

        .transactions-pagination-new {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
            padding-top: 1.5rem;
        }

        .transactions-pagination-btn-new {
            width: 100%;
            justify-content: center;
        }

        .transactions-pagination-info-new {
            text-align: center;
            order: -1;
        }
    }

    @media (max-width: 480px) {
        .transactions-new-page {
            padding: 0;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: hidden;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.95) 0%, rgba(10, 10, 10, 0.95) 100%);
            min-height: 100vh;
        }

        /* Hide hero section on mobile */
        .transactions-hero-new {
            display: none !important;
        }

        /* Financial Summary Cards - Single Card Container with 2x2 Grid */
        .transactions-summary-section-new {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 0.875rem;
            margin-bottom: 1.5rem;
            margin-left: 0.875rem;
            margin-right: 0.875rem;
            width: calc(100% - 1.75rem);
            max-width: calc(100% - 1.75rem);
            box-sizing: border-box;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.625rem;
        }

        .transactions-summary-card-new {
            background: transparent;
            border: none;
            padding: 0.75rem;
            gap: 0.625rem;
        }

        .transactions-summary-icon-new {
            width: 44px;
            height: 44px;
            font-size: 1.125rem;
        }

        .transactions-summary-label-new {
            font-size: 0.6875rem;
            margin-bottom: 0;
        }

        .transactions-summary-value-new {
            font-size: 1rem;
        }

        .transactions-history-card-new {
            padding: 1.25rem;
            border-radius: 12px;
        }

        .transactions-table-new {
            font-size: 0.8125rem;
        }

        .transactions-table-new td {
            padding: 0.75rem 0;
            font-size: 0.8125rem;
        }

        .transactions-type-icon-new {
            width: 44px;
            height: 44px;
            font-size: 1.125rem;
        }

        .transactions-type-name-new {
            font-size: 0.9375rem;
        }

        .transactions-amount-value-new {
            font-size: 1rem;
        }

        .transactions-status-badge-new {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
        }

        .transactions-pagination-btn-new {
            padding: 0.75rem 1.25rem;
            font-size: 0.875rem;
        }
    }

    @media (max-width: 400px) {
        .transactions-new-page {
            padding: 0;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: hidden;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.95) 0%, rgba(10, 10, 10, 0.95) 100%);
            min-height: 100vh;
        }

        /* Hide hero section on mobile */
        .transactions-hero-new {
            display: none !important;
        }

        /* Financial Summary Cards - Single Card Container with 2x2 Grid */
        .transactions-summary-section-new {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 0.75rem;
            margin-bottom: 1.25rem;
            margin-left: 0.75rem;
            margin-right: 0.75rem;
            width: calc(100% - 1.5rem);
            max-width: calc(100% - 1.5rem);
            box-sizing: border-box;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
        }

        .transactions-summary-card-new {
            background: transparent;
            border: none;
            padding: 0.625rem;
            gap: 0.5rem;
        }

        .transactions-summary-icon-new {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .transactions-summary-label-new {
            font-size: 0.625rem;
            margin-bottom: 0;
        }

        .transactions-summary-value-new {
            font-size: 0.9375rem;
        }

        /* Transaction History Section - Mobile App Design */
        .transactions-history-section-new {
            padding: 0;
            border-radius: 0;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            margin-left: 0;
            margin-right: 0;
            background: var(--bg-primary);
            border: none;
            box-shadow: none;
        }

        .transactions-history-header-new {
            margin-bottom: 1rem;
            gap: 0.75rem;
            padding: 1rem;
            padding-bottom: 0.75rem;
        }

        .transactions-history-title-section-new {
            margin-bottom: 0.75rem;
        }

        .transactions-history-title-new {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            line-height: 1.2;
        }

        .transactions-history-subtitle-new {
            display: none;
        }

        /* Search and Filter Controls - Mobile App Style */
        .transactions-history-controls-new {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
            margin-top: 0.75rem;
        }

        .transactions-search-box-new {
            flex: 1;
            position: relative;
        }

        .transactions-search-box-new i {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.875rem;
            color: var(--text-secondary);
            z-index: 1;
        }

        .transactions-search-input-new {
            padding: 0.75rem 0.875rem 0.75rem 2.5rem;
            font-size: 0.8125rem;
            width: 100%;
            max-width: 100%;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-secondary);
        }

        .transactions-search-input-new::placeholder {
            color: var(--text-secondary);
            opacity: 0.6;
        }

        .transactions-filter-btn-new {
            padding: 0.75rem;
            font-size: 0.875rem;
            border-radius: 10px;
            min-width: 44px;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-secondary);
        }

        .transactions-filter-btn-new span {
            display: none;
        }

        .transactions-date-filter-new {
            padding: 0.75rem 0.875rem;
            font-size: 0.8125rem;
            border-radius: 10px;
            min-width: auto;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23ffffff' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            padding-right: 2.5rem;
        }

        .transactions-history-card-new {
            padding: 0;
            border-radius: 0;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            background: transparent;
            border: none;
            box-shadow: none;
        }

        .transactions-history-card-new::before {
            display: none;
        }

        .transactions-table-wrapper-new {
            overflow-x: visible;
            -webkit-overflow-scrolling: touch;
            margin: 0;
            padding: 0 1rem 1rem 1rem;
            width: 100%;
            max-width: 100%;
        }

        .transactions-table-new {
            width: 100%;
            min-width: 0;
            display: block;
            border-collapse: separate;
        }

        .transactions-table-new thead {
            display: none;
        }

        .transactions-table-new tbody {
            display: block;
            width: 100%;
        }

        /* Transaction Row Layout - Mobile App Style */
        .transactions-table-new tbody tr {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            justify-content: space-between;
            padding: 1rem;
            gap: 0.75rem;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            margin-bottom: 0.75rem;
            box-sizing: border-box;
        }

        .transactions-table-new tbody tr:last-child {
            margin-bottom: 0;
        }

        /* First TD - Transaction info (left side) */
        .transactions-table-new tbody tr td:first-child {
            flex: 1;
            min-width: 0;
            display: flex;
            padding: 0;
            border: none;
        }

        /* Second TD - Amount (right side) */
        .transactions-table-new tbody tr td:nth-child(2) {
            flex: 0 0 auto;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: flex-start;
            gap: 0.5rem;
            padding: 0;
            border: none;
        }

        /* Third TD - Status (hidden on mobile, shown in amount cell) */
        .transactions-table-new tbody tr td:nth-child(3) {
            display: none;
        }

        /* Enhanced Transaction Info Layout */
        .transactions-type-cell-new {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
        }

        .transactions-type-icon-new {
            width: 40px;
            height: 40px;
            font-size: 1rem;
            border-radius: 10px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .transactions-type-icon-new.transactions-type-icon-success-new {
            background: rgba(255, 178, 30, 0.15);
            color: var(--primary-color);
        }

        .transactions-type-icon-new.transactions-type-icon-danger-new {
            background: rgba(255, 68, 68, 0.15);
            color: #FF4444;
        }

        .transactions-type-info-new {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }

        .transactions-type-name-new {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0;
            line-height: 1.3;
        }

        /* Date below name */
        .transactions-type-date-new {
            font-size: 0.6875rem;
            color: var(--text-secondary);
            line-height: 1.4;
            white-space: nowrap;
        }

        /* Amount Cell - Right side styling */
        .transactions-amount-cell-new {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.25rem;
            text-align: right;
        }

        .transactions-amount-value-new {
            font-size: 1rem;
            font-weight: 700;
            font-variant-numeric: tabular-nums;
            line-height: 1.2;
        }

        .transactions-amount-value-new.transactions-amount-success-new {
            color: var(--primary-color);
        }

        .transactions-amount-value-new.transactions-amount-danger-new {
            color: #FF4444;
        }

        .transactions-amount-wallet-new {
            font-size: 0.8125rem;
            color: var(--text-primary);
            font-weight: 500;
            white-space: nowrap;
            line-height: 1.3;
        }

        /* Status Mobile - Show on right side with colors */
        .transactions-status-mobile {
            display: block;
            font-size: 0.6875rem;
            font-weight: 600;
            text-transform: capitalize;
            margin-top: 0.25rem;
            text-align: right;
        }

        .transactions-status-mobile.transactions-status-completed {
            color: var(--primary-color);
        }

        .transactions-status-mobile.transactions-status-pending {
            color: #FFAA00;
        }

        /* Hide status cell on mobile */
        .transactions-status-cell-new {
            display: none;
        }

        /* Hide pagination on mobile */
        .transactions-pagination-new {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="transactions-new-page">
    <!-- Hero Section (Desktop Only) -->
    <div class="transactions-hero-new transactions-hero-desktop">
        <div class="transactions-hero-content-new">
            <h1 class="transactions-hero-title-new">Mining Transaction History</h1>
            <p class="transactions-hero-subtitle-new">Track all your mining activities and transactions in one place</p>
        </div>
    </div>

    <!-- Financial Summary Section -->
    <div class="transactions-summary-section-new">
            <!-- Total Earning -->
        <div class="transactions-summary-card-new">
            <div class="transactions-summary-icon-new transactions-summary-icon-earning-new">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            <div class="transactions-summary-content-new">
                <div class="transactions-summary-label-new">Total Earning</div>
                <div class="transactions-summary-value-new">$0.00</div>
                </div>
            </div>

            <!-- Referral Earning -->
        <div class="transactions-summary-card-new">
            <div class="transactions-summary-icon-new transactions-summary-icon-referral-new">
                    <i class="fas fa-users"></i>
                </div>
            <div class="transactions-summary-content-new">
                <div class="transactions-summary-label-new">Referral Earning</div>
                <div class="transactions-summary-value-new">$0.00</div>
                </div>
            </div>

            <!-- Total Deposit -->
        <div class="transactions-summary-card-new">
            <div class="transactions-summary-icon-new transactions-summary-icon-deposit-new">
                    <i class="fas fa-arrow-up"></i>
                </div>
            <div class="transactions-summary-content-new">
                <div class="transactions-summary-label-new">Total Deposit</div>
                <div class="transactions-summary-value-new">$0.30</div>
                </div>
            </div>

            <!-- Total Withdrawn -->
        <div class="transactions-summary-card-new">
            <div class="transactions-summary-icon-new transactions-summary-icon-withdraw-new">
                    <i class="fas fa-arrow-down"></i>
                </div>
            <div class="transactions-summary-content-new">
                <div class="transactions-summary-label-new">Total Withdrawn</div>
                <div class="transactions-summary-value-new">$0.00</div>
            </div>
        </div>
    </div>

    <!-- Transaction History Section -->
    <div class="transactions-history-section-new">
        <div class="transactions-history-header-new">
            <div class="transactions-history-title-section-new">
                <h2 class="transactions-history-title-new">All Transactions</h2>
                <p class="transactions-history-subtitle-new">View and filter your complete transaction history</p>
            </div>
            <div class="transactions-history-controls-new">
                <div class="transactions-search-box-new">
                    <i class="fas fa-search"></i>
                    <input type="text" class="transactions-search-input-new" placeholder="Search transactions..." id="transactionSearch">
                </div>
                <button class="transactions-filter-btn-new" title="Filter">
                    <i class="fas fa-filter"></i>
                    <span>Filter</span>
                </button>
                <select class="transactions-date-filter-new" id="transactionDateFilter">
                    <option value="3">3 Days</option>
                    <option value="7">7 Days</option>
                    <option value="30">30 Days</option>
                    <option value="all">All Time</option>
                </select>
            </div>
        </div>

        <div class="transactions-history-card-new">
            <div class="transactions-table-wrapper-new">
                <table class="transactions-table-new">
                    <thead>
                        <tr>
                            <th>Transaction</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="transactionsTableBody">
                        <tr>
                            <td>
                                <div class="transactions-type-cell-new">
                                    <div class="transactions-type-icon-new transactions-type-icon-success-new">
                                        <i class="fas fa-gift"></i>
                                    </div>
                                    <div class="transactions-type-info-new">
                                        <div class="transactions-type-name-new">Bonus</div>
                                        <div class="transactions-type-date-new">Dec 28, 2025, 11:11 PM</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="transactions-amount-cell-new">
                                    <div class="transactions-amount-value-new transactions-amount-success-new">+$0.30</div>
                                    <div class="transactions-amount-wallet-new">Earning Wallet: $0</div>
                                    <div class="transactions-status-mobile transactions-status-completed">Completed</div>
                                </div>
                            </td>
                            <td>
                                <div class="transactions-status-cell-new">
                                    <span class="transactions-status-badge-new transactions-status-completed-new">
                                        <span>Completed</span>
                                    </span>
                                    <div class="transactions-status-time-new">2 hours ago</div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="transactions-pagination-new">
                <button class="transactions-pagination-btn-new" id="prevPage" disabled>
                    <i class="fas fa-chevron-left"></i>
                    <span>Previous</span>
                </button>
                <div class="transactions-pagination-info-new">
                    <span>Page</span>
                    <span class="transactions-pagination-current-new" id="currentPage">1</span>
                    <span class="transactions-pagination-separator-new">of</span>
                    <span class="transactions-pagination-total-new" id="totalPages">1</span>
                </div>
                <button class="transactions-pagination-btn-new" id="nextPage">
                    <span>Next</span>
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('dashboard/js/transactions.js') }}"></script>
<script>
    // Search functionality
    const searchInput = document.getElementById('transactionSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.transactions-table-new tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Date filter functionality
    const dateFilter = document.getElementById('transactionDateFilter');
    if (dateFilter) {
        dateFilter.addEventListener('change', function(e) {
            // Filter logic can be implemented here
            console.log('Filter changed to:', e.target.value);
        });
    }
</script>
@endpush
@endsection

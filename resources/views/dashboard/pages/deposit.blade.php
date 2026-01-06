@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Deposit')

@push('styles')
<link rel="stylesheet" href="{{ asset('dashboard/css/dashboard.css') }}">
<style>
    .deposit-page {
        padding: 0;
        width: 100%;
        max-width: 100%;
    }

    /* Deposit Header */
    .deposit-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .deposit-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        letter-spacing: -1px;
    }


    /* Main Content Grid */
    .deposit-content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    /* Left Panel - Deposit Form */
    .deposit-form-section {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .deposit-section-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .deposit-section-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #FFB21E 0%, #FF8A1D 50%, #FFB21E 100%);
        background-size: 200% 100%;
        animation: shimmer 3s linear infinite;
    }

    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .deposit-section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 1.5rem 0;
    }

    /* Payment Methods Grid */
    .deposit-payment-methods {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .deposit-payment-method {
        background: rgba(255, 178, 30, 0.05);
        border: 2px solid rgba(255, 178, 30, 0.2);
        border-radius: 12px;
        padding: 1.5rem;
        cursor: pointer;
        transition: var(--transition);
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .deposit-payment-method:hover {
        background: rgba(255, 178, 30, 0.1);
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(255, 178, 30, 0.3);
    }

    .deposit-payment-method.active {
        background: rgba(255, 178, 30, 0.15);
        border-color: var(--primary-color);
        box-shadow: 0 0 20px rgba(255, 178, 30, 0.4);
    }

    .deposit-payment-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 2rem;
    }

    .deposit-payment-method.easypaisa .deposit-payment-icon {
        background: linear-gradient(135deg, #00A651 0%, #00C853 100%);
        color: white;
    }

    .deposit-payment-method.jazzcash .deposit-payment-icon {
        background: linear-gradient(135deg, #E31E24 0%, #FF4444 100%);
        color: white;
    }

    .deposit-payment-method.crypto .deposit-payment-icon {
        background: linear-gradient(135deg, #F7931A 0%, #FFA500 100%);
        color: white;
    }

    .deposit-payment-method.bank .deposit-payment-icon {
        background: linear-gradient(135deg, #1E88E5 0%, #42A5F5 100%);
        color: white;
    }

    .deposit-payment-name {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    /* Deposit Amount Input */
    .deposit-amount-wrapper {
        position: relative;
    }

    .deposit-amount-input {
        width: 100%;
        padding: 1.25rem 1.5rem 1.25rem 3rem;
        background: rgba(24, 27, 39, 0.8);
        border: 2px solid rgba(255, 178, 30, 0.2);
        border-radius: 12px;
        color: var(--text-primary);
        font-size: 1.25rem;
        font-weight: 600;
        transition: var(--transition);
    }

    .deposit-amount-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 20px rgba(255, 178, 30, 0.3);
    }

    .deposit-amount-input::placeholder {
        color: var(--text-muted);
    }

    .deposit-amount-symbol {
        position: absolute;
        left: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary-color);
        font-size: 1.25rem;
        font-weight: 700;
    }

    .deposit-continue-btn {
        width: 100%;
        padding: 1.25rem;
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
        border: none;
        border-radius: 12px;
        color: white;
        font-size: 1.125rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        margin-top: 1rem;
        box-shadow: 0 4px 20px rgba(255, 178, 30, 0.3);
    }

    .deposit-continue-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 30px rgba(255, 178, 30, 0.5);
    }

    .deposit-continue-btn:active {
        transform: translateY(0);
    }

    /* Right Panel - Instructions and History */
    .deposit-info-section {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    /* Deposit Instructions */
    .deposit-instructions-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .deposit-instruction-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        color: var(--text-secondary);
        font-size: 0.9375rem;
        line-height: 1.6;
    }

    .deposit-instruction-bullet {
        width: 8px;
        height: 8px;
        background: var(--primary-color);
        border-radius: 50%;
        margin-top: 0.5rem;
        flex-shrink: 0;
        box-shadow: 0 0 10px rgba(255, 178, 30, 0.5);
    }

    /* Mobile deposit instructions enhanced styling */
    @media (max-width: 768px) {
        .deposit-instructions-list {
            gap: 1.25rem;
        }

        .deposit-instruction-item {
            font-size: 0.9375rem;
            line-height: 1.7;
        }

        .deposit-instruction-bullet {
            width: 10px;
            height: 10px;
            margin-top: 0.375rem;
            box-shadow: 0 0 12px rgba(255, 178, 30, 0.7);
        }

        .deposit-info-section .deposit-section-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
    }

    /* Deposit History */
    .deposit-history-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .deposit-history-filters {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .deposit-filter-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: rgba(255, 178, 30, 0.1);
        border: 1px solid rgba(255, 178, 30, 0.3);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
    }

    .deposit-filter-icon:hover {
        background: rgba(255, 178, 30, 0.2);
        border-color: var(--primary-color);
    }

    .deposit-filter-dropdown {
        padding: 0.625rem 1rem;
        background: rgba(255, 178, 30, 0.1);
        border: 1px solid rgba(255, 178, 30, 0.3);
        border-radius: 8px;
        color: var(--text-primary);
        font-size: 0.875rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .deposit-filter-dropdown:hover {
        background: rgba(255, 178, 30, 0.2);
        border-color: var(--primary-color);
    }

    .deposit-history-empty {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-muted);
        font-size: 1rem;
    }

    /* Hide empty state when transactions are shown */
    .deposit-transactions-list:not(:empty) ~ .deposit-history-empty {
        display: none;
    }

    /* Show empty state only when no transactions */
    .deposit-transactions-list:empty ~ .deposit-history-empty {
        display: block;
    }

    /* Ensure transaction cards are visible on desktop */
    .deposit-transactions-list {
        display: block;
    }

    .deposit-transactions-list:empty {
        display: none;
    }

    /* Transaction cards for desktop */
    .deposit-transactions-list {
        display: block;
    }

    .deposit-transaction-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        transition: var(--transition);
    }

    .deposit-transaction-card:hover {
        background: var(--card-bg-hover);
        border-color: var(--card-border-hover);
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .deposit-transaction-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        background: rgba(255, 178, 30, 0.1);
        border: 1px solid rgba(255, 178, 30, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .deposit-transaction-icon-wrapper i {
        color: var(--primary-color);
        font-size: 1.5rem;
    }

    .deposit-transaction-content {
        flex: 1;
        min-width: 0;
    }

    .deposit-transaction-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .deposit-transaction-date {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .deposit-transaction-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.5rem;
        flex-shrink: 0;
    }

    .deposit-transaction-amount {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
    }

    .deposit-transaction-wallet {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .deposit-transaction-status {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--primary-color);
        margin: 0;
    }

    /* Search bar for mobile */
    .deposit-search-bar {
        display: none;
    }

    .deposit-search-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .deposit-search-input {
        flex: 1;
        padding: 0.875rem 1rem 0.875rem 3rem;
        background: rgba(24, 27, 39, 0.8);
        border: 1px solid rgba(255, 178, 30, 0.2);
        border-radius: 10px;
        color: var(--text-primary);
        font-size: 0.9375rem;
        transition: var(--transition);
    }

    .deposit-search-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 15px rgba(255, 178, 30, 0.2);
    }

    .deposit-search-input::placeholder {
        color: var(--text-muted);
    }

    .deposit-search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 1rem;
    }

    .deposit-search-filter-btn {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: rgba(24, 27, 39, 0.8);
        border: 1px solid rgba(255, 178, 30, 0.2);
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
        flex-shrink: 0;
    }

    .deposit-search-filter-btn:hover {
        background: rgba(255, 178, 30, 0.1);
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .deposit-search-filter-btn i {
        font-size: 1rem;
    }

    /* Chat Widget */
    .deposit-chat-widget {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 100;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.75rem;
    }

    .deposit-chat-bubble {
        background: white;
        color: #333;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        font-size: 0.875rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        max-width: 200px;
    }

    .deposit-chat-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #1E88E5 0%, #42A5F5 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(30, 136, 229, 0.4);
        transition: var(--transition);
        font-size: 1.5rem;
    }

    .deposit-chat-icon:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 30px rgba(30, 136, 229, 0.6);
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        /* Hide deposit title on mobile */
        .deposit-header {
            display: none;
        }

        .deposit-content-grid {
            margin-top: 0;
        }

        .deposit-content-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        /* Show deposit instructions and history on mobile */
        .deposit-info-section {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* Show deposit history on mobile */
        .deposit-history-card {
            display: block;
        }

        /* Show search bar on mobile */
        .deposit-search-bar {
            display: block;
            margin-bottom: ;
        }

        /* Mobile deposit history card styling */
        .deposit-history-header {
            flex-direction: column;
            align-items: flex-start;
            gap: ;
            margin-bottom: 1.5rem;
        }

        .deposit-history-filters {
            width: 100%;
            display: flex;
            align-items: center;
        }

        /* Hide filter icon on mobile (it's now in search bar) */
        .deposit-filter-icon {
            display: none;
        }

        .deposit-filter-dropdown {
            width: 100%;
            padding: 0.875rem 1rem;
            background: rgba(24, 27, 39, 0.8);
            border: 1px solid rgba(255, 178, 30, 0.2);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23B0B0B0' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 3rem;
        }

        .deposit-filter-dropdown:hover {
            background-color: rgba(255, 178, 30, 0.1);
            border-color: var(--primary-color);
        }

        .deposit-filter-dropdown:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 15px rgba(255, 178, 30, 0.2);
        }

        .deposit-history-empty {
            display: none;
        }

        /* Hide empty state on mobile */
        .deposit-history-empty {
            display: none;
        }

        /* Transaction cards for mobile */
        .deposit-transaction-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .deposit-transaction-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: rgba(255, 178, 30, 0.1);
            border: 1px solid rgba(255, 178, 30, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .deposit-transaction-icon-wrapper i {
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .deposit-transaction-content {
            flex: 1;
            min-width: 0;
        }

        .deposit-transaction-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 0.5rem 0;
        }

        .deposit-transaction-date {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .deposit-transaction-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        .deposit-transaction-amount {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }

        .deposit-transaction-wallet {
            font-size: 0.8125rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .deposit-transaction-status {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
        }

        /* Show only left panel on mobile */
        .deposit-form-section {
            width: 100%;
        }

        /* Mobile deposit instructions styling */
        .deposit-info-section .deposit-section-card {
            margin-top: 0;
        }

        .deposit-instruction-bullet {
            background: var(--primary-color);
            box-shadow: 0 0 10px rgba(255, 178, 30, 0.6);
        }

        /* Payment methods in 2x2 grid on mobile */
        .deposit-payment-methods {
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .deposit-payment-method {
            padding: 1rem;
        }

        .deposit-payment-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .deposit-payment-name {
            font-size: 0.875rem;
        }

        .deposit-section-card {
            padding: 1.5rem;
        }

        .deposit-section-title {
            font-size: 1.125rem;
            margin-bottom: 1.25rem;
        }

        .deposit-amount-input {
            padding: 1rem 1.25rem 1rem 2.5rem;
            font-size: 1.125rem;
        }

        .deposit-amount-symbol {
            left: 1.25rem;
            font-size: 1.125rem;
        }

        .deposit-continue-btn {
            padding: 1rem;
            font-size: 1rem;
            margin-top: 0.75rem;
        }

        /* Hide chat widget on mobile */
        .deposit-chat-widget {
            display: none;
        }
    }

    @media (max-width: 390px) {
        .deposit-page {
            padding: 0;
        }

        .deposit-section-card {
            padding: 1.25rem;
            border-radius: 12px;
        }

        .deposit-section-title {
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .deposit-payment-methods {
            gap: 0.625rem;
        }

        .deposit-payment-method {
            padding: 0.875rem;
        }

        .deposit-payment-icon {
            width: 45px;
            height: 45px;
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .deposit-payment-name {
            font-size: 0.8125rem;
        }

        .deposit-amount-input {
            padding: 0.875rem 1rem 0.875rem 2.25rem;
            font-size: 1rem;
        }

        .deposit-amount-symbol {
            left: 1rem;
            font-size: 1rem;
        }

        .deposit-continue-btn {
            padding: 0.875rem;
            font-size: 0.9375rem;
        }
    }
</style>
@endpush

@section('content')
<div class="deposit-page">
    <!-- Deposit Header -->
    <div class="deposit-header">
        <h1 class="deposit-title">Deposit</h1>
    </div>

    <!-- Main Content Grid -->
    <div class="deposit-content-grid">
        <!-- Left Panel - Deposit Form -->
        <div class="deposit-form-section">
            <!-- Payment Method Selection -->
            <div class="deposit-section-card">
                <h2 class="deposit-section-title">Select Payment Method</h2>
                <div class="deposit-payment-methods">
                    <div class="deposit-payment-method easypaisa" data-method="easypaisa">
                        <div class="deposit-payment-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <p class="deposit-payment-name">Easypaisa</p>
                    </div>
                    <div class="deposit-payment-method jazzcash" data-method="jazzcash">
                        <div class="deposit-payment-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <p class="deposit-payment-name">Jazzcash</p>
                    </div>
                    <div class="deposit-payment-method crypto" data-method="crypto">
                        <div class="deposit-payment-icon">
                            <i class="fab fa-bitcoin"></i>
                        </div>
                        <p class="deposit-payment-name">Crypto</p>
                    </div>
                    <div class="deposit-payment-method bank" data-method="bank">
                        <div class="deposit-payment-icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <p class="deposit-payment-name">Bank</p>
                    </div>
                </div>
            </div>

            <!-- Deposit Amount -->
            <div class="deposit-section-card">
                <h2 class="deposit-section-title">Deposit Amount</h2>
                <div class="deposit-amount-wrapper">
                    <span class="deposit-amount-symbol">$</span>
                    <input type="number" class="deposit-amount-input" placeholder="Enter custom amount" min="2" step="0.01">
                </div>
                <button class="deposit-continue-btn">
                    Continue Deposit
                </button>
            </div>
        </div>

        <!-- Right Panel - Instructions and History -->
        <div class="deposit-info-section">
            <!-- Deposit Instructions -->
            <div class="deposit-section-card">
                <h2 class="deposit-section-title">Deposit Instructions</h2>
                <ul class="deposit-instructions-list">
                    <li class="deposit-instruction-item">
                        <span class="deposit-instruction-bullet"></span>
                        <span>If the transfer time is up, please fill out the deposit form again.</span>
                    </li>
                    <li class="deposit-instruction-item">
                        <span class="deposit-instruction-bullet"></span>
                        <span>The amount you send must be the same as your order.</span>
                    </li>
                    <li class="deposit-instruction-item">
                        <span class="deposit-instruction-bullet"></span>
                        <span>Note: Don't cancel the deposit after sending the money.</span>
                    </li>
                    <li class="deposit-instruction-item">
                        <span class="deposit-instruction-bullet"></span>
                        <span>Minimum deposit is $2</span>
                    </li>
                </ul>
            </div>

            <!-- Deposit History -->
            <div class="deposit-section-card deposit-history-card">
                <div class="deposit-history-header">
                    <h2 class="deposit-section-title">Deposit History</h2>
                    <!-- Search Bar (Mobile Only) -->
                    <div class="deposit-search-bar">
                        <div class="deposit-search-wrapper">
                            <i class="fas fa-search deposit-search-icon"></i>
                            <input type="text" class="deposit-search-input" placeholder="Search transactions...">
                            <button class="deposit-search-filter-btn" type="button">
                                <i class="fas fa-filter"></i>
                            </button>
                        </div>
                    </div>
                    <div class="deposit-history-filters">
                        <div class="deposit-filter-icon">
                            <i class="fas fa-filter"></i>
                        </div>
                        <select class="deposit-filter-dropdown">
                            <option>3 Days</option>
                            <option>7 Days</option>
                            <option>30 Days</option>
                            <option>All Time</option>
                        </select>
                    </div>
                </div>
                <!-- Transaction Cards (for desktop and mobile) -->
                <div class="deposit-transactions-list">
                    <div class="deposit-transaction-card">
                        <div class="deposit-transaction-icon-wrapper">
                            <i class="fas fa-gift"></i>
                        </div>
                        <div class="deposit-transaction-content">
                            <h3 class="deposit-transaction-title">Bonus</h3>
                            <p class="deposit-transaction-date">Dec 28, 2025, 11:11 PM</p>
                        </div>
                        <div class="deposit-transaction-right">
                            <div class="deposit-transaction-amount">+$0</div>
                            <p class="deposit-transaction-wallet">Earning Wallet: $0</p>
                            <span class="deposit-transaction-status">Completed</span>
                        </div>
                    </div>
                </div>
                <div class="deposit-history-empty">
                    No transaction history found!
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Widget -->
    <div class="deposit-chat-widget">
        <div class="deposit-chat-bubble">
            We are online!
        </div>
        <div class="deposit-chat-icon">
            <i class="fas fa-comments"></i>
        </div>
    </div>
</div>

<script>
    // Payment method selection
    document.querySelectorAll('.deposit-payment-method').forEach(method => {
        method.addEventListener('click', function() {
            document.querySelectorAll('.deposit-payment-method').forEach(m => m.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Continue deposit button
    document.querySelector('.deposit-continue-btn').addEventListener('click', function() {
        const selectedMethod = document.querySelector('.deposit-payment-method.active');
        const amount = document.querySelector('.deposit-amount-input').value;

        if (!selectedMethod) {
            alert('Please select a payment method');
            return;
        }

        if (!amount || parseFloat(amount) < 2) {
            alert('Please enter a valid amount (minimum $2)');
            return;
        }

        // Here you would typically submit the form or navigate to payment gateway
        console.log('Deposit:', {
            method: selectedMethod.dataset.method,
            amount: amount
        });
    });
</script>
@endsection


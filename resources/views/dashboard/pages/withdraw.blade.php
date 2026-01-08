@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Withdraw')

@push('styles')
<link rel="stylesheet" href="{{ asset('dashboard/css/dashboard.css') }}">
<style>
    .withdraw-page {
        padding: 0;
        width: 100%;
        max-width: 100%;
    }

    /* Withdraw Header */
    .withdraw-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .withdraw-title {
        font-size: 2.75rem;
        font-weight: 800;
        color: var(--text-primary);
        margin: 0;
        letter-spacing: -1.5px;
        line-height: 1.2;
        background: linear-gradient(135deg, var(--text-primary) 0%, #6366F1 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Main Content Grid */
    .withdraw-content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    /* Left Panel - Withdraw Form */
    .withdraw-form-section {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .withdraw-section-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .withdraw-section-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        border-color: rgba(99, 102, 241, 0.3);
    }

    .withdraw-section-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #6366F1 0%, #8B5CF6 50%, #6366F1 100%);
        background-size: 200% 100%;
        animation: shimmer 3s linear infinite;
        box-shadow: 0 2px 10px rgba(99, 102, 241, 0.5);
    }

    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .withdraw-section-title {
        font-size: 1.375rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 1.75rem 0;
        letter-spacing: -0.3px;
        line-height: 1.3;
    }

    /* Account Details Form */
    .withdraw-form-group {
        margin-bottom: 1.5rem;
    }

    .withdraw-form-group:last-child {
        margin-bottom: 0;
    }

    .withdraw-form-label {
        display: block;
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
    }

    .withdraw-input-wrapper {
        position: relative;
    }

    .withdraw-input-icon {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6366F1;
        font-size: 1.125rem;
        z-index: 1;
        pointer-events: none;
    }

    .withdraw-form-input {
        width: 100%;
        padding: 1.25rem 1.5rem 1.25rem 3.5rem;
        background: rgba(24, 27, 39, 0.9);
        border: 2px solid rgba(99, 102, 241, 0.25);
        border-radius: 14px;
        color: var(--text-primary);
        font-size: 1.0625rem;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-sizing: border-box;
    }

    .withdraw-form-input::placeholder {
        color: var(--text-muted);
        opacity: 0.6;
    }

    .withdraw-form-input:focus {
        outline: none;
        border-color: #6366F1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15), 0 6px 25px rgba(99, 102, 241, 0.3);
        background: rgba(24, 27, 39, 1);
        transform: translateY(-1px);
    }

    /* Payment Methods Grid */
    .withdraw-payment-methods {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .withdraw-payment-method {
        background: rgba(99, 102, 241, 0.05);
        border: 2px solid rgba(99, 102, 241, 0.2);
        border-radius: 16px;
        padding: 1.75rem 1.5rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .withdraw-payment-method::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.1), transparent);
        transition: left 0.5s;
    }

    .withdraw-payment-method:hover {
        background: rgba(99, 102, 241, 0.12);
        border-color: #6366F1;
        transform: translateY(-3px);
        box-shadow: 0 6px 25px rgba(99, 102, 241, 0.35);
    }

    .withdraw-payment-method:hover::before {
        left: 100%;
    }

    .withdraw-payment-method.active {
        background: rgba(99, 102, 241, 0.18);
        border-color: #6366F1;
        box-shadow: 0 0 25px rgba(99, 102, 241, 0.5);
        transform: scale(1.02);
    }

    .withdraw-payment-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 2.125rem;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        background: rgba(255, 255, 255, 0.05);
    }

    .withdraw-payment-icon img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 0.5rem;
    }

    .withdraw-payment-method:hover .withdraw-payment-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .withdraw-payment-method.active .withdraw-payment-icon {
        transform: scale(1.15);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    }

    .withdraw-payment-method.easypaisa .withdraw-payment-icon {
        background: rgba(255, 255, 255, 0.1);
    }

    .withdraw-payment-method.jazzcash .withdraw-payment-icon {
        background: rgba(255, 255, 255, 0.1);
    }

    .withdraw-payment-method.crypto .withdraw-payment-icon {
        background: rgba(255, 255, 255, 0.1);
    }

    .withdraw-payment-method.bank .withdraw-payment-icon {
        background: rgba(255, 255, 255, 0.1);
    }

    .withdraw-payment-name {
        font-size: 1.0625rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        letter-spacing: -0.2px;
        transition: color 0.3s ease;
    }

    .withdraw-payment-method:hover .withdraw-payment-name {
        color: #6366F1;
    }

    /* Withdraw Amount Input */
    .withdraw-amount-wrapper {
        position: relative;
    }

    .withdraw-amount-input {
        padding-left: 3.5rem !important;
    }

    .withdraw-amount-symbol {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6366F1;
        font-size: 1.375rem;
        font-weight: 700;
        text-shadow: 0 2px 8px rgba(99, 102, 241, 0.4);
        z-index: 1;
        pointer-events: none;
    }

    .withdraw-continue-btn {
        width: 100%;
        padding: 1.375rem 1.5rem;
        background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
        border: none;
        border-radius: 14px;
        color: white;
        font-size: 1.1875rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        margin-top: 1.25rem;
        box-shadow: 0 4px 20px rgba(99, 102, 241, 0.35);
        position: relative;
        overflow: hidden;
        letter-spacing: 0.3px;
    }

    .withdraw-continue-btn::before {
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

    .withdraw-continue-btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .withdraw-continue-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 35px rgba(99, 102, 241, 0.55);
    }

    .withdraw-continue-btn:active {
        transform: translateY(-1px);
    }

    /* Right Panel - Instructions and History */
    .withdraw-info-section {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    /* Withdraw Instructions */
    .withdraw-instructions-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .withdraw-instruction-item {
        display: flex;
        align-items: flex-start;
        gap: 1.25rem;
        color: var(--text-secondary);
        font-size: 0.96875rem;
        line-height: 1.7;
        padding: 0.75rem 0;
        transition: color 0.3s ease;
    }

    .withdraw-instruction-item:hover {
        color: var(--text-primary);
    }

    .withdraw-instruction-bullet {
        width: 10px;
        height: 10px;
        background: #6366F1;
        border-radius: 50%;
        margin-top: 0.625rem;
        flex-shrink: 0;
        box-shadow: 0 0 12px rgba(99, 102, 241, 0.6);
        transition: all 0.3s ease;
    }

    .withdraw-instruction-item:hover .withdraw-instruction-bullet {
        transform: scale(1.2);
        box-shadow: 0 0 16px rgba(99, 102, 241, 0.8);
    }

    /* Withdraw History */
    .withdraw-history-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .withdraw-history-filters {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .withdraw-filter-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: rgba(99, 102, 241, 0.1);
        border: 1px solid rgba(99, 102, 241, 0.3);
        color: #6366F1;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
    }

    .withdraw-filter-icon:hover {
        background: rgba(99, 102, 241, 0.2);
        border-color: #6366F1;
    }

    .withdraw-filter-dropdown {
        padding: 0.625rem 1rem;
        background: rgba(99, 102, 241, 0.1);
        border: 1px solid rgba(99, 102, 241, 0.3);
        border-radius: 8px;
        color: var(--text-primary);
        font-size: 0.875rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .withdraw-filter-dropdown:hover {
        background: rgba(99, 102, 241, 0.2);
        border-color: #6366F1;
    }

    .withdraw-history-empty {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-muted);
        font-size: 1rem;
    }

    .withdraw-transactions-list {
        display: block;
    }

    .withdraw-transactions-list:empty {
        display: none;
    }

    .withdraw-transaction-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 1.25rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .withdraw-transaction-card:hover {
        background: var(--card-bg-hover);
        border-color: rgba(99, 102, 241, 0.3);
        transform: translateY(-3px);
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.2);
    }

    .withdraw-transaction-icon-wrapper {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: rgba(99, 102, 241, 0.12);
        border: 1px solid rgba(99, 102, 241, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.15);
    }

    .withdraw-transaction-card:hover .withdraw-transaction-icon-wrapper {
        background: rgba(99, 102, 241, 0.18);
        border-color: rgba(99, 102, 241, 0.4);
        transform: scale(1.05);
    }

    .withdraw-transaction-icon-wrapper i {
        color: #6366F1;
        font-size: 1.5rem;
    }

    .withdraw-transaction-content {
        flex: 1;
        min-width: 0;
    }

    .withdraw-transaction-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .withdraw-transaction-date {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .withdraw-transaction-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.5rem;
        flex-shrink: 0;
    }

    .withdraw-transaction-amount {
        font-size: 1.25rem;
        font-weight: 700;
        color: #6366F1;
        margin: 0;
    }

    .withdraw-transaction-wallet {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .withdraw-transaction-status {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6366F1;
        margin: 0;
    }

    /* Banner Image Section */
    .withdraw-banner {
        width: 100%;
        margin-bottom: 2rem;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    }

    .withdraw-banner img {
        width: 100%;
        height: auto;
        display: block;
        object-fit: cover;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .withdraw-header {
            display: none;
        }

        .withdraw-banner {
            margin-bottom: 1.5rem;
            border-radius: 16px;
        }

        .withdraw-content-grid {
            margin-top: 0;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .withdraw-info-section {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .withdraw-section-card {
            padding: 1.5rem;
        }

        .withdraw-section-title {
            font-size: 1.125rem;
            margin-bottom: 1.25rem;
        }

        .withdraw-payment-methods {
            grid-template-columns: 1fr;
            gap: 0.875rem;
        }

        .withdraw-payment-method {
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            text-align: left;
        }

        .withdraw-payment-icon {
            width: 56px;
            height: 56px;
            margin: 0;
            flex-shrink: 0;
        }

        .withdraw-payment-name {
            font-size: 1rem;
            margin: 0;
        }

        .withdraw-form-input {
            padding: 1rem 1.25rem 1rem 3rem;
            font-size: 1rem;
        }

        .withdraw-input-icon {
            left: 1rem;
            font-size: 1rem;
        }

        .withdraw-amount-symbol {
            left: 1rem;
            font-size: 1.125rem;
        }

        .withdraw-amount-input {
            padding-left: 2.75rem !important;
        }

        .withdraw-continue-btn {
            padding: 1rem;
            font-size: 1rem;
            margin-top: 0.75rem;
        }

        .withdraw-history-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .withdraw-filter-icon {
            display: none;
        }

        .withdraw-filter-dropdown {
            width: 100%;
            padding: 0.875rem 1rem;
            background: rgba(24, 27, 39, 0.8);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236366F1' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 3rem;
        }

        .withdraw-filter-dropdown:hover {
            background-color: rgba(99, 102, 241, 0.1);
            border-color: #6366F1;
        }
    }

    @media (max-width: 390px) {
        .withdraw-page {
            padding: 0;
        }

        .withdraw-section-card {
            padding: 1.25rem;
            border-radius: 12px;
        }

        .withdraw-section-title {
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .withdraw-payment-methods {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .withdraw-payment-method {
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 0.875rem;
            text-align: left;
        }

        .withdraw-payment-icon {
            width: 48px;
            height: 48px;
            margin: 0;
            flex-shrink: 0;
        }

        .withdraw-payment-name {
            font-size: 0.875rem;
            margin: 0;
        }

        .withdraw-form-input {
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            font-size: 0.9375rem;
        }

        .withdraw-amount-symbol {
            left: 1rem;
            font-size: 1rem;
        }

        .withdraw-amount-input {
            padding-left: 2.5rem !important;
        }

        .withdraw-continue-btn {
            padding: 0.875rem;
            font-size: 0.9375rem;
        }
    }
</style>
@endpush

@section('content')
<div class="withdraw-page">
    <!-- Withdraw Header -->
    <div class="withdraw-header">
        <h1 class="withdraw-title">Withdraw</h1>
    </div>

    <!-- Banner Image -->
    <div class="withdraw-banner">
        <img src="{{ asset('dashboard/images/payment-method/bank.png') }}" alt="Withdraw Banner">
    </div>

    <!-- Main Content Grid -->
    <div class="withdraw-content-grid">
        <!-- Left Panel - Withdraw Form -->
        <div class="withdraw-form-section">
            <!-- Account Details -->
            <div class="withdraw-section-card">
                <h2 class="withdraw-section-title">Account Details</h2>
                
                <div class="withdraw-form-group">
                    <label class="withdraw-form-label">Account Name</label>
                    <div class="withdraw-input-wrapper">
                        <i class="fas fa-user withdraw-input-icon"></i>
                        <input type="text" class="withdraw-form-input" id="withdrawAccountName" placeholder="Enter account holder name" maxlength="100">
                    </div>
                </div>

                <div class="withdraw-form-group">
                    <label class="withdraw-form-label">Account Number</label>
                    <div class="withdraw-input-wrapper">
                        <i class="fas fa-hashtag withdraw-input-icon"></i>
                        <input type="text" class="withdraw-form-input" id="withdrawAccountNumber" placeholder="Enter your account number" maxlength="50">
                    </div>
                </div>
            </div>

            <!-- Payment Method Selection -->
            <div class="withdraw-section-card">
                <h2 class="withdraw-section-title">Select Payment Method</h2>
                <div class="withdraw-payment-methods">
                    <div class="withdraw-payment-method easypaisa" data-method="easypaisa">
                        <div class="withdraw-payment-icon">
                            <img src="{{ asset('dashboard/images/payment-method/easypaisa.png') }}" alt="Easypaisa">
                        </div>
                        <p class="withdraw-payment-name">Easypaisa</p>
                    </div>
                    <div class="withdraw-payment-method jazzcash" data-method="jazzcash">
                        <div class="withdraw-payment-icon">
                            <img src="{{ asset('dashboard/images/payment-method/jazzcash.png') }}" alt="Jazzcash">
                        </div>
                        <p class="withdraw-payment-name">Jazzcash</p>
                    </div>
                    <div class="withdraw-payment-method crypto" data-method="crypto">
                        <div class="withdraw-payment-icon">
                            <i class="fab fa-bitcoin"></i>
                        </div>
                        <p class="withdraw-payment-name">Crypto</p>
                    </div>
                    <div class="withdraw-payment-method bank" data-method="bank">
                        <div class="withdraw-payment-icon">
                            <img src="{{ asset('dashboard/images/payment-method/bank.png') }}" alt="Bank">
                        </div>
                        <p class="withdraw-payment-name">Bank</p>
                    </div>
                </div>
            </div>

            <!-- Withdraw Amount -->
            <div class="withdraw-section-card">
                <h2 class="withdraw-section-title">Withdraw Amount</h2>
                <div class="withdraw-amount-wrapper">
                    <span class="withdraw-amount-symbol">$</span>
                    <input type="number" class="withdraw-form-input withdraw-amount-input" id="withdrawAmount" placeholder="Enter withdrawal amount" min="1" step="0.01">
                </div>
                <button class="withdraw-continue-btn" id="submitWithdraw">
                    Continue Withdrawal
                </button>
            </div>
        </div>

        <!-- Right Panel - Instructions and History -->
        <div class="withdraw-info-section">
            <!-- Withdraw Instructions -->
            <div class="withdraw-section-card">
                <h2 class="withdraw-section-title">Withdrawal Instructions</h2>
                <ul class="withdraw-instructions-list">
                    <li class="withdraw-instruction-item">
                        <span class="withdraw-instruction-bullet"></span>
                        <span>Ensure your account details are correct before submitting the withdrawal request.</span>
                    </li>
                    <li class="withdraw-instruction-item">
                        <span class="withdraw-instruction-bullet"></span>
                        <span>Withdrawal requests are processed within 24-48 hours during business days.</span>
                    </li>
                    <li class="withdraw-instruction-item">
                        <span class="withdraw-instruction-bullet"></span>
                        <span>Minimum withdrawal amount is $1. Processing fees may apply.</span>
                    </li>
                    <li class="withdraw-instruction-item">
                        <span class="withdraw-instruction-bullet"></span>
                        <span>You will receive a confirmation email once your withdrawal is processed.</span>
                    </li>
                </ul>
            </div>

            <!-- Withdraw History -->
            <div class="withdraw-section-card withdraw-history-card">
                <div class="withdraw-history-header">
                    <h2 class="withdraw-section-title">Withdrawal History</h2>
                    <div class="withdraw-history-filters">
                        <div class="withdraw-filter-icon">
                            <i class="fas fa-filter"></i>
                        </div>
                        <select class="withdraw-filter-dropdown">
                            <option>3 Days</option>
                            <option>7 Days</option>
                            <option>30 Days</option>
                            <option>All Time</option>
                        </select>
                    </div>
                </div>
                <!-- Transaction Cards -->
                <div class="withdraw-transactions-list">
                    <div class="withdraw-transaction-card">
                        <div class="withdraw-transaction-icon-wrapper">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="withdraw-transaction-content">
                            <h3 class="withdraw-transaction-title">Withdrawal</h3>
                            <p class="withdraw-transaction-date">Dec 28, 2025, 11:11 PM</p>
                        </div>
                        <div class="withdraw-transaction-right">
                            <div class="withdraw-transaction-amount">-$100</div>
                            <p class="withdraw-transaction-wallet">Earning Wallet: $0</p>
                            <span class="withdraw-transaction-status">Completed</span>
                        </div>
                    </div>
                </div>
                <div class="withdraw-history-empty">
                    No withdrawal history found!
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Payment method selection
    document.querySelectorAll('.withdraw-payment-method').forEach(method => {
        method.addEventListener('click', function() {
            document.querySelectorAll('.withdraw-payment-method').forEach(m => m.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Continue withdrawal button
    document.querySelector('#submitWithdraw').addEventListener('click', function() {
        const accountName = document.getElementById('withdrawAccountName').value.trim();
        const accountNumber = document.getElementById('withdrawAccountNumber').value.trim();
        const selectedMethod = document.querySelector('.withdraw-payment-method.active');
        const amount = document.getElementById('withdrawAmount').value.trim();

        if (!accountName) {
            alert('Please enter your account name');
            return;
        }

        if (!accountNumber) {
            alert('Please enter your account number');
            return;
        }

        if (!selectedMethod) {
            alert('Please select a payment method');
            return;
        }

        if (!amount || parseFloat(amount) < 1) {
            alert('Please enter a valid amount (minimum $1)');
            return;
        }

        // Here you would typically submit the form or navigate to next step
        console.log('Withdrawal:', {
            accountName: accountName,
            accountNumber: accountNumber,
            method: selectedMethod.dataset.method,
            amount: amount
        });

        // Show success message
        alert('Withdrawal request submitted successfully!');
    });
</script>
@endsection


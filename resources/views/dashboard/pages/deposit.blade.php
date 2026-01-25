@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Add Money')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/dashboard.css') }}">
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
        font-size: 2.75rem;
        font-weight: 800;
        color: var(--text-primary);
        margin: 0;
        letter-spacing: -1.5px;
        line-height: 1.2;
        background: linear-gradient(135deg, var(--text-primary) 0%, var(--primary-color) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
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
        border-radius: 20px;
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .deposit-section-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        border-color: rgba(255, 178, 30, 0.3);
    }

    .deposit-section-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #FFB21E 0%, #FF8A1D 50%, #FFB21E 100%);
        background-size: 200% 100%;
        animation: shimmer 3s linear infinite;
        box-shadow: 0 2px 10px rgba(255, 178, 30, 0.5);
    }

    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .deposit-section-title {
        font-size: 1.375rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 1.75rem 0;
        letter-spacing: -0.3px;
        line-height: 1.3;
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
        border-radius: 16px;
        padding: 1.75rem 1.5rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .deposit-payment-method::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 178, 30, 0.1), transparent);
        transition: left 0.5s;
    }

    .deposit-payment-method:hover {
        background: rgba(255, 178, 30, 0.12);
        border-color: var(--primary-color);
        transform: translateY(-3px);
        box-shadow: 0 6px 25px rgba(255, 178, 30, 0.35);
    }

    .deposit-payment-method:hover::before {
        left: 100%;
    }

    .deposit-payment-method.active {
        background: rgba(255, 178, 30, 0.18);
        border-color: #ffffff;
        border-width: 2px;
        box-shadow: 0 0 25px rgba(255, 178, 30, 0.5);
        transform: scale(1.02);
    }

    .deposit-payment-icon {
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

    .deposit-payment-icon img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 0.5rem;
    }

    .deposit-payment-method:hover .deposit-payment-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .deposit-payment-method.active .deposit-payment-icon {
        transform: scale(1.15);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    }

    .deposit-payment-method.easypaisa .deposit-payment-icon {
        background: rgba(255, 255, 255, 0.1);
    }

    .deposit-payment-method.jazzcash .deposit-payment-icon {
        background: rgba(255, 255, 255, 0.1);
    }

    .deposit-payment-method.crypto .deposit-payment-icon {
        background: rgba(255, 255, 255, 0.1);
    }

    .deposit-payment-method.bank .deposit-payment-icon {
        background: rgba(255, 255, 255, 0.1);
    }

    .deposit-payment-name {
        font-size: 1.0625rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        letter-spacing: -0.2px;
        transition: color 0.3s ease;
    }

    .deposit-payment-method:hover .deposit-payment-name {
        color: var(--primary-color);
    }

    /* Deposit Amount Section */
    .deposit-amount-section {
        display: none !important;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    .deposit-amount-section.show {
        display: block !important;
        opacity: 1;
    }

    /* Preset Amount Buttons */
    .deposit-preset-amounts {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.875rem;
        margin-bottom: 1.5rem;
    }

    .deposit-preset-btn {
        padding: 1rem 1.25rem;
        background: rgba(24, 27, 39, 0.9);
        border: 2px solid rgba(255, 178, 30, 0.2);
        border-radius: 12px;
        color: var(--text-primary);
        font-size: 1.0625rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: center;
    }

    .deposit-preset-btn:hover {
        background: rgba(255, 178, 30, 0.1);
        border-color: rgba(255, 178, 30, 0.4);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 178, 30, 0.2);
    }

    .deposit-preset-btn.active {
        background: rgba(255, 178, 30, 0.18);
        border-color: var(--primary-color);
        color: var(--primary-color);
        box-shadow: 0 0 20px rgba(255, 178, 30, 0.3);
    }

    /* Deposit Amount Input */
    .deposit-amount-wrapper {
        position: relative;
        margin-bottom: 1.25rem;
    }

    .deposit-amount-input {
        width: 100%;
        padding: 1.375rem 1.5rem 1.375rem 3.5rem;
        background: rgba(24, 27, 39, 0.9);
        border: 2px solid rgba(255, 178, 30, 0.25);
        border-radius: 14px;
        color: var(--text-primary);
        font-size: 1.375rem;
        font-weight: 700;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .deposit-amount-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(255, 178, 30, 0.15), 0 6px 25px rgba(255, 178, 30, 0.3);
        background: rgba(24, 27, 39, 1);
        transform: translateY(-1px);
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
        font-size: 1.375rem;
        font-weight: 700;
        text-shadow: 0 2px 8px rgba(255, 178, 30, 0.4);
        z-index: 1;
    }

    /* PKR Amount Display */
    .deposit-pkr-amount {
        margin-top: 0.75rem;
        padding: 0.2rem 1.25rem;
        background: rgba(24, 27, 39, 0.6);
        border-radius: 10px;
        text-align: center;
        animation: fadeIn 0.3s ease-in-out;
    }

    .deposit-pkr-amount #pkr-amount-text {
        color: var(--text-primary);
        font-size: 1.125rem;
        font-weight: 500;
        letter-spacing: 0.2px;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .deposit-continue-btn {
        width: 100%;
        padding: 1.375rem 1.5rem;
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
        border: none;
        border-radius: 14px;
        color: white;
        font-size: 1.1875rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        margin-top: 1.25rem;
        box-shadow: 0 4px 20px rgba(255, 178, 30, 0.35);
        position: relative;
        overflow: hidden;
        letter-spacing: 0.3px;
    }

    .deposit-continue-btn::before {
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

    .deposit-continue-btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .deposit-continue-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 35px rgba(255, 178, 30, 0.55);
    }

    .deposit-continue-btn:active {
        transform: translateY(-1px);
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
        gap: 1.25rem;
        color: var(--text-secondary);
        font-size: 0.96875rem;
        line-height: 1.45;
        padding: 0.75rem 0;
        transition: color 0.3s ease;
    }

    .deposit-instruction-item:hover {
        color: var(--text-primary);
    }

    .deposit-instruction-bullet {
        width: 10px;
        height: 10px;
        background: var(--primary-color);
        border-radius: 50%;
        margin-top: 0.625rem;
        flex-shrink: 0;
        box-shadow: 0 0 12px rgba(255, 178, 30, 0.6);
        transition: all 0.3s ease;
    }

    .deposit-instruction-item:hover .deposit-instruction-bullet {
        transform: scale(1.2);
        box-shadow: 0 0 16px rgba(255, 178, 30, 0.8);
    }

    /* Mobile deposit instructions enhanced styling */
    @media (max-width: 768px) {
        .deposit-instructions-list {
            gap: 0.1rem;
        }

        .deposit-instruction-item {
            font-size: 0.9375rem;
            line-height: 1.45;
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
        color: #000000;
        font-size: 0.875rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .deposit-filter-dropdown:hover {
        background: rgba(255, 178, 30, 0.2);
        border-color: var(--primary-color);
    }

    .deposit-filter-dropdown option {
        color: #000000;
        background: white;
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

    .deposit-transaction-card:hover {
        background: var(--card-bg-hover);
        border-color: rgba(255, 178, 30, 0.3);
        transform: translateY(-3px);
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.2);
    }

    .deposit-transaction-icon-wrapper {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: rgba(255, 178, 30, 0.12);
        border: 1px solid rgba(255, 178, 30, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(255, 178, 30, 0.15);
    }

    .deposit-transaction-card:hover .deposit-transaction-icon-wrapper {
        background: rgba(255, 178, 30, 0.18);
        border-color: rgba(255, 178, 30, 0.4);
        transform: scale(1.05);
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
        font-size: 0.8rem;
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
        margin: 0;
    }

    /* Status Colors */
    .deposit-transaction-status.status-success {
        color: #4CAF50;
    }

    .deposit-transaction-status.status-warning {
        color: #FFA500;
    }

    .deposit-transaction-status.status-danger {
        color: #FF4444;
    }

    /* Amount Colors */
    .deposit-transaction-amount.amount-success {
        color: #4CAF50;
    }

    .deposit-transaction-amount.amount-warning {
        color: #FFA500;
    }

    .deposit-transaction-amount.amount-danger {
        color: #FF4444;
    }

    .deposit-transaction-icon-wrapper.icon-approved {
        background: rgba(76, 175, 80, 0.12);
        border-color: rgba(76, 175, 80, 0.25);
    }

    .deposit-transaction-icon-wrapper.icon-approved i {
        color: #4CAF50;
    }

    .deposit-transaction-icon-wrapper.icon-pending {
        background: rgba(255, 178, 30, 0.12);
        border-color: rgba(255, 178, 30, 0.25);
    }

    .deposit-transaction-icon-wrapper.icon-pending i {
        color: var(--primary-color);
    }

    .deposit-transaction-icon-wrapper.icon-rejected {
        background: rgba(255, 68, 68, 0.12);
        border-color: rgba(255, 68, 68, 0.25);
    }

    .deposit-transaction-icon-wrapper.icon-rejected i {
        color: #FF4444;
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

    /* Advanced Search Modal */
    .deposit-advance-search-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        backdrop-filter: blur(5px);
    }

    .deposit-advance-search-modal.show {
        display: flex;
    }

    .deposit-advance-search-content {
        background: var(--card-bg);
        border-radius: 20px;
        padding: 2rem;
        width: 100%;
        max-width: 500px;
        position: relative;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        border: 1px solid var(--card-border);
    }

    .deposit-advance-search-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .deposit-advance-search-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .deposit-advance-search-close {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: rgba(255, 178, 30, 0.1);
        border: 1px solid rgba(255, 178, 30, 0.3);
        color: var(--text-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
    }

    .deposit-advance-search-close:hover {
        background: rgba(255, 178, 30, 0.2);
        border-color: var(--primary-color);
    }

    .deposit-advance-search-form {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .deposit-advance-search-field {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .deposit-advance-search-label {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .deposit-advance-search-date-wrapper {
        position: relative;
    }

    .deposit-advance-search-date-input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 3rem;
        background: rgba(24, 27, 39, 0.9);
        border: 1px solid rgba(255, 178, 30, 0.2);
        border-radius: 10px;
        color: var(--text-primary);
        font-size: 0.9375rem;
        transition: var(--transition);
    }

    .deposit-advance-search-date-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 15px rgba(255, 178, 30, 0.2);
    }

    .deposit-advance-search-date-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 1rem;
    }

    .deposit-advance-search-sort-wrapper {
        position: relative;
    }

    .deposit-advance-search-sort {
        width: 100%;
        padding: 0.875rem 1rem;
        background: rgba(24, 27, 39, 0.9);
        border: 1px solid rgba(255, 178, 30, 0.2);
        border-radius: 10px;
        color: #000000;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: var(--transition);
        appearance: none;
        padding-right: 3rem;
    }

    .deposit-advance-search-sort:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 15px rgba(255, 178, 30, 0.2);
    }

    .deposit-advance-search-sort option {
        color: #000000;
        background: white;
    }

    .deposit-advance-search-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .deposit-advance-search-apply {
        flex: 1;
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, #FF00FF 0%, #FF1493 100%);
        border: none;
        border-radius: 12px;
        color: white;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: var(--transition);
    }

    .deposit-advance-search-apply:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 0, 255, 0.4);
    }

    .deposit-advance-search-clear {
        flex: 1;
        padding: 1rem 1.5rem;
        background: rgba(24, 27, 39, 0.9);
        border: 1px solid rgba(255, 178, 30, 0.2);
        border-radius: 12px;
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: var(--transition);
    }

    .deposit-advance-search-clear:hover {
        background: rgba(255, 178, 30, 0.1);
        border-color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .deposit-advance-search-content {
            padding: 1.5rem;
            border-radius: 16px;
        }

        .deposit-advance-search-title {
            font-size: 1.25rem;
        }

        .deposit-advance-search-buttons {
            flex-direction: column;
        }
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

    /* Banner Image Section */
    .deposit-banner {
        width: 100%;
        margin-bottom: 2rem;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    }

    .deposit-banner img {
        width: 100%;
        height: auto;
        display: block;
        object-fit: cover;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        /* Hide deposit title on mobile */
        .deposit-header {
            display: none;
        }

        .deposit-banner {
            margin-bottom: 1.5rem;
            border-radius: 16px;
        }

        .deposit-content-grid {
            margin-top: 0;
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }

        /* Mobile: Stack form sections vertically with better spacing */
        .deposit-form-section {
            gap: 1.25rem;
        }

        /* Mobile: Payment methods in single column for better touch targets */
        .deposit-payment-methods {
            grid-template-columns: 1fr;
            gap: 0.875rem;
        }

        .deposit-payment-method {
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            text-align: left;
        }

        .deposit-payment-icon {
            width: 56px;
            height: 56px;
            margin: 0;
            flex-shrink: 0;
        }

        .deposit-payment-name {
            font-size: 1rem;
            margin: 0;
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
            color: #000000;
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23000000' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
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

        .deposit-filter-dropdown option {
            color: #000000;
            background: white;
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
            font-size: 0.8rem;
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

        /* Payment methods already handled above in mobile section */

        .deposit-section-card {
            padding: 1.5rem;
        }

        .deposit-section-title {
            font-size: 1.125rem;
            margin-bottom: 1.25rem;
        }

        .deposit-preset-amounts {
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .deposit-preset-btn {
            padding: 0.875rem 1rem;
            font-size: 0.9375rem;
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
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .deposit-payment-method {
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 0.875rem;
            text-align: left;
        }

        .deposit-payment-icon {
            width: 48px;
            height: 48px;
            margin: 0;
            flex-shrink: 0;
        }

        .deposit-payment-name {
            font-size: 0.875rem;
            margin: 0;
        }

        .deposit-preset-amounts {
            grid-template-columns: repeat(3, 1fr);
            gap: 0.625rem;
            margin-bottom: 1rem;
        }

        .deposit-preset-btn {
            padding: 0.75rem 0.875rem;
            font-size: 0.875rem;
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
        <h1 class="deposit-title">Add Money</h1>
    </div>

    <!-- Banner Image -->
    {{-- <div class="deposit-banner">
        <img src="{{ asset('assets/dashboard/images/payment-method/bank.png') }}" alt="Deposit Banner">
    </div> --}}

    <!-- Main Content Grid -->
    <div class="deposit-content-grid">
        <!-- Left Panel - Deposit Form -->
        <div class="deposit-form-section">
            <!-- Payment Method Selection -->
            <div class="deposit-section-card">
                <h2 class="deposit-section-title">Selected Payment Method</h2>
                <div class="deposit-payment-methods">
                    @forelse($paymentMethods as $paymentMethod)
                        <div class="deposit-payment-method"
                             data-method-id="{{ $paymentMethod->id }}"
                             data-method-name="{{ $paymentMethod->account_type }}"
                             data-method-type="{{ $paymentMethod->type }}"
                             data-min-deposit="{{ $paymentMethod->minimum_deposit ?? 0 }}"
                             data-max-deposit="{{ $paymentMethod->maximum_deposit ?? 0 }}">
                            <div class="deposit-payment-icon">
                                @if($paymentMethod->image)
                                    <img src="{{ asset($paymentMethod->image) }}" alt="{{ $paymentMethod->account_type }}">
                                @else
                                    @if($paymentMethod->type === 'crypto')
                                        <i class="fab fa-bitcoin" style="font-size: 2rem; color: #F7931A;"></i>
                                    @else
                                        <i class="fab fa-bitcoin"></i>
                                    @endif
                                @endif
                            </div>
                            <p class="deposit-payment-name">{{ $paymentMethod->account_type }}</p>
                        </div>
                    @empty
                        <p style="color: var(--text-secondary); padding: 1rem; text-align: center;">
                            No payment methods available at the moment.
                        </p>
                    @endforelse
                </div>
            </div>

            <!-- Deposit Amount -->
            <div class="deposit-section-card deposit-amount-section">
                <h2 class="deposit-section-title">Deposit Amount</h2>

                <!-- Preset Amount Buttons -->
                <div class="deposit-preset-amounts">
                    <button type="button" class="deposit-preset-btn" data-amount="5">$5</button>
                    <button type="button" class="deposit-preset-btn" data-amount="25">$25</button>
                    <button type="button" class="deposit-preset-btn" data-amount="50">$50</button>
                    <button type="button" class="deposit-preset-btn" data-amount="100">$100</button>
                    <button type="button" class="deposit-preset-btn" data-amount="500">$500</button>
                    <button type="button" class="deposit-preset-btn" data-amount="1000">$1K</button>
                </div>

                <!-- Custom Amount Input -->
                <div class="deposit-amount-wrapper">
                    <span class="deposit-amount-symbol">$</span>
                    <input type="number"
                           class="deposit-amount-input"
                           id="deposit-amount-input"
                           placeholder="Enter custom amount"
                           min="2"
                           >
                </div>
                <!-- PKR Amount Display -->
                <div class="deposit-pkr-amount" id="deposit-pkr-amount" style="display: none;">
                    <span id="pkr-amount-text"></span>
                </div>
                @if(!$conversionRate || $conversionRate == 0)
                <div style="color: #ff6b6b; font-size: 0.875rem; margin-top: 0.5rem; text-align: center;">
                    Note: Currency conversion rate is not set. Please contact admin.
                </div>
                @endif
                <button class="deposit-continue-btn" id="deposit-continue-btn">
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
                        <span>Minimum deposit is $1</span>
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
                            <input type="text" class="deposit-search-input" id="deposit-search-input" placeholder="Search transactions...">
                            <button class="deposit-search-filter-btn" type="button">
                                <i class="fas fa-filter"></i>
                            </button>
                        </div>
                    </div>
                    <div class="deposit-history-filters">
                        <div class="deposit-filter-icon">
                            <i class="fas fa-filter"></i>
                        </div>
                        <select class="deposit-filter-dropdown" id="deposit-date-filter">
                            <option value="3">3 Days</option>
                            <option value="7">7 Days</option>
                            <option value="30">30 Days</option>
                            <option value="all" selected>All Time</option>
                        </select>
                    </div>
                </div>
                <!-- Transaction Cards (for desktop and mobile) -->
                <div class="deposit-transactions-list" id="deposit-transactions-list">
                    @forelse($deposits as $deposit)
                        <div class="deposit-transaction-card" data-date="{{ $deposit->created_at->timestamp }}" data-status="{{ $deposit->status }}" data-amount="{{ $deposit->amount }}" data-transaction-id="{{ $deposit->transaction_id }}">
                            <div class="deposit-transaction-icon-wrapper
                                @if($deposit->status === 'approved') icon-approved
                                @elseif($deposit->status === 'rejected') icon-rejected
                                @else icon-pending
                                @endif">
                                @if($deposit->status === 'approved')
                                    <i class="fas fa-check-circle"></i>
                                @elseif($deposit->status === 'rejected')
                                    <i class="fas fa-times-circle"></i>
                                @else
                                    <i class="fas fa-clock"></i>
                                @endif
                            </div>
                            <div class="deposit-transaction-content">
                                <h3 class="deposit-transaction-title">
                                    {{ $deposit->paymentMethod->account_type ?? 'Deposit' }}
                                </h3>
                                <p class="deposit-transaction-date">{{ $deposit->created_at->format('M d, Y, h:i A') }}</p>
                            </div>
                            <div class="deposit-transaction-right">
                                <div class="deposit-transaction-amount
                                    @if($deposit->status === 'approved') amount-success
                                    @elseif($deposit->status === 'rejected') amount-danger
                                    @else amount-warning
                                    @endif">+${{ number_format(sprintf('%.2f', (float)$deposit->amount), 2, '.', ',') }}</div>
                                @if($deposit->status === 'approved')
                                    <p class="deposit-transaction-wallet">Fund Wallet: ${{ number_format(sprintf('%.2f', (float)(auth()->user()->fund_wallet ?? 0)), 2, '.', ',') }}</p>
                                @else
                                    <p class="deposit-transaction-wallet">Fund Wallet: -</p>
                                @endif
                                <span class="deposit-transaction-status
                                    @if($deposit->status === 'approved') status-success
                                    @elseif($deposit->status === 'rejected') status-danger
                                    @else status-warning
                                    @endif">
                                    @if($deposit->status === 'approved')
                                        Completed
                                    @elseif($deposit->status === 'rejected')
                                        Rejected
                                    @else
                                        Pending
                                    @endif
                                </span>
                            </div>
                        </div>
                    @empty
                        <!-- Empty state will be shown by CSS -->
                    @endforelse
                </div>
                <div class="deposit-history-empty" id="deposit-history-empty" style="{{ $deposits->count() > 0 ? 'display: none;' : '' }}">
                    No transaction history found!
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Advance Search Modal -->
<div class="deposit-advance-search-modal" id="deposit-advance-search-modal">
    <div class="deposit-advance-search-content">
        <div class="deposit-advance-search-header">
            <h3 class="deposit-advance-search-title">Advance Search</h3>
            <button class="deposit-advance-search-close" id="deposit-advance-search-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="deposit-advance-search-form">
            <div class="deposit-advance-search-field">
                <label class="deposit-advance-search-label">Select start & end date:</label>
                <div class="deposit-advance-search-date-wrapper">
                    <i class="fas fa-calendar deposit-advance-search-date-icon"></i>
                    <input type="text"
                           class="deposit-advance-search-date-input"
                           id="deposit-date-range-input"
                           placeholder="dd/mm/yyyy - dd/mm/yyyy"
                           readonly>
                    <input type="date"
                           id="deposit-start-date"
                           style="position: absolute; opacity: 0; width: 1px; height: 1px; pointer-events: none;">
                    <input type="date"
                           id="deposit-end-date"
                           style="position: absolute; opacity: 0; width: 1px; height: 1px; pointer-events: none;">
                </div>
            </div>
            <div class="deposit-advance-search-field">
                <label class="deposit-advance-search-label">Sort:</label>
                <div class="deposit-advance-search-sort-wrapper">
                    <select class="deposit-advance-search-sort" id="deposit-advance-sort">
                        <option value="newest">Newest</option>
                        <option value="oldest">Oldest</option>
                        <option value="amount-high">Amount: High to Low</option>
                        <option value="amount-low">Amount: Low to High</option>
                    </select>
                </div>
            </div>
            <div class="deposit-advance-search-buttons">
                <button type="button" class="deposit-advance-search-apply" id="deposit-advance-apply">
                    Apply Filters
                </button>
                <button type="button" class="deposit-advance-search-clear" id="deposit-advance-clear">
                    Clear Filter
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let selectedPaymentMethod = null;
    const conversionRate = parseFloat({{ $conversionRate ?? 0 }}) || 0;

    // Function to update PKR amount display
    function updatePKRAmount() {
        const amountInput = document.getElementById('deposit-amount-input');
        const pkrAmountDisplay = document.getElementById('deposit-pkr-amount');
        const pkrAmountText = document.getElementById('pkr-amount-text');

        if (!amountInput || !pkrAmountDisplay || !pkrAmountText) {
            return;
        }

        const amount = parseFloat(amountInput.value) || 0;
        const rate = conversionRate;

        // Show PKR amount only if payment method is selected, amount is valid, and conversion rate exists
        if (selectedPaymentMethod && amount > 0 && rate > 0) {
            const pkrAmount = amount * rate;
            // Format dollar amount, remove trailing zeros
            const formattedUSD = amount.toLocaleString('en-US', {
                maximumFractionDigits: 2,
                minimumFractionDigits: 0
            });
            // Format PKR amount with commas, remove trailing zeros
            const formattedPKR = pkrAmount.toLocaleString('en-US', {
                maximumFractionDigits: 2,
                minimumFractionDigits: 0
            });
            pkrAmountText.textContent = `$${formattedUSD} = Rs ${formattedPKR}`;
            pkrAmountDisplay.style.display = 'block';
        } else {
            pkrAmountDisplay.style.display = 'none';
        }
    }

    // Payment method selection
    document.querySelectorAll('.deposit-payment-method').forEach(method => {
        method.addEventListener('click', function() {
            // Remove active class from all methods
            document.querySelectorAll('.deposit-payment-method').forEach(m => m.classList.remove('active'));

            // Add active class to clicked method
            this.classList.add('active');

            // Store selected payment method data
            selectedPaymentMethod = {
                id: this.dataset.methodId,
                name: this.dataset.methodName,
                type: this.dataset.methodType || 'rast',
                minDeposit: parseFloat(this.dataset.minDeposit) || 2,
                maxDeposit: parseFloat(this.dataset.maxDeposit) || null
            };

            // Show deposit amount section with animation
            const depositAmountSection = document.querySelector('.deposit-amount-section');
            if (depositAmountSection) {
                depositAmountSection.classList.add('show');
            }

            // Update continue button text
            const continueBtn = document.getElementById('deposit-continue-btn');
            if (continueBtn && selectedPaymentMethod) {
                continueBtn.textContent = `Continue Deposit with ${selectedPaymentMethod.name}`;
            }

            // Update input min/max attributes
            const amountInput = document.getElementById('deposit-amount-input');
            if (amountInput) {
                amountInput.setAttribute('min', selectedPaymentMethod.minDeposit);
                if (selectedPaymentMethod.maxDeposit) {
                    amountInput.setAttribute('max', selectedPaymentMethod.maxDeposit);
                } else {
                    amountInput.removeAttribute('max');
                }
                // Clear previous amount and preset selections
                amountInput.value = '';
                document.querySelectorAll('.deposit-preset-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
            }
            // Update PKR amount display (will hide if amount is cleared)
            updatePKRAmount();
        });
    });

    // Preset amount buttons
    document.querySelectorAll('.deposit-preset-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all preset buttons
            document.querySelectorAll('.deposit-preset-btn').forEach(b => b.classList.remove('active'));

            // Add active class to clicked button
            this.classList.add('active');

            // Set the amount in the input field
            const amount = this.dataset.amount;
            const amountInput = document.getElementById('deposit-amount-input');
            if (amountInput) {
                amountInput.value = amount;
                // Update PKR amount display immediately
                updatePKRAmount();
                // Also trigger input event for other validations
                amountInput.dispatchEvent(new Event('input'));
            }
        });
    });

    // Clear preset selection when user types custom amount
    const amountInput = document.getElementById('deposit-amount-input');
    if (amountInput) {
        amountInput.addEventListener('input', function() {
            // Check if the value matches any preset
            const value = parseFloat(this.value);
            const presetButtons = document.querySelectorAll('.deposit-preset-btn');
            let matchesPreset = false;

            presetButtons.forEach(btn => {
                const presetAmount = parseFloat(btn.dataset.amount);
                if (value === presetAmount) {
                    btn.classList.add('active');
                    matchesPreset = true;
                } else {
                    btn.classList.remove('active');
                }
            });

            // If doesn't match any preset, clear all active states
            if (!matchesPreset && this.value !== '') {
                presetButtons.forEach(btn => btn.classList.remove('active'));
            }

            // Update PKR amount display
            updatePKRAmount();
        });
    }

    // Continue deposit button
    const continueBtn = document.getElementById('deposit-continue-btn');
    if (continueBtn) {
        continueBtn.addEventListener('click', function() {
            const amountInput = document.getElementById('deposit-amount-input');
            const amount = amountInput ? parseFloat(amountInput.value) : 0;

            if (!selectedPaymentMethod) {
                alert('Please select a payment method');
                return;
            }

            if (!amount || isNaN(amount) || amount < selectedPaymentMethod.minDeposit) {
                alert(`Please enter a valid amount (minimum $${selectedPaymentMethod.minDeposit})`);
                return;
            }

            if (selectedPaymentMethod.maxDeposit && amount > selectedPaymentMethod.maxDeposit) {
                alert(`Maximum deposit amount is $${selectedPaymentMethod.maxDeposit}`);
                return;
            }

            // Redirect to deposit confirmation page
            const confirmUrl = '{{ route("deposit.confirm") }}' +
                '?method_id=' + encodeURIComponent(selectedPaymentMethod.id) +
                '&amount=' + encodeURIComponent(amount);

            window.location.href = confirmUrl;
        });
    }

    // Deposit History Filtering and Search
    const depositSearchInput = document.getElementById('deposit-search-input');
    const depositDateFilter = document.getElementById('deposit-date-filter');
    const depositTransactionsList = document.getElementById('deposit-transactions-list');
    const depositHistoryEmpty = document.getElementById('deposit-history-empty');

    // Advance Search Modal Elements
    const advanceSearchModal = document.getElementById('deposit-advance-search-modal');
    const advanceSearchClose = document.getElementById('deposit-advance-search-close');
    const advanceSearchApply = document.getElementById('deposit-advance-apply');
    const advanceSearchClear = document.getElementById('deposit-advance-clear');
    const dateRangeInput = document.getElementById('deposit-date-range-input');
    const advanceSortSelect = document.getElementById('deposit-advance-sort');

    // Filter state
    let dateRangeFilter = null;
    let sortOrder = 'newest';

    // Date range picker
    let startDate = null;
    let endDate = null;
    const startDateInput = document.getElementById('deposit-start-date');
    const endDateInput = document.getElementById('deposit-end-date');

    function updateDateRangeDisplay() {
        if (startDate && endDate) {
            const formatDate = (date) => {
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                return `${day}/${month}/${year}`;
            };
            dateRangeInput.value = `${formatDate(startDate)} - ${formatDate(endDate)}`;
            dateRangeFilter = {
                start: Math.floor(startDate.getTime() / 1000),
                end: Math.floor(endDate.getTime() / 1000) + 86400 // Add one day to include the end date
            };
        } else {
            dateRangeInput.value = '';
            dateRangeFilter = null;
        }
    }

    if (dateRangeInput) {
        dateRangeInput.addEventListener('click', function() {
            if (startDateInput && typeof startDateInput.showPicker === 'function') {
                startDateInput.showPicker();
            } else {
                startDateInput.click();
            }
        });
    }

    if (startDateInput) {
        startDateInput.addEventListener('change', function() {
            startDate = new Date(this.value);
            if (endDateInput) {
                endDateInput.min = this.value;
                if (endDate && endDate < startDate) {
                    endDate = null;
                    endDateInput.value = '';
                }
                updateDateRangeDisplay();
                // Automatically open end date picker
                setTimeout(() => {
                    if (typeof endDateInput.showPicker === 'function') {
                        endDateInput.showPicker();
                    } else {
                        endDateInput.click();
                    }
                }, 100);
            } else {
                updateDateRangeDisplay();
            }
        });
    }

    if (endDateInput) {
        endDateInput.addEventListener('change', function() {
            endDate = new Date(this.value);
            updateDateRangeDisplay();
        });
    }

    function filterDeposits() {
        const searchTerm = depositSearchInput ? depositSearchInput.value.toLowerCase().trim() : '';
        const dateFilter = depositDateFilter ? depositDateFilter.value : 'all';
        const transactionCards = depositTransactionsList ? Array.from(depositTransactionsList.querySelectorAll('.deposit-transaction-card')) : [];

        let visibleCount = 0;
        const now = Math.floor(Date.now() / 1000);
        const daysInSeconds = {
            '3': 3 * 24 * 60 * 60,
            '7': 7 * 24 * 60 * 60,
            '30': 30 * 24 * 60 * 60
        };

        // Filter cards
        const filteredCards = transactionCards.filter(card => {
            const transactionDate = parseInt(card.dataset.date);
            const transactionStatus = card.dataset.status.toLowerCase();
            const transactionAmount = parseFloat(card.dataset.amount);
            const transactionId = (card.dataset.transactionId || '').toLowerCase();
            const cardText = card.textContent.toLowerCase();

            // Date filter (from dropdown)
            let dateMatch = true;
            if (dateFilter !== 'all' && !dateRangeFilter) {
                const daysAgo = daysInSeconds[dateFilter];
                const cutoffDate = now - daysAgo;
                dateMatch = transactionDate >= cutoffDate;
            }

            // Date range filter (from advance search)
            if (dateRangeFilter) {
                dateMatch = transactionDate >= dateRangeFilter.start && transactionDate <= dateRangeFilter.end;
            }

            // Search filter
            let searchMatch = true;
            if (searchTerm) {
                searchMatch = cardText.includes(searchTerm) ||
                             transactionId.includes(searchTerm) ||
                             transactionAmount.toString().includes(searchTerm);
            }

            return dateMatch && searchMatch;
        });

        // Sort filtered cards
        filteredCards.sort((a, b) => {
            const dateA = parseInt(a.dataset.date);
            const dateB = parseInt(b.dataset.date);
            const amountA = parseFloat(a.dataset.amount);
            const amountB = parseFloat(b.dataset.amount);

            switch(sortOrder) {
                case 'newest':
                    return dateB - dateA;
                case 'oldest':
                    return dateA - dateB;
                case 'amount-high':
                    return amountB - amountA;
                case 'amount-low':
                    return amountA - amountB;
                default:
                    return dateB - dateA;
            }
        });

        // Show/hide cards
        transactionCards.forEach(card => {
            if (filteredCards.includes(card)) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Reorder visible cards
        filteredCards.forEach(card => {
            depositTransactionsList.appendChild(card);
        });

        // Show/hide empty state
        if (depositHistoryEmpty) {
            if (visibleCount === 0) {
                depositHistoryEmpty.style.display = 'block';
            } else {
                depositHistoryEmpty.style.display = 'none';
            }
        }
    }

    // Open advance search modal
    function openAdvanceSearchModal() {
        if (advanceSearchModal) {
            advanceSearchModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    // Close advance search modal
    function closeAdvanceSearchModal() {
        if (advanceSearchModal) {
            advanceSearchModal.classList.remove('show');
            document.body.style.overflow = '';
        }
    }

    // Apply filters from advance search
    if (advanceSearchApply) {
        advanceSearchApply.addEventListener('click', function() {
            sortOrder = advanceSortSelect ? advanceSortSelect.value : 'newest';
            filterDeposits();
            closeAdvanceSearchModal();
        });
    }

    // Clear filters
    if (advanceSearchClear) {
        advanceSearchClear.addEventListener('click', function() {
            startDate = null;
            endDate = null;
            dateRangeFilter = null;
            if (dateRangeInput) {
                dateRangeInput.value = '';
            }
            if (advanceSortSelect) {
                advanceSortSelect.value = 'newest';
            }
            sortOrder = 'newest';
            filterDeposits();
            closeAdvanceSearchModal();
        });
    }

    // Close modal handlers
    if (advanceSearchClose) {
        advanceSearchClose.addEventListener('click', closeAdvanceSearchModal);
    }

    if (advanceSearchModal) {
        advanceSearchModal.addEventListener('click', function(e) {
            if (e.target === advanceSearchModal) {
                closeAdvanceSearchModal();
            }
        });
    }

    // Open modal from filter icons
    const filterIcons = document.querySelectorAll('.deposit-filter-icon, .deposit-search-filter-btn');
    filterIcons.forEach(icon => {
        icon.addEventListener('click', openAdvanceSearchModal);
    });

    // Add event listeners
    if (depositSearchInput) {
        depositSearchInput.addEventListener('input', filterDeposits);
    }

    if (depositDateFilter) {
        depositDateFilter.addEventListener('change', filterDeposits);
    }

    // Initial filter
    filterDeposits();
</script>
@endsection


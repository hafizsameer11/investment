@extends('dashboard.layouts.main')

@section('title', 'Core Mining ⛏️- AI Gold Mining ⛏️')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/dashboard.css') }}">
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
        background: linear-gradient(135deg, var(--text-primary) 0%, var(--primary-color) 100%);
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
        border-color: rgba(255, 178, 30, 0.3);
    }

    .withdraw-section-card::before {
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

    .withdraw-section-title {
        font-size: 1.375rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 1.75rem 0;
        letter-spacing: -0.3px;
        line-height: 1.3;
    }

    /* Pending Withdrawal Warning */
    .pending-withdrawal-warning {
        background: rgba(255, 178, 30, 0.1);
        border: 2px solid rgba(255, 178, 30, 0.4);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .pending-withdrawal-warning-content {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .pending-withdrawal-icon {
        font-size: 1.5rem;
        color: #FFB21E;
        flex-shrink: 0;
        margin-top: 0.125rem;
    }

    .pending-withdrawal-text {
        flex: 1;
    }

    .pending-withdrawal-title {
        margin: 0 0 0.5rem 0;
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.3;
    }

    .pending-withdrawal-message {
        margin: 0;
        color: var(--text-secondary);
        font-size: 0.875rem;
        line-height: 1.5;
    }

    /* Payment Methods Grid */
    .withdraw-payment-methods {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .withdraw-payment-method {
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

    .withdraw-payment-method::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 178, 30, 0.1), transparent);
        transition: left 0.5s;
    }

    .withdraw-payment-method:hover {
        background: rgba(255, 178, 30, 0.12);
        border-color: var(--primary-color);
        transform: translateY(-3px);
        box-shadow: 0 6px 25px rgba(255, 178, 30, 0.35);
    }

    .withdraw-payment-method:hover::before {
        left: 100%;
    }

    .withdraw-payment-method.active {
        background: rgba(255, 178, 30, 0.18);
        border-color: #ffffff;
        border-width: 2px;
        box-shadow: 0 0 25px rgba(255, 178, 30, 0.5);
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

    .withdraw-payment-name {
        font-size: 1.0625rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        letter-spacing: -0.2px;
        transition: color 0.3s ease;
    }

    .withdraw-payment-method:hover .withdraw-payment-name {
        color: var(--primary-color);
    }

    /* Withdraw Amount Section */
    .withdraw-section-card.withdraw-amount-section {
        display: none !important;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    .withdraw-section-card.withdraw-amount-section.show {
        display: block !important;
        opacity: 1;
    }

    /* Preset Amount Buttons */
    .withdraw-preset-amounts {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.875rem;
        margin-bottom: 1.5rem;
    }

    .withdraw-preset-btn {
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

    .withdraw-preset-btn:hover {
        background: rgba(255, 178, 30, 0.1);
        border-color: rgba(255, 178, 30, 0.4);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 178, 30, 0.2);
    }

    .withdraw-preset-btn.active {
        background: rgba(255, 178, 30, 0.18);
        border-color: var(--primary-color);
        color: var(--primary-color);
        box-shadow: 0 0 20px rgba(255, 178, 30, 0.3);
    }

    /* Withdraw Amount Input */
    .withdraw-amount-wrapper {
        position: relative;
        margin-bottom: 1.25rem;
    }

    .withdraw-amount-input {
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

    .withdraw-amount-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(255, 178, 30, 0.15), 0 6px 25px rgba(255, 178, 30, 0.3);
        background: rgba(24, 27, 39, 1);
        transform: translateY(-1px);
    }

    .withdraw-amount-input::placeholder {
        color: var(--text-muted);
    }

    .withdraw-amount-symbol {
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
    .withdraw-pkr-amount {
        margin-top: 0.75rem;
        padding: 0.2rem 1.25rem;
        background: rgba(24, 27, 39, 0.6);
        border-radius: 10px;
        text-align: center;
        animation: fadeIn 0.3s ease-in-out;
    }

    .withdraw-pkr-amount #withdraw-pkr-amount-text {
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

    .withdraw-continue-btn {
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

    .withdraw-continue-btn:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow: 0 8px 35px rgba(255, 178, 30, 0.55);
    }

    .withdraw-continue-btn:active {
        transform: translateY(-1px);
    }

    .withdraw-continue-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Right Panel - History */
    .withdraw-info-section {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    /* Withdrawal Instructions */
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
        line-height: 1.45;
        padding: 0.75rem 0;
        transition: color 0.3s ease;
    }

    .withdraw-instruction-item:hover {
        color: var(--text-primary);
    }

    .withdraw-instruction-bullet {
        width: 10px;
        height: 10px;
        background: var(--primary-color);
        border-radius: 50%;
        margin-top: 0.625rem;
        flex-shrink: 0;
        box-shadow: 0 0 12px rgba(255, 178, 30, 0.6);
        transition: all 0.3s ease;
    }

    .withdraw-instruction-item:hover .withdraw-instruction-bullet {
        transform: scale(1.2);
        box-shadow: 0 0 16px rgba(255, 178, 30, 0.8);
    }

    /* Withdrawal History */
    .withdraw-history-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
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
        background: rgba(255, 178, 30, 0.1);
        border: 1px solid rgba(255, 178, 30, 0.3);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
    }

    .withdraw-filter-icon:hover {
        background: rgba(255, 178, 30, 0.2);
        border-color: var(--primary-color);
    }

    .withdraw-filter-dropdown {
        padding: 0.625rem 1rem;
        background: rgba(255, 178, 30, 0.1);
        border: 1px solid rgba(255, 178, 30, 0.3);
        border-radius: 8px;
        color: #ffffff;
        font-size: 0.875rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .withdraw-filter-dropdown:hover {
        background: rgba(255, 178, 30, 0.2);
        border-color: var(--primary-color);
    }

    .withdraw-filter-dropdown option {
        color: #ffffff;
        background: #101828;
    }

    .withdraw-transactions-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
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
        border-color: rgba(255, 178, 30, 0.3);
        transform: translateY(-3px);
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.2);
    }

    .withdraw-transaction-icon-wrapper {
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

    .withdraw-transaction-card:hover .withdraw-transaction-icon-wrapper {
        background: rgba(255, 178, 30, 0.18);
        border-color: rgba(255, 178, 30, 0.4);
        transform: scale(1.05);
    }

    .withdraw-transaction-icon-wrapper i {
        color: var(--primary-color);
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
        margin: 0;
    }

    .withdraw-transaction-status {
        font-size: 0.875rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
    }

    .withdraw-transaction-status.pending {
        background: rgba(255, 178, 30, 0.1);
        color: var(--primary-color);
    }

    .withdraw-transaction-status.approved {
        background: rgba(76, 175, 80, 0.1);
        color: #4CAF50;
    }

    .withdraw-transaction-status.rejected {
        background: rgba(255, 68, 68, 0.1);
        color: #FF4444;
    }

    .withdraw-transaction-amount.amount-success {
        color: #4CAF50;
    }

    .withdraw-transaction-amount.amount-warning {
        color: var(--primary-color);
    }

    .withdraw-transaction-amount.amount-danger {
        color: #FF4444;
    }

    .withdraw-transaction-icon-wrapper.icon-approved {
        background: rgba(76, 175, 80, 0.12);
        border-color: rgba(76, 175, 80, 0.25);
    }

    .withdraw-transaction-icon-wrapper.icon-approved i {
        color: #4CAF50;
    }

    .withdraw-transaction-icon-wrapper.icon-pending {
        background: rgba(255, 178, 30, 0.12);
        border-color: rgba(255, 178, 30, 0.25);
    }

    .withdraw-transaction-icon-wrapper.icon-pending i {
        color: var(--primary-color);
    }

    .withdraw-transaction-icon-wrapper.icon-rejected {
        background: rgba(255, 68, 68, 0.12);
        border-color: rgba(255, 68, 68, 0.25);
    }

    .withdraw-transaction-icon-wrapper.icon-rejected i {
        color: #FF4444;
    }

    .withdraw-history-empty {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-muted);
        font-size: 1rem;
        display: none;
    }

    .withdraw-history-empty.show {
        display: block;
    }

    /* Search bar for mobile */
    .withdraw-search-bar {
        display: none;
    }

    .withdraw-search-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .withdraw-search-input {
        flex: 1;
        padding: 0.875rem 1rem 0.875rem 3rem;
        background: rgba(24, 27, 39, 0.8);
        border: 1px solid rgba(255, 178, 30, 0.2);
        border-radius: 10px;
        color: var(--text-primary);
        font-size: 0.9375rem;
        transition: var(--transition);
    }

    .withdraw-search-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 15px rgba(255, 178, 30, 0.2);
    }

    .withdraw-search-input::placeholder {
        color: var(--text-muted);
    }

    .withdraw-search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 1rem;
    }

    .withdraw-search-filter-btn {
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

    .withdraw-search-filter-btn:hover {
        background: rgba(255, 178, 30, 0.1);
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .withdraw-search-filter-btn i {
        font-size: 1rem;
    }

    /* Advanced Search Modal */
    .withdraw-advance-search-modal {
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

    .withdraw-advance-search-modal.show {
        display: flex;
    }

    .withdraw-advance-search-content {
        background: var(--card-bg);
        border-radius: 20px;
        padding: 2rem;
        width: 100%;
        max-width: 500px;
        position: relative;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        border: 1px solid var(--card-border);
    }

    .withdraw-advance-search-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .withdraw-advance-search-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .withdraw-advance-search-close {
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

    .withdraw-advance-search-close:hover {
        background: rgba(255, 178, 30, 0.2);
        border-color: var(--primary-color);
    }

    .withdraw-advance-search-form {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .withdraw-advance-search-field {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .withdraw-advance-search-label {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .withdraw-advance-search-date-wrapper {
        position: relative;
    }

    .withdraw-advance-search-date-input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 3rem;
        background: rgba(24, 27, 39, 0.9);
        border: 1px solid rgba(255, 178, 30, 0.2);
        border-radius: 10px;
        color: var(--text-primary);
        font-size: 0.9375rem;
        transition: var(--transition);
    }

    .withdraw-advance-search-date-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 15px rgba(255, 178, 30, 0.2);
    }

    .withdraw-advance-search-date-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 1rem;
    }

    .withdraw-advance-search-sort-wrapper {
        position: relative;
    }

    .withdraw-advance-search-sort {
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

    .withdraw-advance-search-sort:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 15px rgba(255, 178, 30, 0.2);
    }

    .withdraw-advance-search-sort option {
        color: #000000;
        background: white;
    }

    .withdraw-advance-search-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .withdraw-advance-search-apply {
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

    .withdraw-advance-search-apply:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 0, 255, 0.4);
    }

    .withdraw-advance-search-clear {
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

    .withdraw-advance-search-clear:hover {
        background: rgba(255, 178, 30, 0.1);
        border-color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .withdraw-search-bar {
            display: block;
        }

        .withdraw-history-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 2px;
            margin-bottom: 1.5rem;
        }

        .withdraw-history-filters {
            width: 100%;
            display: flex;
            align-items: center;
        }

        .withdraw-filter-icon {
            display: none;
        }

        .withdraw-filter-dropdown {
            width: 100%;
            padding: 0.875rem 1rem;
            background: rgba(24, 27, 39, 0.8);
            border: 1px solid rgba(255, 178, 30, 0.2);
            border-radius: 10px;
            color: #ffffff;
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23ffffff' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 3rem;
        }

        .withdraw-filter-dropdown:hover {
            background-color: rgba(255, 178, 30, 0.1);
            border-color: var(--primary-color);
        }

        .withdraw-filter-dropdown:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 15px rgba(255, 178, 30, 0.2);
        }

        .withdraw-filter-dropdown option {
            color: #ffffff;
            background: #101828;
        }

        .withdraw-advance-search-content {
            padding: 1.5rem;
            border-radius: 16px;
        }

        .withdraw-advance-search-title {
            font-size: 1.25rem;
        }

        .withdraw-advance-search-buttons {
            flex-direction: column;
        }
    }

    .withdraw-view-proof-btn {
        width: auto;
        padding: 0.375rem 1rem;
        background: rgba(255, 178, 30, 0.1);
        border: 1px solid rgba(255, 178, 30, 0.3);
        border-radius: 8px;
        color: var(--primary-color);
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        align-self: flex-end;
    }

    .withdraw-view-proof-btn i {
        font-size: 1rem;
    }

    .withdraw-view-proof-btn:hover {
        background: rgba(255, 178, 30, 0.2);
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 178, 30, 0.3);
    }

    /* Proof Image Modal */
    .proof-modal {
        display: none;
        position: fixed;
        z-index: 10000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        animation: fadeIn 0.3s ease;
    }

    .proof-modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .proof-modal-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
        margin: auto;
        animation: zoomIn 0.3s ease;
    }

    @keyframes zoomIn {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .proof-modal-image {
        width: 100%;
        height: auto;
        max-height: 90vh;
        object-fit: contain;
        border-radius: 12px;
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.5);
    }

    .proof-modal-close {
        position: absolute;
        top: -40px;
        right: 0;
        color: #fff;
        font-size: 2rem;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.1);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .proof-modal-close:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: var(--primary-color);
        transform: rotate(90deg);
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .withdraw-header {
            display: none;
        }

        .withdraw-content-grid {
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }

        .withdraw-form-section {
            gap: 1.25rem;
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

        .withdraw-section-card {
            padding: 1.5rem;
        }

        .withdraw-section-title {
            font-size: 1.125rem;
            margin-bottom: 1.25rem;
        }

        .withdraw-preset-amounts {
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .withdraw-preset-btn {
            padding: 0.875rem 1rem;
            font-size: 0.9375rem;
        }

        .withdraw-amount-input {
            padding: 1rem 1.25rem 1rem 2.5rem;
            font-size: 1.125rem;
        }

        .withdraw-amount-symbol {
            left: 1.25rem;
            font-size: 1.125rem;
        }

        .withdraw-continue-btn {
            padding: 1rem;
            font-size: 1rem;
            margin-top: 0.75rem;
        }

        .withdraw-info-section {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* Mobile withdrawal instructions enhanced styling */
        .withdraw-instructions-list {
            gap: 0.1rem;
        }

        .withdraw-instruction-item {
            font-size: 0.9375rem;
            line-height: 1.45;
        }

        .withdraw-instruction-bullet {
            width: 10px;
            height: 10px;
            margin-top: 0.375rem;
            box-shadow: 0 0 12px rgba(255, 178, 30, 0.7);
        }

        .withdraw-info-section .withdraw-section-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .pending-withdrawal-warning {
            padding: 1.25rem;
            margin-bottom: 1.25rem;
            border-radius: 12px;
        }

        .pending-withdrawal-warning-content {
            gap: 0.875rem;
        }

        .pending-withdrawal-icon {
            font-size: 1.25rem;
            margin-top: 0.125rem;
        }

        .pending-withdrawal-title {
            font-size: 0.9375rem;
            margin-bottom: 0.375rem;
        }

        .pending-withdrawal-message {
            font-size: 0.8125rem;
            line-height: 1.4;
        }
    }

    @media (max-width: 450px) {
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

        .withdraw-preset-amounts {
            grid-template-columns: repeat(3, 1fr);
            gap: 0.625rem;
            margin-bottom: 1rem;
        }

        .withdraw-preset-btn {
            padding: 0.75rem 0.875rem;
            font-size: 0.875rem;
        }

        .withdraw-amount-input {
            padding: 0.875rem 1rem 0.875rem 2.25rem;
            font-size: 1rem;
        }

        .withdraw-amount-symbol {
            left: 1rem;
            font-size: 1rem;
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
    <!-- Error Message Display -->
    @if(session('error'))
    <div class="withdraw-error-message" style="background: rgba(255, 68, 68, 0.1); border: 2px solid rgba(255, 68, 68, 0.3); border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 2rem; color: #FF4444; display: flex; align-items: center; gap: 0.75rem;">
        <i class="fas fa-exclamation-circle" style="font-size: 1.25rem;"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Withdraw Header -->
    <div class="withdraw-header">
        <h1 class="withdraw-title">Get Money</h1>
    </div>

    <!-- Main Content Grid -->
    <div class="withdraw-content-grid">
        <!-- Left Panel - Withdraw Form -->
        <div class="withdraw-form-section">
            @if($hasPendingWithdrawal ?? false)
            <!-- Pending Withdrawal Warning -->
            <div class="pending-withdrawal-warning">
                <div class="pending-withdrawal-warning-content">
                    <i class="fas fa-exclamation-triangle pending-withdrawal-icon"></i>
                    <div class="pending-withdrawal-text">
                        <h3 class="pending-withdrawal-title">Pending Withdrawal Request</h3>
                        <p class="pending-withdrawal-message">
                            Please wait for your current withdrawal to be processed before submitting a new request.
                        </p>
                    </div>
                </div>
            </div>
            @endif
            <!-- Payment Method Selection -->
            <div class="withdraw-section-card" @if($hasPendingWithdrawal ?? false) style="opacity: 0.6; pointer-events: none;" @endif>
                <h2 class="withdraw-section-title">Selected Payment Method</h2>
                <div class="withdraw-payment-methods">
                    @forelse($paymentMethods as $paymentMethod)
                        <div class="withdraw-payment-method"
                             data-method-id="{{ $paymentMethod->id }}"
                             data-method-name="{{ $paymentMethod->account_type }}"
                             data-method-type="{{ $paymentMethod->type }}"
                             data-min-withdrawal="{{ $paymentMethod->minimum_withdrawal_amount ?? 0 }}"
                             data-max-withdrawal="{{ $paymentMethod->maximum_withdrawal_amount ?? 0 }}">
                            <div class="withdraw-payment-icon">
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
                            <p class="withdraw-payment-name">{{ $paymentMethod->account_type }}</p>
                        </div>
                    @empty
                        <p style="color: var(--text-secondary); padding: 1rem; text-align: center; grid-column: 1 / -1;">
                            No withdrawal methods available at the moment.
                        </p>
                    @endforelse
                </div>
            </div>

            <!-- Withdraw Amount -->
            <div class="withdraw-section-card withdraw-amount-section" style="display: none;">
                <h2 class="withdraw-section-title">Withdraw Amount</h2>

                <!-- Preset Amount Buttons -->
                <div class="withdraw-preset-amounts">
                    <button type="button" class="withdraw-preset-btn" data-amount="5">$5</button>
                    <button type="button" class="withdraw-preset-btn" data-amount="25">$25</button>
                    <button type="button" class="withdraw-preset-btn" data-amount="50">$50</button>
                    <button type="button" class="withdraw-preset-btn" data-amount="100">$100</button>
                    <button type="button" class="withdraw-preset-btn" data-amount="500">$500</button>
                    <button type="button" class="withdraw-preset-btn" data-amount="1000">$1K</button>
                </div>

                <!-- Custom Amount Input -->
                <div class="withdraw-amount-wrapper">
                    <span class="withdraw-amount-symbol">$</span>
                    <input type="number"
                           class="withdraw-amount-input"
                           id="withdraw-amount-input"
                           placeholder="Enter custom amount"
                           min="0.01"
                           step="0.01">
                </div>
                <!-- PKR Amount Display -->
                <div class="withdraw-pkr-amount" id="withdraw-pkr-amount" style="display: none;">
                    <span id="withdraw-pkr-amount-text"></span>
                </div>
                @if(!$conversionRate || $conversionRate == 0)
                <div style="color: #ff6b6b; font-size: 0.875rem; margin-top: 0.5rem; text-align: center;">
                    Note: Currency conversion rate is not set. Please contact admin.
                </div>
                @endif
                {{-- <div id="withdraw-limit-info" style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 1rem; display: none;"></div> --}}
                <!-- Insufficient Balance Message -->
                <div id="withdraw-insufficient-balance-message" style="display: none; background: rgba(255, 68, 68, 0.1); border: 2px solid rgba(255, 68, 68, 0.3); border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 1rem; color: #FF4444; font-size: 0.875rem;">
                    <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i>
                    <span id="withdraw-insufficient-balance-text"></span>
                </div>
                <button class="withdraw-continue-btn" id="withdraw-continue-btn" disabled>
                    Continue Withdrawal
                </button>
            </div>
        </div>

        <!-- Right Panel - Instructions and History -->
        <div class="withdraw-info-section">
            <!-- Withdrawal Instructions -->
            <div class="withdraw-section-card">
                <h2 class="withdraw-section-title">Withdrawal Instructions</h2>
                <ul class="withdraw-instructions-list">
                    <li class="withdraw-instruction-item">
                        <span class="withdraw-instruction-bullet"></span>
                        <span>Please ensure your account details are correct before submitting a withdrawal request.</span>
                    </li>
                    <li class="withdraw-instruction-item">
                        <span class="withdraw-instruction-bullet"></span>
                        <span>Withdrawal requests are processed within 24-48 hours during business days.</span>
                    </li>
                    <li class="withdraw-instruction-item">
                        <span class="withdraw-instruction-bullet"></span>
                        <span>Note: Don't cancel the withdrawal request after submission.</span>
                    </li>
                    <li class="withdraw-instruction-item">
                        <span class="withdraw-instruction-bullet"></span>
                        <span id="withdraw-min-amount-text">Minimum withdrawal amount varies by payment method.</span>
                    </li>
                </ul>
            </div>

            <!-- Withdrawal History -->
            <div class="withdraw-section-card withdraw-history-card">
                <div class="withdraw-history-header">
                    <h2 class="withdraw-section-title">Withdrawal History</h2>
                    <!-- Search Bar (Mobile Only) -->
                    <div class="withdraw-search-bar">
                        <div class="withdraw-search-wrapper">
                            <i class="fas fa-search withdraw-search-icon"></i>
                            <input type="text" class="withdraw-search-input" id="withdraw-search-input" placeholder="Search transactions...">
                            <button class="withdraw-search-filter-btn" type="button">
                                <i class="fas fa-filter"></i>
                            </button>
                        </div>
                    </div>
                    <div class="withdraw-history-filters">
                        <div class="withdraw-filter-icon">
                            <i class="fas fa-filter"></i>
                        </div>
                        <select class="withdraw-filter-dropdown" id="withdraw-date-filter">
                            <option value="3">3 Days</option>
                            <option value="7">7 Days</option>
                            <option value="30">30 Days</option>
                            <option value="all" selected>All Time</option>
                        </select>
                    </div>
                </div>
                <!-- Transaction Cards -->
                <div class="withdraw-transactions-list" id="withdraw-transactions-list">
                    @forelse($withdrawals as $withdrawal)
                        <div class="withdraw-transaction-card" data-date="{{ $withdrawal->created_at->timestamp }}" data-status="{{ $withdrawal->status }}" data-amount="{{ $withdrawal->amount }}" data-transaction-id="{{ $withdrawal->id }}">
                            <div class="withdraw-transaction-icon-wrapper
                                @if($withdrawal->status === 'approved') icon-approved
                                @elseif($withdrawal->status === 'rejected') icon-rejected
                                @else icon-pending
                                @endif">
                                @if($withdrawal->status === 'approved')
                                    <i class="fas fa-check-circle"></i>
                                @elseif($withdrawal->status === 'rejected')
                                    <i class="fas fa-times-circle"></i>
                                @else
                                    <i class="fas fa-clock"></i>
                                @endif
                            </div>
                            <div class="withdraw-transaction-content">
                                <h3 class="withdraw-transaction-title">
                                    {{ $withdrawal->paymentMethod->account_type ?? 'Withdrawal' }}
                                </h3>
                                <p class="withdraw-transaction-date">{{ $withdrawal->created_at->format('M d, Y, h:i A') }}</p>
                                {{-- <p class="withdraw-transaction-date" style="font-size: 0.8125rem; margin-top: 0.25rem;">
                                    {{ $withdrawal->account_holder_name ?? 'N/A' }} - {{ $withdrawal->account_number ?? 'N/A' }}
                                </p> --}}
                            </div>
                            <div class="withdraw-transaction-right">
                                <div class="withdraw-transaction-amount
                                    @if($withdrawal->status === 'approved') amount-success
                                    @elseif($withdrawal->status === 'rejected') amount-danger
                                    @else amount-warning
                                    @endif">-${{ number_format($withdrawal->amount, 2) }}</div>
                                <span class="withdraw-transaction-status {{ $withdrawal->status }}">
                                    @if($withdrawal->status === 'approved')
                                        Approved
                                    @elseif($withdrawal->status === 'rejected')
                                        Rejected
                                    @else
                                        Pending
                                    @endif
                                </span>
                                @if($withdrawal->admin_proof_image)
                                    <button type="button"
                                            class="withdraw-view-proof-btn"
                                            onclick="openProofModal('{{ asset($withdrawal->admin_proof_image) }}')"
                                            title="View proof image">
                                        <i class="fas fa-image"></i>
                                        <span>View</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <!-- Empty state will be shown by CSS -->
                    @endforelse
                </div>
                <div class="withdraw-history-empty {{ $withdrawals->count() === 0 ? 'show' : '' }}" id="withdraw-history-empty">
                    No withdrawal history found!
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Proof Image Modal -->
<div id="proof-modal" class="proof-modal">
    <div class="proof-modal-content">
        <span class="proof-modal-close" onclick="closeProofModal()">&times;</span>
        <img id="proof-modal-image" class="proof-modal-image" src="" alt="Proof Image">
    </div>
</div>

<!-- Advance Search Modal -->
<div class="withdraw-advance-search-modal" id="withdraw-advance-search-modal">
    <div class="withdraw-advance-search-content">
        <div class="withdraw-advance-search-header">
            <h3 class="withdraw-advance-search-title">Advance Search</h3>
            <button class="withdraw-advance-search-close" id="withdraw-advance-search-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="withdraw-advance-search-form">
            <div class="withdraw-advance-search-field">
                <label class="withdraw-advance-search-label">Select start & end date:</label>
                <div class="withdraw-advance-search-date-wrapper">
                    <i class="fas fa-calendar withdraw-advance-search-date-icon"></i>
                    <input type="text"
                           class="withdraw-advance-search-date-input"
                           id="withdraw-date-range-input"
                           placeholder="dd/mm/yyyy - dd/mm/yyyy"
                           readonly>
                    <input type="date"
                           id="withdraw-start-date"
                           style="position: absolute; opacity: 0; width: 1px; height: 1px; pointer-events: none;">
                    <input type="date"
                           id="withdraw-end-date"
                           style="position: absolute; opacity: 0; width: 1px; height: 1px; pointer-events: none;">
                </div>
            </div>
            <div class="withdraw-advance-search-field">
                <label class="withdraw-advance-search-label">Sort:</label>
                <div class="withdraw-advance-search-sort-wrapper">
                    <select class="withdraw-advance-search-sort" id="withdraw-advance-sort">
                        <option value="newest">Newest</option>
                        <option value="oldest">Oldest</option>
                        <option value="amount-high">Amount: High to Low</option>
                        <option value="amount-low">Amount: Low to High</option>
                    </select>
                </div>
            </div>
            <div class="withdraw-advance-search-buttons">
                <button type="button" class="withdraw-advance-search-apply" id="withdraw-advance-apply">
                    Apply Filters
                </button>
                <button type="button" class="withdraw-advance-search-clear" id="withdraw-advance-clear">
                    Clear Filter
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let selectedPaymentMethod = null;
    const conversionRate = parseFloat({{ $conversionRate ?? 0 }}) || 0;
    const hasPendingWithdrawal = {{ ($hasPendingWithdrawal ?? false) ? 'true' : 'false' }};
    
    // Disable form elements if user has pending withdrawal
    if (hasPendingWithdrawal) {
        document.addEventListener('DOMContentLoaded', function() {
            // Disable all payment method selections
            document.querySelectorAll('.withdraw-payment-method').forEach(method => {
                method.style.pointerEvents = 'none';
                method.style.opacity = '0.6';
                method.style.cursor = 'not-allowed';
            });
            
            // Disable amount input
            const amountInput = document.getElementById('withdraw-amount-input');
            if (amountInput) {
                amountInput.disabled = true;
                amountInput.style.cursor = 'not-allowed';
            }
            
            // Disable preset amount buttons
            document.querySelectorAll('.withdraw-preset-btn').forEach(btn => {
                btn.disabled = true;
                btn.style.cursor = 'not-allowed';
                btn.style.opacity = '0.6';
            });
            
            // Disable continue button
            const continueBtn = document.getElementById('withdraw-continue-btn');
            if (continueBtn) {
                continueBtn.disabled = true;
                continueBtn.style.cursor = 'not-allowed';
            }
        });
    }
    
    // Crypto wallets data for minimum withdrawal calculation
    const cryptoWallets = @json($cryptoWallets ?? []);
    const cryptoFee = 1.00;

    // Function to update PKR amount display
    function updateWithdrawPKRAmount() {
        const amountInput = document.getElementById('withdraw-amount-input');
        const pkrAmountDisplay = document.getElementById('withdraw-pkr-amount');
        const pkrAmountText = document.getElementById('withdraw-pkr-amount-text');

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
    document.querySelectorAll('.withdraw-payment-method').forEach(method => {
        method.addEventListener('click', function() {
            // Remove active class from all methods
            document.querySelectorAll('.withdraw-payment-method').forEach(m => m.classList.remove('active'));

            // Add active class to clicked method
            this.classList.add('active');

            // Store selected payment method data
            let minWithdrawal = parseFloat(this.dataset.minWithdrawal) || 0;
            let maxWithdrawal = parseFloat(this.dataset.maxWithdrawal) || null;
            const methodType = this.dataset.methodType || 'rast';
            
            // For crypto payment methods, calculate minimum from crypto wallets (accounting for fee)
            if (methodType === 'crypto' && cryptoWallets.length > 0) {
                const cryptoMinWithdrawals = cryptoWallets
                    .filter(w => w.minimum_withdrawal)
                    .map(w => parseFloat(w.minimum_withdrawal));
                
                if (cryptoMinWithdrawals.length > 0) {
                    const minCryptoWithdrawal = Math.min(...cryptoMinWithdrawals);
                    // User needs to select minimum + fee to receive the minimum
                    minWithdrawal = minCryptoWithdrawal + cryptoFee;
                } else {
                    // No minimum set, but still need to account for fee
                    minWithdrawal = cryptoFee + 0.01; // At least $1.01 to receive $0.01
                }
                
                // For maximum, also account for fee if set
                if (cryptoWallets.some(w => w.maximum_withdrawal)) {
                    const cryptoMaxWithdrawals = cryptoWallets
                        .filter(w => w.maximum_withdrawal)
                        .map(w => parseFloat(w.maximum_withdrawal));
                    if (cryptoMaxWithdrawals.length > 0) {
                        const maxCryptoWithdrawal = Math.max(...cryptoMaxWithdrawals);
                        maxWithdrawal = maxCryptoWithdrawal + cryptoFee;
                    }
                }
            }
            
            selectedPaymentMethod = {
                id: this.dataset.methodId,
                name: this.dataset.methodName,
                type: methodType,
                minWithdrawal: minWithdrawal,
                maxWithdrawal: maxWithdrawal
            };

            // Show withdraw amount section with animation
            const withdrawAmountSection = document.querySelector('.withdraw-amount-section');
            if (withdrawAmountSection) {
                // Add show class
                withdrawAmountSection.classList.add('show');
                // Use inline styles to ensure visibility (overrides any CSS conflicts)
                withdrawAmountSection.style.display = 'block';
                withdrawAmountSection.style.opacity = '1';
                // Smooth scroll to the amount section for better UX
                setTimeout(() => {
                    withdrawAmountSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 300);
            }

            // Update limit info
            const limitInfo = document.getElementById('withdraw-limit-info');
            if (limitInfo) {
                let limitText = '';
                if (selectedPaymentMethod.minWithdrawal > 0) {
                    limitText += 'Min: $' + selectedPaymentMethod.minWithdrawal.toFixed(2);
                }
                if (selectedPaymentMethod.maxWithdrawal) {
                    if (limitText) limitText += ' | ';
                    limitText += 'Max: $' + selectedPaymentMethod.maxWithdrawal.toFixed(2);
                }
                limitInfo.textContent = limitText;
                limitInfo.style.display = limitText ? 'block' : 'none';
            }

            // Update minimum withdrawal instruction text
            const minAmountText = document.getElementById('withdraw-min-amount-text');
            if (minAmountText && selectedPaymentMethod) {
                let minWithdrawal = selectedPaymentMethod.minWithdrawal || 0;
                let instructionText = '';
                
                // For crypto payment methods, use crypto wallet minimum (accounting for fee)
                if (selectedPaymentMethod.type === 'crypto' && cryptoWallets.length > 0) {
                    // Get the minimum withdrawal from all active crypto wallets
                    // User needs to select: crypto_wallet_minimum + $1 fee
                    const cryptoMinWithdrawals = cryptoWallets
                        .filter(w => w.minimum_withdrawal)
                        .map(w => parseFloat(w.minimum_withdrawal));
                    
                    if (cryptoMinWithdrawals.length > 0) {
                        const minCryptoWithdrawal = Math.min(...cryptoMinWithdrawals);
                        // User needs to select minimum + fee to receive the minimum
                        minWithdrawal = minCryptoWithdrawal + cryptoFee;
                        instructionText = `Minimum withdrawal for ${selectedPaymentMethod.name} is $${minWithdrawal.toFixed(2)} (including $${cryptoFee.toFixed(2)} fee). You will receive $${minCryptoWithdrawal.toFixed(2)}.`;
                    } else {
                        // No minimum set, but still need to account for fee
                        minWithdrawal = cryptoFee + 0.01; // At least $1.01 to receive $0.01
                        instructionText = `Minimum withdrawal for ${selectedPaymentMethod.name} is $${minWithdrawal.toFixed(2)} (including $${cryptoFee.toFixed(2)} fee).`;
                    }
                } else {
                    // For non-crypto payment methods
                    const formattedMinWithdrawal = minWithdrawal > 0 
                        ? minWithdrawal.toFixed(2) 
                        : '0.00';
                    instructionText = `Minimum withdrawal for ${selectedPaymentMethod.name} is $${formattedMinWithdrawal}`;
                }
                
                minAmountText.textContent = instructionText;
            }

            // Update input min/max attributes and clear previous selections
            const amountInput = document.getElementById('withdraw-amount-input');
            if (amountInput) {
                amountInput.min = selectedPaymentMethod.minWithdrawal || 0.01;
                if (selectedPaymentMethod.maxWithdrawal) {
                    amountInput.max = selectedPaymentMethod.maxWithdrawal;
                } else {
                    amountInput.removeAttribute('max');
                }
                // Clear previous amount and preset selections
                amountInput.value = '';
                document.querySelectorAll('.withdraw-preset-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                // Update PKR amount display (will hide since amount is cleared)
                updateWithdrawPKRAmount();
            }

            // Update continue button text
            const continueBtn = document.getElementById('withdraw-continue-btn');
            if (continueBtn && selectedPaymentMethod) {
                continueBtn.textContent = `Continue Withdrawal with ${selectedPaymentMethod.name}`;
            }
        });
    });

    // Preset amount buttons
    document.querySelectorAll('.withdraw-preset-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all preset buttons
            document.querySelectorAll('.withdraw-preset-btn').forEach(b => b.classList.remove('active'));

            // Add active class to clicked button
            this.classList.add('active');

            // Set the amount in the input field
            const amount = this.dataset.amount;
            const amountInput = document.getElementById('withdraw-amount-input');
            if (amountInput) {
                amountInput.value = amount;
                // Update PKR amount display immediately
                updateWithdrawPKRAmount();
                // Trigger input event to validate
                amountInput.dispatchEvent(new Event('input'));
            }
        });
    });

    // Clear preset selection when user types custom amount
    const amountInput = document.getElementById('withdraw-amount-input');
    const continueBtn = document.getElementById('withdraw-continue-btn');

    if (amountInput && continueBtn) {
        amountInput.addEventListener('input', function() {
            // Check if the value matches any preset
            const value = parseFloat(this.value);
            const presetButtons = document.querySelectorAll('.withdraw-preset-btn');
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
            updateWithdrawPKRAmount();

            const amount = parseFloat(this.value);
            // Note: Withdrawals can only use mining_earning + referral_earning (NOT fund_wallet)
            const miningEarning = {{ auth()->user()->mining_earning ?? 0 }};
            const referralEarning = {{ auth()->user()->referral_earning ?? 0 }};
            const userBalance = miningEarning + referralEarning;

            // Get message elements
            const insufficientBalanceMsg = document.getElementById('withdraw-insufficient-balance-message');
            const insufficientBalanceText = document.getElementById('withdraw-insufficient-balance-text');

            if (!selectedPaymentMethod) {
                continueBtn.disabled = true;
                if (insufficientBalanceMsg) insufficientBalanceMsg.style.display = 'none';
                return;
            }

            if (!amount || amount <= 0) {
                continueBtn.disabled = true;
                if (insufficientBalanceMsg) insufficientBalanceMsg.style.display = 'none';
                return;
            }

            // Check minimum
            if (selectedPaymentMethod.minWithdrawal > 0 && amount < selectedPaymentMethod.minWithdrawal) {
                continueBtn.disabled = true;
                if (insufficientBalanceMsg && insufficientBalanceText) {
                    if (userBalance < selectedPaymentMethod.minWithdrawal) {
                        insufficientBalanceText.textContent = `Minimum withdrawal amount is $${selectedPaymentMethod.minWithdrawal.toFixed(2)}, but your available balance is only $${userBalance.toFixed(2)}. You can only withdraw from mining and referral earnings.`;
                        insufficientBalanceMsg.style.display = 'block';
                    } else {
                        insufficientBalanceMsg.style.display = 'none';
                    }
                }
                return;
            }

            // Check maximum
            if (selectedPaymentMethod.maxWithdrawal && amount > selectedPaymentMethod.maxWithdrawal) {
                continueBtn.disabled = true;
                if (insufficientBalanceMsg) insufficientBalanceMsg.style.display = 'none';
                return;
            }

            // Check user balance
            
            if (amount > userBalance) {
                continueBtn.disabled = true;
                if (insufficientBalanceMsg && insufficientBalanceText) {
                    const minRequired = selectedPaymentMethod.minWithdrawal || 0;
                    if (minRequired > 0 && userBalance < minRequired) {
                        insufficientBalanceText.textContent = `Minimum withdrawal amount is $${minRequired.toFixed(2)}, but your available balance is only $${userBalance.toFixed(2)}. You can only withdraw from mining and referral earnings.`;
                    } else {
                        insufficientBalanceText.textContent = `Insufficient balance. Your available withdrawal balance is $${userBalance.toFixed(2)}. You can only withdraw from mining and referral earnings.`;
                    }
                    insufficientBalanceMsg.style.display = 'block';
                }
                return;
            } else {
                if (insufficientBalanceMsg) {
                    insufficientBalanceMsg.style.display = 'none';
                }
            }

            continueBtn.disabled = false;
        });
    }

    // Continue button click
    if (continueBtn) {
        continueBtn.addEventListener('click', function() {
            if (this.disabled) return;

            const amount = parseFloat(amountInput.value);
            if (!selectedPaymentMethod || !amount) {
                alert('Please select a payment method and enter an amount.');
                return;
            }

            // Navigate to confirmation page
            window.location.href = `{{ route('withdraw.confirm') }}?method_id=${selectedPaymentMethod.id}&amount=${amount}`;
        });
    }

    // Withdrawal History Filtering and Search
    const withdrawSearchInput = document.getElementById('withdraw-search-input');
    const withdrawDateFilter = document.getElementById('withdraw-date-filter');
    const withdrawTransactionsList = document.getElementById('withdraw-transactions-list');
    const withdrawHistoryEmpty = document.getElementById('withdraw-history-empty');

    // Advance Search Modal Elements
    const withdrawAdvanceSearchModal = document.getElementById('withdraw-advance-search-modal');
    const withdrawAdvanceSearchClose = document.getElementById('withdraw-advance-search-close');
    const withdrawAdvanceSearchApply = document.getElementById('withdraw-advance-apply');
    const withdrawAdvanceSearchClear = document.getElementById('withdraw-advance-clear');
    const withdrawDateRangeInput = document.getElementById('withdraw-date-range-input');
    const withdrawAdvanceSortSelect = document.getElementById('withdraw-advance-sort');

    // Filter state
    let withdrawDateRangeFilter = null;
    let withdrawSortOrder = 'newest';

    // Date range picker
    let withdrawStartDate = null;
    let withdrawEndDate = null;
    const withdrawStartDateInput = document.getElementById('withdraw-start-date');
    const withdrawEndDateInput = document.getElementById('withdraw-end-date');

    function updateWithdrawDateRangeDisplay() {
        if (withdrawStartDate && withdrawEndDate) {
            const formatDate = (date) => {
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                return `${day}/${month}/${year}`;
            };
            withdrawDateRangeInput.value = `${formatDate(withdrawStartDate)} - ${formatDate(withdrawEndDate)}`;
            withdrawDateRangeFilter = {
                start: Math.floor(withdrawStartDate.getTime() / 1000),
                end: Math.floor(withdrawEndDate.getTime() / 1000) + 86400 // Add one day to include the end date
            };
        } else {
            withdrawDateRangeInput.value = '';
            withdrawDateRangeFilter = null;
        }
    }

    if (withdrawDateRangeInput) {
        withdrawDateRangeInput.addEventListener('click', function() {
            if (withdrawStartDateInput && typeof withdrawStartDateInput.showPicker === 'function') {
                withdrawStartDateInput.showPicker();
            } else {
                withdrawStartDateInput.click();
            }
        });
    }

    if (withdrawStartDateInput) {
        withdrawStartDateInput.addEventListener('change', function() {
            withdrawStartDate = new Date(this.value);
            if (withdrawEndDateInput) {
                withdrawEndDateInput.min = this.value;
                if (withdrawEndDate && withdrawEndDate < withdrawStartDate) {
                    withdrawEndDate = null;
                    withdrawEndDateInput.value = '';
                }
                updateWithdrawDateRangeDisplay();
                // Automatically open end date picker
                setTimeout(() => {
                    if (typeof withdrawEndDateInput.showPicker === 'function') {
                        withdrawEndDateInput.showPicker();
                    } else {
                        withdrawEndDateInput.click();
                    }
                }, 100);
            } else {
                updateWithdrawDateRangeDisplay();
            }
        });
    }

    if (withdrawEndDateInput) {
        withdrawEndDateInput.addEventListener('change', function() {
            withdrawEndDate = new Date(this.value);
            updateWithdrawDateRangeDisplay();
        });
    }

    function filterWithdrawals() {
        const searchTerm = withdrawSearchInput ? withdrawSearchInput.value.toLowerCase().trim() : '';
        const dateFilter = withdrawDateFilter ? withdrawDateFilter.value : 'all';
        const transactionCards = withdrawTransactionsList ? Array.from(withdrawTransactionsList.querySelectorAll('.withdraw-transaction-card')) : [];

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
            if (dateFilter !== 'all' && !withdrawDateRangeFilter) {
                const daysAgo = daysInSeconds[dateFilter];
                const cutoffDate = now - daysAgo;
                dateMatch = transactionDate >= cutoffDate;
            }

            // Date range filter (from advance search)
            if (withdrawDateRangeFilter) {
                dateMatch = transactionDate >= withdrawDateRangeFilter.start && transactionDate <= withdrawDateRangeFilter.end;
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

            switch(withdrawSortOrder) {
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
            withdrawTransactionsList.appendChild(card);
        });

        // Show/hide empty state
        if (withdrawHistoryEmpty) {
            if (visibleCount === 0) {
                withdrawHistoryEmpty.classList.add('show');
            } else {
                withdrawHistoryEmpty.classList.remove('show');
            }
        }
    }

    // Open advance search modal
    function openWithdrawAdvanceSearchModal() {
        if (withdrawAdvanceSearchModal) {
            withdrawAdvanceSearchModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    // Close advance search modal
    function closeWithdrawAdvanceSearchModal() {
        if (withdrawAdvanceSearchModal) {
            withdrawAdvanceSearchModal.classList.remove('show');
            document.body.style.overflow = '';
        }
    }

    // Apply filters from advance search
    if (withdrawAdvanceSearchApply) {
        withdrawAdvanceSearchApply.addEventListener('click', function() {
            withdrawSortOrder = withdrawAdvanceSortSelect ? withdrawAdvanceSortSelect.value : 'newest';
            filterWithdrawals();
            closeWithdrawAdvanceSearchModal();
        });
    }

    // Clear filters
    if (withdrawAdvanceSearchClear) {
        withdrawAdvanceSearchClear.addEventListener('click', function() {
            withdrawStartDate = null;
            withdrawEndDate = null;
            withdrawDateRangeFilter = null;
            if (withdrawDateRangeInput) {
                withdrawDateRangeInput.value = '';
            }
            if (withdrawStartDateInput) {
                withdrawStartDateInput.value = '';
            }
            if (withdrawEndDateInput) {
                withdrawEndDateInput.value = '';
            }
            if (withdrawAdvanceSortSelect) {
                withdrawAdvanceSortSelect.value = 'newest';
            }
            withdrawSortOrder = 'newest';
            filterWithdrawals();
            closeWithdrawAdvanceSearchModal();
        });
    }

    // Close modal handlers
    if (withdrawAdvanceSearchClose) {
        withdrawAdvanceSearchClose.addEventListener('click', closeWithdrawAdvanceSearchModal);
    }

    if (withdrawAdvanceSearchModal) {
        withdrawAdvanceSearchModal.addEventListener('click', function(e) {
            if (e.target === withdrawAdvanceSearchModal) {
                closeWithdrawAdvanceSearchModal();
            }
        });
    }

    // Open modal from filter icons
    const withdrawFilterIcons = document.querySelectorAll('.withdraw-filter-icon, .withdraw-search-filter-btn');
    withdrawFilterIcons.forEach(icon => {
        icon.addEventListener('click', openWithdrawAdvanceSearchModal);
    });

    // Add event listeners
    if (withdrawSearchInput) {
        withdrawSearchInput.addEventListener('input', filterWithdrawals);
    }

    if (withdrawDateFilter) {
        withdrawDateFilter.addEventListener('change', filterWithdrawals);
    }

    // Initial filter
    filterWithdrawals();

    // Proof Image Modal Functions
    function openProofModal(imageUrl) {
        const modal = document.getElementById('proof-modal');
        const modalImage = document.getElementById('proof-modal-image');
        if (modal && modalImage) {
            modalImage.src = imageUrl;
            modal.classList.add('show');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }
    }

    function closeProofModal() {
        const modal = document.getElementById('proof-modal');
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = ''; // Restore scrolling
        }
    }

    // Close modal when clicking outside the image
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('proof-modal');
        if (modal && event.target === modal) {
            closeProofModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeProofModal();
        }
    });
</script>
@endsection

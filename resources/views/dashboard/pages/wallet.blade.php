@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Wallet')

@push('styles')
<link rel="stylesheet" href="{{ asset('dashboard/css/wallet.css') }}">
<style>
    .wallet-new-page {
        padding: 0;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        overflow-x: hidden;
    }

    /* Header Section */
    .wallet-new-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .wallet-new-title-section {
        flex: 1;
    }

    .wallet-new-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #00FF88 0%, #00D977 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 0.5rem 0;
        letter-spacing: -1px;
    }

    .wallet-new-subtitle {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .wallet-visibility-btn {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: rgba(0, 255, 136, 0.1);
        border: 1px solid rgba(0, 255, 136, 0.3);
        color: var(--primary-color);
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .wallet-visibility-btn:hover {
        background: rgba(0, 255, 136, 0.2);
        border-color: var(--primary-color);
        box-shadow: 0 0 20px rgba(0, 255, 136, 0.4);
        transform: scale(1.05);
    }

    /* Main Balance Card */
    .wallet-main-balance-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        box-sizing: border-box;
        width: 100%;
        max-width: 100%;
    }

    .wallet-main-balance-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #00FF88 0%, #00D977 50%, #00FF88 100%);
        background-size: 200% 100%;
        animation: shimmer 3s linear infinite;
    }

    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .wallet-main-balance-card::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(0, 255, 136, 0.1) 0%, transparent 70%);
        pointer-events: none;
    }

    .wallet-balance-content {
        position: relative;
        z-index: 1;
    }

    .wallet-balance-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .wallet-balance-amount-wrapper {
        display: flex;
        align-items: baseline;
        gap: 0.75rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .wallet-balance-currency {
        font-size: 2rem;
        font-weight: 600;
        color: var(--text-primary);
        opacity: 0.7;
    }

    .wallet-balance-amount {
        font-size: 4.5rem;
        font-weight: 700;
        color: var(--primary-color);
        font-variant-numeric: tabular-nums;
        text-shadow: 0 0 30px rgba(0, 255, 136, 0.6);
        letter-spacing: -2px;
        line-height: 1;
    }

    .wallet-balance-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .wallet-balance-detail-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: rgba(0, 255, 136, 0.05);
        border: 1px solid rgba(0, 255, 136, 0.15);
        border-radius: 12px;
        transition: var(--transition);
    }

    .wallet-balance-detail-item:hover {
        background: rgba(0, 255, 136, 0.1);
        border-color: rgba(0, 255, 136, 0.3);
        transform: translateY(-2px);
    }

    .wallet-detail-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(0, 255, 136, 0.2) 0%, rgba(0, 217, 119, 0.1) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 1.125rem;
    }

    .wallet-detail-content {
        flex: 1;
    }

    .wallet-detail-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
    }

    .wallet-detail-value {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .wallet-action-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }

    .wallet-action-button {
        padding: 1.25rem 1.5rem;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }

    .wallet-action-button::before {
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

    .wallet-action-button:hover::before {
        width: 300px;
        height: 300px;
    }

    .wallet-action-button span {
        position: relative;
        z-index: 1;
    }

    .wallet-action-button i {
        position: relative;
        z-index: 1;
    }

    .wallet-btn-primary {
        background: linear-gradient(135deg, #00FF88 0%, #00D977 100%);
        color: #000;
        box-shadow: 0 4px 20px rgba(0, 255, 136, 0.4);
    }

    .wallet-btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 30px rgba(0, 255, 136, 0.6);
    }

    .wallet-btn-secondary {
        background: var(--card-bg);
        border: 2px solid var(--card-border);
        color: var(--text-primary);
    }

    .wallet-btn-secondary:hover {
        background: rgba(0, 255, 136, 0.1);
        border-color: var(--primary-color);
        box-shadow: 0 0 20px rgba(0, 255, 136, 0.3);
    }

    .wallet-btn-tertiary {
        background: rgba(0, 255, 136, 0.08);
        border: 2px solid rgba(0, 255, 136, 0.3);
        color: var(--primary-color);
    }

    .wallet-btn-tertiary:hover {
        background: rgba(0, 255, 136, 0.15);
        border-color: var(--primary-color);
        box-shadow: 0 0 20px rgba(0, 255, 136, 0.3);
    }

    /* Wallet Cards Grid */
    .wallet-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .wallet-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        transition: var(--transition);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
        box-sizing: border-box;
    }

    .wallet-card::before {
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

    .wallet-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 32px rgba(0, 255, 136, 0.2);
        border-color: var(--primary-color);
    }

    .wallet-card:hover::before {
        transform: scaleX(1);
    }

    .wallet-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }

    .wallet-card-icon-wrapper {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(0, 255, 136, 0.2) 0%, rgba(0, 217, 119, 0.1) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(0, 255, 136, 0.3);
    }

    .wallet-card-icon {
        font-size: 1.75rem;
        color: var(--primary-color);
    }

    .wallet-card-trend {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(0, 255, 136, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 0.875rem;
    }

    .wallet-card-body {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .wallet-card-label {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .wallet-card-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        font-variant-numeric: tabular-nums;
    }

    .wallet-card-description {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        line-height: 1.5;
    }

    /* Transactions Section */
    .wallet-transactions-section {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
        box-sizing: border-box;
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
        overflow-y: visible;
    }

    .wallet-transactions-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .wallet-transactions-title-section {
        flex: 1;
    }

    .wallet-transactions-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .wallet-transactions-subtitle {
        font-size: 0.9375rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .wallet-transactions-controls {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .wallet-search-box {
        position: relative;
        display: flex;
        align-items: center;
    }

    .wallet-search-box i {
        position: absolute;
        left: 1.25rem;
        color: var(--text-secondary);
        font-size: 0.9375rem;
    }

    .wallet-search-input {
        padding: 0.875rem 1.25rem 0.875rem 3rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        color: var(--text-primary);
        font-size: 0.9375rem;
        width: 100%;
        max-width: 100%;
        transition: var(--transition);
        box-sizing: border-box;
    }

    .wallet-search-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(0, 255, 136, 0.1), 0 4px 16px rgba(0, 255, 136, 0.1);
        background: rgba(255, 255, 255, 0.05);
    }

    .wallet-filter-button {
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

    .wallet-filter-button:hover {
        background: rgba(0, 255, 136, 0.1);
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .wallet-date-select {
        padding: 0.875rem 1.25rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        color: var(--text-primary);
        font-size: 0.9375rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .wallet-date-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(0, 255, 136, 0.1);
    }

    .wallet-table-container {
        overflow-x: visible;
        overflow-y: visible;
        margin-bottom: 1.5rem;
        border-radius: 12px;
        -webkit-overflow-scrolling: touch;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .wallet-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: auto;
    }

    .wallet-table thead {
        background: linear-gradient(180deg, rgba(0, 255, 136, 0.1) 0%, rgba(0, 217, 119, 0.05) 100%);
        border-bottom: 2px solid rgba(0, 255, 136, 0.2);
    }

    .wallet-table th {
        padding: 1.25rem 1.5rem;
        text-align: left;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .wallet-table td {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .wallet-table tbody tr {
        transition: var(--transition);
    }

    .wallet-table tbody tr:hover {
        background: rgba(0, 255, 136, 0.05);
    }

    .wallet-transaction-cell {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .wallet-transaction-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.125rem;
    }

    .wallet-transaction-icon.success {
        background: rgba(0, 255, 136, 0.15);
        color: var(--primary-color);
    }

    .wallet-transaction-icon.danger {
        background: rgba(255, 68, 68, 0.15);
        color: #FF4444;
    }

    .wallet-transaction-info {
        flex: 1;
    }

    .wallet-transaction-name {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .wallet-transaction-id {
        font-size: 0.8125rem;
        color: var(--text-secondary);
    }

    .wallet-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        font-size: 0.8125rem;
        font-weight: 600;
    }

    .wallet-type-credit {
        background: rgba(0, 255, 136, 0.15);
        color: var(--primary-color);
        border: 1px solid rgba(0, 255, 136, 0.3);
    }

    .wallet-type-debit {
        background: rgba(255, 68, 68, 0.15);
        color: #FF4444;
        border: 1px solid rgba(255, 68, 68, 0.3);
    }

    .wallet-amount-cell {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .wallet-amount-value {
        font-size: 1.125rem;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
    }

    .wallet-amount-positive {
        color: var(--primary-color);
    }

    .wallet-amount-negative {
        color: #FF4444;
    }

    .wallet-amount-wallet {
        font-size: 0.8125rem;
        color: var(--text-secondary);
    }

    .wallet-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        font-size: 0.8125rem;
        font-weight: 600;
    }

    .wallet-status-completed {
        background: rgba(0, 255, 136, 0.15);
        color: var(--primary-color);
        border: 1px solid rgba(0, 255, 136, 0.3);
    }

    .wallet-status-pending {
        background: rgba(255, 170, 0, 0.15);
        color: #FFAA00;
        border: 1px solid rgba(255, 170, 0, 0.3);
    }

    .wallet-date-cell {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .wallet-date-main {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .wallet-date-time {
        font-size: 0.8125rem;
        color: var(--text-secondary);
    }

    .wallet-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 1.5rem;
    }

    .wallet-pagination-button {
        padding: 0.75rem 1.25rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        color: var(--text-secondary);
        cursor: pointer;
        transition: var(--transition);
        font-size: 0.9375rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .wallet-pagination-button:hover:not(:disabled) {
        background: rgba(0, 255, 136, 0.1);
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .wallet-pagination-button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .wallet-pagination-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9375rem;
        color: var(--text-primary);
    }

    .wallet-pagination-current {
        font-weight: 600;
        color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .wallet-new-page {
            padding: 0;
        }

        .wallet-new-header {
            margin-bottom: 1.5rem;
            gap: 1rem;
        }

        .wallet-new-title {
            font-size: 1.75rem;
        }

        .wallet-new-subtitle {
            font-size: 0.875rem;
        }

        .wallet-main-balance-card {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 16px;
        }

        .wallet-balance-amount {
            font-size: 2.5rem;
        }

        .wallet-balance-currency {
            font-size: 1.5rem;
        }

        .wallet-balance-amount-wrapper {
            margin-bottom: 1.5rem;
        }

        .wallet-balance-details {
            grid-template-columns: 1fr;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .wallet-balance-detail-item {
            padding: 0.875rem;
        }

        .wallet-action-buttons {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .wallet-action-button {
            padding: 1rem 1.25rem;
            font-size: 0.875rem;
        }

        .wallet-cards-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .wallet-card {
            padding: 1.25rem;
        }

        .wallet-card-icon-wrapper {
            width: 56px;
            height: 56px;
        }

        .wallet-card-value {
            font-size: 1.5rem;
        }

        .wallet-transactions-section {
            padding: 1.5rem;
            border-radius: 16px;
        }

        .wallet-transactions-title {
            font-size: 1.25rem;
        }

        .wallet-transactions-subtitle {
            font-size: 0.8125rem;
        }

        .wallet-transactions-header {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .wallet-transactions-controls {
            flex-direction: column;
            width: 100%;
            gap: 0.75rem;
        }

        .wallet-search-box {
            width: 100%;
        }

        .wallet-search-input {
            width: 100%;
            max-width: 100%;
        }

        .wallet-filter-button,
        .wallet-date-select {
            width: 100%;
            justify-content: center;
        }

        .wallet-table-container {
            overflow-x: visible;
            -webkit-overflow-scrolling: touch;
            margin: 0;
            padding: 0;
            width: 100%;
            max-width: 100%;
        }

        .wallet-table {
            width: 100%;
            min-width: 0;
        }

        .wallet-table th,
        .wallet-table td {
            padding: 1rem 0.75rem;
            font-size: 0.8125rem;
        }

        .wallet-transaction-icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .wallet-pagination {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .wallet-pagination-button {
            width: 100%;
            justify-content: center;
        }

        .wallet-visibility-btn {
            width: 44px;
            height: 44px;
            font-size: 1rem;
        }
    }

    @media (max-width: 480px) {
        .wallet-new-page {
            padding: 0;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .wallet-new-title {
            font-size: 1.5rem;
        }

        .wallet-new-subtitle {
            font-size: 0.8125rem;
        }

        .wallet-main-balance-card {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            margin-left: 0;
            margin-right: 0;
            padding: 1.25rem;
            border-radius: 12px;
        }

        .wallet-balance-amount {
            font-size: 2rem;
        }

        .wallet-balance-currency {
            font-size: 1.25rem;
        }

        .wallet-balance-detail-item {
            padding: 0.75rem;
        }

        .wallet-detail-icon {
            width: 36px;
            height: 36px;
            font-size: 1rem;
        }

        .wallet-action-button {
            padding: 0.875rem 1rem;
            font-size: 0.8125rem;
        }

        .wallet-card {
            padding: 1rem;
        }

        .wallet-card-icon-wrapper {
            width: 48px;
            height: 48px;
        }

        .wallet-card-icon {
            font-size: 1.5rem;
        }

        .wallet-card-value {
            font-size: 1.25rem;
        }

        .wallet-transactions-section {
            padding: 1.25rem;
            border-radius: 12px;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            margin-left: 0;
            margin-right: 0;
            overflow-x: hidden;
        }

        .wallet-transactions-title {
            font-size: 1.125rem;
        }

        .wallet-table-container {
            overflow-x: visible;
            -webkit-overflow-scrolling: touch;
            margin: 0;
            padding: 0;
            width: 100%;
            max-width: 100%;
        }

        .wallet-table {
            width: 100%;
            min-width: 0;
            display: block;
            border-collapse: separate;
        }

        .wallet-table thead {
            display: none;
        }

        .wallet-table tbody {
            display: block;
            width: 100%;
        }

        .wallet-table tbody tr {
            display: block;
            width: 100%;
            margin-bottom: 1rem;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 1rem;
            box-sizing: border-box;
        }

        .wallet-table tbody tr:last-child {
            margin-bottom: 0;
        }

        .wallet-table td {
            display: block;
            width: 100%;
            padding: 0.75rem 0;
            border: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            text-align: left;
            font-size: 0.75rem;
            box-sizing: border-box;
        }

        .wallet-table td:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .wallet-table td:first-child {
            padding-top: 0;
        }

        .wallet-transaction-cell,
        .wallet-amount-cell,
        .wallet-date-cell {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        .wallet-transaction-icon {
            width: 36px;
            height: 36px;
            font-size: 0.875rem;
        }

        .wallet-transaction-name {
            font-size: 0.8125rem;
        }

        .wallet-transaction-id {
            font-size: 0.75rem;
        }

        .wallet-type-badge,
        .wallet-status-badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.625rem;
        }

        .wallet-amount-value {
            font-size: 1rem;
        }

        .wallet-date-main {
            font-size: 0.8125rem;
        }

        .wallet-date-time {
            font-size: 0.75rem;
        }
    }

    @media (max-width: 400px) {
        .wallet-new-page {
            padding: 0;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .wallet-new-header {
            margin-bottom: 1.5rem;
            gap: 0.75rem;
        }

        .wallet-new-title {
            font-size: 1.25rem;
            margin-bottom: 0.25rem;
            letter-spacing: -0.5px;
        }

        .wallet-new-subtitle {
            font-size: 0.75rem;
            line-height: 1.4;
        }

        .wallet-visibility-btn {
            width: 38px;
            height: 38px;
            font-size: 0.875rem;
            border-radius: 10px;
            flex-shrink: 0;
        }

        .wallet-main-balance-card {
            padding: 1rem;
            margin-bottom: 1.5rem;
            margin-left: 0;
            margin-right: 0;
            border-radius: 12px;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        .wallet-balance-label {
            font-size: 0.6875rem;
            margin-bottom: 0.75rem;
        }

        .wallet-balance-amount-wrapper {
            margin-bottom: 1.5rem;
            gap: 0.5rem;
            flex-wrap: nowrap;
        }

        .wallet-balance-currency {
            font-size: 1rem;
        }

        .wallet-balance-amount {
            font-size: 1.5rem;
            letter-spacing: -1px;
            line-height: 1.1;
        }

        .wallet-balance-details {
            grid-template-columns: 1fr;
            gap: 0.625rem;
            margin-bottom: 1.5rem;
        }

        .wallet-balance-detail-item {
            padding: 0.625rem;
            gap: 0.625rem;
            border-radius: 10px;
        }

        .wallet-detail-icon {
            width: 32px;
            height: 32px;
            font-size: 0.875rem;
            border-radius: 8px;
            flex-shrink: 0;
        }

        .wallet-detail-label {
            font-size: 0.625rem;
            margin-bottom: 0.125rem;
        }

        .wallet-detail-value {
            font-size: 0.9375rem;
        }

        .wallet-action-buttons {
            grid-template-columns: 1fr;
            gap: 0.625rem;
        }

        .wallet-action-button {
            padding: 0.75rem 1rem;
            font-size: 0.75rem;
            border-radius: 10px;
            gap: 0.5rem;
        }

        .wallet-action-button span {
            font-size: 0.75rem;
        }

        .wallet-action-button i {
            font-size: 0.75rem;
        }

        .wallet-cards-grid {
            grid-template-columns: 1fr;
            gap: 0.875rem;
            margin-bottom: 1.5rem;
        }

        .wallet-card {
            padding: 0.875rem;
            border-radius: 12px;
        }

        .wallet-card-header {
            margin-bottom: 1rem;
        }

        .wallet-card-icon-wrapper {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            flex-shrink: 0;
        }

        .wallet-card-icon {
            font-size: 1.125rem;
        }

        .wallet-card-trend {
            width: 32px;
            height: 32px;
            font-size: 0.75rem;
            border-radius: 8px;
            flex-shrink: 0;
        }

        .wallet-card-label {
            font-size: 0.6875rem;
        }

        .wallet-card-value {
            font-size: 1.125rem;
        }

        .wallet-card-description {
            font-size: 0.6875rem;
            line-height: 1.4;
        }

        .wallet-transactions-section {
            padding: 1rem;
            border-radius: 12px;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            margin-left: 0;
            margin-right: 0;
        }

        .wallet-transactions-header {
            margin-bottom: 1.25rem;
            gap: 0.875rem;
        }

        .wallet-transactions-title {
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .wallet-transactions-subtitle {
            font-size: 0.6875rem;
            line-height: 1.4;
        }

        .wallet-transactions-controls {
            flex-direction: column;
            width: 100%;
            gap: 0.625rem;
        }

        .wallet-search-box {
            width: 100%;
        }

        .wallet-search-box i {
            left: 1rem;
            font-size: 0.8125rem;
        }

        .wallet-search-input {
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            font-size: 0.75rem;
            width: 100%;
            max-width: 100%;
            border-radius: 10px;
        }

        .wallet-filter-button,
        .wallet-date-select {
            padding: 0.75rem 1rem;
            font-size: 0.75rem;
            border-radius: 10px;
            width: 100%;
            justify-content: center;
        }

        .wallet-filter-button {
            gap: 0.5rem;
        }

        .wallet-table-container {
            overflow-x: visible;
            -webkit-overflow-scrolling: touch;
            margin: 0;
            padding: 0;
            width: 100%;
            max-width: 100%;
        }

        .wallet-table {
            width: 100%;
            min-width: 0;
            display: block;
            border-collapse: separate;
        }

        .wallet-table thead {
            display: none;
        }

        .wallet-table tbody {
            display: block;
            width: 100%;
        }

        .wallet-table tbody tr {
            display: block;
            width: 100%;
            margin-bottom: 1rem;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 1rem;
            box-sizing: border-box;
        }

        .wallet-table tbody tr:last-child {
            margin-bottom: 0;
        }

        .wallet-table td {
            display: block;
            width: 100%;
            padding: 0.75rem 0;
            border: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            text-align: left;
            font-size: 0.75rem;
            box-sizing: border-box;
        }

        .wallet-table td:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .wallet-table td:first-child {
            padding-top: 0;
        }

        .wallet-transaction-cell,
        .wallet-amount-cell,
        .wallet-date-cell {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        .wallet-transaction-cell {
            gap: 0.625rem;
        }

        .wallet-transaction-icon {
            width: 32px;
            height: 32px;
            font-size: 0.75rem;
            border-radius: 8px;
            flex-shrink: 0;
        }

        .wallet-transaction-name {
            font-size: 0.6875rem;
            margin-bottom: 0.125rem;
        }

        .wallet-transaction-id {
            font-size: 0.625rem;
        }

        .wallet-type-badge,
        .wallet-status-badge {
            font-size: 0.625rem;
            padding: 0.25rem 0.5rem;
            gap: 0.375rem;
            border-radius: 6px;
            white-space: nowrap;
        }

        .wallet-amount-cell {
            gap: 0.125rem;
        }

        .wallet-amount-value {
            font-size: 0.8125rem;
        }

        .wallet-amount-wallet {
            font-size: 0.625rem;
        }

        .wallet-date-cell {
            gap: 0.125rem;
        }

        .wallet-date-main {
            font-size: 0.6875rem;
        }

        .wallet-date-time {
            font-size: 0.625rem;
        }

        .wallet-pagination {
            flex-direction: column;
            gap: 0.875rem;
            align-items: stretch;
        }

        .wallet-pagination-button {
            padding: 0.625rem 1rem;
            font-size: 0.75rem;
            width: 100%;
            justify-content: center;
            border-radius: 10px;
            gap: 0.5rem;
        }

        .wallet-pagination-info {
            font-size: 0.75rem;
            text-align: center;
            gap: 0.375rem;
        }
    }
</style>
@endpush

@section('content')
<div class="wallet-new-page">
    <!-- Header -->
    <div class="wallet-new-header">
        <div class="wallet-new-title-section">
            <h1 class="wallet-new-title">Mining Wallet</h1>
            <p class="wallet-new-subtitle">Manage your cryptocurrency mining earnings and transactions</p>
                </div>
        <button class="wallet-visibility-btn" id="balanceToggleWallet" title="Toggle balance visibility">
                    <i class="fas fa-eye" id="eyeIconWallet"></i>
                    <i class="fas fa-eye-slash" id="eyeSlashIconWallet" style="display: none;"></i>
                </button>
            </div>

    <!-- Main Balance Card -->
    <div class="wallet-main-balance-card">
        <div class="wallet-balance-content">
            <div class="wallet-balance-label">Total Mining Balance</div>
            <div class="wallet-balance-amount-wrapper" id="balanceAmountWallet">
                <span class="wallet-balance-currency">$</span>
                <span class="wallet-balance-amount">0.00</span>
                </div>
            
            <div class="wallet-balance-details">
                <div class="wallet-balance-detail-item">
                    <div class="wallet-detail-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="wallet-detail-content">
                        <div class="wallet-detail-label">Deposit Wallet</div>
                        <div class="wallet-detail-value">$0.30</div>
                    </div>
                </div>
                <div class="wallet-balance-detail-item">
                    <div class="wallet-detail-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="wallet-detail-content">
                        <div class="wallet-detail-label">Mining Earnings</div>
                        <div class="wallet-detail-value">$0.00</div>
                    </div>
                </div>
                <div class="wallet-balance-detail-item">
                    <div class="wallet-detail-icon">
                        <i class="fas fa-gift"></i>
                    </div>
                    <div class="wallet-detail-content">
                        <div class="wallet-detail-label">Referral Bonus</div>
                        <div class="wallet-detail-value">$0.00</div>
                    </div>
                </div>
            </div>

            <div class="wallet-action-buttons">
                <button class="wallet-action-button wallet-btn-primary">
                    <i class="fas fa-arrow-up"></i>
                    <span>Deposit Funds</span>
                </button>
                <a href="{{ route('withdraw-security.index') }}" class="wallet-action-button wallet-btn-secondary">
                    <i class="fas fa-arrow-down"></i>
                    <span>Withdraw Funds</span>
                </a>
                <button class="wallet-action-button wallet-btn-tertiary">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Transfer</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Wallet Cards Grid -->
    <div class="wallet-cards-grid">
        <div class="wallet-card">
            <div class="wallet-card-header">
                <div class="wallet-card-icon-wrapper">
                    <i class="fas fa-users wallet-card-icon"></i>
                </div>
                <div class="wallet-card-trend">
                    <i class="fas fa-arrow-up"></i>
                </div>
            </div>
            <div class="wallet-card-body">
                <div class="wallet-card-label">Referral Earnings</div>
                <div class="wallet-card-value">$0.00</div>
                <div class="wallet-card-description">Commissions from your referral network</div>
            </div>
        </div>

        <div class="wallet-card">
            <div class="wallet-card-header">
                <div class="wallet-card-icon-wrapper">
                    <i class="fas fa-chart-line wallet-card-icon"></i>
                </div>
                <div class="wallet-card-trend">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="wallet-card-body">
                <div class="wallet-card-label">Mining Earnings</div>
                <div class="wallet-card-value">$0.00</div>
                <div class="wallet-card-description">Active mining returns and profits</div>
            </div>
        </div>

        <div class="wallet-card">
            <div class="wallet-card-header">
                <div class="wallet-card-icon-wrapper">
                    <i class="fas fa-arrow-up wallet-card-icon"></i>
                </div>
                <div class="wallet-card-trend">
                    <i class="fas fa-arrow-up"></i>
                </div>
            </div>
            <div class="wallet-card-body">
                <div class="wallet-card-label">Total Deposits</div>
                <div class="wallet-card-value">$0.30</div>
                <div class="wallet-card-description">All-time deposit amount</div>
            </div>
        </div>

        <div class="wallet-card">
            <div class="wallet-card-header">
                <div class="wallet-card-icon-wrapper">
                    <i class="fas fa-arrow-down wallet-card-icon"></i>
                </div>
                <div class="wallet-card-trend">
                    <i class="fas fa-minus"></i>
                </div>
            </div>
            <div class="wallet-card-body">
                <div class="wallet-card-label">Total Withdrawals</div>
                <div class="wallet-card-value">$0.00</div>
                <div class="wallet-card-description">All-time withdrawal amount</div>
            </div>
        </div>
    </div>

    <!-- Transactions Section -->
    <div class="wallet-transactions-section">
        <div class="wallet-transactions-header">
            <div class="wallet-transactions-title-section">
                <h2 class="wallet-transactions-title">Transaction History</h2>
                <p class="wallet-transactions-subtitle">View and manage all your mining transactions</p>
            </div>
            <div class="wallet-transactions-controls">
                <div class="wallet-search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" class="wallet-search-input" placeholder="Search transactions..." id="walletSearchInput">
                </div>
                <button class="wallet-filter-button" title="Filter">
                        <i class="fas fa-filter"></i>
                        <span>Filter</span>
                    </button>
                <select class="wallet-date-select" id="walletDateFilter">
                        <option value="all">All Time</option>
                        <option value="7">Last 7 Days</option>
                        <option value="30">Last 30 Days</option>
                        <option value="90">Last 90 Days</option>
                    </select>
            </div>
        </div>

        <div class="wallet-table-container">
            <table class="wallet-table">
                <thead>
                    <tr>
                        <th>Transaction</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="wallet-transaction-cell">
                                <div class="wallet-transaction-icon success">
                                    <i class="fas fa-gift"></i>
                                </div>
                                <div class="wallet-transaction-info">
                                    <div class="wallet-transaction-name">User Bonus</div>
                                    <div class="wallet-transaction-id">ID: #12345</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="wallet-type-badge wallet-type-credit">
                                <i class="fas fa-arrow-up"></i>
                                <span>Credit</span>
                            </span>
                        </td>
                        <td>
                            <div class="wallet-amount-cell">
                                <div class="wallet-amount-value wallet-amount-positive">+$0.30</div>
                                <div class="wallet-amount-wallet">Earning Wallet</div>
                            </div>
                        </td>
                        <td>
                            <span class="wallet-status-badge wallet-status-completed">
                                <span>Completed</span>
                            </span>
                        </td>
                        <td>
                            <div class="wallet-date-cell">
                                <div class="wallet-date-main">Dec 28, 2025</div>
                                <div class="wallet-date-time">11:11 PM</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="wallet-transaction-cell">
                                <div class="wallet-transaction-icon danger">
                                    <i class="fas fa-arrow-down"></i>
                                </div>
                                <div class="wallet-transaction-info">
                                    <div class="wallet-transaction-name">Withdrawal</div>
                                    <div class="wallet-transaction-id">ID: #12344</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="wallet-type-badge wallet-type-debit">
                                <i class="fas fa-arrow-down"></i>
                                <span>Debit</span>
                            </span>
                        </td>
                        <td>
                            <div class="wallet-amount-cell">
                                <div class="wallet-amount-value wallet-amount-negative">-$50.00</div>
                                <div class="wallet-amount-wallet">Main Wallet</div>
                            </div>
                        </td>
                        <td>
                            <span class="wallet-status-badge wallet-status-pending">
                                <span>Pending</span>
                            </span>
                        </td>
                        <td>
                            <div class="wallet-date-cell">
                                <div class="wallet-date-main">Dec 27, 2025</div>
                                <div class="wallet-date-time">03:45 PM</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="wallet-transaction-cell">
                                <div class="wallet-transaction-icon success">
                                    <i class="fas fa-arrow-up"></i>
                                </div>
                                <div class="wallet-transaction-info">
                                    <div class="wallet-transaction-name">Deposit</div>
                                    <div class="wallet-transaction-id">ID: #12343</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="wallet-type-badge wallet-type-credit">
                                <i class="fas fa-arrow-up"></i>
                                <span>Credit</span>
                            </span>
                        </td>
                        <td>
                            <div class="wallet-amount-cell">
                                <div class="wallet-amount-value wallet-amount-positive">+$100.00</div>
                                <div class="wallet-amount-wallet">Main Wallet</div>
                            </div>
                        </td>
                        <td>
                            <span class="wallet-status-badge wallet-status-completed">
                                <span>Completed</span>
                            </span>
                        </td>
                        <td>
                            <div class="wallet-date-cell">
                                <div class="wallet-date-main">Dec 26, 2025</div>
                                <div class="wallet-date-time">09:20 AM</div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="wallet-pagination">
            <button class="wallet-pagination-button" disabled>
                <i class="fas fa-chevron-left"></i>
                <span>Previous</span>
            </button>
            <div class="wallet-pagination-info">
                <span>Page</span>
                <span class="wallet-pagination-current">1</span>
                <span>of</span>
                <span>1</span>
            </div>
            <button class="wallet-pagination-button" disabled>
                <span>Next</span>
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('dashboard/js/wallet.js') }}"></script>
<script>
    // Balance toggle functionality
    const balanceToggle = document.getElementById('balanceToggleWallet');
    const eyeIcon = document.getElementById('eyeIconWallet');
    const eyeSlashIcon = document.getElementById('eyeSlashIconWallet');
    const balanceAmount = document.getElementById('balanceAmountWallet');
    let balanceVisible = true;

    if (balanceToggle) {
        balanceToggle.addEventListener('click', function() {
            balanceVisible = !balanceVisible;
            if (balanceVisible) {
                eyeIcon.style.display = 'block';
                eyeSlashIcon.style.display = 'none';
                balanceAmount.style.opacity = '1';
            } else {
                eyeIcon.style.display = 'none';
                eyeSlashIcon.style.display = 'block';
                balanceAmount.style.opacity = '0.3';
            }
        });
    }
</script>
@endpush
@endsection

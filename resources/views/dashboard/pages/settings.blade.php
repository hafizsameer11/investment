@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Settings')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/profile.css') }}">
<style>
    .settings-page-modern {
        padding: 0;
        width: 100%;
        max-width: 100%;
    }

    /* User Profile Section */
    .settings-profile-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .settings-profile-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 0;
    }

    .settings-profile-avatar-wrapper {
        position: relative;
        flex-shrink: 0;
    }

    .settings-profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255, 178, 30, 0.3);
    }

    .settings-avatar-camera {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 28px;
        height: 28px;
        background: var(--card-bg);
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--text-primary);
        font-size: 0.75rem;
    }

    .settings-profile-info {
        flex: 1;
    }

    .settings-profile-name {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .settings-profile-email {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0 0 0.5rem 0;
    }

    .settings-profile-uid {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
        font-family: 'Courier New', monospace;
    }

    .settings-profile-badge {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #000;
        font-size: 1.25rem;
        cursor: pointer;
    }

    /* Total Earnings Section */
    .settings-earnings-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .settings-earnings-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 1.5rem 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .settings-earnings-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .settings-earnings-item {
        text-align: center;
    }

    .settings-earnings-icon {
        font-size: 2rem;
        color: var(--primary-color);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .settings-earnings-icon i {
        filter: drop-shadow(0 0 8px rgba(255, 178, 30, 0.4));
    }

    .settings-earnings-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .settings-earnings-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    /* Settings Menu */
    .settings-menu-list {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        overflow: hidden;
    }

    .settings-menu-item {
        display: flex;
        align-items: center;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(255, 178, 30, 0.1);
        text-decoration: none;
        color: var(--text-primary);
        transition: var(--transition);
        cursor: pointer;
        position: relative;
        min-height: 60px;
        font-family: inherit;
        font-size: inherit;
    }

    .settings-menu-item:last-child {
        border-bottom: none;
    }

    .settings-menu-item:hover {
        background: rgba(255, 178, 30, 0.05);
        border-left: 3px solid var(--primary-color);
    }

    .settings-menu-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 178, 30, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: var(--primary-color);
        font-size: 1.125rem;
        flex-shrink: 0;
    }

    .settings-menu-text {
        flex: 1;
        font-size: 1rem;
        font-weight: 500;
    }

    .settings-menu-arrow {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-left: 1rem;
    }

    .settings-menu-badge {
        background: rgba(255, 178, 30, 0.2);
        border: 1px solid rgba(255, 178, 30, 0.4);
        border-radius: 6px;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        color: var(--primary-color);
        margin-left: auto;
        margin-right: 1rem;
    }

    .settings-menu-notification {
        width: 24px;
        height: 24px;
        background: rgba(0, 170, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: auto;
        margin-right: 1rem;
        font-size: 0.75rem;
        color: #00AAFF;
    }

    /* Mobile Styles */
    @media (max-width: 768px) {
        .settings-page-modern {
            padding: 0;
        }

        .settings-profile-card {
            padding: 1.5rem;
            border-radius: 12px;
        }

        .settings-profile-header {
            gap: 1rem;
        }

        .settings-profile-avatar {
            width: 70px;
            height: 70px;
        }

        .settings-profile-name {
            font-size: 1.25rem;
        }

        .settings-profile-email,
        .settings-profile-uid {
            font-size: 0.8125rem;
        }

        .settings-profile-badge {
            top: 1rem;
            right: 1rem;
            width: 36px;
            height: 36px;
            font-size: 1.125rem;
        }

        .settings-earnings-card {
            padding: 1.25rem;
            border-radius: 12px;
        }

        .settings-earnings-grid {
            gap: 1rem;
        }

        .settings-earnings-icon {
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }

        .settings-earnings-value {
            font-size: 1.25rem;
        }

        .settings-menu-item {
            padding: 1rem 1.25rem;
            min-height: 56px;
        }

        .settings-menu-icon {
            width: 36px;
            height: 36px;
            font-size: 1rem;
            margin-right: 0.875rem;
        }

        .settings-menu-text {
            font-size: 0.9375rem;
        }
    }
</style>
@endpush

@section('content')
<div class="settings-page-modern">
    <!-- User Profile Section -->
    <div class="settings-profile-card">
        <div class="settings-profile-header">
            <div class="settings-profile-avatar-wrapper">
                <img src="https://ui-avatars.com/api/?name=Rameez+Nazar&background=00FF88&color=000&size=200" alt="Profile" class="settings-profile-avatar">
                <div class="settings-avatar-camera">
                    <i class="fas fa-camera"></i>
                </div>
            </div>
            <div class="settings-profile-info">
                <h2 class="settings-profile-name">Rameez Nazar</h2>
                <p class="settings-profile-email">ramiznazar600@gmail.com</p>
                <p class="settings-profile-uid">UID: RAMEEZNAZAR2473</p>
            </div>
            <div class="settings-profile-badge">
                <i class="fas fa-shield-alt"></i>
            </div>
        </div>
    </div>

    <!-- Total Earnings Section -->
    <div class="settings-earnings-card">
        <div class="settings-earnings-grid">
            <div class="settings-earnings-item">
                <div class="settings-earnings-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="settings-earnings-label">USD Earnings</div>
                <div class="settings-earnings-value">$0</div>
            </div>
            <div class="settings-earnings-item">
                <div class="settings-earnings-icon">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="settings-earnings-label">PKR Earnings</div>
                <div class="settings-earnings-value">Rs0</div>
            </div>
        </div>
    </div>

    <!-- Settings Menu -->
    <div class="settings-menu-list">
        <a href="{{ route('profile.index') }}" class="settings-menu-item">
            <div class="settings-menu-icon">
                <i class="fas fa-user"></i>
            </div>
            <span class="settings-menu-text">Profile</span>
            <i class="fas fa-chevron-right settings-menu-arrow"></i>
        </a>

        <a href="{{ route('plans.index') }}" class="settings-menu-item">
            <div class="settings-menu-icon">
                <i class="fas fa-gem"></i>
            </div>
            <span class="settings-menu-text">Mining Plans</span>
            <i class="fas fa-chevron-right settings-menu-arrow"></i>
        </a>

        <a href="{{ route('wallet.index') }}" class="settings-menu-item">
            <div class="settings-menu-icon">
                <i class="fas fa-coins"></i>
            </div>
            <span class="settings-menu-text">Core Wallet</span>
            <i class="fas fa-chevron-right settings-menu-arrow"></i>
        </a>

        <a href="{{ route('deposit.index') }}" class="settings-menu-item">
            <div class="settings-menu-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <span class="settings-menu-text">Add Money</span>
            <i class="fas fa-chevron-right settings-menu-arrow"></i>
        </a>

        <a href="{{ route('withdraw.index') }}" class="settings-menu-item">
            <div class="settings-menu-icon">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <span class="settings-menu-text">Get Money</span>
            <i class="fas fa-chevron-right settings-menu-arrow"></i>
        </a>

        <a href="{{ route('goals.index') }}" class="settings-menu-item">
            <div class="settings-menu-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <span class="settings-menu-text">Victory Rewards</span>
            <i class="fas fa-chevron-right settings-menu-arrow"></i>
        </a>

        {{-- <a href="{{ route('targets.index') }}" class="settings-menu-item">
            <div class="settings-menu-icon">
                <i class="fas fa-flag"></i>
            </div>
            <span class="settings-menu-text">Targets</span>
            <i class="fas fa-chevron-right settings-menu-arrow"></i>
        </a> --}}

        <a href="{{ route('transactions.index') }}" class="settings-menu-item">
            <div class="settings-menu-icon">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <span class="settings-menu-text">Financial Records</span>
            <i class="fas fa-chevron-right settings-menu-arrow"></i>
        </a>

        <a href="{{ route('support.index') }}" class="settings-menu-item">
            <div class="settings-menu-icon">
                <i class="fas fa-headset"></i>
            </div>
            <span class="settings-menu-text">Technical Support</span>
            <i class="fas fa-chevron-right settings-menu-arrow"></i>
        </a>

        <form action="{{ route('logout') }}" method="POST" style="display: contents;">
            @csrf
            <button type="submit" class="settings-menu-item" style="width: 100%; border: none; background: none; text-align: left; cursor: pointer;">
                <div class="settings-menu-icon">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <span class="settings-menu-text">Quick Exit</span>
                <i class="fas fa-chevron-right settings-menu-arrow"></i>
            </button>
        </form>
    </div>
</div>
@endsection


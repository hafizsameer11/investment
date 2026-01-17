@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Notifications')

@push('styles')
<style>
    .notifications-page {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
        box-sizing: border-box;
    }

    /* Header Section */
    .notifications-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .notifications-title-section {
        flex: 1;
    }

    .notifications-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
        letter-spacing: -1px;
    }

    .notifications-header-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 1rem;
    }

    .notifications-header-top {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .notifications-bell-icon {
        position: relative;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 178, 30, 0.1);
        border: 1px solid rgba(255, 178, 30, 0.3);
        border-radius: 12px;
        color: #FFB21E;
        font-size: 1.25rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .notifications-bell-icon:hover {
        background: rgba(255, 178, 30, 0.2);
        border-color: #FFB21E;
    }

    .notifications-bell-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        width: 24px;
        height: 24px;
        background: #FFB21E;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        border: 2px solid var(--bg-primary);
    }

    .notifications-user-profile {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .notifications-user-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid #FFB21E;
        box-shadow: 0 0 12px rgba(255, 178, 30, 0.4);
    }

    .notifications-user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .notifications-user-info {
        display: flex;
        flex-direction: column;
    }

    .notifications-user-name {
        font-weight: 600;
        font-size: 0.9375rem;
        color: var(--text-primary);
    }

    .notifications-user-email {
        font-size: 0.8125rem;
        color: var(--text-secondary);
    }

    .notifications-unread-btn {
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(255, 178, 30, 0.3);
    }

    .notifications-unread-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 178, 30, 0.4);
    }

    /* Notifications List */
    .notifications-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .notification-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        gap: 1.25rem;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .notification-card:hover {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 178, 30, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
    }

    .notification-card-icon {
        position: relative;
        width: 56px;
        height: 56px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .notification-card-icon i {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: rgba(255, 178, 30, 0.15);
        border: 2px solid rgba(255, 178, 30, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #FFB21E;
        font-size: 1.25rem;
    }

    .notification-card-content {
        flex: 1;
        min-width: 0;
    }

    .notification-card-greeting {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .notification-card-message {
        font-size: 0.9375rem;
        color: var(--text-secondary);
        line-height: 1.6;
        margin-bottom: 0.75rem;
    }

    .notification-card-time {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        opacity: 0.7;
    }

    /* Empty State */
    .notifications-empty {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
    }

    .notifications-empty-icon {
        font-size: 4rem;
        color: var(--text-muted);
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }

    .notifications-empty-text {
        font-size: 1.125rem;
        margin: 0;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .notifications-page {
            padding: 1rem;
        }

        .notifications-header {
            display: none;
        }

        .notifications-title {
            font-size: 1.75rem;
        }

        .notifications-header-right {
            width: 100%;
            align-items: flex-start;
        }

        .notifications-header-top {
            width: 100%;
            justify-content: space-between;
        }

        .notification-card {
            padding: 1.25rem;
        }

        .notification-card-icon {
            width: 48px;
            height: 48px;
        }

        .notification-card-icon i {
            width: 40px;
            height: 40px;
            font-size: 1.125rem;
        }
    }

    @media (max-width: 390px) {
        .notifications-page {
            padding: 0.75rem;
        }

        .notifications-title {
            font-size: 1.5rem;
        }

        .notifications-bell-icon {
            width: 40px;
            height: 40px;
            font-size: 1.125rem;
        }

        .notifications-bell-badge {
            width: 20px;
            height: 20px;
            font-size: 0.6875rem;
        }

        .notifications-user-avatar {
            width: 40px;
            height: 40px;
        }

        .notifications-user-name {
            font-size: 0.875rem;
        }

        .notifications-user-email {
            font-size: 0.75rem;
        }

        .notifications-unread-btn {
            padding: 0.625rem 1.25rem;
            font-size: 0.8125rem;
        }

        .notification-card {
            padding: 1rem;
            gap: 1rem;
        }

        .notification-card-greeting {
            font-size: 0.9375rem;
        }

        .notification-card-message {
            font-size: 0.875rem;
        }

        .notification-card-time {
            font-size: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="notifications-page">
    <!-- Header Section -->
    <div class="notifications-header">
        <div class="notifications-title-section">
            <h1 class="notifications-title">Notifications</h1>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="notifications-list">
        <!-- Notification 1 -->
        <div class="notification-card">
            <div class="notification-card-icon">
                <i class="fas fa-bell"></i>
            </div>
            <div class="notification-card-content">
                <div class="notification-card-greeting">Hi, {{ Auth::user()->name ?? 'Moneymaker' }}</div>
                <div class="notification-card-message">Your withdrawal request approved.</div>
                <div class="notification-card-time">Jan 14, 2026 3:34 PM</div>
            </div>
        </div>

        <!-- Notification 2 -->
        <div class="notification-card">
            <div class="notification-card-icon">
                <i class="fas fa-bell"></i>
            </div>
            <div class="notification-card-content">
                <div class="notification-card-greeting">Hi, {{ Auth::user()->name ?? 'Moneymaker' }}</div>
                <div class="notification-card-message">Your withdrawal request approved.</div>
                <div class="notification-card-time">Jan 12, 2026 12:40 PM</div>
            </div>
        </div>

        <!-- Notification 3 -->
        <div class="notification-card">
            <div class="notification-card-icon">
                <i class="fas fa-bell"></i>
            </div>
            <div class="notification-card-content">
                <div class="notification-card-greeting">Hi, {{ Auth::user()->name ?? 'Moneymaker' }}</div>
                <div class="notification-card-message">Your rank level has been updated to Team leader, and you've earned a $5 reward.</div>
                <div class="notification-card-time">Jan 12, 2026 2:13 AM</div>
            </div>
        </div>

    </div>
</div>
@endsection


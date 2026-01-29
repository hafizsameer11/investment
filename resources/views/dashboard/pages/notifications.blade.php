@extends('dashboard.layouts.main')

@section('title', 'Core Mining ⛏️- AI Gold Mining ⛏️')

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
        position: relative;
    }

    .notification-card.unread {
        border-left: 4px solid #FFB21E;
        background: rgba(255, 178, 30, 0.05);
    }

    .notification-card:hover {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 178, 30, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
    }

    .notification-card.unread::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 8px;
        height: 8px;
        background: #FFB21E;
        border-radius: 50%;
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

    .notifications-actions {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 1.5rem;
    }

    .notification-icon-type {
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

    .notification-icon-type.deposit {
        background: rgba(34, 197, 94, 0.15);
        border-color: rgba(34, 197, 94, 0.3);
        color: #22c55e;
    }

    .notification-icon-type.withdrawal {
        background: rgba(59, 130, 246, 0.15);
        border-color: rgba(59, 130, 246, 0.3);
        color: #3b82f6;
    }

    .notification-icon-type.admin {
        background: rgba(168, 85, 247, 0.15);
        border-color: rgba(168, 85, 247, 0.3);
        color: #a855f7;
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

    @media (max-width: 450px) {
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

    /* Pagination Styles */
    .notifications-pagination-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        padding: 1.5rem 0;
        flex-wrap: wrap;
    }

    .notifications-pagination-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        padding: 0;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        color: var(--text-primary);
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .notifications-pagination-btn i {
        font-size: 0.75rem;
        color: var(--text-primary);
    }

    .notifications-pagination-btn:hover:not(:disabled) {
        background: rgba(255, 178, 30, 0.1);
        border-color: rgba(255, 178, 30, 0.3);
        box-shadow: 0 0 12px rgba(255, 178, 30, 0.2);
        transform: translateY(-1px);
    }

    .notifications-pagination-btn:hover:not(:disabled) i {
        color: var(--primary-color);
    }

    .notifications-pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        color: var(--text-secondary);
        background: rgba(255, 255, 255, 0.03);
        border-color: rgba(255, 255, 255, 0.05);
    }

    .notifications-pagination-btn:disabled i {
        color: var(--text-secondary);
    }

    .notifications-pagination-page {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 0.75rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        color: var(--text-primary);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .notifications-pagination-page:hover {
        background: rgba(255, 178, 30, 0.1);
        border-color: rgba(255, 178, 30, 0.3);
        color: var(--primary-color);
        box-shadow: 0 0 12px rgba(255, 178, 30, 0.2);
        transform: translateY(-1px);
    }

    .notifications-pagination-page.active {
        background: rgba(255, 178, 30, 0.2);
        border-color: rgba(255, 178, 30, 0.4);
        color: var(--primary-color);
        font-weight: 600;
        box-shadow: 0 0 12px rgba(255, 178, 30, 0.3);
    }

    /* Mobile responsive pagination */
    @media (max-width: 768px) {
        .notifications-pagination-wrapper {
            gap: 0.375rem;
            padding: 1rem 0;
        }

        .notifications-pagination-btn {
            width: 32px;
            height: 32px;
        }

        .notifications-pagination-btn i {
            font-size: 0.6875rem;
        }

        .notifications-pagination-page {
            min-width: 32px;
            height: 32px;
            padding: 0 0.625rem;
            font-size: 0.8125rem;
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
        @if($notifications->count() > 0 && $notifications->where('is_read', false)->count() > 0)
        <div class="notifications-actions">
            <button type="button" class="notifications-unread-btn" id="markAllReadBtn">
                Mark All as Read
            </button>
        </div>
        @endif
    </div>

    <!-- Notifications List -->
    <div class="notifications-list">
        @forelse($notifications as $notification)
            @php
                $iconClass = 'fas fa-bell';
                $typeClass = '';
                if (str_contains($notification->type, 'deposit')) {
                    $iconClass = 'fas fa-arrow-down';
                    $typeClass = 'deposit';
                } elseif (str_contains($notification->type, 'withdrawal')) {
                    $iconClass = 'fas fa-arrow-up';
                    $typeClass = 'withdrawal';
                } elseif (str_contains($notification->type, 'admin')) {
                    $iconClass = 'fas fa-bullhorn';
                    $typeClass = 'admin';
                }
            @endphp
            <div class="notification-card {{ !$notification->is_read ? 'unread' : '' }}" 
                 data-notification-id="{{ $notification->id }}"
                 onclick="markAsRead({{ $notification->id }})">
                <div class="notification-card-icon">
                    <div class="notification-icon-type {{ $typeClass }}">
                        <i class="{{ $iconClass }}"></i>
                    </div>
                </div>
                <div class="notification-card-content">
                    <div class="notification-card-greeting">{{ $notification->title }}</div>
                    <div class="notification-card-message">{{ $notification->message }}</div>
                    <div class="notification-card-time">{{ $notification->created_at->format('M d, Y h:i A') }}</div>
                </div>
            </div>
        @empty
            <div class="notifications-empty">
                <div class="notifications-empty-icon">
                    <i class="fas fa-bell-slash"></i>
                </div>
                <div class="notifications-empty-text">No notifications yet</div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
    <div class="notifications-pagination-wrapper">
        @if($notifications->onFirstPage())
            <button disabled class="notifications-pagination-btn">
                <i class="fas fa-chevron-left"></i>
            </button>
        @else
            <a href="{{ $notifications->previousPageUrl() }}" class="notifications-pagination-btn">
                <i class="fas fa-chevron-left"></i>
            </a>
        @endif

        @foreach($notifications->getUrlRange(1, $notifications->lastPage()) as $page => $url)
            @if($page == $notifications->currentPage())
                <span class="notifications-pagination-page active">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="notifications-pagination-page">{{ $page }}</a>
            @endif
        @endforeach

        @if($notifications->hasMorePages())
            <a href="{{ $notifications->nextPageUrl() }}" class="notifications-pagination-btn">
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <button disabled class="notifications-pagination-btn">
                <i class="fas fa-chevron-right"></i>
            </button>
        @endif
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Update unread count badge when page loads (all notifications are marked as read)
    document.addEventListener('DOMContentLoaded', function() {
        updateUnreadCount();
    });

    function markAsRead(notificationId) {
        fetch(`{{ route('notifications.mark-read', '') }}/${notificationId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const card = document.querySelector(`[data-notification-id="${notificationId}"]`);
                if (card) {
                    card.classList.remove('unread');
                    updateUnreadCount();
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    document.getElementById('markAllReadBtn')?.addEventListener('click', function() {
        if (typeof window.showConfirmDialog === 'function') {
            window.showConfirmDialog('Mark all notifications as read?', function() {
                fetch('{{ route("notifications.mark-all-read") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error marking all notifications as read:', error);
                });
            });
        }
    });

    function updateUnreadCount() {
        fetch('{{ route("notifications.unread-count") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const badge = document.querySelector('.notification-badge');
                    if (badge) {
                        if (data.count > 0) {
                            badge.textContent = data.count;
                            badge.style.display = 'flex';
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
</script>
@endpush
@endsection


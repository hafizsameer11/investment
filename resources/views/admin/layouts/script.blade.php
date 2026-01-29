<!-- jQuery  -->
<script src="{{ asset('assets/admin/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/modernizr.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/detect.js') }}"></script>
<script src="{{ asset('assets/admin/js/fastclick.js') }}"></script>
<script src="{{ asset('assets/admin/js/jquery.blockUI.js') }}"></script>
<script src="{{ asset('assets/admin/js/waves.js') }}"></script>
<script src="{{ asset('assets/admin/js/jquery.nicescroll.js') }}"></script>

<script src="{{ asset('assets/admin/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js') }}"></script>

<script src="{{ asset('assets/admin/plugins/skycons/skycons.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/fullcalendar/vanillaCalendar.js') }}"></script>

<script src="{{ asset('assets/admin/plugins/raphael/raphael-min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/morris/morris.min.js') }}"></script>

<script src="{{ asset('assets/admin/pages/dashborad.js') }}"></script>

<!-- App js -->
<script src="{{ asset('assets/admin/js/app.js') }}"></script>

@stack('scripts')

<script>
// Styled messages + confirm (no browser popups)
(function() {
    function ensureToastStyles() {
        if (document.getElementById('appToastStyles')) return;
        const style = document.createElement('style');
        style.id = 'appToastStyles';
        style.textContent = `
            .app-toast-container{position:fixed;top:18px;left:50%;transform:translateX(-50%);z-index:100000;pointer-events:none;width:calc(100% - 32px);max-width:520px;}
            .app-toast{pointer-events:none;display:flex;align-items:center;gap:12px;padding:14px 16px;border-radius:14px;box-shadow:0 10px 30px rgba(0,0,0,.25);font-weight:600;line-height:1.35;}
            .app-toast__icon{width:34px;height:34px;border-radius:999px;display:flex;align-items:center;justify-content:center;flex:0 0 auto;background:rgba(255,255,255,.18);}
            .app-toast__icon i{color:#fff;font-size:16px;}
            .app-toast__text{color:#fff;font-size:14px;}
            .app-toast--success{background:#FFB21E;}
            .app-toast--error{background:#ff4444;}
            .app-toast--info{background:#6366F1;}
            .app-toast-enter{animation:appToastIn .25s ease-out;}
            .app-toast-exit{animation:appToastOut .25s ease-in forwards;}
            @keyframes appToastIn{from{transform:translateY(-8px);opacity:0}to{transform:translateY(0);opacity:1}}
            @keyframes appToastOut{from{transform:translateY(0);opacity:1}to{transform:translateY(-8px);opacity:0}}
        `;
        document.head.appendChild(style);
    }

    function getContainer() {
        let container = document.getElementById('appToastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'appToastContainer';
            container.className = 'app-toast-container';
            document.body.appendChild(container);
        }
        return container;
    }

    window.showAppMessage = function(message, type = 'success') {
        ensureToastStyles();
        const container = getContainer();
        container.innerHTML = '';

        const toast = document.createElement('div');
        toast.className = `app-toast app-toast--${type} app-toast-enter`;

        const icon = document.createElement('div');
        icon.className = 'app-toast__icon';
        const iconI = document.createElement('i');
        iconI.className = type === 'success' ? 'mdi mdi-check' : (type === 'error' ? 'mdi mdi-close' : 'mdi mdi-information');
        icon.appendChild(iconI);

        const text = document.createElement('div');
        text.className = 'app-toast__text';
        text.textContent = message;

        toast.appendChild(icon);
        toast.appendChild(text);
        container.appendChild(toast);

        setTimeout(() => {
            toast.classList.remove('app-toast-enter');
            toast.classList.add('app-toast-exit');
            setTimeout(() => {
                if (toast.parentNode) toast.parentNode.removeChild(toast);
            }, 260);
        }, 2500);
    };

    window.showSuccessMessage = function(message) { window.showAppMessage(message, 'success'); };
    window.showErrorMessage = function(message) { window.showAppMessage(message, 'error'); };
    window.showInfoMessage = function(message) { window.showAppMessage(message, 'info'); };

    window.showConfirmDialog = function(message, onConfirm, onCancel) {
        ensureToastStyles();
        const existing = document.getElementById('appConfirmOverlay');
        if (existing) existing.remove();

        const overlay = document.createElement('div');
        overlay.id = 'appConfirmOverlay';
        overlay.style.cssText = 'position:fixed;inset:0;z-index:100001;background:rgba(0,0,0,.55);display:flex;align-items:center;justify-content:center;padding:16px;';

        const modal = document.createElement('div');
        modal.style.cssText = 'width:100%;max-width:420px;border-radius:16px;background:#ffffff;border:1px solid rgba(0,0,0,.08);box-shadow:0 18px 50px rgba(0,0,0,.25);padding:18px;';

        const title = document.createElement('div');
        title.textContent = 'Confirm';
        title.style.cssText = 'font-weight:800;color:#111827;font-size:16px;margin-bottom:10px;';

        const body = document.createElement('div');
        body.textContent = message;
        body.style.cssText = 'color:#374151;font-size:14px;line-height:1.5;margin-bottom:14px;white-space:pre-line;';

        const actions = document.createElement('div');
        actions.style.cssText = 'display:flex;gap:10px;justify-content:flex-end;';

        const cancelBtn = document.createElement('button');
        cancelBtn.type = 'button';
        cancelBtn.textContent = 'Cancel';
        cancelBtn.className = 'btn btn-light';

        const okBtn = document.createElement('button');
        okBtn.type = 'button';
        okBtn.textContent = 'OK';
        okBtn.className = 'btn btn-warning';

        function close() { overlay.remove(); }

        cancelBtn.addEventListener('click', function() {
            close();
            if (typeof onCancel === 'function') onCancel();
        });
        okBtn.addEventListener('click', function() {
            close();
            if (typeof onConfirm === 'function') onConfirm();
        });
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                close();
                if (typeof onCancel === 'function') onCancel();
            }
        });

        actions.appendChild(cancelBtn);
        actions.appendChild(okBtn);
        modal.appendChild(title);
        modal.appendChild(body);
        modal.appendChild(actions);
        overlay.appendChild(modal);
        document.body.appendChild(overlay);
    };

    // Intercept forms that declare data-confirm
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form && form.matches && form.matches('form[data-confirm]')) {
            e.preventDefault();
            const msg = form.getAttribute('data-confirm') || 'Are you sure?';
            window.showConfirmDialog(msg, function() {
                form.submit();
            });
        }
    }, true);

    if (typeof window.alert === 'function') {
        window.alert = function(message) {
            window.showInfoMessage(String(message ?? ''));
        };
    }

    if (typeof window.confirm === 'function') {
        window.confirm = function(message) {
            window.showConfirmDialog(String(message ?? ''), function() {}, function() {});
            return false;
        };
    }
})();

$(document).ready(function() {
    // Function to check if we're on a specific page
    function isOnPage(pathSegment) {
        return window.location.pathname.includes('/admin/' + pathSegment);
    }
    
    // Mark page as visited and store the count when on that page
    if (isOnPage('deposits')) {
        // Get current count and store it as the "seen" count
        $.ajax({
            url: '{{ route("admin.pending-counts") }}',
            method: 'GET',
            async: false,
            success: function(data) {
                if (data.success) {
                    sessionStorage.setItem('deposits_seen_count', data.pending_deposits_count || 0);
                }
            }
        });
        $('#pending-deposits-badge').hide();
    }
    
    if (isOnPage('withdrawals')) {
        $.ajax({
            url: '{{ route("admin.pending-counts") }}',
            method: 'GET',
            async: false,
            success: function(data) {
                if (data.success) {
                    sessionStorage.setItem('withdrawals_seen_count', data.pending_withdrawals_count || 0);
                }
            }
        });
        $('#pending-withdrawals-badge').hide();
    }
    
    if (isOnPage('chats')) {
        $.ajax({
            url: '{{ route("admin.pending-counts") }}',
            method: 'GET',
            async: false,
            success: function(data) {
                if (data.success) {
                    sessionStorage.setItem('chats_seen_count', data.unread_chats_count || 0);
                }
            }
        });
        $('#unread-chats-badge').hide();
    }
    
    // Function to update badge counts
    function updateBadgeCounts() {
        // Don't update if we're on one of these pages
        if (isOnPage('deposits') || isOnPage('withdrawals') || isOnPage('chats')) {
            return;
        }
        
        $.ajax({
            url: '{{ route("admin.pending-counts") }}',
            method: 'GET',
            success: function(data) {
                if (data.success) {
                    // Update pending deposits badge
                    const depositsBadge = $('#pending-deposits-badge');
                    const currentDepositsCount = data.pending_deposits_count || 0;
                    const seenDepositsCount = parseInt(sessionStorage.getItem('deposits_seen_count') || '0');
                    
                    // Only show if current count is greater than what was seen
                    if (currentDepositsCount > seenDepositsCount) {
                        depositsBadge.text(currentDepositsCount).show();
                    } else {
                        depositsBadge.hide();
                    }
                    
                    // Update pending withdrawals badge
                    const withdrawalsBadge = $('#pending-withdrawals-badge');
                    const currentWithdrawalsCount = data.pending_withdrawals_count || 0;
                    const seenWithdrawalsCount = parseInt(sessionStorage.getItem('withdrawals_seen_count') || '0');
                    
                    if (currentWithdrawalsCount > seenWithdrawalsCount) {
                        withdrawalsBadge.text(currentWithdrawalsCount).show();
                    } else {
                        withdrawalsBadge.hide();
                    }
                    
                    // Update unread chats badge
                    const chatsBadge = $('#unread-chats-badge');
                    const currentChatsCount = data.unread_chats_count || 0;
                    const seenChatsCount = parseInt(sessionStorage.getItem('chats_seen_count') || '0');
                    
                    if (currentChatsCount > seenChatsCount) {
                        chatsBadge.text(currentChatsCount).show();
                    } else {
                        chatsBadge.hide();
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error updating badge counts:', error);
            }
        });
    }
    
    // Hide badges when on corresponding pages
    function hideBadgesOnCurrentPage() {
        if (isOnPage('deposits')) {
            $('#pending-deposits-badge').hide();
        }
        if (isOnPage('withdrawals')) {
            $('#pending-withdrawals-badge').hide();
        }
        if (isOnPage('chats')) {
            $('#unread-chats-badge').hide();
        }
    }
    
    // Hide badges immediately on page load if on corresponding pages
    hideBadgesOnCurrentPage();
    
    // Initial update (only if not on those pages)
    if (!isOnPage('deposits') && !isOnPage('withdrawals') && !isOnPage('chats')) {
        updateBadgeCounts();
    }
    
    // Update badges every 30 seconds (only if not on those pages)
    setInterval(function() {
        if (!isOnPage('deposits') && !isOnPage('withdrawals') && !isOnPage('chats')) {
            updateBadgeCounts();
        }
        hideBadgesOnCurrentPage();
    }, 30000);
    
    // Admin Notifications
    function loadAdminNotifications() {
        $.ajax({
            url: '{{ route("admin.notifications") }}',
            method: 'GET',
            success: function(data) {
                if (data.success) {
                    const badge = $('#admin-notification-badge');
                    const countBadge = $('#admin-notification-count');
                    const notificationsList = $('#admin-notifications-list');
                    const viewAllLink = $('#view-all-notifications');
                    
                    // Update badge count
                    if (data.total_count > 0) {
                        badge.text(data.total_count).show();
                        countBadge.text(data.total_count);
                    } else {
                        badge.hide();
                        countBadge.text('0');
                    }
                    
                    // Update notifications list
                    if (data.notifications && data.notifications.length > 0) {
                        let html = '';
                        data.notifications.forEach(function(notification) {
                            html += `
                                <a href="${notification.url}" class="dropdown-item notify-item">
                                    <div class="notify-icon ${notification.icon_bg}">
                                        <i class="mdi ${notification.icon}"></i>
                                    </div>
                                    <p class="notify-details">
                                        <b>${notification.title}</b>
                                        <small class="text-muted">${notification.message}</small>
                                        <small class="text-muted d-block">${notification.time}</small>
                                    </p>
                                </a>
                            `;
                        });
                        notificationsList.html(html);
                        viewAllLink.show();
                    } else {
                        notificationsList.html('<div class="dropdown-item notify-item text-center"><p class="text-muted mb-0">No notifications</p></div>');
                        viewAllLink.hide();
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading notifications:', error);
            }
        });
    }
    
    // Load notifications on page load
    loadAdminNotifications();
    
    // Update notifications every 30 seconds
    setInterval(function() {
        loadAdminNotifications();
    }, 30000);
});
</script>

</body>

</html>

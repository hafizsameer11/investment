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
// Sidebar Badge Updates
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

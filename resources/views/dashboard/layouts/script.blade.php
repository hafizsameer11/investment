@stack('scripts')
<script>
    // Global function for sidebar toggle - works even if dashboard.js hasn't loaded
    function toggleMobileSidebar(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        const sidebar = document.getElementById('dashboardSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        if (!sidebar) {
            console.error('Sidebar not found');
            return false;
        }
        
        const isActive = sidebar.classList.contains('active');
        
        if (isActive) {
            sidebar.classList.remove('active');
            if (sidebarOverlay) {
                sidebarOverlay.classList.remove('active');
            }
            document.body.style.overflow = '';
        } else {
            sidebar.classList.add('active');
            if (sidebarOverlay) {
                sidebarOverlay.classList.add('active');
            }
            document.body.style.overflow = 'hidden';
        }
        
        return false;
    }
    
    // Also add event listener as backup
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle) {
            // Remove onclick and add event listener instead for better control
            sidebarToggle.removeAttribute('onclick');
            sidebarToggle.addEventListener('click', function(e) {
                toggleMobileSidebar(e);
            }, true);
            
            sidebarToggle.addEventListener('touchend', function(e) {
                e.preventDefault();
                toggleMobileSidebar(e);
            }, true);
            
            // Ensure it's clickable
            sidebarToggle.style.pointerEvents = 'auto';
            sidebarToggle.style.cursor = 'pointer';
            sidebarToggle.style.zIndex = '10002';
        }
    });

    // Mobile bottom navigation visibility control - Hide on desktop
    function toggleMobileNavVisibility() {
        const mobileNav = document.getElementById('mobileBottomNav');
        if (mobileNav) {
            if (window.innerWidth > 768) {
                // Desktop: Hide mobile navigation
                mobileNav.style.display = 'none';
                mobileNav.style.visibility = 'hidden';
                mobileNav.style.opacity = '0';
            } else {
                // Mobile: Show mobile navigation (CSS will handle this, but ensure it's visible)
                mobileNav.style.display = '';
                mobileNav.style.visibility = '';
                mobileNav.style.opacity = '';
            }
        }
    }

    // Run on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleMobileNavVisibility();
        
        // Run on window resize
        window.addEventListener('resize', toggleMobileNavVisibility);
    });

    // Mobile bottom navigation active state
    document.addEventListener('DOMContentLoaded', function() {
        const currentRoute = '{{ request()->route() ? request()->route()->getName() : "" }}';
        const navItems = document.querySelectorAll('.mobile-nav-item');
        
        if (currentRoute && navItems.length > 0) {
            navItems.forEach(function(item) {
                const itemRoute = item.getAttribute('data-route');
                if (itemRoute === currentRoute) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
        }
    });

    // Back navigation function for mobile header
    function goBack() {
        // Check if there's history to go back to
        if (window.history.length > 1) {
            // Check if we can go back within the dashboard
            const referrer = document.referrer;
            if (referrer && referrer.includes('/user/dashboard')) {
                window.history.back();
            } else {
                // If coming from outside, go to dashboard home
                window.location.href = '{{ route("dashboard.index") }}';
            }
        } else {
            // No history, go to dashboard home
            window.location.href = '{{ route("dashboard.index") }}';
        }
    }

    // Notification Panel Toggle - Desktop Only, Mobile redirects to page
    document.addEventListener('DOMContentLoaded', function() {
        const notificationIcon = document.getElementById('notificationIcon');
        const notificationPanel = document.getElementById('notificationPanel');
        const notificationPanelBody = document.getElementById('notificationPanelBody');
        const notificationBadge = document.getElementById('notificationBadge');
        
        // Load unread count on page load
        loadUnreadCount();
        
        // Load notifications when panel is opened
        if (notificationIcon) {
            // Function to check if device is mobile
            function isMobile() {
                return window.innerWidth <= 768;
            }

            // Handle notification icon click
            notificationIcon.addEventListener('click', function(e) {
                e.stopPropagation();
                
                // On mobile, redirect to notifications page
                if (isMobile()) {
                    window.location.href = '{{ route("notifications.index") }}';
                    return;
                }
                
                // On desktop, toggle dropdown panel
                if (notificationPanel) {
                    const isActive = notificationPanel.classList.contains('active');
                    notificationPanel.classList.toggle('active');
                    
                    // Load notifications when opening panel
                    if (!isActive) {
                        loadNotifications();
                    }
                }
            });

            // Desktop-only: Close notification panel when clicking outside
            if (notificationPanel) {
                document.addEventListener('click', function(e) {
                    if (!isMobile()) {
                        const isClickInside = notificationPanel.contains(e.target) || notificationIcon.contains(e.target);
                        if (!isClickInside && notificationPanel.classList.contains('active')) {
                            notificationPanel.classList.remove('active');
                        }
                    }
                });

                // Desktop-only: Close notification panel on escape key
                document.addEventListener('keydown', function(e) {
                    if (!isMobile() && e.key === 'Escape' && notificationPanel.classList.contains('active')) {
                        notificationPanel.classList.remove('active');
                    }
                });
            }
        }

        // Load notifications from API
        function loadNotifications() {
            if (!notificationPanelBody) return;
            
            notificationPanelBody.innerHTML = '<div class="notification-loading" style="text-align: center; padding: 1rem; color: var(--text-secondary);"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
            
            fetch('{{ route("notifications.index") }}?ajax=1', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (!data.success || !data.notifications || data.notifications.length === 0) {
                        notificationPanelBody.innerHTML = '<div style="text-align: center; padding: 2rem; color: var(--text-secondary);">No notifications</div>';
                        return;
                    }
                    
                    // Get first 5 notifications
                    let htmlContent = '';
                    data.notifications.slice(0, 5).forEach(notification => {
                        const isUnread = !notification.is_read;
                        
                        htmlContent += `
                            <div class="notification-item ${isUnread ? 'unread' : ''}" data-notification-id="${notification.id}" onclick="markNotificationAsRead(${notification.id})">
                                <div class="notification-icon-wrapper">
                                    <i class="fas fa-bell"></i>
                                    ${isUnread ? '<span class="notification-dot"></span>' : ''}
                                </div>
                                <div class="notification-content">
                                    <div class="notification-greeting">${notification.title}</div>
                                    <div class="notification-message">${notification.message}</div>
                                    <div class="notification-time">${notification.created_at}</div>
                                </div>
                            </div>
                        `;
                    });
                    
                    notificationPanelBody.innerHTML = htmlContent;
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    notificationPanelBody.innerHTML = '<div style="text-align: center; padding: 2rem; color: var(--text-secondary);">Error loading notifications</div>';
                });
        }

        // Load unread count
        function loadUnreadCount() {
            fetch('{{ route("notifications.unread-count") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success && notificationBadge) {
                        if (data.count > 0) {
                            notificationBadge.textContent = data.count > 99 ? '99+' : data.count;
                            notificationBadge.style.display = 'flex';
                        } else {
                            notificationBadge.style.display = 'none';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading unread count:', error);
                });
        }

        // Mark notification as read
        window.markNotificationAsRead = function(notificationId) {
            fetch(`{{ route("notifications.mark-read", "") }}/${notificationId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = document.querySelector(`[data-notification-id="${notificationId}"]`);
                    if (item) {
                        item.classList.remove('unread');
                        const dot = item.querySelector('.notification-dot');
                        if (dot) dot.remove();
                    }
                    loadUnreadCount();
                }
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
            });
        };

        // Refresh unread count every 30 seconds
        setInterval(loadUnreadCount, 30000);
    });
</script>
<script src="{{ asset('assets/dashboard/js/dashboard.js') }}"></script>

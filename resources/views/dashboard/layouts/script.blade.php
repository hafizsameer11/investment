@stack('scripts')
<script>
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
            iconI.className = type === 'success' ? 'fas fa-check' : (type === 'error' ? 'fas fa-times' : 'fas fa-info');
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
            }, 6000);
        };

        window.showSuccessMessage = function(message) {
            window.showAppMessage(message, 'success');
        };

        window.showErrorMessage = function(message) {
            window.showAppMessage(message, 'error');
        };

        window.showInfoMessage = function(message) {
            window.showAppMessage(message, 'info');
        };

        if (typeof window.alert === 'function') {
            window.alert = function(message) {
                window.showInfoMessage(String(message ?? ''));
            };
        }

        window.showConfirmDialog = function(message, onConfirm, onCancel) {
            ensureToastStyles();
            const existing = document.getElementById('appConfirmOverlay');
            if (existing) existing.remove();

            const overlay = document.createElement('div');
            overlay.id = 'appConfirmOverlay';
            overlay.style.cssText = 'position:fixed;inset:0;z-index:100001;background:rgba(0,0,0,.55);display:flex;align-items:center;justify-content:center;padding:16px;';

            const modal = document.createElement('div');
            modal.style.cssText = 'width:100%;max-width:420px;border-radius:16px;background:#1f2433;border:1px solid rgba(255,255,255,.08);box-shadow:0 18px 50px rgba(0,0,0,.45);padding:18px;';

            const title = document.createElement('div');
            title.textContent = 'Confirm';
            title.style.cssText = 'font-weight:800;color:#fff;font-size:16px;margin-bottom:10px;';

            const body = document.createElement('div');
            body.textContent = message;
            body.style.cssText = 'color:rgba(255,255,255,.85);font-size:14px;line-height:1.5;margin-bottom:14px;white-space:pre-line;';

            const actions = document.createElement('div');
            actions.style.cssText = 'display:flex;gap:10px;justify-content:flex-end;';

            const cancelBtn = document.createElement('button');
            cancelBtn.type = 'button';
            cancelBtn.textContent = 'Cancel';
            cancelBtn.style.cssText = 'padding:10px 14px;border-radius:12px;border:1px solid rgba(255,255,255,.12);background:transparent;color:#fff;font-weight:700;cursor:pointer;';

            const okBtn = document.createElement('button');
            okBtn.type = 'button';
            okBtn.textContent = 'OK';
            okBtn.style.cssText = 'padding:10px 14px;border-radius:12px;border:none;background:#FFB21E;color:#fff;font-weight:800;cursor:pointer;';

            function close() {
                overlay.remove();
            }

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

            document.addEventListener('keydown', function escHandler(e) {
                if (e.key === 'Escape') {
                    document.removeEventListener('keydown', escHandler);
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
    })();

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

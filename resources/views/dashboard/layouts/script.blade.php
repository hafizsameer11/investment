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
                    notificationPanel.classList.toggle('active');
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
    });
</script>
<script src="{{ asset('assets/dashboard/js/dashboard.js') }}"></script>

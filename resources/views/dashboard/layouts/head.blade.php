<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Core Mining - Dashboard')</title>
<link rel="stylesheet" href="{{ asset('dashboard/css/dashboard.css') }}">
@stack('styles')
<style>
    /* Global Sidebar Responsive Styles - Applied to All Pages */
    @media (max-width: 768px) {
        .dashboard-sidebar {
            transform: translateX(-100%);
            width: 300px;
            z-index: 9999;
        }

        .dashboard-sidebar.active {
            transform: translateX(0);
            z-index: 9999;
        }

        /* Mobile Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9998;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
            pointer-events: auto;
        }

        /* Ensure nav links are clickable on mobile */
        .dashboard-sidebar.active .nav-link {
            pointer-events: auto !important;
            touch-action: manipulation;
            -webkit-tap-highlight-color: rgba(0, 255, 136, 0.3);
            cursor: pointer;
            position: relative;
            z-index: 10001;
            min-height: 48px;
            display: flex;
            align-items: center;
        }

        .dashboard-sidebar.active .nav-item {
            pointer-events: auto !important;
            position: relative;
            z-index: 10001;
        }

        .dashboard-sidebar.active .sidebar-content {
            pointer-events: auto !important;
            position: relative;
            z-index: 10001;
        }

        .dashboard-sidebar.active .sidebar-nav {
            pointer-events: auto !important;
            position: relative;
            z-index: 10001;
        }

        .dashboard-sidebar.active .nav-menu {
            pointer-events: auto !important;
        }

        /* Child elements should not block clicks */
        .dashboard-sidebar.active .nav-link .nav-icon-wrapper,
        .dashboard-sidebar.active .nav-link .nav-text,
        .dashboard-sidebar.active .nav-link .nav-indicator {
            pointer-events: none;
        }

        /* Better touch targets on mobile */
        .dashboard-sidebar.active .nav-link {
            min-height: 48px;
            padding: 1rem 1.25rem;
        }

        /* Navigation Toggle Button - Global Fix */
        .sidebar-toggle {
            display: flex !important;
            z-index: 10002 !important;
            position: relative !important;
            min-width: 44px !important;
            min-height: 44px !important;
            touch-action: manipulation !important;
            pointer-events: auto !important;
            -webkit-tap-highlight-color: rgba(0, 255, 136, 0.3) !important;
            background: none !important;
            border: none !important;
            cursor: pointer !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .sidebar-toggle:active,
        .sidebar-toggle:focus {
            outline: 2px solid rgba(0, 255, 136, 0.5) !important;
            outline-offset: 2px !important;
        }

        .sidebar-toggle i {
            pointer-events: none !important;
            z-index: 0 !important;
        }

        .header-left {
            position: relative !important;
            z-index: 10002 !important;
            pointer-events: auto !important;
        }

        .dashboard-header {
            z-index: 10001 !important;
            position: sticky !important;
        }

        .header-content {
            z-index: 10002 !important;
            position: relative !important;
        }

        /* Move logo downward on mobile for better visibility */
        .sidebar-content {
            padding-top: 4rem !important;
        }

        .sidebar-logo {
            margin-top: 1rem !important;
            padding-top: 1rem !important;
        }
    }
</style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

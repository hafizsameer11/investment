<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Core Mining - Dashboard')</title>
<link rel="stylesheet" href="{{ asset('dashboard/css/dashboard.css') }}">
@stack('styles')
<style>
    /* Mobile-first improvements - Sidebar is hidden on mobile, using bottom nav instead */
    @media (max-width: 768px) {
        /* Sidebar is completely hidden on mobile - handled in dashboard.css */
        
        /* Header adjustments for mobile */
        .dashboard-header {
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .header-content {
            padding: 1rem;
        }

        /* Improved mobile typography */
        body {
            font-size: 14px;
            line-height: 1.5;
        }

        h1, h2, h3, h4, h5, h6 {
            line-height: 1.3;
        }

        /* Better spacing for mobile content */
        .content-area {
            padding: 1rem;
            padding-bottom: 1rem;
        }

        /* Ensure proper scrolling on mobile */
        .main-content {
            overflow-x: hidden;
        }
    }

    /* Mobile App Interface - Max-width 390px */
    @media (max-width: 390px) {
        /* Compact header for mobile app - Blended with page background */
        .dashboard-header {
            position: sticky;
            top: 0;
            z-index: 999;
            background: var(--bg-primary) !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            border-bottom: none !important;
            box-shadow: none !important;
        }

        .header-content {
            padding: 4px 1rem;
        }

        /* Mobile app typography */
        body {
            font-size: 13px;
            line-height: 1.4;
        }

        /* Remove padding from content area on mobile app view */
        .content-area {
            padding: 0;
            padding-bottom: 80px; /* Space for bottom nav */
        }

        /* Ensure no horizontal scroll */
        .main-content {
            overflow-x: hidden;
            max-width: 100%;
        }

        /* Mobile user profile styling */
        .mobile-user-profile {
            display: flex !important;
        }
        
        .mobile-user-profile.mobile-user-profile-visible {
            margin-left: -1.5rem !important;
        }
        
        .mobile-user-avatar {
            width: 36px;
            height: 36px;
        }
        
        .mobile-user-name {
            font-size: 0.8125rem;
        }
        
        .mobile-user-email {
            font-size: 0.6875rem;
        }

        .notification-icon {
            font-size: 1.125rem;
            padding: 0.5rem;
        }
        
        .header-right {
            margin-right: -1rem !important;
        }
    }
</style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

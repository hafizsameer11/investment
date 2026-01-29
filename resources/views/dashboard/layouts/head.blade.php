<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
@auth
<meta name="user-id" content="{{ auth()->user()->id }}">
@endauth
<title>@yield('title', 'Core Mining ⛏️- AI Gold Mining ⛏️')</title>
<!-- Favicon -->
<link rel="icon" type="image/jpeg" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
<!-- Apple Touch Icons for iOS -->
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
<link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
<link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
<link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
<link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
<link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
<!-- Mobile Web App Meta Tags -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="Core Mining ⛏️">
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/dashboard.css') }}">
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
    @media (max-width: 450px) {
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

    /* Notification Panel Styles - Desktop */
    .notification-wrapper {
        position: relative;
    }

    .notification-panel {
        position: absolute;
        top: calc(100% + 1rem);
        right: 0;
        width: 380px;
        max-height: 500px;
        background: var(--card-bg);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.6);
        z-index: 1000;
        display: none;
        flex-direction: column;
        overflow: hidden;
        animation: slideDown 0.3s ease-out;
    }

    .notification-panel.active {
        display: flex;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .notification-panel-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .notification-panel-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .notification-panel-body {
        max-height: 400px;
        overflow-y: auto;
        padding: 0.75rem 0;
    }

    .notification-panel-body::-webkit-scrollbar {
        width: 6px;
    }

    .notification-panel-body::-webkit-scrollbar-track {
        background: transparent;
    }

    .notification-panel-body::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 3px;
    }

    .notification-item {
        display: flex;
        gap: 1rem;
        padding: 1rem 1.5rem;
        transition: background 0.2s ease;
        cursor: pointer;
    }

    .notification-item:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .notification-icon-wrapper {
        position: relative;
        width: 48px;
        height: 48px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .notification-icon-wrapper i {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 178, 30, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #FFB21E;
        font-size: 1.125rem;
    }

    .notification-dot {
        position: absolute;
        top: 0;
        right: 0;
        width: 12px;
        height: 12px;
        background: #FFB21E;
        border-radius: 50%;
        border: 2px solid var(--card-bg);
    }

    .notification-content {
        flex: 1;
        min-width: 0;
    }

    .notification-greeting {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.375rem;
    }

    .notification-message {
        font-size: 0.875rem;
        color: var(--text-secondary);
        line-height: 1.5;
        margin-bottom: 0.5rem;
    }

    .notification-time {
        font-size: 0.75rem;
        color: var(--text-secondary);
        opacity: 0.7;
    }

    .notification-panel-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        text-align: center;
    }

    .notification-see-all {
        color: #FFB21E;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .notification-see-all:hover {
        color: #FF8A1D;
    }

    /* Hide notification panel on mobile */
    @media (max-width: 768px) {
        .notification-panel {
            display: none !important;
        }
    }
</style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

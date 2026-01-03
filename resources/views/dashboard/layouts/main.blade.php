<!DOCTYPE html>
<html lang="en">
<head>
    @include('dashboard.layouts.head')
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Mobile Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Left Sidebar -->
        @include('dashboard.layouts.sidebar')

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Top Header -->
            @include('dashboard.layouts.header')

            <!-- Page Content -->
            <div class="content-area">
                @yield('content')
            </div>

            <!-- Footer -->
            @include('dashboard.layouts.footer')
        </div>
    </div>

    <!-- Mobile Bottom Navigation -->
    @include('dashboard.layouts.mobile-nav')

    @include('dashboard.layouts.script')
</body>
</html>

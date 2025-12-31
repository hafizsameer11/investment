<!DOCTYPE html>
<html lang="en">
<head>
    @include('dashboard.layouts.head')
</head>
<body>
    <div class="dashboard-wrapper">
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

    <!-- Chat Widget -->
    <div class="chat-widget">
        <div class="chat-bubble">
            <span class="chat-bubble-text">We are online!</span>
        </div>
        <div class="chat-icon-container">
            <div class="chat-icon">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/>
                </svg>
            </div>
            <span class="chat-text">Live Tawk</span>
        </div>
    </div>

    @include('dashboard.layouts.script')
</body>
</html>

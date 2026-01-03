<div class="dashboard-header">
    <div class="header-content">
        <div class="header-left">
            <button class="sidebar-toggle" id="sidebarToggle" onclick="toggleMobileSidebar(event)">
                <i class="fas fa-bars"></i>
            </button>
            <!-- Header Logo (shown on mobile) -->
            <div class="header-logo">
                <div class="logo">
                    <div class="logo-icon-wrapper">
                        <svg class="logo-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Mining Pickaxe Icon -->
                            <path d="M12 2L8 6L10 8L6 12L8 14L12 10L16 14L18 12L14 8L16 6L12 2Z" fill="url(#coreMiningGradient)" stroke="#00FF88" stroke-width="1.5" stroke-linejoin="round"/>
                            <!-- Mining Blocks -->
                            <rect x="4" y="16" width="4" height="4" rx="1" fill="#00FF88" opacity="0.6"/>
                            <rect x="10" y="18" width="4" height="4" rx="1" fill="#00D977" opacity="0.6"/>
                            <rect x="16" y="16" width="4" height="4" rx="1" fill="#00FF88" opacity="0.6"/>
                            <!-- Glow Effect -->
                            <circle cx="12" cy="8" r="8" fill="url(#coreMiningGlow)" opacity="0.3"/>
                            <defs>
                                <linearGradient id="coreMiningGradient" x1="12" y1="2" x2="12" y2="14" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#00FF88"/>
                                    <stop offset="100%" stop-color="#00D977"/>
                                </linearGradient>
                                <radialGradient id="coreMiningGlow" cx="50%" cy="50%">
                                    <stop offset="0%" stop-color="#00FF88" stop-opacity="0.8"/>
                                    <stop offset="100%" stop-color="#00FF88" stop-opacity="0"/>
                                </radialGradient>
                            </defs>
                        </svg>
                    </div>
                    <span class="logo-text">Core Mining</span>
                </div>
            </div>
        </div>

        <div class="header-right">
            <!-- Notifications -->
            <div class="notification-icon">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </div>

            <!-- User Profile -->
            <div class="user-profile">
                <div class="user-avatar">
                    <img src="https://ui-avatars.com/api/?name=Rameez+Nazar&background=00FF88&color=000&size=128" alt="User Avatar">
                </div>
                <div class="user-info">
                    <div class="user-name">Rameez Nazar</div>
                    <div class="user-email">ramiznazar600@gmail.com</div>
                </div>
            </div>
        </div>
    </div>
</div>

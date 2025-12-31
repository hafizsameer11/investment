/**
 * Dashboard - JavaScript
 * Handles sidebar, navigation, and interactive elements
 */

(function() {
    'use strict';

    // DOM Elements
    const sidebar = document.getElementById('dashboardSidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const balanceToggle = document.getElementById('balanceToggle');
    const balanceAmount = document.getElementById('balanceAmount');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeSlashIcon = document.getElementById('eyeSlashIcon');

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        initSidebarToggle();
        highlightActiveNavItem();
        // Only initialize balance toggle if on dashboard page
        if (document.getElementById('balanceToggle')) {
            initBalanceToggle();
        }
        initSmoothScroll();
        // Only initialize charts if on dashboard page
        if (document.getElementById('investmentChart') || document.getElementById('profitChart')) {
            initCharts();
        }
    });

    /**
     * Highlight Active Navigation Item
     */
    function highlightActiveNavItem() {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.sidebar-nav .nav-link');
        
        navLinks.forEach(link => {
            const navItem = link.closest('.nav-item');
            if (navItem) {
                navItem.classList.remove('active');
                const href = link.getAttribute('href');
                if (href) {
                    try {
                        const linkUrl = new URL(href, window.location.origin);
                        const linkPath = linkUrl.pathname;
                        
                        // Check if current path matches the link path
                        if (currentPath === linkPath || 
                            (currentPath === '/user/dashboard' && linkPath === '/user/dashboard') ||
                            (currentPath === '/user/dashboard/' && linkPath === '/user/dashboard') ||
                            (currentPath.includes('/user/dashboard/wallet') && linkPath.includes('/wallet'))) {
                            navItem.classList.add('active');
                        }
                    } catch (e) {
                        // Fallback for relative URLs
                        if (href.startsWith('/')) {
                            if (currentPath === href || currentPath.startsWith(href + '/')) {
                                navItem.classList.add('active');
                            }
                        }
                    }
                }
            }
        });
    }

    /**
     * Sidebar Toggle (Mobile)
     */
    function initSidebarToggle() {
        if (!sidebarToggle || !sidebar) return;

        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });

        // Close sidebar when clicking outside (mobile)
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    }

    /**
     * Balance Toggle (Show/Hide Balance)
     */
    function initBalanceToggle() {
        if (!balanceToggle || !balanceAmount) return;

        let isVisible = true;

        balanceToggle.addEventListener('click', function() {
            isVisible = !isVisible;

            if (isVisible) {
                balanceAmount.style.opacity = '1';
                eyeIcon.style.display = 'block';
                eyeSlashIcon.style.display = 'none';
            } else {
                balanceAmount.style.opacity = '0.3';
                eyeIcon.style.display = 'none';
                eyeSlashIcon.style.display = 'block';
            }
        });
    }


    /**
     * Smooth Scroll
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href !== '') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });
    }

    /**
     * Handle Window Resize
     */
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && sidebar) {
            sidebar.classList.remove('active');
        }
    });

    /**
     * Chat Widget Interaction
     */
    const chatWidget = document.querySelector('.chat-icon-container');
    if (chatWidget) {
        chatWidget.addEventListener('click', function() {
            // Placeholder for chat widget integration
            console.log('Chat widget clicked');
            // You can integrate with Tawk.to or other chat services here
        });
    }

    /**
     * Card Hover Effects
     */
    const cards = document.querySelectorAll('.summary-card, .balance-card, .live-earning-card, .active-investment-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    /**
     * Button Click Animations
     */
    const buttons = document.querySelectorAll('button, .social-btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Create ripple effect
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');

            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    /**
     * Add Ripple Effect Styles
     */
    const style = document.createElement('style');
    style.textContent = `
        button, .social-btn {
            position: relative;
            overflow: hidden;
        }
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple-animation 0.6s ease-out;
            pointer-events: none;
        }
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    /**
     * Notification Badge Animation
     */
    const notificationBadge = document.querySelector('.notification-badge');
    if (notificationBadge) {
        setInterval(() => {
            notificationBadge.style.animation = 'none';
            setTimeout(() => {
                notificationBadge.style.animation = 'pulse 1s ease-in-out';
            }, 10);
        }, 3000);
    }

    /**
     * Add Pulse Animation
     */
    const pulseStyle = document.createElement('style');
    pulseStyle.textContent = `
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }
    `;
    document.head.appendChild(pulseStyle);

    /**
     * Live Earning Counter (Demo)
     */
    const earningAmount = document.querySelector('.earning-amount .amount');
    if (earningAmount) {
        let currentValue = 0;
        const targetValue = 0.000000;
        
        // Simulate live updates (demo)
        setInterval(() => {
            if (currentValue < targetValue) {
                currentValue += 0.000001;
                earningAmount.textContent = currentValue.toFixed(6);
            }
        }, 1000);
    }

    /**
     * Initialize Charts
     */
    function initCharts() {
        // Investment Overview Chart (Line Chart)
        const investmentCtx = document.getElementById('investmentChart');
        if (investmentCtx) {
            new Chart(investmentCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [
                        {
                            label: 'Earning',
                            data: [],
                            borderColor: '#00FF88',
                            backgroundColor: 'rgba(0, 255, 136, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Investment',
                            data: [],
                            borderColor: '#00D977',
                            backgroundColor: 'rgba(0, 217, 119, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 4,
                            ticks: {
                                stepSize: 1,
                                color: '#A0A0B0'
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.05)'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#A0A0B0'
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.05)'
                            }
                        }
                    }
                }
            });
        }

        // Profit Analysis Chart (Bar Chart)
        const profitCtx = document.getElementById('profitChart');
        if (profitCtx) {
            new Chart(profitCtx, {
                type: 'bar',
                data: {
                    labels: ['2025-02', '2025-04', '2025-06', '2025-08', '2025-10', '2025-12'],
                    datasets: [{
                        label: 'Profit',
                        data: [],
                        backgroundColor: 'rgba(0, 255, 136, 0.6)',
                        borderColor: '#00FF88',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 4,
                            ticks: {
                                stepSize: 1,
                                color: '#A0A0B0'
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.05)'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#A0A0B0'
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.05)'
                            }
                        }
                    }
                }
            });
        }
    }

})();


/**
 * Investment Plans Page - JavaScript
 * Handles plans page interactions
 */

(function() {
    'use strict';

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        highlightActiveNavItem();
        initPlanButtons();
        initCalculator();
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
                            (currentPath.includes('/user/dashboard/plans') && linkPath.includes('/plans'))) {
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
     * Initialize Plan Buttons
     */
    function initPlanButtons() {
        const startInvestingBtns = document.querySelectorAll('.plan-action-primary-modern, .plan-btn-primary');
        
        startInvestingBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                // Placeholder for investment action
                console.log('Start Investing clicked');
                // You can add modal or redirect logic here
            });
        });
    }

    /**
     * Initialize Investment Calculator
     */
    function initCalculator() {
        const calculatorBtns = document.querySelectorAll('#openCalculatorBtn, .plan-action-secondary-new, .plan-calculator-toggle-new, #calculatorToggle');
        const calculatorSection = document.getElementById('calculatorSection');
        const calculatorContent = document.getElementById('calculatorContent');
        
        calculatorBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (calculatorSection) {
                    const isOpen = calculatorSection.classList.contains('show');
                    
                    if (isOpen) {
                        // Close calculator
                        calculatorSection.classList.remove('show');
                        if (calculatorContent) {
                            calculatorContent.style.display = 'none';
                        }
                        // Update toggle button text
                        const toggleBtn = document.getElementById('calculatorToggle');
                        if (toggleBtn) {
                            const span = toggleBtn.querySelector('span');
                            if (span) span.textContent = 'Open Calculator';
                            const icon = toggleBtn.querySelector('i');
                            if (icon) icon.className = 'fas fa-calculator';
                        }
                    } else {
                        // Open calculator
                        calculatorSection.classList.add('show');
                        if (calculatorContent) {
                            calculatorContent.style.display = 'grid';
                        }
                        // Update toggle button text
                        const toggleBtn = document.getElementById('calculatorToggle');
                        if (toggleBtn) {
                            const span = toggleBtn.querySelector('span');
                            if (span) span.textContent = 'Close Calculator';
                            const icon = toggleBtn.querySelector('i');
                            if (icon) icon.className = 'fas fa-times';
                        }
                    }
                }
            });
        });
    }

    /**
     * Add Ripple Effect to Buttons
     */
    const planButtons = document.querySelectorAll('.plan-action-btn-modern, .plan-btn');
    planButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('plan-ripple');

            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Add ripple styles
    const style = document.createElement('style');
    style.textContent = `
        .plan-btn {
            position: relative;
            overflow: hidden;
        }
        .plan-ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: plan-ripple-animation 0.6s ease-out;
            pointer-events: none;
        }
        @keyframes plan-ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

})();


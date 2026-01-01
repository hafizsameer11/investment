/**
 * Targets Page - JavaScript
 * Handles targets page interactions
 */

(function() {
    'use strict';

    // DOM Elements
    const progressFill = document.getElementById('targetProgressFill');
    const progressPercent = document.getElementById('targetProgressPercent');

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        highlightActiveNavItem();
        initProgressBar();
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
                            (currentPath.includes('/user/dashboard/targets') && linkPath.includes('/targets'))) {
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
     * Initialize Progress Bar Animation
     */
    function initProgressBar() {
        if (!progressFill || !progressPercent) return;

        // Get progress value (currently 0%, but can be updated from backend)
        const progressValue = 0; // This should come from backend/API
        
        // Animate progress bar on page load
        setTimeout(() => {
            progressFill.style.width = progressValue + '%';
            progressPercent.textContent = progressValue + '%';
        }, 300);

        // Animate progress bar when it comes into view
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Reset and animate
                    progressFill.style.width = '0%';
                    setTimeout(() => {
                        progressFill.style.width = progressValue + '%';
                        progressPercent.textContent = progressValue + '%';
                    }, 100);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        observer.observe(progressFill);
    }

    /**
     * Update Progress (can be called when target progress changes)
     */
    function updateProgress(newProgress) {
        if (!progressFill || !progressPercent) return;
        
        const clampedProgress = Math.max(0, Math.min(100, newProgress));
        
        progressFill.style.width = clampedProgress + '%';
        progressPercent.textContent = clampedProgress + '%';
    }

    // Expose updateProgress function globally if needed
    window.updateTargetProgress = updateProgress;

})();







/**
 * Wallet Page - JavaScript
 * Handles wallet page interactions
 */

(function() {
    'use strict';

    // DOM Elements
    const balanceToggle = document.getElementById('balanceToggleWallet');
    const balanceAmount = document.getElementById('balanceAmountWallet');
    const eyeIcon = document.getElementById('eyeIconWallet');
    const eyeSlashIcon = document.getElementById('eyeSlashIconWallet');

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        initBalanceToggle();
        highlightActiveNavItem();
        initSearch();
        initFilters();
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
     * Balance Toggle (Show/Hide Balance)
     */
    function initBalanceToggle() {
        if (!balanceToggle || !balanceAmount) return;

        let isVisible = true;

        balanceToggle.addEventListener('click', function() {
            isVisible = !isVisible;

            if (isVisible) {
                balanceAmount.style.opacity = '1';
                balanceAmount.style.filter = 'blur(0px)';
                eyeIcon.style.display = 'block';
                eyeSlashIcon.style.display = 'none';
            } else {
                balanceAmount.style.opacity = '0.5';
                balanceAmount.style.filter = 'blur(4px)';
                eyeIcon.style.display = 'none';
                eyeSlashIcon.style.display = 'block';
            }
        });
    }

    /**
     * Initialize Search Functionality
     */
    function initSearch() {
        const searchInput = document.getElementById('walletSearchInput') || document.querySelector('.wallet-search-input-modern');
        if (!searchInput) return;

        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const tableRows = document.querySelectorAll('.wallet-table-modern tbody tr, .wallet-transactions-table tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    /**
     * Initialize Filter Functionality
     */
    function initFilters() {
        const dateFilter = document.getElementById('walletDateFilter') || document.querySelector('.wallet-date-select-modern') || document.querySelector('.wallet-date-filter');
        if (dateFilter) {
            dateFilter.addEventListener('change', function(e) {
                // Filter logic can be implemented here
                console.log('Date filter changed:', e.target.value);
            });
        }

        const filterBtn = document.querySelector('.wallet-filter-btn-modern') || document.querySelector('.wallet-filter-btn');
        if (filterBtn) {
            filterBtn.addEventListener('click', function() {
                // Filter modal or dropdown can be implemented here
                console.log('Filter button clicked');
            });
        }
    }

})();

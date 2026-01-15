/**
 * Transactions Page - JavaScript
 * Handles transactions page interactions
 */

(function() {
    'use strict';

    // DOM Elements
    const searchInput = document.getElementById('transactionSearch');
    const dateFilter = document.getElementById('transactionDateFilter');
    const filterBtn = document.querySelector('.transactions-filter-btn-modern, .transactions-filter-btn');
    const tableBody = document.getElementById('transactionsTableBody');
    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');
    const currentPageSpan = document.getElementById('currentPage');
    const totalPagesSpan = document.getElementById('totalPages');

    // State
    let currentPage = 1;
    let filteredTransactions = [];

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        highlightActiveNavItem();
        initSearch();
        initDateFilter();
        initFilter();
        initPagination();
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
                            (currentPath.includes('/user/dashboard/transactions') && linkPath.includes('/transactions'))) {
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
     * Initialize Search Functionality
     */
    function initSearch() {
        if (!searchInput) return;

        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            filterTransactions(searchTerm);
        });
    }

    /**
     * Initialize Date Filter
     */
    function initDateFilter() {
        if (!dateFilter) return;

        dateFilter.addEventListener('change', function(e) {
            const days = e.target.value;
            filterByDate(days);
        });
    }

    /**
     * Initialize Filter Button
     */
    function initFilter() {
        if (!filterBtn) return;

        filterBtn.addEventListener('click', function() {
            // Placeholder for filter modal
            console.log('Filter button clicked');
            // You can add a filter modal here
        });
    }

    /**
     * Initialize Pagination
     */
    function initPagination() {
        if (prevPageBtn) {
            prevPageBtn.addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    updatePagination();
                }
            });
        }

        if (nextPageBtn) {
            nextPageBtn.addEventListener('click', function() {
                currentPage++;
                updatePagination();
            });
        }
    }

    /**
     * Filter Transactions by Search Term
     */
    function filterTransactions(searchTerm) {
        const rows = tableBody.querySelectorAll('tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    /**
     * Filter Transactions by Date
     */
    function filterByDate(days) {
        if (days === 'all') {
            // Show all transactions
            const rows = tableBody.querySelectorAll('tr');
            rows.forEach(row => {
                row.style.display = '';
            });
            return;
        }

        const daysAgo = parseInt(days);
        const cutoffDate = new Date();
        cutoffDate.setDate(cutoffDate.getDate() - daysAgo);

        // Filter logic would go here
        // For now, just log the filter
        console.log('Filtering by date:', daysAgo, 'days');
    }

    /**
     * Update Pagination UI
     */
    function updatePagination() {
        if (currentPageSpan) {
            currentPageSpan.textContent = currentPage;
        }

        if (totalPagesSpan) {
            // Calculate total pages based on visible rows
            const visibleRows = tableBody.querySelectorAll('tr:not([style*="display: none"])').length;
            const rowsPerPage = 10; // Adjust as needed
            const totalPages = Math.max(1, Math.ceil(visibleRows / rowsPerPage));
            totalPagesSpan.textContent = totalPages;
        }

        if (prevPageBtn) {
            prevPageBtn.disabled = currentPage === 1;
        }

        if (nextPageBtn) {
            const visibleRows = tableBody.querySelectorAll('tr:not([style*="display: none"])').length;
            const rowsPerPage = 10; // Adjust as needed
            const totalPages = Math.max(1, Math.ceil(visibleRows / rowsPerPage));
            nextPageBtn.disabled = currentPage >= totalPages;
        }
    }

})();


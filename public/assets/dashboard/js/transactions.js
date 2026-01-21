/**
 * Transactions Page - JavaScript
 * Handles transactions page interactions
 */

(function() {
    'use strict';

    // DOM Elements
    const searchInput = document.getElementById('transactionSearch');
    const dateFilter = document.getElementById('transactionDateFilter');
    const filterBtn = document.querySelector('.transactions-filter-btn-new');
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
        initFilters();
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
            applyFilters();
        });
    }

    /**
     * Get start and end of week (Monday to Sunday)
     */
    function getWeekRange(weekType) {
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        
        // Get Monday of current week
        const dayOfWeek = today.getDay();
        const diffToMonday = dayOfWeek === 0 ? -6 : 1 - dayOfWeek; // If Sunday, go back 6 days, else go to Monday
        
        if (weekType === 'this_week') {
            const weekStart = new Date(today);
            weekStart.setDate(today.getDate() + diffToMonday);
            weekStart.setHours(0, 0, 0, 0);
            
            const weekEnd = new Date(weekStart);
            weekEnd.setDate(weekStart.getDate() + 6);
            weekEnd.setHours(23, 59, 59, 999);
            
            return {
                start: Math.floor(weekStart.getTime() / 1000),
                end: Math.floor(weekEnd.getTime() / 1000)
            };
        } else if (weekType === 'last_week') {
            const thisWeekStart = new Date(today);
            thisWeekStart.setDate(today.getDate() + diffToMonday);
            
            const lastWeekStart = new Date(thisWeekStart);
            lastWeekStart.setDate(thisWeekStart.getDate() - 7);
            lastWeekStart.setHours(0, 0, 0, 0);
            
            const lastWeekEnd = new Date(lastWeekStart);
            lastWeekEnd.setDate(lastWeekStart.getDate() + 6);
            lastWeekEnd.setHours(23, 59, 59, 999);
            
            return {
                start: Math.floor(lastWeekStart.getTime() / 1000),
                end: Math.floor(lastWeekEnd.getTime() / 1000)
            };
        }
        
        return null;
    }

    /**
     * Apply filters to transaction table
     */
    function applyFilters() {
        const dateFilterEl = document.getElementById('transactionDateFilter');
        const typeFilter = document.querySelector('input[name="transactionTypeFilter"]:checked');
        const searchInputEl = document.getElementById('transactionSearch');
        const tableRows = document.querySelectorAll('.transactions-table-new tbody tr');
        
        const selectedDays = dateFilterEl ? dateFilterEl.value : 'all';
        const selectedType = typeFilter ? typeFilter.value : 'all';
        const searchTerm = searchInputEl ? searchInputEl.value.toLowerCase().trim() : '';
        const now = Math.floor(Date.now() / 1000);
        
        // Calculate date range based on filter type
        let dateRange = null;
        if (selectedDays === 'this_week' || selectedDays === 'last_week') {
            dateRange = getWeekRange(selectedDays);
        } else if (selectedDays !== 'all') {
            const daysInSeconds = parseInt(selectedDays) * 24 * 60 * 60;
            dateRange = {
                start: now - daysInSeconds,
                end: now
            };
        }
        
        tableRows.forEach(row => {
            // Check date filter
            let passesDateFilter = true;
            if (dateRange) {
                const timestamp = parseInt(row.getAttribute('data-transaction-timestamp'));
                if (timestamp) {
                    passesDateFilter = timestamp >= dateRange.start && timestamp <= dateRange.end;
                } else {
                    passesDateFilter = false;
                }
            }
            
            // Check type filter
            let passesTypeFilter = true;
            if (selectedType !== 'all') {
                const rowType = row.getAttribute('data-transaction-type');
                passesTypeFilter = rowType === selectedType;
            }
            
            // Check search filter
            let passesSearchFilter = true;
            if (searchTerm) {
                const text = row.textContent.toLowerCase();
                passesSearchFilter = text.includes(searchTerm);
            }
            
            // Show row only if it passes all filters
            row.style.display = (passesDateFilter && passesTypeFilter && passesSearchFilter) ? '' : 'none';
        });
    }

    /**
     * Initialize Filter Functionality
     */
    function initFilters() {
        const dateFilterEl = document.getElementById('transactionDateFilter');
        const filterBtnEl = document.querySelector('.transactions-filter-btn-new');
        
        // Date filter functionality
        if (dateFilterEl) {
            dateFilterEl.addEventListener('change', function() {
                applyFilters();
            });
        }

        // Type filter button functionality
        if (filterBtnEl) {
            // Create filter dropdown/modal
            let filterDropdown = document.getElementById('transactionsFilterDropdown');
            if (!filterDropdown) {
                filterDropdown = document.createElement('div');
                filterDropdown.id = 'transactionsFilterDropdown';
                filterDropdown.className = 'transactions-filter-dropdown';
                
                const filterTypes = [
                    { value: 'all', label: 'All Types' },
                    { value: 'deposit', label: 'Deposits' },
                    { value: 'withdrawal', label: 'Withdrawals' },
                    { value: 'referral_earning', label: 'Referral Earnings' },
                    { value: 'mining_earning', label: 'Mining Earnings' },
                ];
                
                filterTypes.forEach(type => {
                    const label = document.createElement('label');
                    
                    const input = document.createElement('input');
                    input.type = 'radio';
                    input.name = 'transactionTypeFilter';
                    input.value = type.value;
                    if (type.value === 'all') {
                        input.checked = true;
                    }
                    
                    const span = document.createElement('span');
                    span.textContent = type.label;
                    
                    label.appendChild(input);
                    label.appendChild(span);
                    filterDropdown.appendChild(label);
                });
                
                // Insert after filter button's parent (controls container)
                const controlsContainer = filterBtnEl.closest('.transactions-history-controls-new');
                if (controlsContainer) {
                    controlsContainer.style.position = 'relative';
                    controlsContainer.appendChild(filterDropdown);
                }
            }
            
            // Explicitly hide dropdown on initialization (ensure it's hidden on page load)
            filterDropdown.style.display = 'none';
            
            // Toggle filter dropdown
            filterBtnEl.addEventListener('click', function(e) {
                e.stopPropagation();
                const isVisible = filterDropdown.style.display === 'block' || filterDropdown.style.display === 'flex';
                if (isVisible) {
                    filterDropdown.style.display = 'none';
                } else {
                    // Check if mobile view (window width <= 480px)
                    const isMobile = window.innerWidth <= 480;
                    filterDropdown.style.display = isMobile ? 'block' : 'block';
                }
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!filterBtnEl.contains(e.target) && !filterDropdown.contains(e.target)) {
                    filterDropdown.style.display = 'none';
                }
            });
            
            // Handle type filter selection
            const typeInputs = filterDropdown.querySelectorAll('input[name="transactionTypeFilter"]');
            typeInputs.forEach(input => {
                input.addEventListener('change', function() {
                    applyFilters();
                    filterDropdown.style.display = 'none';
                });
            });
        }
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


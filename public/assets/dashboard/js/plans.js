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
        initCalculatorToggle();
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
        const calculatorModalOverlay = document.getElementById('calculatorModalOverlay');
        const calculatorBtns = document.querySelectorAll('.open-calculator-btn');
        const closeModalBtns = document.querySelectorAll('#closeCalculatorModal, #closeCalculatorModalBtn');
        const resetBtn = document.getElementById('resetCalculator');
        const investmentInput = document.getElementById('investmentAmount');
        
        // Store current plan data
        let currentPlanData = null;
        
        // Open modal with plan data
        calculatorBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (calculatorModalOverlay) {
                    // Get plan data from button attributes
                    currentPlanData = {
                        id: btn.getAttribute('data-plan-id'),
                        name: btn.getAttribute('data-plan-name'),
                        subtitle: btn.getAttribute('data-plan-subtitle'),
                        minInvestment: parseFloat(btn.getAttribute('data-min-investment')),
                        maxInvestment: parseFloat(btn.getAttribute('data-max-investment')),
                        dailyRoiMin: parseFloat(btn.getAttribute('data-daily-roi-min')),
                        dailyRoiMax: parseFloat(btn.getAttribute('data-daily-roi-max')),
                        hourlyRate: parseFloat(btn.getAttribute('data-hourly-rate')) || 0
                    };
                    
                    // Populate modal with plan data
                    populateCalculatorModal(currentPlanData);
                    
                    calculatorModalOverlay.classList.add('show');
                    // Prevent body scroll when modal is open
                    document.body.style.overflow = 'hidden';
                }
            });
        });

        // Close modal
        function closeModal() {
            if (calculatorModalOverlay) {
                calculatorModalOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }
        }

        closeModalBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                closeModal();
            });
        });

        // Close modal when clicking overlay
        if (calculatorModalOverlay) {
            calculatorModalOverlay.addEventListener('click', function(e) {
                if (e.target === calculatorModalOverlay) {
                    closeModal();
                }
            });
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && calculatorModalOverlay && calculatorModalOverlay.classList.contains('show')) {
                closeModal();
            }
        });

        // Reset calculator
        if (resetBtn && investmentInput) {
            resetBtn.addEventListener('click', function(e) {
                e.preventDefault();
                investmentInput.value = '';
                calculateInvestment(investmentInput.value, currentPlanData);
            });
        }

        // Calculate investment on input
        if (investmentInput) {
            investmentInput.addEventListener('input', function() {
                calculateInvestment(this.value, currentPlanData);
            });

            investmentInput.addEventListener('change', function() {
                calculateInvestment(this.value, currentPlanData);
            });
        }
    }
    
    /**
     * Populate Calculator Modal with Plan Data
     */
    function populateCalculatorModal(planData) {
        if (!planData) return;
        
        // Update modal title
        const modalTitle = document.getElementById('calculatorModalTitle');
        if (modalTitle) {
            modalTitle.textContent = `Investment Profit Calculator - ${planData.name}`;
        }
        
        // Update plan name and description
        const planName = document.getElementById('calculatorPlanName');
        const planDescription = document.getElementById('calculatorPlanDescription');
        if (planName) planName.textContent = planData.name;
        if (planDescription) planDescription.textContent = planData.subtitle || 'Earn through mining';
        
        // Update investment range
        const investmentRange = document.getElementById('calculatorInvestmentRange');
        if (investmentRange) {
            investmentRange.textContent = `$${planData.minInvestment.toFixed(2)} - $${planData.maxInvestment.toFixed(2)}`;
        }
        
        // Update return rate (use hourly rate if available, otherwise calculate from daily)
        const returnRateEl = document.getElementById('calculatorReturnRate');
        const returnRateDetailEl = document.getElementById('calculatorReturnRateDetail');
        const hourlyRate = planData.hourlyRate || (planData.dailyRoiMin / 24);
        
        if (returnRateEl) {
            returnRateEl.textContent = `${hourlyRate.toFixed(3)}%`;
        }
        if (returnRateDetailEl) {
            returnRateDetailEl.textContent = `${hourlyRate.toFixed(3)}% every hour`;
        }
        
        // Update input placeholder and min/max
        const investmentInput = document.getElementById('investmentAmount');
        if (investmentInput) {
            investmentInput.placeholder = `Enter amount between ${planData.minInvestment} - ${planData.maxInvestment}`;
            investmentInput.min = planData.minInvestment;
            investmentInput.max = planData.maxInvestment;
            investmentInput.value = '';
        }
        
        // Reset calculation cards
        const investmentDetailsCard = document.getElementById('investmentDetailsCard');
        const profitBreakdownCard = document.getElementById('profitBreakdownCard');
        if (investmentDetailsCard) investmentDetailsCard.style.display = 'none';
        if (profitBreakdownCard) profitBreakdownCard.style.display = 'none';
    }

    /**
     * Calculate Investment Returns
     */
    function calculateInvestment(amount, planData) {
        if (!planData) {
            // Fallback to default values if no plan data
            planData = {
                minInvestment: 2,
                maxInvestment: 100000,
                hourlyRate: 0.145
            };
        }
        
        const returnRate = (planData.hourlyRate || 0) / 100; // Convert percentage to decimal
        const investmentDetailsCard = document.getElementById('investmentDetailsCard');
        const profitBreakdownCard = document.getElementById('profitBreakdownCard');
        
        // Parse amount
        const investmentAmount = parseFloat(amount) || 0;
        
        // Validate amount
        if (investmentAmount < planData.minInvestment || investmentAmount > planData.maxInvestment) {
            if (investmentDetailsCard) investmentDetailsCard.style.display = 'none';
            if (profitBreakdownCard) profitBreakdownCard.style.display = 'none';
            return;
        }
        
        // Show cards if amount is valid
        if (investmentDetailsCard) investmentDetailsCard.style.display = 'block';
        if (profitBreakdownCard) profitBreakdownCard.style.display = 'block';
        
        // Calculate profits
        const profitPerCycle = investmentAmount * returnRate;
        const profitHourly = profitPerCycle;
        const profitDaily = profitHourly * 24;
        const profitWeekly = profitDaily * 7;
        const profitMonthly = profitDaily * 30;
        
        // Update Investment Details
        const calculatedAmountEl = document.getElementById('calculatedAmount');
        const profitPerCycleEl = document.getElementById('profitPerCycle');
        
        if (calculatedAmountEl) {
            calculatedAmountEl.textContent = '$' + investmentAmount.toFixed(2);
        }
        if (profitPerCycleEl) {
            profitPerCycleEl.textContent = '$' + profitPerCycle.toFixed(4);
        }
        
        // Update Profit Breakdown
        const profitHourlyEl = document.getElementById('profitHourly');
        const profitDailyEl = document.getElementById('profitDaily');
        const profitWeeklyEl = document.getElementById('profitWeekly');
        const profitMonthlyEl = document.getElementById('profitMonthly');
        
        if (profitHourlyEl) {
            profitHourlyEl.textContent = '$' + profitHourly.toFixed(4);
        }
        if (profitDailyEl) {
            profitDailyEl.textContent = '$' + profitDaily.toFixed(2);
        }
        if (profitWeeklyEl) {
            profitWeeklyEl.textContent = '$' + profitWeekly.toFixed(2);
        }
        if (profitMonthlyEl) {
            profitMonthlyEl.textContent = '$' + profitMonthly.toFixed(2);
        }
    }

    /**
     * Handle calculator toggle for existing calculator section
     */
    function initCalculatorToggle() {
        const calculatorToggles = document.querySelectorAll('.calculator-toggle');
        
        calculatorToggles.forEach(calculatorToggle => {
            const planId = calculatorToggle.getAttribute('data-plan-id');
            const calculatorSection = document.getElementById('calculatorSection' + planId);
            const calculatorContent = document.getElementById('calculatorContent' + planId);
            
            if (calculatorToggle && calculatorSection) {
                calculatorToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const isOpen = calculatorSection.classList.contains('show');
                    
                    if (isOpen) {
                        calculatorSection.classList.remove('show');
                        if (calculatorContent) {
                            calculatorContent.style.display = 'none';
                        }
                        const span = calculatorToggle.querySelector('span');
                        if (span) span.textContent = 'Open Calculator';
                        const icon = calculatorToggle.querySelector('i');
                        if (icon) icon.className = 'fas fa-calculator';
                    } else {
                        calculatorSection.classList.add('show');
                        if (calculatorContent) {
                            calculatorContent.style.display = 'grid';
                        }
                        const span = calculatorToggle.querySelector('span');
                        if (span) span.textContent = 'Close Calculator';
                        const icon = calculatorToggle.querySelector('i');
                        if (icon) icon.className = 'fas fa-times';
                    }
                });
            }
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


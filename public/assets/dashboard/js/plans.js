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
        initInvestmentModal();
        initClaimEarnings();
        initManageActivePlan();
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
     * Initialize Investment Modal
     */
    function initInvestmentModal() {
        const investmentModalOverlay = document.getElementById('investmentModalOverlay');
        const startInvestingBtns = document.querySelectorAll('.start-investing-btn');
        const closeModalBtns = document.querySelectorAll('#closeInvestmentModal, #cancelInvestmentBtn');
        const confirmBtn = document.getElementById('confirmInvestmentBtn');
        const sourceBalanceSelect = document.getElementById('sourceBalanceSelect');
        const investmentAmountInput = document.getElementById('investmentAmountInput');
        const depositAmountBtn = document.getElementById('depositAmountBtn');

        let currentPlanData = null;
        let currentBalances = null;

        // Open modal when Start Investing button is clicked
        startInvestingBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const planId = btn.getAttribute('data-plan-id');
                
                if (!planId) {
                    console.error('Plan ID not found');
                    return;
                }

                // Show loading state
                if (investmentModalOverlay) {
                    investmentModalOverlay.classList.add('show');
                    document.body.style.overflow = 'hidden';
                }

                // Fetch plan and balance data
                fetch(`/user/dashboard/investments/modal/${planId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentPlanData = data.plan;
                        currentBalances = data.balances;
                        populateInvestmentModal(data);
                    } else {
                        alert('Failed to load investment data: ' + (data.message || 'Unknown error'));
                        closeInvestmentModal();
                    }
                })
                .catch(error => {
                    console.error('Error fetching investment data:', error);
                    alert('An error occurred while loading investment data.');
                    closeInvestmentModal();
                });
            });
        });

        // Close modal
        function closeInvestmentModal() {
            if (investmentModalOverlay) {
                investmentModalOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }
            // Reset form
            if (investmentAmountInput) investmentAmountInput.value = '';
            if (sourceBalanceSelect) sourceBalanceSelect.value = 'fund_wallet';
            hideAlert();
        }

        closeModalBtns.forEach(btn => {
            if (btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    closeInvestmentModal();
                });
            }
        });

        // Close modal when clicking overlay
        if (investmentModalOverlay) {
            investmentModalOverlay.addEventListener('click', function(e) {
                if (e.target === investmentModalOverlay) {
                    closeInvestmentModal();
                }
            });
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && investmentModalOverlay && investmentModalOverlay.classList.contains('show')) {
                closeInvestmentModal();
            }
        });

        // Handle source balance change
        if (sourceBalanceSelect) {
            sourceBalanceSelect.addEventListener('change', function() {
                validateInvestmentAmount();
            });
        }

        // Handle investment amount input
        if (investmentAmountInput) {
            investmentAmountInput.addEventListener('input', function() {
                validateInvestmentAmount();
            });
        }

        // Handle confirm investment
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function(e) {
                e.preventDefault();
                submitInvestment();
            });
        }

        // Handle deposit amount button
        if (depositAmountBtn) {
            depositAmountBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = '/user/dashboard/deposit';
            });
        }

        /**
         * Populate Investment Modal with Data
         */
        function populateInvestmentModal(data) {
            const plan = data.plan;
            const balances = data.balances;

            // Update plan name in header
            const planNameEl = document.getElementById('investmentPlanName');
            if (planNameEl) planNameEl.textContent = plan.name;

            // Update plan name in body section
            const planNameTextEl = document.getElementById('investmentPlanNameText');
            if (planNameTextEl) planNameTextEl.textContent = plan.name;

            // Update investment range
            const minAmountEl = document.getElementById('investmentMinAmount');
            const maxAmountEl = document.getElementById('investmentMaxAmount');
            if (minAmountEl) {
                const minValue = parseFloat(plan.min_investment).toFixed(2).replace(/\.?0+$/, '');
                minAmountEl.textContent = minValue;
            }
            if (maxAmountEl) {
                const maxValue = parseFloat(plan.max_investment).toFixed(2).replace(/\.?0+$/, '');
                maxAmountEl.textContent = maxValue;
            }

            // Update balances
            const fundBalanceEl = document.getElementById('fundBalanceDisplay');
            const earningBalanceEl = document.getElementById('earningBalanceDisplay');
            // Helper function to format number without trailing zeros
            const formatBalance = (num) => {
                return parseFloat(num).toFixed(2).replace(/\.?0+$/, '');
            };
            if (fundBalanceEl) fundBalanceEl.textContent = '$' + formatBalance(balances.fund_balance);
            if (earningBalanceEl) earningBalanceEl.textContent = '$' + formatBalance(balances.earning_balance);

            // Update input placeholder and hint
            if (investmentAmountInput) {
                investmentAmountInput.placeholder = 'Enter amount';
                investmentAmountInput.min = plan.min_investment;
                investmentAmountInput.max = plan.max_investment;
            }

            // Update hint text
            const amountHintEl = document.getElementById('investmentAmountHint');
            if (amountHintEl) {
                const minValue = parseFloat(plan.min_investment).toFixed(2).replace(/\.?0+$/, '');
                const maxValue = parseFloat(plan.max_investment).toFixed(2).replace(/\.?0+$/, '');
                amountHintEl.textContent = `Min: $${minValue} - Max: $${maxValue}`;
            }

            // Validate and show/hide alert
            validateInvestmentAmount();
        }

        /**
         * Validate Investment Amount
         */
        function validateInvestmentAmount() {
            if (!currentPlanData || !currentBalances) return;

            const selectedSource = sourceBalanceSelect ? sourceBalanceSelect.value : 'fund_wallet';
            const amount = parseFloat(investmentAmountInput ? investmentAmountInput.value : 0);
            const minInvestment = parseFloat(currentPlanData.min_investment);
            
            let selectedBalance = 0;
            if (selectedSource === 'fund_wallet') {
                selectedBalance = parseFloat(currentBalances.fund_balance);
            } else {
                selectedBalance = parseFloat(currentBalances.earning_balance);
            }

            const alertEl = document.getElementById('investmentAlert');
            const alertMessageEl = document.getElementById('investmentAlertMessage');

            // Helper function to format number without trailing zeros
            const formatAmount = (num) => {
                return parseFloat(num).toFixed(2).replace(/\.?0+$/, '');
            };
            
            // Check if balance is sufficient
            if (selectedBalance < minInvestment) {
                showAlert(`Please deposit at least $${formatAmount(minInvestment)} to buy this plan.`);
                if (depositAmountBtn) depositAmountBtn.style.display = 'block';
                if (investmentAmountInput) investmentAmountInput.disabled = true;
                if (confirmBtn) confirmBtn.disabled = true;
            } else {
                hideAlert();
                if (depositAmountBtn) depositAmountBtn.style.display = 'none';
                if (investmentAmountInput) investmentAmountInput.disabled = false;
                
                // Validate amount if entered
                if (amount > 0) {
                    if (amount < minInvestment || amount > parseFloat(currentPlanData.max_investment)) {
                        showAlert(`Investment amount must be between $${formatAmount(minInvestment)} and $${formatAmount(currentPlanData.max_investment)}.`);
                        if (confirmBtn) confirmBtn.disabled = true;
                    } else if (amount > selectedBalance) {
                        showAlert(`Insufficient balance. Available: $${formatAmount(selectedBalance)}`);
                        if (confirmBtn) confirmBtn.disabled = true;
                    } else {
                        hideAlert();
                        if (confirmBtn) confirmBtn.disabled = false;
                    }
                } else {
                    if (confirmBtn) confirmBtn.disabled = false;
                }
            }
        }

        /**
         * Show Alert
         */
        function showAlert(message) {
            const alertEl = document.getElementById('investmentAlert');
            const alertMessageEl = document.getElementById('investmentAlertMessage');
            if (alertEl && alertMessageEl) {
                alertMessageEl.textContent = message;
                alertEl.style.display = 'flex';
            }
        }

        /**
         * Hide Alert
         */
        function hideAlert() {
            const alertEl = document.getElementById('investmentAlert');
            if (alertEl) {
                alertEl.style.display = 'none';
            }
        }

        /**
         * Submit Investment
         */
        function submitInvestment() {
            if (!currentPlanData) return;

            const amount = parseFloat(investmentAmountInput ? investmentAmountInput.value : 0);
            const sourceBalance = sourceBalanceSelect ? sourceBalanceSelect.value : 'fund_wallet';

            // Validate amount
            if (!amount || amount <= 0) {
                showAlert('Please enter a valid investment amount.');
                return;
            }

            // Helper function to format number without trailing zeros
            const formatAmount = (num) => {
                return parseFloat(num).toFixed(2).replace(/\.?0+$/, '');
            };
            
            if (amount < parseFloat(currentPlanData.min_investment) || amount > parseFloat(currentPlanData.max_investment)) {
                showAlert(`Investment amount must be between $${formatAmount(currentPlanData.min_investment)} and $${formatAmount(currentPlanData.max_investment)}.`);
                return;
            }

            // Disable button during submission
            if (confirmBtn) {
                confirmBtn.disabled = true;
                confirmBtn.textContent = 'Processing...';
            }

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Submit investment
            fetch('/user/dashboard/investments', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    plan_id: currentPlanData.id,
                    amount: amount,
                    source_balance: sourceBalance
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Investment created successfully!');
                    closeInvestmentModal();
                    // Reload page to update balances
                    window.location.reload();
                } else {
                    showAlert(data.message || 'Failed to create investment. Please try again.');
                    if (confirmBtn) {
                        confirmBtn.disabled = false;
                        confirmBtn.textContent = 'Confirm Investment';
                    }
                }
            })
            .catch(error => {
                console.error('Error creating investment:', error);
                showAlert('An error occurred while creating the investment.');
                if (confirmBtn) {
                    confirmBtn.disabled = false;
                    confirmBtn.textContent = 'Confirm Investment';
                }
            });
        }
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
            const minValue = planData.minInvestment.toFixed(2).replace(/\.?0+$/, '');
            const maxValue = planData.maxInvestment.toFixed(2).replace(/\.?0+$/, '');
            investmentRange.textContent = `$${minValue} - $${maxValue}`;
        }
        
        // Update return rate (use hourly rate if available, otherwise calculate from daily)
        const returnRateEl = document.getElementById('calculatorReturnRate');
        const returnRateDetailEl = document.getElementById('calculatorReturnRateDetail');
        const hourlyRate = planData.hourlyRate || (planData.dailyRoiMin / 24);
        
        // Helper function to format number without trailing zeros
        const formatNumber = (num) => {
            return parseFloat(num.toFixed(3)).toString();
        };
        
        if (returnRateEl) {
            returnRateEl.textContent = `${formatNumber(hourlyRate)}%`;
        }
        if (returnRateDetailEl) {
            returnRateDetailEl.textContent = `${formatNumber(hourlyRate)}% every hour`;
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

    /**
     * Initialize Claim Earnings
     */
    function initClaimEarnings() {
        const claimEarningsModalOverlay = document.getElementById('claimEarningsModalOverlay');
        const claimBtns = document.querySelectorAll('.claim-earning-btn');
        const closeModalBtns = document.querySelectorAll('#closeClaimEarningsModal, #cancelClaimEarningsBtn');
        const confirmClaimBtn = document.getElementById('confirmClaimEarningsBtn');

        let currentInvestmentId = null;
        let currentUnclaimedProfit = 0;

        // Open modal when Claim Earning button is clicked
        claimBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const investmentId = btn.getAttribute('data-investment-id');
                
                if (!investmentId) {
                    alert('Investment ID not found');
                    return;
                }

                // Show modal immediately
                if (claimEarningsModalOverlay) {
                    claimEarningsModalOverlay.classList.add('show');
                    document.body.style.overflow = 'hidden';
                }

                // Fetch claim earnings data
                fetch(`/user/dashboard/investments/${investmentId}/claim-modal`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {

                    if (data.success) {
                        currentInvestmentId = investmentId;
                        currentUnclaimedProfit = parseFloat(data.investment.unclaimed_profit);
                        populateClaimEarningsModal(data);
                    } else {
                        alert('Failed to load claim earnings data: ' + (data.message || 'Unknown error'));
                        closeClaimEarningsModal();
                    }
                })
                .catch(error => {
                    console.error('Error fetching claim earnings data:', error);
                    alert('An error occurred while loading claim earnings data.');
                    // Reset button state
                    btn.style.opacity = '1';
                    if (loadingText) {
                        loadingText.textContent = originalText.includes('$') ? originalText : 'Claim Earning';
                    }
                    closeClaimEarningsModal();
                });
            });
        });

        // Close modal
        function closeClaimEarningsModal() {
            if (claimEarningsModalOverlay) {
                claimEarningsModalOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }
            currentInvestmentId = null;
            currentUnclaimedProfit = 0;
        }

        closeModalBtns.forEach(btn => {
            if (btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    closeClaimEarningsModal();
                });
            }
        });

        // Close modal when clicking overlay
        if (claimEarningsModalOverlay) {
            claimEarningsModalOverlay.addEventListener('click', function(e) {
                if (e.target === claimEarningsModalOverlay) {
                    closeClaimEarningsModal();
                }
            });
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && claimEarningsModalOverlay && claimEarningsModalOverlay.classList.contains('show')) {
                closeClaimEarningsModal();
            }
        });

        // Handle confirm claim
        if (confirmClaimBtn) {
            confirmClaimBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (currentInvestmentId && currentUnclaimedProfit > 0) {
                    submitClaimEarnings();
                }
            });
        }

        /**
         * Populate Claim Earnings Modal with Data
         */
        function populateClaimEarningsModal(data) {
            const investment = data.investment;
            const plan = data.plan;
            const unclaimedProfit = parseFloat(investment.unclaimed_profit);
            const hasEarnings = data.has_earnings;

            // Update plan name
            const planNameEl = document.getElementById('claimEarningsPlanName');
            if (planNameEl) planNameEl.textContent = plan.name;

            // Update earnings amount
            const earningsAmountEl = document.getElementById('claimEarningsAmount');
            const earningsStatusEl = document.getElementById('claimEarningsStatus');
            // Helper function to format number without trailing zeros
            const formatBalance = (num) => {
                return parseFloat(num).toFixed(2).replace(/\.?0+$/, '');
            };
            if (earningsAmountEl) {
                earningsAmountEl.textContent = '$' + formatBalance(unclaimedProfit);
                if (hasEarnings) {
                    earningsAmountEl.style.color = '#10b981';
                } else {
                    earningsAmountEl.style.color = 'var(--text-secondary)';
                }
            }
            if (earningsStatusEl) {
                if (hasEarnings) {
                    earningsStatusEl.textContent = 'Earnings available to claim';
                    earningsStatusEl.style.color = '#10b981';
                } else {
                    earningsStatusEl.textContent = 'No earnings available';
                    earningsStatusEl.style.color = 'var(--text-secondary)';
                }
            }

            // Update mining balance
            const miningBalanceEl = document.getElementById('claimEarningsMiningBalance');
            if (miningBalanceEl) {
                miningBalanceEl.textContent = '$' + formatBalance(data.mining_earning);
            }

            // Enable/disable claim button
            if (confirmClaimBtn) {
                if (hasEarnings && unclaimedProfit > 0) {
                    confirmClaimBtn.disabled = false;
                    confirmClaimBtn.style.opacity = '1';
                    confirmClaimBtn.style.cursor = 'pointer';
                } else {
                    confirmClaimBtn.disabled = true;
                    confirmClaimBtn.style.opacity = '0.6';
                    confirmClaimBtn.style.cursor = 'not-allowed';
                }
            }
        }

        /**
         * Submit Claim Earnings
         */
        function submitClaimEarnings() {
            if (!currentInvestmentId || currentUnclaimedProfit <= 0) return;

            // Disable button during submission
            if (confirmClaimBtn) {
                confirmClaimBtn.disabled = true;
                confirmClaimBtn.style.opacity = '0.6';
                const originalText = confirmClaimBtn.innerHTML;
                confirmClaimBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Claiming...';

                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                // Claim earnings
                fetch(`/user/dashboard/investments/${currentInvestmentId}/claim`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Earnings claimed successfully! Claimed amount: $' + data.claimed_amount);
                        closeClaimEarningsModal();
                        // Reload page to update balances and button states
                        window.location.reload();
                    } else {
                        alert(data.message || 'Failed to claim earnings. Please try again.');
                        if (confirmClaimBtn) {
                            confirmClaimBtn.disabled = false;
                            confirmClaimBtn.style.opacity = '1';
                            confirmClaimBtn.innerHTML = '<i class="fas fa-coins"></i> Claim Earnings';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error claiming earnings:', error);
                    alert('An error occurred while claiming earnings.');
                    if (confirmClaimBtn) {
                        confirmClaimBtn.disabled = false;
                        confirmClaimBtn.style.opacity = '1';
                        confirmClaimBtn.innerHTML = '<i class="fas fa-coins"></i> Claim Earnings';
                    }
                });
            }
        }
    }

    /**
     * Initialize Manage Active Plan
     */
    function initManageActivePlan() {
        const managePlanModalOverlay = document.getElementById('managePlanModalOverlay');
        const managePlanBtns = document.querySelectorAll('.manage-active-plan-btn');
        const closeModalBtns = document.querySelectorAll('#closeManagePlanModal, #cancelManagePlanBtn');
        const updateBtn = document.getElementById('updateManagePlanBtn');
        const sourceBalanceSelect = document.getElementById('managePlanSourceBalanceSelect');
        const amountInput = document.getElementById('managePlanAmountInput');

        let currentInvestmentData = null;
        let currentPlanData = null;
        let currentBalances = null;

        // Open modal when Manage Active Plan button is clicked
        managePlanBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const planId = btn.getAttribute('data-plan-id');
                
                if (!planId) {
                    console.error('Plan ID not found');
                    return;
                }

                // Show loading state
                if (managePlanModalOverlay) {
                    managePlanModalOverlay.classList.add('show');
                    document.body.style.overflow = 'hidden';
                }

                // Fetch investment and plan data
                fetch(`/user/dashboard/investments/manage/${planId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentInvestmentData = data.investment;
                        currentPlanData = data.plan;
                        currentBalances = data.balances;
                        populateManagePlanModal(data);
                    } else {
                        alert('Failed to load manage plan data: ' + (data.message || 'Unknown error'));
                        closeManagePlanModal();
                    }
                })
                .catch(error => {
                    console.error('Error fetching manage plan data:', error);
                    alert('An error occurred while loading manage plan data.');
                    closeManagePlanModal();
                });
            });
        });

        // Close modal
        function closeManagePlanModal() {
            if (managePlanModalOverlay) {
                managePlanModalOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }
            // Reset form
            if (amountInput) amountInput.value = '';
            if (sourceBalanceSelect) sourceBalanceSelect.value = 'fund_wallet';
            hideManagePlanAlert();
        }

        closeModalBtns.forEach(btn => {
            if (btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    closeManagePlanModal();
                });
            }
        });

        // Close modal when clicking overlay
        if (managePlanModalOverlay) {
            managePlanModalOverlay.addEventListener('click', function(e) {
                if (e.target === managePlanModalOverlay) {
                    closeManagePlanModal();
                }
            });
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && managePlanModalOverlay && managePlanModalOverlay.classList.contains('show')) {
                closeManagePlanModal();
            }
        });

        // Handle source balance change
        if (sourceBalanceSelect) {
            sourceBalanceSelect.addEventListener('change', function() {
                validateManagePlanAmount();
            });
        }

        // Handle amount input
        if (amountInput) {
            amountInput.addEventListener('input', function() {
                validateManagePlanAmount();
            });
        }

        // Handle update investment
        if (updateBtn) {
            updateBtn.addEventListener('click', function(e) {
                e.preventDefault();
                submitManagePlanUpdate();
            });
        }

        /**
         * Populate Manage Plan Modal with Data
         */
        function populateManagePlanModal(data) {
            const investment = data.investment;
            const plan = data.plan;
            const balances = data.balances;

            // Update plan name in header
            const planNameEl = document.getElementById('managePlanName');
            if (planNameEl) planNameEl.textContent = plan.name;

            // Update plan name in body
            const planNameTextEl = document.getElementById('managePlanNameText');
            if (planNameTextEl) planNameTextEl.textContent = plan.name;

            // Update active investment amount
            const activeInvestmentEl = document.getElementById('activeInvestmentAmount');
            // Helper function to format number without trailing zeros
            const formatBalance = (num) => {
                return parseFloat(num).toFixed(2).replace(/\.?0+$/, '');
            };
            if (activeInvestmentEl) activeInvestmentEl.textContent = '$' + formatBalance(investment.amount);

            // Update investment range
            const minAmountEl = document.getElementById('managePlanMinAmount');
            const maxAmountEl = document.getElementById('managePlanMaxAmount');
            if (minAmountEl) minAmountEl.textContent = formatBalance(plan.min_investment);
            if (maxAmountEl) maxAmountEl.textContent = formatBalance(plan.max_investment);

            // Update balances
            const fundBalanceEl = document.getElementById('managePlanFundBalance');
            const earningBalanceEl = document.getElementById('managePlanEarningBalance');
            if (fundBalanceEl) fundBalanceEl.textContent = '$' + formatBalance(balances.fund_balance);
            if (earningBalanceEl) earningBalanceEl.textContent = '$' + formatBalance(balances.earning_balance);

            // Update input hint with max additional
            const amountHintEl = document.getElementById('managePlanAmountHint');
            if (amountHintEl) {
                const maxAdditional = parseFloat(data.max_additional);
                // Helper function to format number without trailing zeros
                const formatAmount = (num) => {
                    return parseFloat(num).toFixed(2).replace(/\.?0+$/, '');
                };
                amountHintEl.textContent = `Min: $0.01 - Max: $${formatAmount(maxAdditional)}`;
            }

            // Validate and show/hide alert
            validateManagePlanAmount();
        }

        /**
         * Validate Manage Plan Amount
         */
        function validateManagePlanAmount() {
            if (!currentPlanData || !currentBalances || !currentInvestmentData) return;

            const selectedSource = sourceBalanceSelect ? sourceBalanceSelect.value : 'fund_wallet';
            const amount = parseFloat(amountInput ? amountInput.value : 0);
            const existingAmount = parseFloat(currentInvestmentData.amount);
            const maxAdditional = parseFloat(currentPlanData.max_investment) - existingAmount;
            
            let selectedBalance = 0;
            if (selectedSource === 'fund_wallet') {
                selectedBalance = parseFloat(currentBalances.fund_balance);
            } else {
                selectedBalance = parseFloat(currentBalances.earning_balance);
            }

            const alertEl = document.getElementById('managePlanAlert');
            const alertMessageEl = document.getElementById('managePlanAlertMessage');

            // Validate amount if entered
            if (amount > 0) {
                if (amount > maxAdditional) {
                    showManagePlanAlert(`Additional amount cannot exceed $${maxAdditional.toFixed(2)}. Maximum total investment is $${parseFloat(currentPlanData.max_investment).toFixed(2)}.`);
                    if (updateBtn) updateBtn.disabled = true;
                } else if (amount > selectedBalance) {
                    showManagePlanAlert(`Insufficient balance. Available: $${selectedBalance.toFixed(2)}`);
                    if (updateBtn) updateBtn.disabled = true;
                } else {
                    hideManagePlanAlert();
                    if (updateBtn) updateBtn.disabled = false;
                }
            } else {
                hideManagePlanAlert();
                if (updateBtn) updateBtn.disabled = false;
            }
        }

        /**
         * Show Manage Plan Alert
         */
        function showManagePlanAlert(message) {
            const alertEl = document.getElementById('managePlanAlert');
            const alertMessageEl = document.getElementById('managePlanAlertMessage');
            if (alertEl && alertMessageEl) {
                alertMessageEl.textContent = message;
                alertEl.style.display = 'flex';
            }
        }

        /**
         * Hide Manage Plan Alert
         */
        function hideManagePlanAlert() {
            const alertEl = document.getElementById('managePlanAlert');
            if (alertEl) {
                alertEl.style.display = 'none';
            }
        }

        /**
         * Submit Manage Plan Update
         */
        function submitManagePlanUpdate() {
            if (!currentInvestmentData || !currentPlanData) return;

            const amount = parseFloat(amountInput ? amountInput.value : 0);
            const sourceBalance = sourceBalanceSelect ? sourceBalanceSelect.value : 'fund_wallet';
            const investmentId = currentInvestmentData.id;

            // Validate amount
            if (!amount || amount <= 0) {
                showManagePlanAlert('Please enter a valid additional investment amount.');
                return;
            }

            const existingAmount = parseFloat(currentInvestmentData.amount);
            const maxAdditional = parseFloat(currentPlanData.max_investment) - existingAmount;

            // Helper function to format number without trailing zeros
            const formatAmount = (num) => {
                return parseFloat(num).toFixed(2).replace(/\.?0+$/, '');
            };
            
            if (amount > maxAdditional) {
                showManagePlanAlert(`Additional amount cannot exceed $${formatAmount(maxAdditional)}.`);
                return;
            }

            // Disable button during submission
            if (updateBtn) {
                updateBtn.disabled = true;
                updateBtn.textContent = 'Processing...';
            }

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Submit update
            fetch(`/user/dashboard/investments/${investmentId}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    amount: amount,
                    source_balance: sourceBalance
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Investment updated successfully!');
                    closeManagePlanModal();
                    // Reload page to update balances
                    window.location.reload();
                } else {
                    showManagePlanAlert(data.message || 'Failed to update investment. Please try again.');
                    if (updateBtn) {
                        updateBtn.disabled = false;
                        updateBtn.textContent = 'Update Settings';
                    }
                }
            })
            .catch(error => {
                console.error('Error updating investment:', error);
                showManagePlanAlert('An error occurred while updating the investment.');
                if (updateBtn) {
                    updateBtn.disabled = false;
                    updateBtn.textContent = 'Update Settings';
                }
            });
        }
    }

})();


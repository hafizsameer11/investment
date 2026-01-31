/**
 * Withdraw Security Page - JavaScript
 * Handles multi-step withdrawal security setup
 */

(function() {
    'use strict';

    // State
    let currentStep = 1;
    let otpTimer = null;
    let otpTimerSeconds = 0;

    // DOM Elements
    const steps = document.querySelectorAll('.security-step');
    const stepContents = document.querySelectorAll('.security-step-content');
    const stepLines = document.querySelectorAll('.security-step-line');
    const emailInput = document.getElementById('securityEmail');
    const otpInput = document.getElementById('otpCode');
    const continueStep2Btn = document.getElementById('continueToStep2');
    const continueStep3Btn = document.getElementById('continueToStep3');
    const continueStep4Btn = document.getElementById('continueToStep4');
    const resendOtpBtn = document.getElementById('resendOtp');
    const finishBtn = document.getElementById('finishSetup');
    const otpTimerElement = document.getElementById('otpTimer');
    const submitWithdrawalBtn = document.getElementById('submitWithdrawal');
    const withdrawalAccountNumber = document.getElementById('withdrawalAccountNumber');
    const withdrawalAccountName = document.getElementById('withdrawalAccountName');
    const withdrawalAmount = document.getElementById('withdrawalAmount');
    const withdrawalPaymentMethods = document.querySelectorAll('.withdrawal-payment-method');

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        highlightActiveNavItem();
        initStepNavigation();
        initFormValidation();
        initOTPTimer();
        initWithdrawalForm();
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
                        
                        if (currentPath === linkPath || 
                            (currentPath.includes('/user/dashboard/withdraw-security') && linkPath.includes('/withdraw-security'))) {
                            navItem.classList.add('active');
                        }
                    } catch (e) {
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
     * Initialize Step Navigation
     */
    function initStepNavigation() {
        if (continueStep2Btn) {
            continueStep2Btn.addEventListener('click', function() {
                if (validateEmail()) {
                    goToStep(2);
                }
            });
        }

        if (continueStep3Btn) {
            continueStep3Btn.addEventListener('click', function() {
                if (validateOTP()) {
                    goToStep(3);
                }
            });
        }

        if (continueStep4Btn) {
            continueStep4Btn.addEventListener('click', function() {
                goToStep(4);
            });
        }

        if (submitWithdrawalBtn) {
            submitWithdrawalBtn.addEventListener('click', function() {
                if (validateWithdrawalForm()) {
                    submitWithdrawal();
                }
            });
        }

        if (resendOtpBtn) {
            resendOtpBtn.addEventListener('click', function() {
                if (this.disabled) return;
                resendOTP();
            });
        }
    }

    /**
     * Initialize Form Validation
     */
    function initFormValidation() {
        if (emailInput) {
            emailInput.addEventListener('input', function() {
                validateEmail();
            });
        }

        if (otpInput) {
            otpInput.addEventListener('input', function() {
                // Only allow numbers
                this.value = this.value.replace(/[^0-9]/g, '');
                validateOTP();
            });
        }

        if (withdrawalAccountNumber) {
            withdrawalAccountNumber.addEventListener('input', function() {
                validateWithdrawalForm();
            });
        }

        if (withdrawalAccountName) {
            withdrawalAccountName.addEventListener('input', function() {
                validateWithdrawalForm();
            });
        }

        if (withdrawalAmount) {
            withdrawalAmount.addEventListener('input', function() {
                validateWithdrawalForm();
            });
        }
    }

    /**
     * Initialize OTP Timer
     */
    function initOTPTimer() {
        // Timer will start when step 2 is reached
    }

    /**
     * Validate Email
     */
    function validateEmail() {
        if (!emailInput) return false;
        
        const email = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!email) {
            showNotification('Please enter your email address', 'error');
            emailInput.focus();
            return false;
        }
        
        if (!emailRegex.test(email)) {
            showNotification('Please enter a valid email address', 'error');
            emailInput.focus();
            return false;
        }
        
        return true;
    }

    /**
     * Validate OTP
     */
    function validateOTP() {
        if (!otpInput) return false;
        
        const otp = otpInput.value.trim();
        
        if (!otp) {
            if (continueStep3Btn) {
                continueStep3Btn.disabled = true;
            }
            return false;
        }
        
        if (otp.length !== 6) {
            if (continueStep3Btn) {
                continueStep3Btn.disabled = true;
            }
            return false;
        }
        
        if (continueStep3Btn) {
            continueStep3Btn.disabled = false;
        }
        
        return true;
    }

    /**
     * Initialize Withdrawal Form
     */
    function initWithdrawalForm() {
        if (!withdrawalPaymentMethods || withdrawalPaymentMethods.length === 0) return;

        withdrawalPaymentMethods.forEach(method => {
            method.addEventListener('click', function() {
                withdrawalPaymentMethods.forEach(m => m.classList.remove('active'));
                this.classList.add('active');
                validateWithdrawalForm();
            });
        });
    }

    /**
     * Validate Withdrawal Form
     */
    function validateWithdrawalForm() {
        if (!withdrawalAccountNumber || !withdrawalAccountName || !withdrawalAmount) return false;

        const accountNumber = withdrawalAccountNumber.value.trim();
        const accountName = withdrawalAccountName.value.trim();
        const amount = withdrawalAmount.value.trim();
        const selectedMethod = document.querySelector('.withdrawal-payment-method.active');

        let isValid = true;

        if (!accountNumber) {
            isValid = false;
        }

        if (!accountName) {
            isValid = false;
        }

        if (!selectedMethod) {
            isValid = false;
        }

        if (!amount || parseFloat(amount) <= 0) {
            isValid = false;
        }

        if (submitWithdrawalBtn) {
            submitWithdrawalBtn.disabled = !isValid;
        }

        return isValid;
    }

    /**
     * Submit Withdrawal
     */
    function submitWithdrawal() {
        if (!validateWithdrawalForm()) {
            showNotification('Please fill in all required fields', 'error');
            return;
        }

        const accountNumber = withdrawalAccountNumber.value.trim();
        const accountName = withdrawalAccountName.value.trim();
        const amount = withdrawalAmount.value.trim();
        const selectedMethod = document.querySelector('.withdrawal-payment-method.active');

        if (!selectedMethod) {
            showNotification('Please select a payment method', 'error');
            return;
        }

        const method = selectedMethod.dataset.method;

        // Disable button during submission
        if (submitWithdrawalBtn) {
            submitWithdrawalBtn.disabled = true;
            submitWithdrawalBtn.innerHTML = '<span>Processing...</span>';
        }

        // Simulate API call
        setTimeout(() => {
            showNotification('Withdrawal request submitted successfully!', 'success');
            
            // Reset form
            if (withdrawalAccountNumber) withdrawalAccountNumber.value = '';
            if (withdrawalAccountName) withdrawalAccountName.value = '';
            if (withdrawalAmount) withdrawalAmount.value = '';
            withdrawalPaymentMethods.forEach(m => m.classList.remove('active'));

            // Re-enable button
            if (submitWithdrawalBtn) {
                submitWithdrawalBtn.disabled = false;
                submitWithdrawalBtn.innerHTML = '<span>Submit Withdrawal Request</span>';
            }

            // Redirect to transactions or wallet page after 2 seconds
            setTimeout(() => {
                window.location.href = '/user/dashboard/transactions';
            }, 2000);
        }, 1500);
    }

    /**
     * Go to Specific Step
     */
    function goToStep(step) {
        if (step < 1 || step > 4) return;
        
        currentStep = step;
        
        // Update step indicators
        steps.forEach((stepEl, index) => {
            const stepNum = index + 1;
            if (stepNum < currentStep) {
                stepEl.classList.add('completed');
                stepEl.classList.remove('active');
            } else if (stepNum === currentStep) {
                stepEl.classList.add('active');
                stepEl.classList.remove('completed');
            } else {
                stepEl.classList.remove('active', 'completed');
            }
        });
        
        // Update step lines
        stepLines.forEach((line, index) => {
            if (index + 1 < currentStep) {
                line.classList.add('active');
            } else {
                line.classList.remove('active');
            }
        });
        
        // Show/hide step contents
        stepContents.forEach((content, index) => {
            if (index + 1 === currentStep) {
                content.classList.add('active');
            } else {
                content.classList.remove('active');
            }
        });
        
        // Start OTP timer if on step 2
        if (currentStep === 2) {
            startOTPTimer();
        }
        
        // Focus on input
        if (currentStep === 2 && otpInput) {
            setTimeout(() => otpInput.focus(), 300);
        }

        // Initialize withdrawal form when step 4 is reached
        if (currentStep === 4) {
            validateWithdrawalForm();
            if (withdrawalAccountNumber) {
                setTimeout(() => withdrawalAccountNumber.focus(), 300);
            }
        }
    }

    /**
     * Start OTP Timer
     */
    function startOTPTimer() {
        if (otpTimer) {
            clearInterval(otpTimer);
        }
        
        otpTimerSeconds = 60; // 60 seconds
        updateOTPTimer();
        
        if (resendOtpBtn) {
            resendOtpBtn.disabled = true;
        }
        
        otpTimer = setInterval(() => {
            otpTimerSeconds--;
            updateOTPTimer();
            
            if (otpTimerSeconds <= 0) {
                clearInterval(otpTimer);
                if (resendOtpBtn) {
                    resendOtpBtn.disabled = false;
                }
            }
        }, 1000);
    }

    /**
     * Update OTP Timer Display
     */
    function updateOTPTimer() {
        if (!otpTimerElement) return;
        
        const minutes = Math.floor(otpTimerSeconds / 60);
        const seconds = otpTimerSeconds % 60;
        otpTimerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    }

    /**
     * Resend OTP
     */
    function resendOTP() {
        // Placeholder for resend OTP functionality
        showNotification('OTP has been resent to your email', 'success');
        startOTPTimer();
        
        if (otpInput) {
            otpInput.value = '';
            otpInput.focus();
        }
    }

    /**
     * Show Notification
     */
    function showNotification(message, type = 'success') {
        // Remove existing notification if any
        const existing = document.querySelector('.security-notification');
        if (existing) {
            existing.remove();
        }
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `security-notification security-notification-${type}`;
        notification.textContent = message;
        
        // Add styles
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            background: ${type === 'success' ? 'var(--success-color)' : type === 'error' ? 'var(--danger-color)' : 'var(--info-color)'};
            color: white;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
            z-index: 10000;
            animation: slideInRight 0.3s ease-out;
            font-weight: 500;
            font-size: 0.875rem;
        `;
        
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 6000);
    }

    // Add animation styles
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

})();









/**
 * Withdrawal Confirmation Page - JavaScript
 * Handles 2-step withdrawal confirmation
 */

(function() {
    'use strict';

    // State
    let currentStep = 1;
    let formData = {
        accountHolderName: '',
        accountNumber: '',
        bankName: ''
    };

    // DOM Elements
    const steps = document.querySelectorAll('.deposit-step');
    const stepContents = document.querySelectorAll('.deposit-step-content');
    const stepLines = document.querySelectorAll('.deposit-step-line');
    const accountHolderNameInput = document.getElementById('withdrawAccountHolderName');
    const accountNumberInput = document.getElementById('withdrawAccountNumber');
    const bankNameInput = document.getElementById('withdrawBankName');
    const bankNameField = document.getElementById('bankNameField');
    const continueStep2Btn = document.getElementById('continueToStep2');
    const submitWithdrawalBtn = document.getElementById('submitWithdrawal');
    const paymentDetailsSection = document.getElementById('withdrawPaymentDetails');
    const displayAccountName = document.getElementById('displayAccountName');
    const displayAccountNumber = document.getElementById('displayAccountNumber');
    const displayBankName = document.getElementById('displayBankName');
    const displayBankNameRow = document.getElementById('displayBankNameRow');
    
    const paymentMethodId = document.getElementById('withdrawPaymentMethodId')?.value;
    const paymentMethodType = document.getElementById('withdrawPaymentMethodType')?.value;
    const amount = document.getElementById('withdrawAmount')?.value;
    const storeUrl = document.getElementById('withdrawStoreUrl')?.value;
    const indexUrl = document.getElementById('withdrawIndexUrl')?.value;
    
    // Check if bank name is required (only for bank type) - use case-insensitive comparison
    const isBankNameRequired = paymentMethodType && paymentMethodType.toLowerCase() === 'bank' && bankNameField !== null;

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Read initial form values if they exist (pre-filled)
        if (accountHolderNameInput) {
            formData.accountHolderName = accountHolderNameInput.value.trim();
        }
        if (accountNumberInput) {
            formData.accountNumber = accountNumberInput.value.trim();
        }
        if (bankNameInput && isBankNameRequired) {
            formData.bankName = bankNameInput.value.trim();
        }
        
        // Initialize bank name row visibility
        if (displayBankNameRow) {
            // Hide bank name row initially if not required or no value
            if (!isBankNameRequired || !formData.bankName || formData.bankName.trim().length === 0) {
                displayBankNameRow.style.display = 'none';
            }
        }
        
        initStepNavigation();
        initFormValidation();
        // Run initial validation to set button state
        validateStep1();
    });

    /**
     * Initialize Step Navigation
     */
    function initStepNavigation() {
        if (continueStep2Btn) {
            continueStep2Btn.addEventListener('click', function() {
                if (validateStep1()) {
                    goToStep(2);
                }
            });
        }

        if (submitWithdrawalBtn) {
            submitWithdrawalBtn.addEventListener('click', function() {
                submitWithdrawal();
            });
        }
    }

    /**
     * Initialize Form Validation
     */
    function initFormValidation() {
        if (accountHolderNameInput) {
            accountHolderNameInput.addEventListener('input', function() {
                formData.accountHolderName = this.value.trim();
                validateStep1();
            });
        }

        if (accountNumberInput) {
            accountNumberInput.addEventListener('input', function() {
                formData.accountNumber = this.value.trim();
                validateStep1();
            });
        }

        if (bankNameInput && isBankNameRequired) {
            bankNameInput.addEventListener('input', function() {
                formData.bankName = this.value.trim();
                validateStep1();
            });
        }
    }

    /**
     * Validate Step 1 (Account Details)
     */
    function validateStep1() {
        let isValid = formData.accountHolderName.length > 0 && formData.accountNumber.length > 0;
        
        // Bank name is only required for bank type payment methods
        if (isBankNameRequired) {
            isValid = isValid && formData.bankName.length > 0;
        }
        
        if (continueStep2Btn) {
            continueStep2Btn.disabled = !isValid;
        }
        
        return isValid;
    }

    /**
     * Go to specific step
     */
    function goToStep(step) {
        if (step < 1 || step > 2) return;
        
        currentStep = step;
        
        // Update step indicators
        steps.forEach((stepEl, index) => {
            const stepNum = index + 1;
            if (stepNum <= step) {
                stepEl.classList.add('active');
            } else {
                stepEl.classList.remove('active');
            }
        });
        
        // Update step lines
        stepLines.forEach((line, index) => {
            const stepNum = index + 1;
            if (stepNum < step) {
                line.classList.add('active');
            } else {
                line.classList.remove('active');
            }
        });
        
        // Show/hide step contents
        stepContents.forEach((content, index) => {
            const stepNum = index + 1;
            if (stepNum === step) {
                content.classList.add('active');
            } else {
                content.classList.remove('active');
            }
        });
        
        // Show/hide payment details section
        if (paymentDetailsSection) {
            if (step === 2) {
                // Show payment details on step 2 and update account details
                paymentDetailsSection.style.display = 'block';
                if (displayAccountName && formData.accountHolderName) {
                    displayAccountName.textContent = formData.accountHolderName;
                }
                if (displayAccountNumber && formData.accountNumber) {
                    displayAccountNumber.textContent = formData.accountNumber;
                }
                if (displayBankName && formData.bankName) {
                    displayBankName.textContent = formData.bankName;
                }
                // Show/hide bank name row based on whether it's required and has value
                if (displayBankNameRow) {
                    if (isBankNameRequired && formData.bankName && formData.bankName.trim().length > 0) {
                        displayBankNameRow.style.display = 'flex';
                    } else {
                        displayBankNameRow.style.display = 'none';
                    }
                }
            } else {
                // Hide payment details on step 1
                paymentDetailsSection.style.display = 'none';
            }
        }
    }

    /**
     * Submit Withdrawal Request
     */
    function submitWithdrawal() {
        if (!paymentMethodId || !amount) {
            showNotification('Missing payment method or amount. Please go back and try again.', 'error');
            return;
        }

        if (!formData.accountHolderName || !formData.accountNumber) {
            showNotification('Please fill in all required account details.', 'error');
            return;
        }
        
        // Bank name is only required for bank type
        if (isBankNameRequired && !formData.bankName) {
            showNotification('Please fill in all required account details.', 'error');
            return;
        }

        // Disable submit button
        if (submitWithdrawalBtn) {
            submitWithdrawalBtn.disabled = true;
            submitWithdrawalBtn.innerHTML = '<span>Submitting...</span>';
        }

        // Create FormData
        const formDataToSend = new FormData();
        formDataToSend.append('payment_method_id', paymentMethodId);
        formDataToSend.append('amount', amount);
        formDataToSend.append('account_holder_name', formData.accountHolderName);
        formDataToSend.append('account_number', formData.accountNumber);
        // Only include bank_name if it's required (bank type)
        if (isBankNameRequired) {
            formDataToSend.append('bank_name', formData.bankName || '');
        } else {
            formDataToSend.append('bank_name', '');
        }
        formDataToSend.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');

        // Submit via AJAX
        fetch(storeUrl, {
            method: 'POST',
            body: formDataToSend,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => {
                    window.location.href = indexUrl;
                }, 6000);
            } else {
                showNotification(data.message || 'Failed to submit withdrawal request. Please try again.', 'error');
                if (submitWithdrawalBtn) {
                    submitWithdrawalBtn.disabled = false;
                    submitWithdrawalBtn.innerHTML = '<span>Submit Withdrawal Request</span>';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
            if (submitWithdrawalBtn) {
                submitWithdrawalBtn.disabled = false;
                submitWithdrawalBtn.innerHTML = '<span>Submit Withdrawal Request</span>';
            }
        });
    }

    /**
     * Show Notification
     */
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8'};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            z-index: 10000;
            max-width: 400px;
            animation: slideIn 0.3s ease;
        `;
        notification.textContent = message;

        // Add animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);

        document.body.appendChild(notification);

        // Remove after 5 seconds
        setTimeout(() => {
            notification.style.animation = 'slideIn 0.3s ease reverse';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 6000);
    }
})();


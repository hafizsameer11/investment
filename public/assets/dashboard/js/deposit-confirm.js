/**
 * Deposit Confirmation Page - JavaScript
 * Handles multi-step deposit confirmation with 40-second timer on each step
 */

(function() {
    'use strict';

    // State
    let currentStep = 1;
    let stepTimer = null;
    let stepTimerSeconds = 0;
    const TIMER_DURATION = 40; // 40 seconds per step

    // Form data storage
    let formData = {
        transactionId: '',
        paymentProof: null,
        accountNumber: '',
        accountHolderName: ''
    };

    // DOM Elements
    const steps = document.querySelectorAll('.deposit-step');
    const stepContents = document.querySelectorAll('.deposit-step-content');
    const stepLines = document.querySelectorAll('.deposit-step-line');
    const transactionIdInput = document.getElementById('depositTransactionId');
    const paymentProofInput = document.getElementById('depositPaymentProof');
    const accountNumberInput = document.getElementById('depositAccountNumber');
    const accountHolderNameInput = document.getElementById('depositAccountHolderName');
    const continueStep2Btn = document.getElementById('continueToStep2');
    const continueStep3Btn = document.getElementById('continueToStep3');
    const continueStep4Btn = document.getElementById('continueToStep4');
    const submitDepositBtn = document.getElementById('submitDeposit');
    const uploadPreview = document.getElementById('depositUploadPreview');
    const uploadContent = document.getElementById('depositUploadContent');
    const previewImage = document.getElementById('depositPreviewImage');
    const removeFileBtn = document.getElementById('depositRemoveFile');

    // Timer elements for each step
    let timerElement = null;

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        initStepNavigation();
        initFormValidation();
        initFileUpload();
        startStepTimer(); // Start timer on step 1
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

        if (continueStep3Btn) {
            continueStep3Btn.addEventListener('click', function() {
                if (validateStep2()) {
                    goToStep(3);
                }
            });
        }

        if (continueStep4Btn) {
            continueStep4Btn.addEventListener('click', function() {
                if (validateStep3()) {
                    goToStep(4);
                }
            });
        }

        if (submitDepositBtn) {
            submitDepositBtn.addEventListener('click', function() {
                submitDeposit();
            });
        }
    }

    /**
     * Initialize Form Validation
     */
    function initFormValidation() {
        if (transactionIdInput) {
            transactionIdInput.addEventListener('input', function() {
                formData.transactionId = this.value.trim();
                validateStep1();
            });
        }

        if (accountNumberInput) {
            accountNumberInput.addEventListener('input', function() {
                formData.accountNumber = this.value.trim();
                validateStep3();
            });
        }

        if (accountHolderNameInput) {
            accountHolderNameInput.addEventListener('input', function() {
                formData.accountHolderName = this.value.trim();
                validateStep3();
            });
        }
    }

    /**
     * Initialize File Upload
     */
    function initFileUpload() {
        if (paymentProofInput) {
            paymentProofInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            if (previewImage) {
                                previewImage.src = e.target.result;
                            }
                            if (uploadContent) {
                                uploadContent.style.display = 'none';
                            }
                            if (uploadPreview) {
                                uploadPreview.style.display = 'block';
                            }
                            formData.paymentProof = file;
                            validateStep2();
                        };
                        reader.readAsDataURL(file);
                    } else {
                        showNotification('Please upload an image file (JPEG, PNG, etc.)', 'error');
                        paymentProofInput.value = '';
                        formData.paymentProof = null;
                    }
                }
            });
        }

        if (removeFileBtn) {
            removeFileBtn.addEventListener('click', function() {
                if (paymentProofInput) {
                    paymentProofInput.value = '';
                }
                if (uploadPreview) {
                    uploadPreview.style.display = 'none';
                }
                if (uploadContent) {
                    uploadContent.style.display = 'block';
                }
                formData.paymentProof = null;
                validateStep2();
            });
        }
    }

    /**
     * Validate Step 1 - Transaction ID
     */
    function validateStep1() {
        if (!transactionIdInput) return false;

        const transactionId = transactionIdInput.value.trim();

        if (!transactionId) {
            if (continueStep2Btn) {
                continueStep2Btn.disabled = true;
            }
            return false;
        }

        if (continueStep2Btn) {
            continueStep2Btn.disabled = false;
        }

        return true;
    }

    /**
     * Validate Step 2 - Payment Proof
     */
    function validateStep2() {
        if (!formData.paymentProof) {
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
     * Validate Step 3 - Account Details
     */
    function validateStep3() {
        if (!accountNumberInput || !accountHolderNameInput) return false;

        const accountNumber = accountNumberInput.value.trim();
        const accountHolderName = accountHolderNameInput.value.trim();

        if (!accountNumber || !accountHolderName) {
            if (continueStep4Btn) {
                continueStep4Btn.disabled = true;
            }
            return false;
        }

        if (continueStep4Btn) {
            continueStep4Btn.disabled = false;
        }

        return true;
    }

    /**
     * Go to Specific Step
     */
    function goToStep(step) {
        if (step < 1 || step > 4) return;

        // Stop current timer
        stopStepTimer();

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

        // Start timer for steps 1, 2, and 3
        if (currentStep >= 1 && currentStep <= 3) {
            startStepTimer();
        }

        // Focus on input
        if (currentStep === 1 && transactionIdInput) {
            setTimeout(() => transactionIdInput.focus(), 300);
        } else if (currentStep === 3 && accountNumberInput) {
            setTimeout(() => accountNumberInput.focus(), 300);
        }
    }

    /**
     * Start Step Timer
     */
    function startStepTimer() {
        // Clear any existing timer
        stopStepTimer();

        // Get the timer element for current step
        timerElement = document.getElementById(`depositTimerStep${currentStep}`);

        if (!timerElement) {
            console.warn(`Timer element not found for step ${currentStep}`);
            return;
        }

        stepTimerSeconds = TIMER_DURATION;
        updateTimerDisplay();

        // Show timer element
        if (timerElement) {
            timerElement.style.display = 'inline-block';
        }

        stepTimer = setInterval(() => {
            stepTimerSeconds--;
            updateTimerDisplay();

            if (stepTimerSeconds <= 0) {
                stopStepTimer();
                handleTimerExpired();
            }
        }, 1000);
    }

    /**
     * Stop Step Timer
     */
    function stopStepTimer() {
        if (stepTimer) {
            clearInterval(stepTimer);
            stepTimer = null;
        }
    }

    /**
     * Update Timer Display
     */
    function updateTimerDisplay() {
        if (!timerElement) return;

        const minutes = Math.floor(stepTimerSeconds / 60);
        const seconds = stepTimerSeconds % 60;
        timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

        // Add warning class when time is running out
        if (stepTimerSeconds <= 10) {
            timerElement.classList.add('warning');
        } else {
            timerElement.classList.remove('warning');
        }
    }

    /**
     * Handle Timer Expired
     */
    function handleTimerExpired() {
        stopStepTimer();

        // Show alert
        alert('Time expired! Your deposit request has been cancelled. Please start the process again.');

        // Redirect to deposit selection page
        const depositIndexUrl = document.getElementById('depositIndexUrl')?.value || '/user/dashboard/deposit';
        window.location.href = depositIndexUrl;
    }

    /**
     * Submit Deposit
     */
    function submitDeposit() {
        // Stop timer
        stopStepTimer();

        // Final validation
        if (!formData.transactionId) {
            showNotification('Please enter transaction ID', 'error');
            goToStep(1);
            return;
        }

        if (!formData.paymentProof) {
            showNotification('Please upload payment proof', 'error');
            goToStep(2);
            return;
        }

        if (!formData.accountNumber || !formData.accountHolderName) {
            showNotification('Please enter account details', 'error');
            goToStep(3);
            return;
        }

        // Get payment method and amount from hidden inputs
        const paymentMethodId = document.getElementById('depositPaymentMethodId')?.value;
        const amount = document.getElementById('depositAmount')?.value;
        const pkrAmount = document.getElementById('depositPkrAmount')?.value;

        if (!paymentMethodId || !amount || !pkrAmount) {
            showNotification('Missing payment information. Please start over.', 'error');
            const depositIndexUrl = document.getElementById('depositIndexUrl')?.value || '/user/dashboard/deposit';
            window.location.href = depositIndexUrl;
            return;
        }

        // Get current values directly from inputs to ensure accuracy
        const currentTransactionId = transactionIdInput ? transactionIdInput.value.trim() : formData.transactionId;
        const currentAccountNumber = accountNumberInput ? accountNumberInput.value.trim() : formData.accountNumber;
        const currentAccountHolderName = accountHolderNameInput ? accountHolderNameInput.value.trim() : formData.accountHolderName;
        const currentPaymentProof = formData.paymentProof;

        // Create FormData for file upload
        const submitFormData = new FormData();
        submitFormData.append('payment_method_id', paymentMethodId);
        submitFormData.append('amount', amount);
        submitFormData.append('pkr_amount', pkrAmount);
        submitFormData.append('transaction_id', currentTransactionId);
        submitFormData.append('account_number', currentAccountNumber);
        submitFormData.append('account_holder_name', currentAccountHolderName);
        submitFormData.append('payment_proof', currentPaymentProof);

        // Get CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        submitFormData.append('_token', csrfToken);

        // Disable button during submission
        if (submitDepositBtn) {
            submitDepositBtn.disabled = true;
            const originalText = submitDepositBtn.innerHTML;
            submitDepositBtn.innerHTML = '<span>Submitting...</span>';

            // Submit form via AJAX
            fetch(document.getElementById('depositStoreUrl')?.value || '/user/dashboard/deposit', {
                method: 'POST',
                body: submitFormData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message || 'Deposit request submitted successfully!', 'success');

                    // Redirect after 2 seconds
                    setTimeout(() => {
                        window.location.href = data.redirect || '/user/dashboard/deposit';
                    }, 2000);
                } else {
                    submitDepositBtn.disabled = false;
                    submitDepositBtn.innerHTML = originalText;
                    showNotification(data.message || 'An error occurred. Please try again.', 'error');
                }
            })
            .catch(error => {
                submitDepositBtn.disabled = false;
                submitDepositBtn.innerHTML = originalText;
                console.error('Error:', error);
                showNotification('An error occurred while submitting your deposit. Please try again.', 'error');
            });
        }
    }

    /**
     * Show Notification
     */
    function showNotification(message, type = 'success') {
        // Remove existing notification if any
        const existing = document.querySelector('.deposit-notification');
        if (existing) {
            existing.remove();
        }

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `deposit-notification deposit-notification-${type}`;
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
        }, 3000);
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

    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        stopStepTimer();
    });

})();


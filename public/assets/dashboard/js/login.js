/**
 * Dashboard Login Page - JavaScript
 * Handles form interactions, validation, and UX enhancements
 */

(function() {
    'use strict';

    // DOM Elements
    const loginForm = document.getElementById('loginForm');
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    const resetPasswordForm = document.getElementById('resetPasswordForm');
    const passwordInput = document.getElementById('password');
    const passwordToggle = document.getElementById('passwordToggle');
    const loginButton = document.getElementById('loginButton');
    const forgotPasswordButton = document.getElementById('forgotPasswordButton');
    const resetPasswordButton = document.getElementById('resetPasswordButton');
    const emailInput = document.getElementById('email');
    const rememberCheckbox = document.getElementById('remember');

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        initPasswordToggle();
        initFormValidation();
        initFormSubmission();
        initInputAnimations();
        initPasswordResetForms();
        restoreFormData();
    });

    /**
     * Password Toggle Functionality
     */
    function initPasswordToggle() {
        if (!passwordToggle || !passwordInput) return;

        passwordToggle.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Update icon
            const icon = passwordToggle.querySelector('svg');
            if (type === 'text') {
                // Show eye-off icon
                icon.innerHTML = `
                    <path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                `;
            } else {
                // Show eye icon
                icon.innerHTML = `
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                `;
            }
        });
    }

    /**
     * Real-time Form Validation
     */
    function initFormValidation() {
        if (!emailInput) return;

        // Email validation
        emailInput.addEventListener('blur', function() {
            validateEmail(this);
        });

        emailInput.addEventListener('input', function() {
            if (this.classList.contains('input-error')) {
                validateEmail(this);
            }
        });

        // Password validation
        if (passwordInput) {
            passwordInput.addEventListener('blur', function() {
                validatePassword(this);
            });

            passwordInput.addEventListener('input', function() {
                if (this.classList.contains('input-error')) {
                    validatePassword(this);
                }
            });
        }
    }

    /**
     * Validate Email or Username
     */
    function validateEmail(input) {
        // Allow both email and username, just check if field is not empty
        const isValid = input.value.trim().length > 0;

        if (!isValid) {
            input.classList.add('input-error');
            showFieldError(input, 'Please enter your email or username');
            return false;
        } else {
            input.classList.remove('input-error');
            hideFieldError(input);
            return true;
        }
    }

    /**
     * Validate Password
     */
    function validatePassword(input) {
        const isValid = input.value.length >= 6;

        if (input.value && !isValid) {
            input.classList.add('input-error');
            showFieldError(input, 'Password must be at least 6 characters');
            return false;
        } else {
            input.classList.remove('input-error');
            hideFieldError(input);
            return true;
        }
    }

    /**
     * Show Field Error
     */
    function showFieldError(input, message) {
        hideFieldError(input);
        
        const errorElement = document.createElement('div');
        errorElement.className = 'field-error';
        errorElement.textContent = message;
        errorElement.style.cssText = `
            color: var(--error-color);
            font-size: 0.75rem;
            margin-top: 0.25rem;
            animation: slideDown 0.3s ease-out;
        `;
        
        input.parentElement.parentElement.appendChild(errorElement);
    }

    /**
     * Hide Field Error
     */
    function hideFieldError(input) {
        const errorElement = input.parentElement.parentElement.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
    }

    /**
     * Form Submission Handler
     */
    function initFormSubmission() {
        if (!loginForm) return;

        loginForm.addEventListener('submit', function(e) {
            // Basic validation - just check if fields are filled
            const hasEmail = emailInput && emailInput.value.trim().length > 0;
            const hasPassword = passwordInput && passwordInput.value.length > 0;

            if (!hasEmail || !hasPassword) {
                e.preventDefault();
                if (!hasEmail) {
                    showFieldError(emailInput, 'Please enter your email or username');
                }
                if (!hasPassword) {
                    showFieldError(passwordInput, 'Please enter your password');
                }
                return false;
            }

            // Show loading state
            setLoadingState(true);

            // Allow form to submit to backend
            // The form will submit normally, and the backend will handle authentication
            // Save form data if remember me is checked
            if (rememberCheckbox && rememberCheckbox.checked) {
                saveFormData();
            } else {
                clearFormData();
            }
        });
    }

    /**
     * Set Loading State
     */
    function setLoadingState(loading) {
        if (!loginButton) return;

        if (loading) {
            loginButton.classList.add('loading');
            loginButton.disabled = true;
        } else {
            loginButton.classList.remove('loading');
            loginButton.disabled = false;
        }
    }

    /**
     * Input Animations
     */
    function initInputAnimations() {
        const inputs = document.querySelectorAll('.form-input');
        
        inputs.forEach(input => {
            // Add focus animation
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.01)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });

            // Floating label effect (if needed)
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.style.paddingTop = '1.25rem';
                } else {
                    this.style.paddingTop = '';
                }
            });
        });
    }

    /**
     * Save Form Data to LocalStorage
     */
    function saveFormData() {
        if (typeof Storage === 'undefined') return;

        try {
            const formData = {
                email: emailInput.value,
                remember: rememberCheckbox ? rememberCheckbox.checked : false
            };
            localStorage.setItem('dashboard_login_data', JSON.stringify(formData));
        } catch (e) {
            console.warn('Could not save form data:', e);
        }
    }

    /**
     * Restore Form Data from LocalStorage
     */
    function restoreFormData() {
        if (typeof Storage === 'undefined') return;

        try {
            const savedData = localStorage.getItem('dashboard_login_data');
            if (savedData) {
                const formData = JSON.parse(savedData);
                if (formData.email && emailInput) {
                    emailInput.value = formData.email;
                }
                if (formData.remember && rememberCheckbox) {
                    rememberCheckbox.checked = true;
                }
            }
        } catch (e) {
            console.warn('Could not restore form data:', e);
        }
    }

    /**
     * Clear Form Data from LocalStorage
     */
    function clearFormData() {
        if (typeof Storage === 'undefined') return;

        try {
            localStorage.removeItem('dashboard_login_data');
        } catch (e) {
            console.warn('Could not clear form data:', e);
        }
    }

    /**
     * Handle Form Errors from Server
     */
    const alertError = document.querySelector('.alert-error');
    if (alertError) {
        // Auto-hide error after 5 seconds
        setTimeout(() => {
            alertError.style.opacity = '0';
            setTimeout(() => {
                alertError.style.display = 'none';
            }, 300);
        }, 5000);
    }

    /**
     * Chat Widget Interaction
     */
    const chatWidget = document.querySelector('.chat-widget');
    if (chatWidget) {
        chatWidget.addEventListener('click', function() {
            // Placeholder for chat widget integration
            console.log('Chat widget clicked');
            // You can integrate with Tawk.to or other chat services here
        });
    }

    /**
     * Initialize Password Reset Forms
     */
    function initPasswordResetForms() {
        // Forgot Password Form
        if (forgotPasswordForm && forgotPasswordButton) {
            forgotPasswordForm.addEventListener('submit', function(e) {
                const email = emailInput && emailInput.value.trim();
                if (!email) {
                    e.preventDefault();
                    if (emailInput) {
                        showFieldError(emailInput, 'Please enter your email address');
                    }
                    return false;
                }
                setLoadingStateForButton(forgotPasswordButton, true);
            });
        }

        // Reset Password Form
        if (resetPasswordForm && resetPasswordButton) {
            const passwordConfirmationInput = document.getElementById('password_confirmation');
            
            resetPasswordForm.addEventListener('submit', function(e) {
                const password = passwordInput && passwordInput.value;
                const passwordConfirmation = passwordConfirmationInput && passwordConfirmationInput.value;

                if (!password || password.length < 8) {
                    e.preventDefault();
                    if (passwordInput) {
                        showFieldError(passwordInput, 'Password must be at least 8 characters');
                    }
                    return false;
                }

                if (password !== passwordConfirmation) {
                    e.preventDefault();
                    if (passwordConfirmationInput) {
                        showFieldError(passwordConfirmationInput, 'Passwords do not match');
                    }
                    return false;
                }

                setLoadingStateForButton(resetPasswordButton, true);
            });

            // Real-time password confirmation validation
            if (passwordConfirmationInput && passwordInput) {
                passwordConfirmationInput.addEventListener('input', function() {
                    if (this.value && passwordInput.value && this.value !== passwordInput.value) {
                        this.classList.add('input-error');
                        showFieldError(this, 'Passwords do not match');
                    } else {
                        this.classList.remove('input-error');
                        hideFieldError(this);
                    }
                });
            }
        }
    }

    /**
     * Set Loading State for Any Button
     */
    function setLoadingStateForButton(button, loading) {
        if (!button) return;

        if (loading) {
            button.classList.add('loading');
            button.disabled = true;
        } else {
            button.classList.remove('loading');
            button.disabled = false;
        }
    }

    /**
     * Keyboard Navigation Enhancement
     */
    document.addEventListener('keydown', function(e) {
        // Submit form on Enter key
        if (e.key === 'Enter' && (emailInput === document.activeElement || passwordInput === document.activeElement)) {
            if (loginForm && !loginButton.disabled) {
                loginForm.dispatchEvent(new Event('submit'));
            }
        }
    });

})();


/**
 * Profile Page - JavaScript
 * Handles profile page interactions
 */

(function() {
    'use strict';

    // DOM Elements
    const tabs = document.querySelectorAll('.profile-tab-modern, .profile-tab');
    const tabContents = document.querySelectorAll('.profile-tab-content-modern, .profile-tab-content');
    const editBtn = document.getElementById('editProfileBtn');
    const saveBtn = document.getElementById('saveProfileBtn');
    const cancelBtn = document.getElementById('cancelProfileBtn');
    const savePasswordBtn = document.getElementById('savePasswordBtn');
    const formInputs = document.querySelectorAll('.profile-form-input-modern, .profile-form-input');
    let isEditMode = false;

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        highlightActiveNavItem();
        initTabs();
        initEditMode();
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
                            (currentPath.includes('/user/dashboard/profile') && linkPath.includes('/profile'))) {
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
     * Initialize Tab Switching
     */
    function initTabs() {
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');
                
                // Remove active class from all tabs and contents
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding content
                this.classList.add('active');
                const targetContent = document.getElementById(targetTab + 'Tab');
                if (targetContent) {
                    targetContent.classList.add('active');
                }
            });
        });
    }

    /**
     * Initialize Edit Mode
     */
    function initEditMode() {
        if (!editBtn) return;

        editBtn.addEventListener('click', function() {
            isEditMode = !isEditMode;
            
            if (isEditMode) {
                // Enable edit mode
                formInputs.forEach(input => {
                    if (input.id !== 'email' && input.closest('#accountTab')) { // Keep email read-only, only edit account tab
                        input.removeAttribute('readonly');
                        input.classList.add('editable');
                    }
                });
                
                // Show save and cancel buttons
                if (saveBtn) saveBtn.style.display = 'flex';
                if (cancelBtn) cancelBtn.style.display = 'flex';
                
                // Hide edit button
                this.style.display = 'none';
            }
        });

        // Save button handler
        if (saveBtn) {
            saveBtn.addEventListener('click', function() {
                // Disable edit mode
                formInputs.forEach(input => {
                    if (input.closest('#accountTab')) {
                        input.setAttribute('readonly', 'readonly');
                        input.classList.remove('editable');
                    }
                });
                
                // Hide save and cancel buttons
                this.style.display = 'none';
                if (cancelBtn) cancelBtn.style.display = 'none';
                
                // Show edit button
                if (editBtn) editBtn.style.display = 'flex';
                
                isEditMode = false;
                
                // Show success message
                showNotification('Profile updated successfully!', 'success');
            });
        }

        // Cancel button handler
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                // Disable edit mode
                formInputs.forEach(input => {
                    if (input.closest('#accountTab')) {
                        input.setAttribute('readonly', 'readonly');
                        input.classList.remove('editable');
                    }
                });
                
                // Hide save and cancel buttons
                this.style.display = 'none';
                if (saveBtn) saveBtn.style.display = 'none';
                
                // Show edit button
                if (editBtn) editBtn.style.display = 'flex';
                
                isEditMode = false;
            });
        }

        // Save password button handler
        if (savePasswordBtn) {
            savePasswordBtn.addEventListener('click', function() {
                const currentPassword = document.getElementById('currentPassword');
                const newPassword = document.getElementById('newPassword');
                const confirmPassword = document.getElementById('confirmPassword');
                
                // Basic validation
                if (!currentPassword.value || !newPassword.value || !confirmPassword.value) {
                    showNotification('Please fill in all password fields', 'error');
                    return;
                }
                
                if (newPassword.value !== confirmPassword.value) {
                    showNotification('New passwords do not match', 'error');
                    return;
                }
                
                if (newPassword.value.length < 8) {
                    showNotification('Password must be at least 8 characters', 'error');
                    return;
                }
                
                // Clear password fields
                currentPassword.value = '';
                newPassword.value = '';
                confirmPassword.value = '';
                
                // Show success message
                showNotification('Password updated successfully!', 'success');
            });
        }
    }

    /**
     * Show Notification
     */
    function showNotification(message, type = 'success') {
        // Remove existing notification if any
        const existing = document.querySelector('.profile-notification');
        if (existing) {
            existing.remove();
        }
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `profile-notification profile-notification-${type}`;
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

})();


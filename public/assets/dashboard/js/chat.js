/**
 * User-side Chat Functionality
 */

(function() {
    let currentChatId = null;
    let echo = null;

    // Initialize Echo if available
    if (typeof window.Echo !== 'undefined') {
        echo = window.Echo;
    }

    // Chat Button and Modal
    const chatButton = document.getElementById('chatButton');
    const chatNotificationBadge = document.getElementById('chatNotificationBadge');
    const startChatModal = document.getElementById('startChatModal');
    const closeStartChatModal = document.getElementById('closeStartChatModal');
    const startChatForm = document.getElementById('startChatForm');
    const liveChatWindow = document.getElementById('liveChatWindow');
    const closeLiveChat = document.getElementById('closeLiveChat');
    const chatMessages = document.getElementById('chatMessages');
    const chatMessageInput = document.getElementById('chatMessageInput');
    const sendChatMessage = document.getElementById('sendChatMessage');
    const chatStatus = document.getElementById('chatStatus');

    // Open start chat modal or existing chat
    if (chatButton) {
        chatButton.addEventListener('click', function() {
            // Check for existing active chat first
            checkForActiveChat();
        });
    }

    function checkForActiveChat() {
        // Check if user is authenticated by checking for auth token or user data
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        const isAuthenticated = csrfToken && window.location.pathname.includes('/user/dashboard');
        
        let url = '/chat/active';
        
        // For guest users, try to get email from form if it exists
        if (!isAuthenticated) {
            const emailInput = document.getElementById('chatEmail');
            if (emailInput && emailInput.value) {
                url += '?email=' + encodeURIComponent(emailInput.value);
            } else {
                // No email yet, just show form
                if (startChatModal) {
                    startChatModal.style.display = 'flex';
                }
                return;
            }
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.has_active_chat) {
                    // Open existing chat
                    currentChatId = data.chat.id;
                    liveChatWindow.style.display = 'flex';
                    loadChatMessages();
                    subscribeToChat();
                    // Mark admin messages as read when opening chat
                    markAdminMessagesAsRead();
                } else {
                    // Open start chat form
                    if (startChatModal) {
                        startChatModal.style.display = 'flex';
                    }
                }
            })
            .catch(error => {
                console.error('Error checking for active chat:', error);
                // On error, open the form
                if (startChatModal) {
                    startChatModal.style.display = 'flex';
                }
            });
    }

    // Close start chat modal
    if (closeStartChatModal) {
        closeStartChatModal.addEventListener('click', function() {
            if (startChatModal) {
                startChatModal.style.display = 'none';
            }
        });
    }

    // Close modal on overlay click
    if (startChatModal) {
        startChatModal.addEventListener('click', function(e) {
            if (e.target === startChatModal) {
                startChatModal.style.display = 'none';
            }
        });
    }

    // Handle start chat form submission
    if (startChatForm) {
        let isSubmitting = false;
        
        startChatForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Prevent multiple submissions
            if (isSubmitting) {
                return;
            }

            const startChatBtn = document.getElementById('startChatBtn');
            const originalButtonText = startChatBtn ? startChatBtn.innerHTML : 'Start Chat';
            
            // Disable button and show loading state
            isSubmitting = true;
            if (startChatBtn) {
                startChatBtn.disabled = true;
                startChatBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Starting Chat...';
                startChatBtn.style.opacity = '0.7';
                startChatBtn.style.cursor = 'not-allowed';
            }

            const formData = new FormData(startChatForm);
            const email = formData.get('email');
            
            // Helper function to re-enable button
            const reEnableButton = function() {
                isSubmitting = false;
                if (startChatBtn) {
                    startChatBtn.disabled = false;
                    startChatBtn.innerHTML = originalButtonText;
                    startChatBtn.style.opacity = '1';
                    startChatBtn.style.cursor = 'pointer';
                }
            };

            // Helper function to handle success
            const handleSuccess = function(chatId) {
                currentChatId = chatId;
                startChatModal.style.display = 'none';
                liveChatWindow.style.display = 'flex';
                startChatForm.reset();
                
                // Load chat messages
                loadChatMessages();
                
                // Subscribe to chat channel
                subscribeToChat();
                
                // Mark admin messages as read when opening chat
                markAdminMessagesAsRead();
            };

            // Helper function to handle error
            const handleError = function(message) {
                reEnableButton();
                alert(message || 'An error occurred. Please try again.');
            };
            
            // First check if there's an existing active chat for this email
            const checkUrl = '/chat/active' + (email ? '?email=' + encodeURIComponent(email) : '');
            
            fetch(checkUrl)
                .then(response => response.json())
                .then(checkData => {
                    if (checkData.success && checkData.has_active_chat) {
                        // Use existing chat
                        handleSuccess(checkData.chat.id);
                    } else {
                        // Create new chat
                        const data = {
                            name: formData.get('name'),
                            email: email,
                            message: formData.get('message'),
                        };

                        fetch('/chat/start', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                handleSuccess(data.chat.id);
                            } else {
                                handleError('Failed to start chat. Please try again.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            handleError('An error occurred. Please try again.');
                        });
                    }
                })
                .catch(error => {
                    console.error('Error checking for active chat:', error);
                    // On error, proceed with creating new chat
                    const data = {
                        name: formData.get('name'),
                        email: email,
                        message: formData.get('message'),
                    };

                    fetch('/chat/start', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            handleSuccess(data.chat.id);
                        } else {
                            handleError('Failed to start chat. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        handleError('An error occurred. Please try again.');
                    });
                });
        });
    }

    // Close live chat
    if (closeLiveChat) {
        closeLiveChat.addEventListener('click', function() {
            if (liveChatWindow) {
                liveChatWindow.style.display = 'none';
            }
            if (echo && currentChatId) {
                echo.leave(`chat.${currentChatId}`);
            }
            currentChatId = null;
        });
    }

    // Send message - use event delegation to ensure it works even if element is added dynamically
    document.addEventListener('click', function(e) {
        if (e.target && (e.target.id === 'sendChatMessage' || e.target.closest('#sendChatMessage'))) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Also attach directly to button if it exists
    if (sendChatMessage) {
        sendChatMessage.addEventListener('click', function(e) {
            e.preventDefault();
            sendMessage();
        });
    }

    if (chatMessageInput) {
        chatMessageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    }

    // Image upload button
    const imageChatBtn = document.getElementById('imageChatBtn');
    const chatImageInput = document.getElementById('chatImageInput');
    
    if (imageChatBtn && chatImageInput) {
        imageChatBtn.addEventListener('click', function() {
            chatImageInput.click();
        });

        chatImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPEG, PNG, GIF, or WEBP)');
                    chatImageInput.value = '';
                    return;
                }

                // Validate file size (5MB max)
                const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                if (file.size > maxSize) {
                    alert('Image size must be less than 5MB');
                    chatImageInput.value = '';
                    return;
                }

                // Send image immediately
                sendImage(file);
            }
        });
    }

    function sendImage(file) {
        if (!currentChatId) return;

        // Disable send button while sending
        if (sendChatMessage) {
            sendChatMessage.disabled = true;
            sendChatMessage.style.opacity = '0.6';
            sendChatMessage.style.cursor = 'not-allowed';
        }

        const formData = new FormData();
        formData.append('image', file);
        formData.append('message', ''); // Empty message for image-only

        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token not found');
            if (sendChatMessage) {
                sendChatMessage.disabled = false;
                sendChatMessage.style.opacity = '1';
                sendChatMessage.style.cursor = 'pointer';
            }
            alert('Security token not found. Please refresh the page.');
            return;
        }

        fetch(`/chat/${currentChatId}/message`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Failed to send image');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                chatImageInput.value = '';
                addMessageToUI(data.message);
            } else {
                alert(data.message || 'Failed to send image');
            }
        })
        .catch(error => {
            console.error('Error sending image:', error);
            alert('Error: ' + (error.message || 'Failed to send image. Please try again.'));
        })
        .finally(() => {
            // Re-enable send button
            if (sendChatMessage) {
                sendChatMessage.disabled = false;
                sendChatMessage.style.opacity = '1';
                sendChatMessage.style.cursor = 'pointer';
            }
        });
    }

    function sendMessage() {
        if (!currentChatId || !chatMessageInput) return;

        const message = chatMessageInput.value.trim();
        const imageFile = chatImageInput && chatImageInput.files[0];
        
        // If there's an image but no message, use sendImage instead
        if (imageFile && !message) {
            sendImage(imageFile);
            return;
        }
        
        if (!message && !imageFile) return;

        // Disable send button while sending
        if (sendChatMessage) {
            sendChatMessage.disabled = true;
            sendChatMessage.style.opacity = '0.6';
            sendChatMessage.style.cursor = 'not-allowed';
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token not found');
            if (sendChatMessage) {
                sendChatMessage.disabled = false;
                sendChatMessage.style.opacity = '1';
                sendChatMessage.style.cursor = 'pointer';
            }
            alert('Security token not found. Please refresh the page.');
            return;
        }

        // Use FormData if there's an image, otherwise use JSON
        let requestBody;
        let headers = {
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        };

        if (imageFile) {
            const formData = new FormData();
            formData.append('message', message);
            formData.append('image', imageFile);
            requestBody = formData;
            // Don't set Content-Type for FormData, browser will set it with boundary
        } else {
            headers['Content-Type'] = 'application/json';
            requestBody = JSON.stringify({ message: message });
        }

        fetch(`/chat/${currentChatId}/message`, {
            method: 'POST',
            headers: headers,
            body: requestBody
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Failed to send message');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                chatMessageInput.value = '';
                if (chatImageInput) {
                    chatImageInput.value = '';
                }
                addMessageToUI(data.message);
            } else {
                alert(data.message || 'Failed to send message');
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
            alert('Error: ' + (error.message || 'Failed to send message. Please try again.'));
        })
        .finally(() => {
            // Re-enable send button
            if (sendChatMessage) {
                sendChatMessage.disabled = false;
                sendChatMessage.style.opacity = '1';
                sendChatMessage.style.cursor = 'pointer';
            }
        });
    }

    function loadChatMessages() {
        if (!currentChatId) return;

        fetch(`/chat/${currentChatId}/messages`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load messages');
                }
                return response.json();
            })
            .then(data => {
                if (data.success && chatMessages) {
                    chatMessages.innerHTML = '';
                    data.messages.forEach(message => {
                        addMessageToUI(message);
                    });
                    scrollToBottom();
                }
            })
            .catch(error => {
                console.error('Error loading messages:', error);
            });
    }

    function addMessageToUI(message) {
        if (!chatMessages) return;

        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${message.sender_type}`;
        messageDiv.setAttribute('data-message-id', message.id);
        
        const bubble = document.createElement('div');
        bubble.className = 'chat-message-bubble';
        
        // Display image if present
        if (message.image_path || message.image_url) {
            const imageUrl = message.image_url || (message.image_path ? `/storage/${message.image_path}` : null);
            if (imageUrl) {
                const imgContainer = document.createElement('div');
                imgContainer.className = 'chat-image-container';
                imgContainer.style.marginBottom = message.message ? '8px' : '0';
                
                const img = document.createElement('img');
                img.src = imageUrl;
                img.className = 'chat-message-image';
                img.style.maxWidth = '100%';
                img.style.maxHeight = '300px';
                img.style.borderRadius = '8px';
                img.style.cursor = 'pointer';
                img.alt = 'Chat image';
                
                // Click to view full size
                img.addEventListener('click', function() {
                    window.open(imageUrl, '_blank');
                });
                
                imgContainer.appendChild(img);
                bubble.appendChild(imgContainer);
            }
        }
        
        // Display text message if present
        if (message.message) {
            const textDiv = document.createElement('div');
            textDiv.textContent = message.message;
            bubble.appendChild(textDiv);
        }
        
        const timeDiv = document.createElement('div');
        timeDiv.className = 'chat-message-time';
        const time = new Date(message.created_at);
        
        // Show single tick (unread) or double tick (read) for user messages
        let statusIcon = '';
        if (message.sender_type === 'user') {
            if (message.is_read) {
                // Double tick - message read by admin
                statusIcon = '<span class="chat-message-status read"><i class="fas fa-check-double"></i></span>';
            } else {
                // Single tick - message sent but not read
                statusIcon = '<span class="chat-message-status unread"><i class="fas fa-check"></i></span>';
            }
        }
        
        timeDiv.innerHTML = `
            ${time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}
            ${statusIcon}
        `;
        
        messageDiv.appendChild(bubble);
        messageDiv.appendChild(timeDiv);
        chatMessages.appendChild(messageDiv);
        
        scrollToBottom();
    }

    function updateMessageReadStatus(messageId) {
        if (!chatMessages) return;
        
        const messageDiv = chatMessages.querySelector(`[data-message-id="${messageId}"]`);
        if (messageDiv) {
            const statusSpan = messageDiv.querySelector('.chat-message-status');
            if (statusSpan) {
                statusSpan.className = 'chat-message-status read';
                statusSpan.innerHTML = '<i class="fas fa-check-double"></i>';
            }
        }
    }

    function markAllUserMessagesAsRead() {
        if (!chatMessages) return;
        
        const userMessages = chatMessages.querySelectorAll('.chat-message.user');
        userMessages.forEach(messageDiv => {
            const statusSpan = messageDiv.querySelector('.chat-message-status');
            if (statusSpan && statusSpan.classList.contains('unread')) {
                statusSpan.className = 'chat-message-status read';
                statusSpan.innerHTML = '<i class="fas fa-check-double"></i>';
            }
        });
    }

    function scrollToBottom() {
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    }

    function subscribeToChat() {
        if (!echo || !currentChatId) return;

        echo.private(`chat.${currentChatId}`)
            .listen('.message.sent', (e) => {
                addMessageToUI(e.message);
                
                // Update status if admin replied
                if (e.message.sender_type === 'admin') {
                    if (chatStatus) {
                        chatStatus.textContent = 'Agent is typing...';
                        setTimeout(() => {
                            if (chatStatus) {
                                chatStatus.textContent = 'Agent is online';
                            }
                        }, 2000);
                    }
                    
                    // Mark all user messages as read when admin replies
                    markAllUserMessagesAsRead();
                    
                    // Update badge - show if chat window is not open
                    const isChatOpen = liveChatWindow && liveChatWindow.style.display !== 'none';
                    if (!isChatOpen) {
                        updateUnreadBadge();
                    } else {
                        // Mark admin messages as read if chat is open
                        markAdminMessagesAsRead();
                    }
                }
            })
            .listen('.messages.read', (e) => {
                // Mark all user messages as read
                markAllUserMessagesAsRead();
            })
            .listen('.chat.assigned', (e) => {
                if (chatStatus) {
                    chatStatus.textContent = 'Agent is online';
                }
            });
    }

    // Refresh chat button
    const refreshChatBtn = document.getElementById('refreshChatBtn');
    if (refreshChatBtn) {
        refreshChatBtn.addEventListener('click', function() {
            if (currentChatId) {
                loadChatMessages();
            }
        });
    }

    // Check for unread admin messages and update badge
    function updateUnreadBadge() {
        // Check if user is authenticated
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        const isAuthenticated = csrfToken && window.location.pathname.includes('/user/dashboard');
        
        let url = '/chat/unread-count';
        
        // For guest users, try to get email from form if it exists
        if (!isAuthenticated) {
            const emailInput = document.getElementById('chatEmail');
            if (emailInput && emailInput.value) {
                url += '?email=' + encodeURIComponent(emailInput.value);
            }
        }
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success && chatNotificationBadge) {
                    // Only show badge if chat window is not open
                    const isChatOpen = liveChatWindow && liveChatWindow.style.display !== 'none';
                    
                    if (data.unread_count > 0 && !isChatOpen) {
                        chatNotificationBadge.style.display = 'block';
                    } else {
                        chatNotificationBadge.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                console.error('Error checking unread count:', error);
            });
    }

    // Mark admin messages as read when chat is opened
    function markAdminMessagesAsRead() {
        if (!currentChatId) return;

        fetch(`/chat/${currentChatId}/mark-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateUnreadBadge();
            }
        })
        .catch(error => {
            console.error('Error marking messages as read:', error);
        });
    }

    // Update badge when chat window opens/closes
    if (liveChatWindow) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                    const isOpen = liveChatWindow.style.display !== 'none';
                    if (isOpen && currentChatId) {
                        // Mark admin messages as read when chat is opened
                        markAdminMessagesAsRead();
                    } else if (!isOpen) {
                        // Update badge when chat is closed
                        updateUnreadBadge();
                    }
                }
            });
        });
        
        observer.observe(liveChatWindow, {
            attributes: true,
            attributeFilter: ['style']
        });
    }

    // Subscribe to user-specific channel for badge updates (even when chat is closed)
    function subscribeToUserChannel() {
        if (!echo) return;
        
        // Get user ID from meta tag (only for authenticated users)
        const userIdMeta = document.querySelector('meta[name="user-id"]');
        if (!userIdMeta) return; // Guest users will rely on polling
        
        const userId = userIdMeta.getAttribute('content');
        if (!userId) return;
        
        // Subscribe to user-specific channel
        echo.private(`user.${userId}.chats`)
            .listen('.message.sent', (e) => {
                // Only handle admin messages
                if (e.message && e.message.sender_type === 'admin') {
                    // Update badge if chat window is not open
                    const isChatOpen = liveChatWindow && liveChatWindow.style.display !== 'none';
                    if (!isChatOpen && chatNotificationBadge) {
                        // Show badge immediately
                        chatNotificationBadge.style.display = 'block';
                        // Also update count
                        updateUnreadBadge();
                    }
                }
            });
    }

    // Initialize user channel subscription on page load
    subscribeToUserChannel();

    // Update badge periodically and on page load
    if (chatNotificationBadge) {
        // Check immediately on load
        updateUnreadBadge();
        
        // Check every 30 seconds
        setInterval(updateUnreadBadge, 30000);
    }
})();


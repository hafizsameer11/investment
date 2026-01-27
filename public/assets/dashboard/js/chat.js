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

    // Send message
    if (sendChatMessage) {
        sendChatMessage.addEventListener('click', function() {
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

    function sendMessage() {
        if (!currentChatId || !chatMessageInput) return;

        const message = chatMessageInput.value.trim();
        if (!message) return;

        fetch(`/chat/${currentChatId}/message`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                chatMessageInput.value = '';
                addMessageToUI(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function loadChatMessages() {
        if (!currentChatId) return;

        fetch(`/chat/${currentChatId}/messages`)
            .then(response => response.json())
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
                console.error('Error:', error);
            });
    }

    function addMessageToUI(message) {
        if (!chatMessages) return;

        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${message.sender_type}`;
        
        const bubble = document.createElement('div');
        bubble.className = 'chat-message-bubble';
        bubble.textContent = message.message;
        
        const timeDiv = document.createElement('div');
        timeDiv.className = 'chat-message-time';
        const time = new Date(message.created_at);
        timeDiv.innerHTML = `
            ${time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}
            ${message.sender_type === 'user' ? '<span class="chat-message-status"><i class="fas fa-check"></i></span>' : ''}
        `;
        
        messageDiv.appendChild(bubble);
        messageDiv.appendChild(timeDiv);
        chatMessages.appendChild(messageDiv);
        
        scrollToBottom();
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
                if (e.message.sender_type === 'admin' && chatStatus) {
                    chatStatus.textContent = 'Agent is typing...';
                    setTimeout(() => {
                        if (chatStatus) {
                            chatStatus.textContent = 'Agent is online';
                        }
                    }, 2000);
                }
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
})();


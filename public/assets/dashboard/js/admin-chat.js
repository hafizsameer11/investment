/**
 * Admin-side Chat Widget Functionality
 */

(function() {
    let echo = null;
    let currentChatId = null;
    let currentTab = 'pending';

    // Initialize Echo if available
    if (typeof window.Echo !== 'undefined') {
        echo = window.Echo;
    }

    // Widget elements
    const adminChatWidgetButton = document.getElementById('adminChatWidgetButton');
    const adminChatPanel = document.getElementById('adminChatPanel');
    const adminChatPanelClose = document.getElementById('adminChatPanelClose');
    const adminChatList = document.getElementById('adminChatList');
    const adminChatBadge = document.getElementById('adminChatBadge');
    const adminChatTabs = document.querySelectorAll('.admin-chat-tab');
    const adminChatView = document.getElementById('adminChatView');
    const adminChatBack = document.getElementById('adminChatBack');
    const adminChatViewClose = document.getElementById('adminChatViewClose');
    const adminChatViewMessages = document.getElementById('adminChatViewMessages');
    const adminChatViewInput = document.getElementById('adminChatViewInput');
    const adminChatViewSend = document.getElementById('adminChatViewSend');

    // Toggle chat panel
    if (adminChatWidgetButton) {
        adminChatWidgetButton.addEventListener('click', function() {
            if (adminChatPanel) {
                const isVisible = adminChatPanel.style.display !== 'none';
                adminChatPanel.style.display = isVisible ? 'none' : 'flex';
                if (!isVisible) {
                    loadChats();
                }
            }
        });
    }

    // Close chat panel
    if (adminChatPanelClose) {
        adminChatPanelClose.addEventListener('click', function() {
            if (adminChatPanel) {
                adminChatPanel.style.display = 'none';
            }
        });
    }

    // Tab switching
    adminChatTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            adminChatTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            currentTab = this.dataset.tab;
            loadChats();
        });
    });

    // Back button
    if (adminChatBack) {
        adminChatBack.addEventListener('click', function() {
            if (adminChatView) {
                adminChatView.style.display = 'none';
            }
            if (adminChatPanel) {
                adminChatPanel.style.display = 'flex';
            }
            if (echo && currentChatId) {
                echo.leave(`chat.${currentChatId}`);
            }
            currentChatId = null;
        });
    }

    // Close chat view
    if (adminChatViewClose) {
        adminChatViewClose.addEventListener('click', function() {
            if (adminChatView) {
                adminChatView.style.display = 'none';
            }
            if (adminChatPanel) {
                adminChatPanel.style.display = 'flex';
            }
            if (echo && currentChatId) {
                echo.leave(`chat.${currentChatId}`);
            }
            currentChatId = null;
        });
    }

    // Send message
    if (adminChatViewSend) {
        adminChatViewSend.addEventListener('click', function() {
            sendAdminMessage();
        });
    }

    if (adminChatViewInput) {
        adminChatViewInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendAdminMessage();
            }
        });
    }

    function loadChats() {
        if (!adminChatList) return;

        adminChatList.innerHTML = '<div class="admin-chat-loading">Loading chats...</div>';

        const status = currentTab === 'all' ? '' : currentTab;
        const url = `/admin/chats${status ? '?status=' + status : ''}`;

        fetch(url)
            .then(response => response.text())
            .then(html => {
                // Parse HTML and extract chat items
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const rows = doc.querySelectorAll('tbody tr');
                
                adminChatList.innerHTML = '';
                
                if (rows.length === 0) {
                    adminChatList.innerHTML = '<div class="admin-chat-loading">No chats found</div>';
                    return;
                }

                rows.forEach((row, index) => {
                    const chatItem = createChatItemFromRow(row, index);
                    adminChatList.appendChild(chatItem);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                adminChatList.innerHTML = '<div class="admin-chat-loading">Error loading chats</div>';
            });
    }

    function createChatItemFromRow(row, index) {
        const cells = row.querySelectorAll('td');
        if (cells.length < 8) return null;

        const item = document.createElement('div');
        item.className = 'admin-chat-item';
        
        const name = cells[1].textContent.trim();
        const email = cells[2].textContent.trim();
        const status = cells[3].textContent.trim();
        const assigned = cells[4].textContent.trim();
        const lastMessage = cells[5].textContent.trim();
        const time = cells[6].textContent.trim();
        const viewLink = cells[7].querySelector('a');
        const chatId = viewLink ? viewLink.href.match(/\/(\d+)$/)?.[1] : null;

        item.innerHTML = `
            <div class="admin-chat-item-header">
                <span class="admin-chat-item-name">${name}</span>
                <span class="admin-chat-item-time">${time}</span>
            </div>
            <div class="admin-chat-item-message">${lastMessage}</div>
            ${status.includes('Pending') ? '<span class="admin-chat-item-badge">New</span>' : ''}
        `;

        item.addEventListener('click', function() {
            if (chatId) {
                openChatView(chatId, name, email);
            }
        });

        return item;
    }

    function openChatView(chatId, userName, userEmail) {
        currentChatId = chatId;

        if (adminChatView) {
            document.getElementById('adminChatViewUserName').textContent = userName;
            document.getElementById('adminChatViewUserEmail').textContent = userEmail;
            adminChatView.style.display = 'flex';
        }

        if (adminChatPanel) {
            adminChatPanel.style.display = 'none';
        }

        loadChatMessages(chatId);
        subscribeToChat(chatId);
    }

    function loadChatMessages(chatId) {
        if (!adminChatViewMessages || !chatId) return;

        fetch(`/admin/chats/${chatId}/data`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    adminChatViewMessages.innerHTML = '';
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

    function sendAdminMessage() {
        if (!currentChatId || !adminChatViewInput) return;

        const message = adminChatViewInput.value.trim();
        if (!message) return;

        fetch(`/admin/chats/${currentChatId}/message`, {
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
                adminChatViewInput.value = '';
                addMessageToUI(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function addMessageToUI(message) {
        if (!adminChatViewMessages) return;

        const messageDiv = document.createElement('div');
        messageDiv.className = `admin-chat-view-message ${message.sender_type}`;
        
        const bubble = document.createElement('div');
        bubble.className = 'admin-chat-view-message-bubble';
        bubble.textContent = message.message;
        
        const timeDiv = document.createElement('div');
        timeDiv.className = 'admin-chat-view-message-time';
        const time = new Date(message.created_at);
        timeDiv.textContent = time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        
        messageDiv.appendChild(bubble);
        messageDiv.appendChild(timeDiv);
        adminChatViewMessages.appendChild(messageDiv);
        
        scrollToBottom();
    }

    function scrollToBottom() {
        if (adminChatViewMessages) {
            adminChatViewMessages.scrollTop = adminChatViewMessages.scrollHeight;
        }
    }

    function subscribeToChat(chatId) {
        if (!echo || !chatId) return;

        echo.private(`chat.${chatId}`)
            .listen('.message.sent', (e) => {
                addMessageToUI(e.message);
            })
            .listen('.chat.assigned', (e) => {
                // Handle assignment update
            });
    }

    // Subscribe to admin chats channel for new chat notifications
    if (echo) {
        echo.private('admin.chats')
            .listen('.chat.started', (e) => {
                updateUnreadCount();
                if (adminChatPanel && adminChatPanel.style.display !== 'none') {
                    loadChats();
                }
            });
    }

    function updateUnreadCount() {
        fetch('/admin/chats/unread-count')
            .then(response => response.json())
            .then(data => {
                if (data.success && adminChatBadge) {
                    if (data.unread_count > 0) {
                        adminChatBadge.textContent = data.unread_count;
                        adminChatBadge.style.display = 'flex';
                    } else {
                        adminChatBadge.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Update unread count on load and periodically
    if (adminChatBadge) {
        updateUnreadCount();
        setInterval(updateUnreadCount, 30000); // Update every 30 seconds
    }
})();


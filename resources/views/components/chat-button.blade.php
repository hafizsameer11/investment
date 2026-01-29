<!-- Fixed Chat Button -->
<div class="chat-button-container" id="chatButtonContainer">
    <button class="chat-button" id="chatButton" aria-label="Start a chat">
        <i class="fas fa-comments"></i>
        <span class="chat-notification-badge" id="chatNotificationBadge" style="display: none;"></span>
    </button>
</div>

<!-- Start Chat Modal -->
<div class="chat-modal-overlay" id="startChatModal" style="display: none;">
    <div class="chat-modal">
        <div class="chat-modal-header">
            <h3>Start a Chat</h3>
            <button class="chat-modal-close" id="closeStartChatModal" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="chat-modal-body">
            <form id="startChatForm">
                @csrf
                <div class="form-group">
                    <label for="chatName">Name *</label>
                    <input type="text" id="chatName" name="name" class="form-control" placeholder="Enter your name" required>
                </div>
                <div class="form-group">
                    <label for="chatEmail">Email *</label>
                    <input type="email" id="chatEmail" name="email" class="form-control" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="chatMessage">Message *</label>
                    <textarea id="chatMessage" name="message" class="form-control" rows="4" placeholder="Type your message" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-block" id="startChatBtn">
                    Start Chat
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Live Chat Window -->
<div class="live-chat-window" id="liveChatWindow" style="display: none;">
    <div class="live-chat-header">
        <div class="live-chat-header-left">
            <h4>Live Chat</h4>
            <span class="live-chat-status" id="chatStatus">Waiting for agent...</span>
        </div>
        <button class="live-chat-close" id="closeLiveChat" aria-label="Close chat">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="live-chat-messages" id="chatMessages">
        <!-- Messages will be loaded here -->
    </div>
    <div class="live-chat-input-container">
        <div class="live-chat-input-actions">
            <button class="chat-action-btn" id="refreshChatBtn" aria-label="Refresh">
                <i class="fas fa-redo"></i>
            </button>
            <button class="chat-action-btn" id="imageChatBtn" aria-label="Upload image" type="button">
                <i class="fas fa-image"></i>
            </button>
            <input type="file" id="chatImageInput" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" style="display: none;">
        </div>
        <input type="text" id="chatMessageInput" class="live-chat-input" placeholder="Ask me anything...">
        <button type="button" class="live-chat-send" id="sendChatMessage" aria-label="Send message">
            <i class="fas fa-arrow-up"></i>
        </button>
    </div>
</div>

<style>
/* Chat Button Styles */
.chat-button-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
}

.chat-button {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(255, 178, 30, 0.4);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.chat-button:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 25px rgba(255, 178, 30, 0.6);
}

.chat-button:active {
    transform: scale(0.95);
}

.chat-notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    width: 12px;
    height: 12px;
    background: #ef4444;
    border-radius: 50%;
    border: 2px solid white;
    display: none;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.8;
        transform: scale(1.1);
    }
}

/* Chat Modal Styles */
.chat-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    z-index: 2000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.chat-modal {
    background: #1a1a1a;
    border-radius: 16px;
    width: 100%;
    max-width: 500px;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
}

.chat-modal-header {
    background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-modal-header h3 {
    color: white;
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.chat-modal-close {
    background: transparent;
    border: none;
    color: white;
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.25rem;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: background 0.2s;
}

.chat-modal-close:hover {
    background: rgba(255, 255, 255, 0.1);
}

.chat-modal-body {
    padding: 1.5rem;
}

.chat-modal-body .form-group {
    margin-bottom: 1.25rem;
}

.chat-modal-body label {
    display: block;
    color: #e5e5e5;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.chat-modal-body .form-control {
    width: 100%;
    padding: 0.75rem;
    background: #2a2a2a;
    border: 1px solid #3a3a3a;
    border-radius: 8px;
    color: #e5e5e5;
    font-size: 0.9375rem;
    transition: border-color 0.2s;
}

.chat-modal-body .form-control:focus {
    outline: none;
    border-color: #FFB21E;
}

.chat-modal-body textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

.btn-primary {
    background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    width: 100%;
}

.btn-primary:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

.btn-primary:active {
    transform: translateY(0);
}

.btn-primary:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    pointer-events: none;
}

.btn-primary:disabled:hover {
    transform: none;
    opacity: 0.7;
}

/* Live Chat Window Styles */
.live-chat-window {
    position: fixed;
    bottom: 100px;
    right: 20px;
    width: 380px;
    max-width: calc(100vw - 40px);
    height: 600px;
    max-height: calc(100vh - 120px);
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    z-index: 1500;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.live-chat-header {
    background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
    padding: 1rem 1.25rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
}

.live-chat-header-left h4 {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 600;
}

.live-chat-status {
    font-size: 0.75rem;
    opacity: 0.9;
    margin-top: 0.25rem;
    display: block;
}

.live-chat-close {
    background: transparent;
    border: none;
    color: white;
    font-size: 1.125rem;
    cursor: pointer;
    padding: 0.25rem;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: background 0.2s;
}

.live-chat-close:hover {
    background: rgba(255, 255, 255, 0.1);
}

.live-chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1.25rem;
    background: #f5f5f5;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.chat-message {
    display: flex;
    flex-direction: column;
    max-width: 75%;
}

.chat-message.user {
    align-self: flex-end;
}

.chat-message.admin {
    align-self: flex-start;
}

.chat-message-bubble {
    padding: 0.75rem 1rem;
    border-radius: 12px;
    word-wrap: break-word;
}

.chat-message.user .chat-message-bubble {
    background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
    color: white;
}

.chat-message.admin .chat-message-bubble {
    background: #e5e5e5;
    color: #1a1a1a;
}

.chat-message-time {
    font-size: 0.75rem;
    color: #666;
    margin-top: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.chat-message.user .chat-message-time {
    justify-content: flex-end;
}

.chat-message-status {
    font-size: 0.75rem;
    margin-left: 0.25rem;
}

.chat-message-status.unread {
    color: #999;
}

.chat-message-status.read {
    color: #4CAF50;
}

.live-chat-input-container {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: white;
    border-top: 1px solid #e5e5e5;
}

.live-chat-input-actions {
    display: flex;
    gap: 0.5rem;
    flex-shrink: 0;
}

.chat-action-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #FFB21E;
    border: none;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.chat-action-btn:hover {
    background: #FF8A1D;
    transform: scale(1.05);
}

.live-chat-input {
    flex: 1;
    padding: 0.75rem;
    border: 1px solid #e5e5e5;
    border-radius: 20px;
    font-size: 0.9375rem;
    outline: none;
    min-width: 0;
}

.live-chat-input:focus {
    border-color: #FFB21E;
}

.live-chat-send {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
    border: none;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    flex-shrink: 0;
}

.live-chat-send:hover {
    transform: scale(1.05);
}

.live-chat-send:active {
    transform: scale(0.95);
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .live-chat-window {
        width: 90%;
        max-width: 350px;
        height: 70vh;
        max-height: 500px;
        bottom: 80px;
        right: 5%;
        left: auto;
        border-radius: 16px;
    }

    .live-chat-input-container {
        gap: 0.375rem;
        padding: 0.75rem;
    }

    .chat-button-container {
        bottom: 80px;
        right: 15px;
    }

    .chat-button {
        width: 56px;
        height: 56px;
        font-size: 22px;
    }
}
</style>


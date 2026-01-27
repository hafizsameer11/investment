<!-- Admin Chat Widget -->
<div class="admin-chat-widget" id="adminChatWidget">
    <button class="admin-chat-widget-button" id="adminChatWidgetButton">
        <i class="fas fa-comments"></i>
        <span class="admin-chat-badge" id="adminChatBadge" style="display: none;">0</span>
    </button>

    <!-- Chat List Panel -->
    <div class="admin-chat-panel" id="adminChatPanel" style="display: none;">
        <div class="admin-chat-panel-header">
            <h5>Chats</h5>
            <button class="admin-chat-panel-close" id="adminChatPanelClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="admin-chat-panel-tabs">
            <button class="admin-chat-tab active" data-tab="pending">Pending</button>
            <button class="admin-chat-tab" data-tab="active">Active</button>
            <button class="admin-chat-tab" data-tab="all">All</button>
        </div>
        <div class="admin-chat-list" id="adminChatList">
            <!-- Chat items will be loaded here -->
            <div class="admin-chat-loading">Loading chats...</div>
        </div>
    </div>

    <!-- Individual Chat View -->
    <div class="admin-chat-view" id="adminChatView" style="display: none;">
        <div class="admin-chat-view-header">
            <button class="admin-chat-back" id="adminChatBack">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div class="admin-chat-view-info">
                <h6 id="adminChatViewUserName">User Name</h6>
                <small id="adminChatViewUserEmail">user@example.com</small>
            </div>
            <button class="admin-chat-view-close" id="adminChatViewClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="admin-chat-view-messages" id="adminChatViewMessages">
            <!-- Messages will be loaded here -->
        </div>
        <div class="admin-chat-view-input">
            <input type="text" id="adminChatViewInput" placeholder="Type your message...">
            <button id="adminChatViewSend">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<style>
/* Admin Chat Widget Styles */
.admin-chat-widget {
    position: fixed;
    bottom: 20px;
    left: 20px;
    z-index: 1000;
}

.admin-chat-widget-button {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%);
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(147, 51, 234, 0.4);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.admin-chat-widget-button:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 25px rgba(147, 51, 234, 0.6);
}

.admin-chat-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 600;
}

.admin-chat-panel {
    position: absolute;
    bottom: 80px;
    left: 0;
    width: 350px;
    max-height: 600px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.admin-chat-panel-header {
    background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%);
    padding: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
}

.admin-chat-panel-header h5 {
    margin: 0;
    font-size: 1.125rem;
}

.admin-chat-panel-close {
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

.admin-chat-panel-close:hover {
    background: rgba(255, 255, 255, 0.1);
}

.admin-chat-panel-tabs {
    display: flex;
    border-bottom: 1px solid #e5e5e5;
    background: #f8f9fa;
}

.admin-chat-tab {
    flex: 1;
    padding: 0.75rem;
    background: transparent;
    border: none;
    border-bottom: 2px solid transparent;
    cursor: pointer;
    font-weight: 500;
    color: #666;
    transition: all 0.2s;
}

.admin-chat-tab.active {
    color: #9333ea;
    border-bottom-color: #9333ea;
}

.admin-chat-list {
    flex: 1;
    overflow-y: auto;
    padding: 0.5rem;
}

.admin-chat-item {
    padding: 1rem;
    border-bottom: 1px solid #e5e5e5;
    cursor: pointer;
    transition: background 0.2s;
}

.admin-chat-item:hover {
    background: #f8f9fa;
}

.admin-chat-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.admin-chat-item-name {
    font-weight: 600;
    color: #1a1a1a;
}

.admin-chat-item-time {
    font-size: 0.75rem;
    color: #666;
}

.admin-chat-item-message {
    font-size: 0.875rem;
    color: #666;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.admin-chat-item-badge {
    display: inline-block;
    background: #9333ea;
    color: white;
    border-radius: 12px;
    padding: 0.125rem 0.5rem;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.admin-chat-view {
    position: absolute;
    bottom: 80px;
    left: 0;
    width: 400px;
    max-height: 600px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.admin-chat-view-header {
    background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%);
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: white;
}

.admin-chat-back {
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

.admin-chat-back:hover {
    background: rgba(255, 255, 255, 0.1);
}

.admin-chat-view-info {
    flex: 1;
}

.admin-chat-view-info h6 {
    margin: 0;
    font-size: 1rem;
}

.admin-chat-view-info small {
    font-size: 0.75rem;
    opacity: 0.9;
}

.admin-chat-view-close {
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

.admin-chat-view-close:hover {
    background: rgba(255, 255, 255, 0.1);
}

.admin-chat-view-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
    background: #f5f5f5;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.admin-chat-view-message {
    display: flex;
    flex-direction: column;
    max-width: 75%;
}

.admin-chat-view-message.user {
    align-self: flex-end;
}

.admin-chat-view-message.admin {
    align-self: flex-start;
}

.admin-chat-view-message-bubble {
    padding: 0.75rem 1rem;
    border-radius: 12px;
    word-wrap: break-word;
}

.admin-chat-view-message.user .admin-chat-view-message-bubble {
    background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%);
    color: white;
}

.admin-chat-view-message.admin .admin-chat-view-message-bubble {
    background: #e5e5e5;
    color: #1a1a1a;
}

.admin-chat-view-message-time {
    font-size: 0.75rem;
    color: #666;
    margin-top: 0.25rem;
}

.admin-chat-view-input {
    display: flex;
    gap: 0.5rem;
    padding: 1rem;
    background: white;
    border-top: 1px solid #e5e5e5;
}

.admin-chat-view-input input {
    flex: 1;
    padding: 0.75rem;
    border: 1px solid #e5e5e5;
    border-radius: 20px;
    font-size: 0.9375rem;
    outline: none;
}

.admin-chat-view-input input:focus {
    border-color: #9333ea;
}

.admin-chat-view-input button {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%);
    border: none;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.admin-chat-loading {
    padding: 2rem;
    text-align: center;
    color: #666;
}
</style>


@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Settings')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/profile.css') }}">
<style>
    .settings-page-modern {
        padding: 0;
        width: 100%;
        max-width: 100%;
    }

    /* User Profile Section */
    .settings-profile-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .settings-profile-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 0;
    }

    .settings-profile-avatar-wrapper {
        position: relative;
        flex-shrink: 0;
        cursor: pointer;
    }

    .settings-profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255, 178, 30, 0.3);
        transition: var(--transition);
    }

    .settings-profile-avatar-wrapper:hover .settings-profile-avatar {
        border-color: var(--primary-color);
        transform: scale(1.05);
    }

    .settings-avatar-camera {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 28px;
        height: 28px;
        background: var(--card-bg);
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--text-primary);
        font-size: 0.75rem;
        transition: var(--transition);
    }

    .settings-avatar-camera:hover {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: #000;
        transform: scale(1.1);
    }

    .settings-photo-input {
        display: none;
    }

    .settings-profile-info {
        flex: 1;
    }

    .settings-profile-name-wrapper {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0 0 0.5rem 0;
    }

    .settings-profile-name {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .settings-profile-name-edit {
        cursor: pointer;
        color: var(--text-secondary);
        font-size: 1rem;
        transition: var(--transition);
        padding: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .settings-profile-name-edit:hover {
        color: var(--primary-color);
        transform: scale(1.1);
    }

    .settings-profile-name-input {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-primary);
        background: transparent;
        border: 2px solid var(--primary-color);
        border-radius: 8px;
        padding: 0.25rem 0.5rem;
        width: 100%;
        max-width: 300px;
    }

    .settings-profile-name-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(255, 178, 30, 0.2);
    }

    .settings-profile-email {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        line-height: 1.2;
    }

    .settings-profile-uid {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        line-height: 1.2;
        margin-top: -10px
    }

    .settings-profile-uid-code {
        font-family: 'Courier New', monospace;
        color: var(--text-primary);
    }

    .settings-profile-badge {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #000;
        font-size: 1.25rem;
        cursor: pointer;
    }

    /* Total Earnings Section */
    .settings-earnings-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .settings-earnings-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 1.5rem 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .settings-earnings-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

        .settings-earnings-item {
            text-align: center;
        }

        .settings-earnings-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

    .settings-earnings-icon i {
        filter: drop-shadow(0 0 8px rgba(255, 178, 30, 0.4));
    }

    .settings-earnings-content {
        display: flex;
        flex-direction: column;
    }

    .settings-earnings-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .settings-earnings-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    /* Settings Menu */
    .settings-menu-list {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        overflow: hidden;
    }

    .settings-menu-item {
        display: flex;
        align-items: center;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(255, 178, 30, 0.1);
        text-decoration: none;
        color: var(--text-primary);
        transition: var(--transition);
        cursor: pointer;
        position: relative;
        min-height: 60px;
        font-family: inherit;
        font-size: inherit;
    }

    .settings-menu-item:last-child {
        border-bottom: none;
    }

    .settings-menu-item:hover {
        background: rgba(255, 178, 30, 0.05);
        border-left: 3px solid var(--primary-color);
    }

    .settings-menu-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 178, 30, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: var(--primary-color);
        font-size: 1.125rem;
        flex-shrink: 0;
    }

    .settings-menu-text {
        flex: 1;
        font-size: 1rem;
        font-weight: 500;
    }

    .settings-menu-arrow {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-left: 1rem;
    }

    .settings-menu-badge {
        background: rgba(255, 178, 30, 0.2);
        border: 1px solid rgba(255, 178, 30, 0.4);
        border-radius: 6px;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        color: var(--primary-color);
        margin-left: auto;
        margin-right: 1rem;
    }

    .settings-menu-notification {
        width: 24px;
        height: 24px;
        background: rgba(0, 170, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: auto;
        margin-right: 1rem;
        font-size: 0.75rem;
        color: #00AAFF;
    }

    /* Mobile Styles */
    @media (max-width: 768px) {
        .settings-page-modern {
            padding: 0;
        }

        .settings-profile-card {
            padding: 1.5rem;
            border-radius: 12px;
        }

        .settings-profile-header {
            gap: 1rem;
        }

        .settings-profile-avatar {
            width: 70px;
            height: 70px;
        }

        .settings-profile-name-wrapper {
            margin-bottom: 0.5rem;
        }

        .settings-profile-name {
            font-size: 1.25rem;
        }

        .settings-profile-email {
            margin-bottom: 0.5rem;
            font-size: 0.8125rem;
        }

        .settings-profile-uid {
            font-size: 0.8125rem;
        }

        .settings-profile-badge {
            top: 1rem;
            right: 1rem;
            width: 36px;
            height: 36px;
            font-size: 1.125rem;
        }

        .settings-earnings-card {
            padding: 1.25rem;
            border-radius: 12px;
        }

        .settings-earnings-grid {
            gap: 1rem;
        }

        .settings-earnings-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-align: left;
        }

        .settings-earnings-icon {
            font-size: 1.5rem;
            margin-bottom: 0;
            flex-shrink: 0;
        }

        .settings-earnings-content {
            flex: 1;
        }

        .settings-earnings-label,
        .settings-earnings-value {
            text-align: left;
        }

        .settings-earnings-value {
            font-size: 1.25rem;
        }

        .settings-menu-item {
            padding: 1rem 1.25rem;
            min-height: 56px;
        }

        .settings-menu-icon {
            width: 36px;
            height: 36px;
            font-size: 1rem;
            margin-right: 0.875rem;
        }

        .settings-menu-text {
            font-size: 0.9375rem;
        }
    }

    .settings-copy-uid-btn {
        background: none;
        border: none;
        padding: 0;
        color: var(--text-secondary);
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
    }

    .settings-copy-uid-btn:hover {
        color: var(--primary-color);
        transform: scale(1.1);
    }
</style>
@endpush

@push('scripts')
<script>
    // Edit Name Functionality
    const editNameBtn = document.getElementById('settings-edit-name-btn');
    const saveNameBtn = document.getElementById('settings-save-name-btn');
    const cancelNameBtn = document.getElementById('settings-cancel-name-btn');
    const nameDisplay = document.getElementById('settings-profile-name-display');
    const nameInput = document.getElementById('settings-profile-name-input');

    if (editNameBtn) {
        editNameBtn.addEventListener('click', function() {
            nameDisplay.style.display = 'none';
            nameInput.style.display = 'block';
            editNameBtn.style.display = 'none';
            saveNameBtn.style.display = 'flex';
            cancelNameBtn.style.display = 'flex';
            nameInput.focus();
            nameInput.select();
        });
    }

    if (cancelNameBtn) {
        cancelNameBtn.addEventListener('click', function() {
            nameInput.value = nameDisplay.textContent;
            nameDisplay.style.display = 'block';
            nameInput.style.display = 'none';
            editNameBtn.style.display = 'flex';
            saveNameBtn.style.display = 'none';
            cancelNameBtn.style.display = 'none';
        });
    }

    if (saveNameBtn) {
        saveNameBtn.addEventListener('click', function() {
            const newName = nameInput.value.trim();

            if (!newName) {
                alert('Name cannot be empty');
                return;
            }

            // Disable buttons during save
            saveNameBtn.style.pointerEvents = 'none';
            cancelNameBtn.style.pointerEvents = 'none';

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Update name via AJAX
            fetch('{{ route("profile.update-name") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: newName
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    nameDisplay.textContent = data.name;
                    nameDisplay.style.display = 'block';
                    nameInput.style.display = 'none';
                    editNameBtn.style.display = 'flex';
                    saveNameBtn.style.display = 'none';
                    cancelNameBtn.style.display = 'none';

                    // Show success notification
                    showNotification('Name updated successfully!', 'success');
                } else {
                    alert(data.message || 'Failed to update name');
                    saveNameBtn.style.pointerEvents = 'auto';
                    cancelNameBtn.style.pointerEvents = 'auto';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating name');
                saveNameBtn.style.pointerEvents = 'auto';
                cancelNameBtn.style.pointerEvents = 'auto';
            });
        });
    }

    // Allow Enter key to save
    if (nameInput) {
        nameInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                saveNameBtn.click();
            }
            if (e.key === 'Escape') {
                cancelNameBtn.click();
            }
        });
    }

    // Profile Photo Upload
    const avatarWrapper = document.getElementById('settings-avatar-wrapper');
    const photoInput = document.getElementById('settings-photo-input');
    const avatarImg = document.getElementById('settings-profile-avatar-img');

    if (avatarWrapper && photoInput) {
        avatarWrapper.addEventListener('click', function(e) {
            // Don't trigger if clicking the camera icon (it has its own handler)
            if (e.target.closest('.settings-avatar-camera')) {
                return;
            }
            photoInput.click();
        });

        // Also allow camera icon to trigger file input
        const cameraIcon = avatarWrapper.querySelector('.settings-avatar-camera');
        if (cameraIcon) {
            cameraIcon.addEventListener('click', function(e) {
                e.stopPropagation();
                photoInput.click();
            });
        }
    }

    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file');
                return;
            }

            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Image size must be less than 2MB');
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarImg.src = e.target.result;
            };
            reader.readAsDataURL(file);

            // Upload photo
            const formData = new FormData();
            formData.append('photo', file);

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch('{{ route("profile.update-photo") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.photo_url) {
                        avatarImg.src = data.photo_url;
                    }
                    showNotification('Profile photo updated successfully!', 'success');
                } else {
                    alert(data.message || 'Failed to update profile photo');
                    // Revert to original image
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating profile photo');
                // Revert to original image
                location.reload();
            });
        });
    }

    // Copy UID Function
    function copyUID(uid) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(uid).then(function() {
                showNotification('UID copied to clipboard!', 'success');
            }).catch(function(err) {
                fallbackCopyToClipboard(uid);
            });
        } else {
            fallbackCopyToClipboard(uid);
        }
    }

    // Fallback copy function
    function fallbackCopyToClipboard(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand('copy');
            if (successful) {
                showNotification('UID copied to clipboard!', 'success');
            } else {
                alert('Failed to copy. Please copy manually: ' + text);
            }
        } catch (err) {
            alert('Failed to copy. Please copy manually: ' + text);
        }

        document.body.removeChild(textArea);
    }

    // Show notification function
    function showNotification(message, type = 'success') {
        const existingNotification = document.querySelector('.settings-notification');
        if (existingNotification) {
            existingNotification.remove();
        }

        const notification = document.createElement('div');
        notification.className = 'settings-notification';
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? 'linear-gradient(135deg, #4CAF50 0%, #45a049 100%)' : 'linear-gradient(135deg, #FF4444 0%, #cc0000 100%)'};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            z-index: 10000;
            font-weight: 600;
            font-size: 0.9375rem;
            animation: slideInRight 0.3s ease-out;
            max-width: 300px;
        `;
        notification.textContent = message;

        if (!document.getElementById('settings-notification-styles')) {
            const style = document.createElement('style');
            style.id = 'settings-notification-styles';
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
        }

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
</script>
@endpush

@section('content')
<div class="settings-page-modern">
    <!-- User Profile Section -->
    <div class="settings-profile-card">
        <div class="settings-profile-header">
            <div class="settings-profile-avatar-wrapper" id="settings-avatar-wrapper">
                <img src="{{ auth()->user()->profile_photo ? asset(auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=00FF88&color=000&size=200' }}" alt="Profile" class="settings-profile-avatar" id="settings-profile-avatar-img">
                <div class="settings-avatar-camera">
                    <i class="fas fa-camera"></i>
                </div>
                <input type="file" id="settings-photo-input" class="settings-photo-input" accept="image/*">
            </div>
            <div class="settings-profile-info">
                <div class="settings-profile-name-wrapper">
                    <h2 class="settings-profile-name" id="settings-profile-name-display">{{ auth()->user()->name }}</h2>
                    <input type="text" class="settings-profile-name-input" id="settings-profile-name-input" value="{{ auth()->user()->name }}" style="display: none;">
                    <i class="fas fa-pencil settings-profile-name-edit" id="settings-edit-name-btn"></i>
                    <i class="fas fa-check settings-profile-name-edit" id="settings-save-name-btn" style="display: none; color: #4CAF50;"></i>
                    <i class="fas fa-times settings-profile-name-edit" id="settings-cancel-name-btn" style="display: none; color: #FF4444;"></i>
                </div>
                <p class="settings-profile-email">
                    <i class="fas fa-envelope"></i>
                    <span>{{ auth()->user()->email }}</span>
                </p>
                <p class="settings-profile-uid">
                    <span>UID:</span>
                    <span class="settings-profile-uid-code">{{ auth()->user()->refer_code }}</span>
                    <button type="button" class="settings-copy-uid-btn" onclick="copyUID('{{ auth()->user()->refer_code }}')" title="Copy UID">
                        <i class="fas fa-copy"></i>
                    </button>
                </p>
            </div>
            {{-- <div class="settings-profile-badge">
                <i class="fas fa-shield-alt"></i>
            </div> --}}
        </div>
    </div>

    <!-- Total Earnings Section -->
    <div class="settings-earnings-card">
        <div class="settings-earnings-grid">
            <div class="settings-earnings-item">
                <div class="settings-earnings-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="settings-earnings-content">
                    <div class="settings-earnings-label">USD Earnings</div>
                    <div class="settings-earnings-value">${{ number_format($totalEarningsUSD ?? 0, 2) }}</div>
                    {{-- <div style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.25rem;">
                        Mining: ${{ number_format($miningEarning ?? 0, 2) }} | Referral: ${{ number_format($referralEarning ?? 0, 2) }}
                    </div> --}}
                </div>
            </div>
            <div class="settings-earnings-item">
                <div class="settings-earnings-icon">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="settings-earnings-content">
                    <div class="settings-earnings-label">PKR Earnings</div>
                    <div class="settings-earnings-value">Rs{{ number_format($totalEarningsPKR ) }}</div>
                    {{-- <div style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.25rem;">
                        Rate: 1 USD = Rs{{ number_format($conversionRate ?? 0, 2) }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Menu -->
    <div class="settings-menu-list">
        <a href="{{ route('profile.index') }}" class="settings-menu-item">
            <div class="settings-menu-icon">
                <i class="fas fa-user"></i>
            </div>
            <span class="settings-menu-text">Profile</span>
            <i class="fas fa-chevron-right settings-menu-arrow"></i>
        </a>

        <a href="{{ route('plans.index') }}" class="settings-menu-item">
            <div class="settings-menu-icon">
                <i class="fas fa-gem"></i>
            </div>
            <span class="settings-menu-text">Mining Plans</span>
            <i class="fas fa-chevron-right settings-menu-arrow"></i>
        </a>

        <a href="{{ route('wallet.index') }}" class="settings-menu-item">
            <div class="settings-menu-icon">
                <i class="fas fa-coins"></i>
            </div>
            <span class="settings-menu-text">Core Wallet</span>
            <i class="fas fa-chevron-right settings-menu-arrow"></i>
        </a>

        <a href="{{ route('deposit.index') }}" class="settings-menu-item">
            <div class="settings-menu-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <span class="settings-menu-text">Add Money</span>
            <i class="fas fa-chevron-right settings-menu-arrow"></i>
        </a>

        <a href="{{ route('withdraw.index') }}" class="settings-menu-item">
            <div class="settings-menu-icon">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <span class="settings-menu-text">Get Money</span>
            <i class="fas fa-chevron-right settings-menu-arrow"></i>
        </a>

        <a href="{{ route('goals.index') }}" class="settings-menu-item">
            <div class="settings-menu-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <span class="settings-menu-text">Victory Rewards</span>
            <i class="fas fa-chevron-right settings-menu-arrow"></i>
        </a>

        {{-- <a href="{{ route('targets.index') }}" class="settings-menu-item">
            <div class="settings-menu-icon">
                <i class="fas fa-flag"></i>
            </div>
            <span class="settings-menu-text">Targets</span>
            <i class="fas fa-chevron-right settings-menu-arrow"></i>
        </a> --}}

        <a href="{{ route('transactions.index') }}" class="settings-menu-item">
            <div class="settings-menu-icon">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <span class="settings-menu-text">Financial Records</span>
            <i class="fas fa-chevron-right settings-menu-arrow"></i>
        </a>

        <a href="{{ route('support.index') }}" class="settings-menu-item">
            <div class="settings-menu-icon">
                <i class="fas fa-headset"></i>
            </div>
            <span class="settings-menu-text">Technical Support</span>
            <i class="fas fa-chevron-right settings-menu-arrow"></i>
        </a>

        <form action="{{ route('logout') }}" method="POST" style="display: contents;">
            @csrf
            <button type="submit" class="settings-menu-item" style="width: 100%; border: none; background: none; text-align: left; cursor: pointer;">
                <div class="settings-menu-icon">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <span class="settings-menu-text">Quick Exit</span>
                <i class="fas fa-chevron-right settings-menu-arrow"></i>
            </button>
        </form>
    </div>
</div>
@endsection


@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Profile')

@push('styles')
<link rel="stylesheet" href="{{ asset('dashboard/css/profile.css') }}">
@endpush

@section('content')
<div class="profile-page-modern">
    <!-- Hero Section -->
    <div class="profile-hero-section">
        <div class="profile-hero-content">
            <h1 class="profile-hero-title">My Profile</h1>
            <p class="profile-hero-subtitle">Manage your account settings and personal information</p>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="profile-main-card">
        <!-- Profile Header -->
        <div class="profile-header-modern">
            <div class="profile-header-left-modern">
                <div class="profile-avatar-modern">
                    <img src="https://ui-avatars.com/api/?name=Rameez+Nazar&background=00FF88&color=000&size=200" alt="Profile Avatar">
                    <div class="profile-avatar-badge">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>
                <div class="profile-info-modern">
                    <h2 class="profile-name-modern">Rameez Nazar</h2>
                    <p class="profile-email-modern">ramiznazar600@gmail.com</p>
                    <div class="profile-rank-modern">
                        <div class="profile-rank-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="profile-rank-text">No Rank</span>
                    </div>
                </div>
            </div>
            <div class="profile-header-actions-modern">
                <button class="profile-edit-btn-modern" id="editProfileBtn">
                    <i class="fas fa-edit"></i>
                    <span>Edit Profile</span>
                </button>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="profile-tabs-modern">
            <button class="profile-tab-modern active" data-tab="account">
                <i class="fas fa-user"></i>
                <span>Account Information</span>
            </button>
            <button class="profile-tab-modern" data-tab="password">
                <i class="fas fa-lock"></i>
                <span>Change Password</span>
            </button>
        </div>

        <!-- Account Tab Content -->
        <div class="profile-tab-content-modern active" id="accountTab">
            <div class="profile-tab-header-modern">
                <h3 class="profile-tab-title-modern">Account Information</h3>
                <p class="profile-tab-subtitle-modern">Update your personal details and contact information</p>
            </div>

            <div class="profile-form-modern">
                <div class="profile-form-grid-modern">
                    <div class="profile-form-group-modern">
                        <label class="profile-form-label-modern">
                            <i class="fas fa-user"></i>
                            <span>First Name</span>
                        </label>
                        <input type="text" class="profile-form-input-modern" id="firstName" value="Rameez Nazar" readonly>
                    </div>

                    <div class="profile-form-group-modern">
                        <label class="profile-form-label-modern">
                            <i class="fas fa-user"></i>
                            <span>Last Name</span>
                        </label>
                        <input type="text" class="profile-form-input-modern" id="lastName" placeholder="Enter your Last Name" readonly>
                    </div>

                    <div class="profile-form-group-modern">
                        <label class="profile-form-label-modern">
                            <i class="fas fa-envelope"></i>
                            <span>Email Address</span>
                        </label>
                        <input type="email" class="profile-form-input-modern" id="email" value="ramiznazar600@gmail.com" readonly>
                    </div>

                    <div class="profile-form-group-modern">
                        <label class="profile-form-label-modern">
                            <i class="fas fa-phone"></i>
                            <span>Phone Number</span>
                        </label>
                        <input type="tel" class="profile-form-input-modern" id="phone" value="+923262853600" readonly>
                    </div>
                </div>

                <div class="profile-form-actions-modern">
                    <button class="profile-save-btn-modern" id="saveProfileBtn" style="display: none;">
                        <i class="fas fa-save"></i>
                        <span>Save Changes</span>
                    </button>
                    <button class="profile-cancel-btn-modern" id="cancelProfileBtn" style="display: none;">
                        <span>Cancel</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Change Password Tab Content -->
        <div class="profile-tab-content-modern" id="passwordTab">
            <div class="profile-tab-header-modern">
                <h3 class="profile-tab-title-modern">Change Password</h3>
                <p class="profile-tab-subtitle-modern">Update your password to keep your account secure</p>
            </div>

            <div class="profile-form-modern">
                <div class="profile-form-grid-modern">
                    <div class="profile-form-group-modern">
                        <label class="profile-form-label-modern">
                            <i class="fas fa-lock"></i>
                            <span>Current Password</span>
                        </label>
                        <input type="password" class="profile-form-input-modern" id="currentPassword" placeholder="Enter current password">
                    </div>

                    <div class="profile-form-group-modern">
                        <label class="profile-form-label-modern">
                            <i class="fas fa-lock"></i>
                            <span>New Password</span>
                        </label>
                        <input type="password" class="profile-form-input-modern" id="newPassword" placeholder="Enter new password">
                    </div>

                    <div class="profile-form-group-modern">
                        <label class="profile-form-label-modern">
                            <i class="fas fa-lock"></i>
                            <span>Confirm New Password</span>
                        </label>
                        <input type="password" class="profile-form-input-modern" id="confirmPassword" placeholder="Confirm new password">
                    </div>
                </div>

                <div class="profile-form-actions-modern">
                    <button class="profile-save-btn-modern" id="savePasswordBtn">
                        <i class="fas fa-save"></i>
                        <span>Update Password</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Contact Upliner Section -->
        <div class="profile-contact-section-modern">
            <div class="profile-contact-card-modern">
                <div class="profile-contact-icon-modern">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="profile-contact-content-modern">
                    <h3 class="profile-contact-title-modern">Need Help?</h3>
                    <p class="profile-contact-subtitle-modern">Contact your upliner for assistance</p>
                </div>
                <button class="profile-contact-btn-modern">
                    <i class="fas fa-comments"></i>
                    <span>Contact Upliner</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('dashboard/js/profile.js') }}"></script>
@endpush
@endsection

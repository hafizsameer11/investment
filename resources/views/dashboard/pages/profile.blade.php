@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Profile')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/profile.css') }}">
@endpush

@section('content')
<div class="profile-page-modern">
    <!-- Hero Section (Desktop Only) -->
    <div class="profile-hero-section profile-hero-desktop">
        <div class="profile-hero-content">
            <h1 class="profile-hero-title">My Profile</h1>
            <p class="profile-hero-subtitle">Manage your account settings and personal information</p>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="profile-main-card">
        <!-- Profile Header (Desktop Only) -->
        <div class="profile-header-modern profile-header-desktop">
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

        <!-- Mobile Header -->
        <div class="profile-mobile-header">
            <h2 class="profile-mobile-title">Account</h2>
            <button class="profile-mobile-edit-btn" id="editProfileBtnMobile">
                Edit
            </button>
        </div>

        <!-- Navigation Tabs -->
        <div class="profile-tabs-modern">
            <button class="profile-tab-modern active" data-tab="account">
                <span class="profile-tab-text">Account</span>
            </button>
            <button class="profile-tab-modern" data-tab="password">
                <span class="profile-tab-text">Change Password</span>
            </button>
        </div>

        <!-- Account Tab Content -->
        <div class="profile-tab-content-modern active" id="accountTab">
            <div class="profile-tab-header-modern profile-tab-header-desktop">
                <h3 class="profile-tab-title-modern">Account Information</h3>
                <p class="profile-tab-subtitle-modern">Update your personal details and contact information</p>
            </div>

            <div class="profile-form-modern">
                <div class="profile-form-grid-modern">
                    <div class="profile-form-group-modern">
                        <label class="profile-form-label-modern">
                            <span>First Name</span>
                        </label>
                        <div class="profile-input-wrapper">
                            <i class="fas fa-user profile-input-icon"></i>
                            <input type="text" class="profile-form-input-modern" id="firstName" value="Rameez Nazar" placeholder="Enter your First Name" readonly>
                        </div>
                    </div>

                    <div class="profile-form-group-modern">
                        <label class="profile-form-label-modern">
                            <span>Last Name</span>
                        </label>
                        <div class="profile-input-wrapper">
                            <i class="fas fa-user profile-input-icon"></i>
                            <input type="text" class="profile-form-input-modern" id="lastName" placeholder="Enter your Last Name" readonly>
                        </div>
                    </div>

                    <div class="profile-form-group-modern">
                        <label class="profile-form-label-modern">
                            <span>Email</span>
                        </label>
                        <div class="profile-input-wrapper">
                            <i class="fas fa-envelope profile-input-icon"></i>
                            <input type="email" class="profile-form-input-modern" id="email" value="ramiznazar600@gmail.com" readonly>
                        </div>
                    </div>

                    <div class="profile-form-group-modern">
                        <label class="profile-form-label-modern">
                            <span>Phone</span>
                        </label>
                        <div class="profile-input-wrapper">
                            <i class="fas fa-phone profile-input-icon"></i>
                            <input type="tel" class="profile-form-input-modern" id="phone" value="+923262853600" readonly>
                        </div>
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
            <div class="profile-tab-header-modern profile-tab-header-desktop">
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
            <button class="profile-contact-btn-modern profile-contact-btn-mobile">
                <span>Contact with upliner</span>
            </button>
            <!-- Desktop Contact Card -->
            <div class="profile-contact-card-modern profile-contact-card-desktop">
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
<script src="{{ asset('assets/dashboard/js/profile.js') }}"></script>
@endpush
@endsection

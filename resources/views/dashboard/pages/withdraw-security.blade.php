@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Withdraw Security')

@push('styles')
<link rel="stylesheet" href="{{ asset('dashboard/css/withdraw-security.css') }}">
@endpush

@section('content')
<div class="withdraw-security-page">
    <!-- Page Header -->
    <div class="withdraw-security-header">
        <h1 class="withdraw-security-title">Withdraw Security</h1>
    </div>

    <!-- Main Security Card -->
    <div class="withdraw-security-section">
        <div class="withdraw-security-card">
            <!-- Progress Steps -->
            <div class="security-progress-steps">
                <div class="security-step active" data-step="1">
                    <div class="security-step-number">1</div>
                    <div class="security-step-label">Enter Email</div>
                </div>
                <div class="security-step-line"></div>
                <div class="security-step" data-step="2">
                    <div class="security-step-number">2</div>
                    <div class="security-step-label">Send OTP</div>
                </div>
                <div class="security-step-line"></div>
                <div class="security-step" data-step="3">
                    <div class="security-step-number">3</div>
                    <div class="security-step-label">Verify OTP</div>
                </div>
            </div>

            <!-- Step 1: Enter Email -->
            <div class="security-step-content active" id="step1Content">
                <div class="security-step-icon">
                    <i class="far fa-envelope"></i>
                </div>
                <h2 class="security-step-title">Enter Your Email</h2>
                <p class="security-step-subtitle">Enter your email to set up withdrawal security</p>

                <div class="security-form">
                    <label class="security-form-label">Email Address</label>
                    <div class="security-input-wrapper">
                        <i class="far fa-envelope security-input-icon"></i>
                        <input type="email" class="security-form-input" id="securityEmail" value="ramiznazar600@gmail.com" placeholder="Enter your email address">
                    </div>
                </div>

                <button class="security-continue-btn" id="continueToStep2">
                    <span>Continue</span>
                </button>
            </div>

            <!-- Step 2: Send OTP -->
            <div class="security-step-content" id="step2Content">
                <div class="security-step-icon">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <h2 class="security-step-title">Send OTP</h2>
                <p class="security-step-subtitle">We've sent a verification code to your email</p>

                <div class="security-form">
                    <label class="security-form-label">Enter OTP Code</label>
                    <div class="security-input-wrapper">
                        <i class="fas fa-key security-input-icon"></i>
                        <input type="text" class="security-form-input" id="otpCode" placeholder="Enter 6-digit OTP code" maxlength="6">
                    </div>
                    <div class="security-otp-actions">
                        <button class="security-resend-btn" id="resendOtp">
                            <i class="fas fa-redo"></i>
                            <span>Resend OTP</span>
                        </button>
                        <span class="security-timer" id="otpTimer">00:00</span>
                    </div>
                </div>

                <button class="security-continue-btn" id="continueToStep3">
                    <span>Verify OTP</span>
                </button>
            </div>

            <!-- Step 3: Verify OTP -->
            <div class="security-step-content" id="step3Content">
                <div class="security-step-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 class="security-step-title">Verification Complete</h2>
                <p class="security-step-subtitle">Your withdrawal security has been successfully set up</p>

                <div class="security-success-message">
                    <i class="fas fa-shield-alt"></i>
                    <p>Your email has been verified and withdrawal security is now active.</p>
                </div>

                <button class="security-continue-btn" id="finishSetup">
                    <span>Done</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('dashboard/js/withdraw-security.js') }}"></script>
@endpush
@endsection


@extends('dashboard.layouts.main')

@section('title', 'Core Mining ⛏️- AI Gold Mining ⛏️')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/deposit-confirm.css') }}">
<style>
    .withdraw-confirm-page {
        max-width: 800px;
        margin: 0 auto;
        padding: 0;
        width: 100%;
        box-sizing: border-box;
    }

    .withdraw-confirm-page .deposit-confirm-card {
        max-width: 600px;
    }

    .withdraw-success-message {
        background: rgba(99, 102, 241, 0.1);
        border: 2px solid rgba(99, 102, 241, 0.3);
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        margin: 2rem 0;
    }

    .withdraw-success-message i {
        font-size: 3rem;
        color: #6366F1;
        margin-bottom: 1rem;
    }

    .withdraw-success-message p {
        font-size: 1.125rem;
        color: var(--text-primary);
        margin: 0;
        line-height: 1.6;
    }
</style>
@endpush

@section('content')
<div class="deposit-confirm-page withdraw-confirm-page">
    <!-- Page Header -->
    <div class="deposit-confirm-header">
        <h1 class="deposit-confirm-title">{{ $paymentMethod->account_type }} Withdrawal</h1>
    </div>

    <!-- Main Withdrawal Card -->
    <div class="deposit-confirm-section">
        <div class="deposit-confirm-card">
            <!-- Progress Steps -->
            <div class="deposit-progress-steps">
                <div class="deposit-step active" data-step="1">
                    <div class="deposit-step-number">1</div>
                    <div class="deposit-step-label">Account Details</div>
                </div>
                <div class="deposit-step-line"></div>
                <div class="deposit-step" data-step="2">
                    <div class="deposit-step-number">2</div>
                    <div class="deposit-step-label">Confirmation</div>
                </div>
            </div>

            <!-- Payment Details Display (shown only on step 2 - Confirmation) -->
            <div class="deposit-payment-details" id="withdrawPaymentDetails" style="display: none;">
                <div class="deposit-detail-row">
                    <span class="deposit-detail-label">Payment Method</span>
                    <span class="deposit-detail-value">{{ $paymentMethod->account_type }}</span>
                </div>
                <div class="deposit-detail-row">
                    <span class="deposit-detail-label">Account Name</span>
                    <span class="deposit-detail-value" id="displayAccountName"></span>
                </div>
                <div class="deposit-detail-row">
                    <span class="deposit-detail-label">Account Number</span>
                    <span class="deposit-detail-value" id="displayAccountNumber"></span>
                </div>
                <div class="deposit-detail-row" id="displayBankNameRow" style="display: none;">
                    <span class="deposit-detail-label">Bank Name</span>
                    <span class="deposit-detail-value" id="displayBankName"></span>
                </div>
                <div class="deposit-detail-row">
                    <span class="deposit-detail-label">Withdrawal Amount</span>
                    <span class="deposit-detail-value">${{ number_format($amount, 2) }}</span>
                </div>
            </div>

            <!-- Step 1: Enter Account Details -->
            <div class="deposit-step-content active" id="step1Content">
                <div class="deposit-step-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h2 class="deposit-step-title">Enter Account Details</h2>
                <p class="deposit-step-subtitle">Enter the account details where you want to receive the money</p>

                @if(strtolower($paymentMethod->type ?? '') === 'bank')
                <div class="deposit-form" id="bankNameField">
                    <label class="deposit-form-label">Bank Name <span style="color: var(--danger-color);">*</span></label>
                    <div class="deposit-input-wrapper">
                        <i class="fas fa-university deposit-input-icon"></i>
                        <input type="text" 
                               class="deposit-form-input" 
                               id="withdrawBankName" 
                               placeholder="Enter bank name" 
                               maxlength="255"
                               required>
                    </div>
                </div>
                @endif
                <div class="deposit-form">
                    <label class="deposit-form-label">Account Holder Name <span style="color: var(--danger-color);">*</span></label>
                    <div class="deposit-input-wrapper">
                        <i class="fas fa-user deposit-input-icon"></i>
                        <input type="text" 
                               class="deposit-form-input" 
                               id="withdrawAccountHolderName" 
                               placeholder="Enter account holder name" 
                               maxlength="255"
                               required>
                    </div>
                </div>

                <div class="deposit-form">
                    <label class="deposit-form-label">Account Number <span style="color: var(--danger-color);">*</span></label>
                    <div class="deposit-input-wrapper">
                        <i class="fas fa-hashtag deposit-input-icon"></i>
                        <input type="text" 
                               class="deposit-form-input" 
                               id="withdrawAccountNumber" 
                               placeholder="Enter account number" 
                               maxlength="255"
                               required>
                    </div>
                </div>

               

                <button class="deposit-continue-btn" id="continueToStep2" disabled>
                    <span>Continue</span>
                </button>
            </div>

            <!-- Step 2: Confirmation Message -->
            <div class="deposit-step-content" id="step2Content">
                <div class="deposit-step-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 class="deposit-step-title">Request Submitted</h2>
                <p class="deposit-step-subtitle">Your withdrawal request has been received</p>

                <div class="withdraw-success-message">
                    <i class="fas fa-info-circle"></i>
                    <p>After admin approval, the money will be transferred to your account.</p>
                </div>

                <button class="deposit-continue-btn" id="submitWithdrawal">
                    <span>Submit Withdrawal Request</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden inputs for form submission -->
<input type="hidden" id="withdrawPaymentMethodId" value="{{ $paymentMethod->id }}">
<input type="hidden" id="withdrawPaymentMethodType" value="{{ $paymentMethod->type }}">
<input type="hidden" id="withdrawAmount" value="{{ $amount }}">
<input type="hidden" id="withdrawStoreUrl" value="{{ route('withdraw.store') }}">
<input type="hidden" id="withdrawIndexUrl" value="{{ route('withdraw.index') }}">

@push('scripts')
<script src="{{ asset('assets/dashboard/js/withdraw-confirm.js') }}"></script>
@endpush
@endsection


@extends('dashboard.layouts.main')

@section('title', 'Core Mining ⛏️- AI Gold Mining ⛏️')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/deposit-confirm.css') }}">
@endpush

@section('content')
<div class="deposit-confirm-page">
    <!-- Page Header -->
    <div class="deposit-confirm-header">
        <h1 class="deposit-confirm-title">{{ $paymentMethod->account_type }} Deposit</h1>
    </div>

    <!-- Main Deposit Card -->
    <div class="deposit-confirm-section">
        <div class="deposit-confirm-card">
            <!-- Progress Steps -->
            <div class="deposit-progress-steps">
                <div class="deposit-step active" data-step="1">
                    <div class="deposit-step-number">1</div>
                    <div class="deposit-step-label">Transaction ID</div>
                </div>
                <div class="deposit-step-line"></div>
                <div class="deposit-step" data-step="2">
                    <div class="deposit-step-number">2</div>
                    <div class="deposit-step-label">Upload Proof</div>
                </div>
                <div class="deposit-step-line"></div>
                <div class="deposit-step" data-step="3">
                    <div class="deposit-step-number">3</div>
                    <div class="deposit-step-label">Account Details</div>
                </div>
                <div class="deposit-step-line"></div>
                <div class="deposit-step" data-step="4">
                    <div class="deposit-step-number">4</div>
                    <div class="deposit-step-label">Confirmation</div>
                </div>
            </div>

            <!-- Payment Details Display (shown on all steps) -->
            <div class="deposit-payment-details">
                <div class="deposit-detail-row">
                    <span class="deposit-detail-label">Payment Method</span>
                    <span class="deposit-detail-value">{{ $paymentMethod->account_type }}</span>
                </div>
                @if($paymentMethod->type == 'bank' && $paymentMethod->bank_name)
                <div class="deposit-detail-row">
                    <span class="deposit-detail-label">Bank Name</span>
                    <span class="deposit-detail-value">{{ $paymentMethod->bank_name }}</span>
                </div>
                @endif
                @if($paymentMethod->account_name)
                <div class="deposit-detail-row">
                    <span class="deposit-detail-label">Account Name</span>
                    <span class="deposit-detail-value">{{ $paymentMethod->account_name }}</span>
                </div>
                @endif
                <div class="deposit-detail-row">
                    <span class="deposit-detail-label" style="min-width: 90px;">{{ $paymentMethod->type === 'rast' ? 'Till ID' : 'Account Number' }}</span>
                    <div style="display: flex; flex: 1; min-width: 0; flex-direction: column; align-items: flex-end;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span class="deposit-detail-value" id="paymentIdentifier">{{ $paymentMethod->type === 'rast' ? $paymentMethod->till_id : $paymentMethod->account_number }}</span>
                            <button type="button" class="deposit-copy-btn" onclick="copyAccountIdentifier()" title="Copy">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>

                @if($paymentMethod->type === 'rast')
                <div class="deposit-detail-row" style="padding-top: 0.25rem; padding-bottom: 0.75rem; align-items: flex-start;">
                    <div style="width: 100%; font-size: 12px; line-height: 1.35; color: var(--primary-gradient-start);">
                        Till ID (Dial 78610# and send funds to our TILL ID below)
                    </div>
                </div>
                @endif

                @if(($paymentMethod->type === 'rast' || $paymentMethod->type === 'onepay') && $paymentMethod->qr_scanner)
                <div class="deposit-detail-row" style="flex-direction: column; align-items: center; width: 100%;">
                    <span class="deposit-detail-label" style="margin-bottom: 0.5rem; width: 100%;">QR Scanner</span>
                    <div style="background: #fff; padding: 0.75rem; border-radius: 12px; margin: 0 auto;">
                        <img src="{{ asset($paymentMethod->qr_scanner) }}" alt="QR Scanner" style="width: 200px; height: 200px; object-fit: contain; display: block;">
                    </div>
                </div>
                @endif
                <div class="deposit-detail-row">
                    <span class="deposit-detail-label">Amount</span>
                    <span class="deposit-detail-value">{{ number_format($pkrAmount, 0) }} PKR</span>
                </div>
            </div>

            <!-- Step 1: Enter Transaction ID -->
            <div class="deposit-step-content active" id="step1Content">
                <div class="deposit-step-icon">
                    <i class="far fa-envelope"></i>
                </div>
                <h2 class="deposit-step-title">Enter Transaction ID</h2>
                <p class="deposit-step-subtitle">Enter the transaction ID from your payment</p>

                <div class="deposit-form">
                    <label class="deposit-form-label">Transaction ID <span style="color: var(--danger-color);">*</span></label>
                    <div class="deposit-input-wrapper">
                        <i class="fas fa-hashtag deposit-input-icon"></i>
                        <input type="text" 
                               class="deposit-form-input" 
                               id="depositTransactionId" 
                               placeholder="Enter your transaction ID" 
                               maxlength="255"
                               required>
                    </div>
                    <div class="deposit-timer-wrapper">
                        <span class="deposit-timer" id="depositTimerStep1">30:00</span>
                    </div>
                </div>

                <button class="deposit-continue-btn" id="continueToStep2" disabled>
                    <span>Continue</span>
                </button>
            </div>

            <!-- Step 2: Upload Payment Proof -->
            <div class="deposit-step-content" id="step2Content">
                <div class="deposit-step-icon">
                    <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <h2 class="deposit-step-title">Upload Payment Proof</h2>
                <p class="deposit-step-subtitle">Upload a screenshot or image of your payment confirmation</p>

                <div class="deposit-form">
                    <label class="deposit-form-label">Payment Proof Screenshot <span style="color: var(--danger-color);">*</span></label>
                    <div class="deposit-upload-area" id="depositUploadArea">
                        <input type="file" 
                               class="deposit-file-input" 
                               id="depositPaymentProof"
                               name="payment_proof"
                               accept="image/*"
                               required>
                        <div class="deposit-upload-content" id="depositUploadContent">
                            <i class="fas fa-cloud-upload-alt deposit-upload-icon"></i>
                            <p class="deposit-upload-text">Click to upload screenshot (JPEG, PNG, etc.)</p>
                        </div>
                        <div class="deposit-upload-preview" id="depositUploadPreview" style="display: none;">
                            <img id="depositPreviewImage" src="" alt="Preview">
                            <button type="button" class="deposit-remove-file" id="depositRemoveFile">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="deposit-timer-wrapper">
                        <span class="deposit-timer" id="depositTimerStep2">30:00</span>
                    </div>
                </div>

                <button class="deposit-continue-btn" id="continueToStep3" disabled>
                    <span>Continue</span>
                </button>
            </div>

            <!-- Step 3: Enter Account Details -->
            <div class="deposit-step-content" id="step3Content">
                <div class="deposit-step-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h2 class="deposit-step-title">Enter Account Details</h2>
                <p class="deposit-step-subtitle">Enter the account details from which you sent the payment</p>

                <div class="deposit-form">
                    <label class="deposit-form-label">Account Number <span style="color: var(--danger-color);">*</span></label>
                    <div class="deposit-input-wrapper">
                        <i class="fas fa-hashtag deposit-input-icon"></i>
                        <input type="text" 
                               class="deposit-form-input" 
                               id="depositAccountNumber" 
                               placeholder="Enter account number" 
                               maxlength="255"
                               required>
                    </div>
                </div>

                <div class="deposit-form">
                    <label class="deposit-form-label">Account Holder Name <span style="color: var(--danger-color);">*</span></label>
                    <div class="deposit-input-wrapper">
                        <i class="fas fa-user deposit-input-icon"></i>
                        <input type="text" 
                               class="deposit-form-input" 
                               id="depositAccountHolderName" 
                               placeholder="Enter account holder name" 
                               maxlength="255"
                               required>
                    </div>
                    <div class="deposit-timer-wrapper">
                        <span class="deposit-timer" id="depositTimerStep3">30:00</span>
                    </div>
                </div>

                <button class="deposit-continue-btn" id="continueToStep4" disabled>
                    <span>Continue</span>
                </button>
            </div>

            <!-- Step 4: Confirmation -->
            <div class="deposit-step-content" id="step4Content">
                <div class="deposit-step-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 class="deposit-step-title">Request Received</h2>
                <p class="deposit-step-subtitle">Your recharge request has been received</p>

                <div class="deposit-success-message">
                    <i class="fas fa-check-circle"></i>
                    <p>Your recharge request has been received.<br>The amount will be added to your account within 24 hours.</p>
                </div>

                <button class="deposit-continue-btn" id="submitDeposit">
                    <span>Submit Deposit Request</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden inputs for form submission -->
<input type="hidden" id="depositPaymentMethodId" value="{{ $paymentMethod->id }}">
<input type="hidden" id="depositAmount" value="{{ $amount }}">
<input type="hidden" id="depositPkrAmount" value="{{ $pkrAmount }}">
<input type="hidden" id="depositStoreUrl" value="{{ route('deposit.store') }}">
<input type="hidden" id="depositIndexUrl" value="{{ route('deposit.index') }}">

@push('scripts')
<script src="{{ asset('assets/dashboard/js/deposit-confirm.js') }}"></script>
<script>
    function copyAccountIdentifier() {
        const identifier = document.getElementById('paymentIdentifier').textContent;
        
        // Create temporary textarea
        const textarea = document.createElement('textarea');
        textarea.value = identifier;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        textarea.setSelectionRange(0, 99999); // For mobile devices
        
        try {
            document.execCommand('copy');
            
            // Show feedback
            const copyBtn = event.target.closest('.deposit-copy-btn');
            if (copyBtn) {
                const originalIcon = copyBtn.innerHTML;
                copyBtn.innerHTML = '<i class="fas fa-check"></i>';
                copyBtn.style.color = 'var(--success-color)';
                
                setTimeout(() => {
                    copyBtn.innerHTML = originalIcon;
                    copyBtn.style.color = '';
                }, 2000);
            }
        } catch (err) {
            console.error('Failed to copy:', err);
        } finally {
            document.body.removeChild(textarea);
        }
    }
</script>
@endpush
@endsection

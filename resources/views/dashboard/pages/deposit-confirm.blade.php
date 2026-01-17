@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Deposit Confirmation')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/dashboard.css') }}">
<style>
    .deposit-confirm-page {
        min-height: 100vh;
        padding: 2rem 1rem;
        max-width: 500px;
        margin: 0 auto;
    }

    .deposit-confirm-header {
        margin-bottom: 2rem;
    }

    .deposit-confirm-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .deposit-confirm-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-md);
    }

    .deposit-info-row {
        margin-bottom: 1.5rem;
    }

    .deposit-info-row:last-child {
        margin-bottom: 0;
    }

    .deposit-info-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .deposit-info-value {
        font-size: 1.125rem;
        color: var(--text-primary);
        font-weight: 600;
    }

    .deposit-phone-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .deposit-phone-input {
        width: 100%;
        background: rgba(24, 27, 39, 0.6);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 0.875rem 3rem 0.875rem 1rem;
        color: var(--text-primary);
        font-size: 1rem;
        transition: var(--transition);
    }

    .deposit-phone-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(255, 178, 30, 0.1);
    }

    .deposit-copy-icon {
        position: absolute;
        right: 1rem;
        background: transparent;
        border: none;
        color: var(--primary-color);
        font-size: 1.25rem;
        cursor: pointer;
        padding: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
    }

    .deposit-copy-icon:hover {
        color: var(--primary-light);
        transform: scale(1.1);
    }

    .deposit-amount-display {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    .deposit-transaction-input {
        width: 100%;
        background: rgba(24, 27, 39, 0.6);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 0.875rem 1rem;
        color: var(--text-primary);
        font-size: 1rem;
        transition: var(--transition);
        margin-top: 0.5rem;
    }

    .deposit-transaction-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(255, 178, 30, 0.1);
    }

    .deposit-transaction-input::placeholder {
        color: var(--text-muted);
    }

    .deposit-required-label {
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .deposit-required-label .required {
        color: var(--danger-color);
    }

    .deposit-action-buttons {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-top: 2rem;
    }

    .deposit-verify-btn {
        width: 100%;
        background: linear-gradient(135deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
        border: none;
        border-radius: 12px;
        padding: 1rem;
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 4px 16px rgba(255, 178, 30, 0.3);
    }

    .deposit-verify-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 178, 30, 0.4);
    }

    .deposit-verify-btn:active {
        transform: translateY(0);
    }

    .deposit-cancel-btn {
        width: 100%;
        background: transparent;
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 1rem;
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }

    .deposit-cancel-btn:hover {
        background: rgba(255, 178, 30, 0.1);
        border-color: var(--primary-color);
    }

    /* Manual Verification Form Styles */
    .manual-verification-form {
        margin-top: 1.5rem;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .manual-verification-alert {
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.9) 0%, rgba(255, 138, 29, 0.9) 100%);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 16px rgba(255, 178, 30, 0.3);
    }

    .manual-verification-alert p {
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.9375rem;
        margin: 0;
    }

    .deposit-hint-text {
        display: block;
        color: var(--text-muted);
        font-size: 0.75rem;
        margin-top: 0.5rem;
    }

    .deposit-upload-area {
        position: relative;
        border: 2px dashed var(--card-border);
        border-radius: 12px;
        padding: 2rem 1rem;
        text-align: center;
        background: rgba(24, 27, 39, 0.4);
        cursor: pointer;
        transition: var(--transition);
        margin-top: 0.5rem;
        min-height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .deposit-upload-area:hover {
        border-color: var(--primary-color);
        background: rgba(24, 27, 39, 0.6);
    }

    .deposit-file-input {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
        z-index: 2;
    }

    .deposit-upload-content {
        position: relative;
        z-index: 1;
    }

    .deposit-upload-icon {
        font-size: 2.5rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .deposit-upload-text {
        color: var(--text-muted);
        font-size: 0.875rem;
        margin: 0;
    }

    .deposit-upload-preview {
        position: relative;
        width: 100%;
        max-height: 200px;
        border-radius: 8px;
        overflow: hidden;
    }

    .deposit-upload-preview img {
        width: 100%;
        height: auto;
        max-height: 200px;
        object-fit: contain;
    }

    .deposit-remove-file {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: rgba(0, 0, 0, 0.7);
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        color: var(--text-primary);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
    }

    .deposit-remove-file:hover {
        background: rgba(255, 68, 68, 0.9);
    }

    .deposit-payment-method-display {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(24, 27, 39, 0.6);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 0.875rem 1rem;
        margin-top: 0.5rem;
    }

    .deposit-payment-method-display span {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 600;
    }

    .deposit-method-icon {
        color: var(--info-color);
        font-size: 1rem;
        cursor: pointer;
    }

    .deposit-submit-review-btn {
        width: 100%;
        background: linear-gradient(135deg, #9C27B0 0%, #E91E63 100%);
        border: none;
        border-radius: 12px;
        padding: 1rem;
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 4px 16px rgba(156, 39, 176, 0.3);
        margin-top: 1.5rem;
    }

    .deposit-submit-review-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(156, 39, 176, 0.4);
    }

    .deposit-submit-review-btn:active {
        transform: translateY(0);
    }

    @media (max-width: 768px) {
        .deposit-confirm-page {
            padding: 1rem;
        }

        .deposit-confirm-title {
            font-size: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<div class="deposit-confirm-page">
    <!-- Header -->
    <div class="deposit-confirm-header">
        <h1 class="deposit-confirm-title">{{ $paymentMethod->account_type }} Deposit</h1>
    </div>

    <!-- Payment Details Card -->
    <div class="deposit-confirm-card">
        <!-- Payment Method -->
        <div class="deposit-info-row">
            <div class="deposit-info-label">Payment Method</div>
            <div class="deposit-info-value">{{ $paymentMethod->account_type }}</div>
        </div>

        @if($paymentMethod->account_name)
        <!-- Account Name -->
        <div class="deposit-info-row">
            <div class="deposit-info-label">Account Name</div>
            <div class="deposit-info-value">{{ $paymentMethod->account_name }}</div>
        </div>
        @endif

        <!-- Phone Number -->
        <div class="deposit-info-row">
            <div class="deposit-info-label">Phone Number</div>
            <div class="deposit-phone-input-wrapper">
                <input type="text" 
                       class="deposit-phone-input" 
                       id="phone-number-input"
                       value="{{ $paymentMethod->account_number }}" 
                       readonly>
                <button type="button" 
                        class="deposit-copy-icon" 
                        onclick="copyPhoneNumber()"
                        title="Copy phone number">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>

        <!-- Amount -->
        <div class="deposit-info-row">
            <div class="deposit-info-label">Amount</div>
            <div class="deposit-amount-display">{{ number_format($pkrAmount, 0) }} PKR</div>
        </div>

        <!-- Transaction ID -->
        <div class="deposit-info-row">
            <label class="deposit-required-label">
                Transaction ID <span class="required">*</span>
            </label>
            <input type="text" 
                   class="deposit-transaction-input" 
                   id="transaction-id-input"
                   name="transaction_id"
                   placeholder="Enter your transaction ID" 
                   required>
        </div>
    </div>

    <!-- Manual Verification Form (Hidden by default) -->
    <div id="manual-verification-form" class="manual-verification-form" style="display: none;">
        <!-- Alert Box -->
        <div class="manual-verification-alert">
            <p>Your transaction requires manual verification. Provide details:</p>
        </div>

        <!-- Verification Form Card -->
        <div class="deposit-confirm-card">
            <!-- Phone Number -->
            <div class="deposit-info-row">
                <label class="deposit-required-label">
                    Phone Number <span class="required">*</span>
                </label>
                <input type="text" 
                       class="deposit-transaction-input" 
                       id="verification-phone-input"
                       name="verification_phone"
                       placeholder="Enter 10-digit phone number" 
                       maxlength="10"
                       required>
                <small class="deposit-hint-text">10-digit phone number without country code</small>
            </div>

            <!-- Payment Proof Screenshot -->
            <div class="deposit-info-row">
                <label class="deposit-required-label">
                    Payment Proof Screenshot <span class="required">*</span>
                </label>
                <div class="deposit-upload-area" id="upload-area">
                    <input type="file" 
                           class="deposit-file-input" 
                           id="payment-proof-input"
                           name="payment_proof"
                           accept="image/*,.pdf"
                           required>
                    <div class="deposit-upload-content">
                        <i class="fas fa-cloud-upload-alt deposit-upload-icon"></i>
                        <p class="deposit-upload-text">Click to upload screenshot (JPEG, PNG, etc.)</p>
                    </div>
                    <div class="deposit-upload-preview" id="upload-preview" style="display: none;">
                        <img id="preview-image" src="" alt="Preview">
                        <button type="button" class="deposit-remove-file" onclick="removeFile()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Amount -->
            <div class="deposit-info-row">
                <label class="deposit-required-label">
                    Amount <span class="required">*</span>
                </label>
                <input type="text" 
                       class="deposit-transaction-input" 
                       id="verification-amount-input"
                       name="verification_amount"
                       value="{{ number_format($pkrAmount, 0) }}" 
                       readonly>
            </div>

            <!-- Payment Method -->
            <div class="deposit-info-row">
                <div class="deposit-info-label">Payment Method</div>
                <div class="deposit-payment-method-display">
                    <span>{{ $paymentMethod->account_type }}</span>
                    <i class="fas fa-ellipsis-h deposit-method-icon"></i>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="button" class="deposit-submit-review-btn" onclick="submitForReview()">
            Submit for manual review
        </button>
    </div>

    <!-- Action Buttons -->
    <div class="deposit-action-buttons" style="margin-bottom: 50px">
        <button type="button" class="deposit-verify-btn" onclick="verifyPayment()">
            Verify the payment
        </button>
        <button type="button" class="deposit-cancel-btn" onclick="cancelDeposit()">
            Cancel
        </button>
    </div>
</div>

<script>
    function copyPhoneNumber() {
        const phoneInput = document.getElementById('phone-number-input');
        phoneInput.select();
        phoneInput.setSelectionRange(0, 99999); // For mobile devices
        
        try {
            document.execCommand('copy');
            
            // Show feedback
            const copyBtn = event.target.closest('.deposit-copy-icon');
            const originalIcon = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="fas fa-check"></i>';
            copyBtn.style.color = 'var(--success-color)';
            
            setTimeout(() => {
                copyBtn.innerHTML = originalIcon;
                copyBtn.style.color = 'var(--primary-color)';
            }, 2000);
        } catch (err) {
            console.error('Failed to copy:', err);
        }
    }

    function verifyPayment() {
        const transactionId = document.getElementById('transaction-id-input').value.trim();
        
        if (!transactionId) {
            alert('Please enter your transaction ID');
            return;
        }

        // Show the manual verification form
        const verificationForm = document.getElementById('manual-verification-form');
        if (verificationForm) {
            verificationForm.style.display = 'block';
            
            // Scroll to the form
            verificationForm.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            
            // Hide the verify button and show cancel button
            const verifyBtn = document.querySelector('.deposit-verify-btn');
            if (verifyBtn) {
                verifyBtn.style.display = 'none';
            }
        }
    }

    // File upload handling
    const fileInput = document.getElementById('payment-proof-input');
    const uploadArea = document.getElementById('upload-area');
    const uploadPreview = document.getElementById('upload-preview');
    const previewImage = document.getElementById('preview-image');
    const uploadContent = uploadArea.querySelector('.deposit-upload-content');

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        uploadContent.style.display = 'none';
                        uploadPreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert('Please upload an image file (JPEG, PNG, etc.)');
                    fileInput.value = '';
                }
            }
        });
    }

    function removeFile() {
        fileInput.value = '';
        uploadPreview.style.display = 'none';
        uploadContent.style.display = 'block';
    }

    function submitForReview() {
        const phone = document.getElementById('verification-phone-input').value.trim();
        const file = document.getElementById('payment-proof-input').files[0];
        const transactionId = document.getElementById('transaction-id-input').value.trim();

        // Validation
        if (!phone) {
            alert('Please enter your phone number');
            return;
        }

        if (phone.length !== 10 || !/^\d+$/.test(phone)) {
            alert('Please enter a valid 10-digit phone number');
            return;
        }

        if (!file) {
            alert('Please upload a payment proof screenshot');
            return;
        }

        if (!transactionId) {
            alert('Please enter your transaction ID');
            return;
        }

        // Create FormData for file upload
        const formData = new FormData();
        formData.append('payment_method_id', {{ $paymentMethod->id }});
        formData.append('amount', {{ $amount }});
        formData.append('pkr_amount', {{ $pkrAmount }});
        formData.append('transaction_id', transactionId);
        formData.append('phone', phone);
        formData.append('payment_proof', file);
        formData.append('_token', '{{ csrf_token() }}');

        // Show loading state
        const submitBtn = document.querySelector('.deposit-submit-review-btn');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Submitting...';
        submitBtn.disabled = true;

        // Submit form via AJAX
        fetch('{{ route("deposit.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            
            if (data.success) {
                alert(data.message);
                window.location.href = data.redirect || '{{ route("deposit.index") }}';
            } else {
                alert(data.message || 'An error occurred. Please try again.');
            }
        })
        .catch(error => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            console.error('Error:', error);
            alert('An error occurred while submitting your deposit. Please try again.');
        });
    }

    function cancelDeposit() {
        if (confirm('Are you sure you want to cancel this deposit?')) {
            window.location.href = '{{ route("deposit.index") }}';
        }
    }
</script>
@endsection


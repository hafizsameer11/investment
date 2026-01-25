@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Crypto Deposit')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/dashboard.css') }}">
<style>
    .crypto-deposit-page {
        padding: 0;
        width: 100%;
        max-width: 100%;
    }

    .crypto-deposit-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .crypto-deposit-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .crypto-deposit-status {
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(255, 178, 30, 0.3);
    }

    .crypto-deposit-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .crypto-deposit-instruction {
        color: var(--text-secondary);
        font-size: 1rem;
        margin-bottom: 2rem;
    }

    .crypto-qr-section {
        text-align: center;
        margin: 2rem 0;
    }

    .crypto-qr-code {
        background: white;
        padding: 1rem;
        border-radius: 12px;
        display: inline-block;
        margin: 0 auto;
    }

    .crypto-qr-code img {
        width: 250px;
        height: 250px;
        display: block;
    }

    .crypto-wallet-address-section {
        margin: 2rem 0;
    }

    .crypto-wallet-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .crypto-wallet-address-box {
        background: rgba(24, 27, 39, 0.9);
        border: 2px solid rgba(255, 178, 30, 0.25);
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        word-break: break-all;
    }

    .crypto-wallet-address {
        flex: 1;
        color: var(--text-primary);
        font-family: monospace;
        font-size: 0.875rem;
    }

    .crypto-copy-btn {
        background: rgba(255, 178, 30, 0.1);
        border: 1px solid rgba(255, 178, 30, 0.3);
        border-radius: 8px;
        padding: 0.5rem 1rem;
        color: var(--primary-color);
        cursor: pointer;
        transition: all 0.3s;
        flex-shrink: 0;
    }

    .crypto-copy-btn:hover {
        background: rgba(255, 178, 30, 0.2);
        border-color: var(--primary-color);
    }

    .crypto-amount-conversion {
        background: rgba(24, 27, 39, 0.6);
        border-radius: 12px;
        padding: 1.5rem;
        margin: 2rem 0;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 2rem;
        flex-direction: row;
    }

    .crypto-amount-item {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-width: 0;
    }

    .crypto-amount-item-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .crypto-amount-item-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .crypto-amount-item-subvalue {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-top: 0.25rem;
    }

    .crypto-instructions-section {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--card-border);
    }

    .crypto-instructions-content {
        background: rgba(24, 27, 39, 0.6);
        border-radius: 16px;
        padding: 1.5rem;
    }

    .crypto-instructions-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1.25rem;
    }

    .crypto-instructions-list {
        list-style: none;
        padding: 0;
        margin: 0 0 2rem 0;
    }

    .crypto-instructions-list li {
        padding: 0.75rem 0;
        padding-left: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        color: var(--text-primary);
        position: relative;
        font-size: 0.9375rem;
        line-height: 1.5;
    }

    .crypto-instructions-list li:last-child {
        border-bottom: none;
    }

    .crypto-instructions-list li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 1rem;
        width: 6px;
        height: 6px;
        background: var(--primary-color);
        border-radius: 50%;
    }

    .crypto-sent-payment-btn {
        width: 100%;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
        border: none;
        border-radius: 12px;
        color: white;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 1.5rem;
        box-shadow: 0 4px 15px rgba(255, 178, 30, 0.3);
    }

    .crypto-sent-payment-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(255, 178, 30, 0.5);
    }

    .crypto-submit-btn {
        width: 100%;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
        border: none;
        border-radius: 12px;
        color: white;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(255, 178, 30, 0.3);
    }

    .crypto-submit-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(255, 178, 30, 0.5);
    }

    .crypto-submit-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .crypto-user-wallet-form {
        margin-top: 2rem;
        display: none;
    }

    .crypto-user-wallet-form.active {
        display: block;
    }

    .crypto-form-group {
        margin-bottom: 1.5rem;
    }

    .crypto-form-label {
        display: block;
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .crypto-form-input {
        width: 100%;
        padding: 1rem 1.25rem;
        background: rgba(24, 27, 39, 0.9);
        border: 2px solid rgba(255, 178, 30, 0.25);
        border-radius: 12px;
        color: var(--text-primary);
        font-size: 1rem;
        transition: all 0.3s;
    }

    .crypto-form-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(255, 178, 30, 0.15);
    }

    .crypto-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-secondary);
        text-decoration: none;
        margin-bottom: 1rem;
        transition: color 0.3s;
    }

    .crypto-back-btn:hover {
        color: var(--primary-color);
    }

    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .crypto-deposit-page {
            padding: 0;
        }

        .crypto-deposit-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .crypto-deposit-title {
            font-size: 1.5rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .crypto-deposit-status {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }

        .crypto-deposit-card {
            padding: 1.5rem;
            border-radius: 16px;
        }

        .crypto-deposit-instruction {
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }

        .crypto-qr-section {
            margin: 1.5rem 0;
        }

        .crypto-qr-code {
            padding: 0.75rem;
        }

        .crypto-qr-code img {
            width: 200px;
            height: 200px;
        }

        .crypto-amount-conversion {
            flex-direction: row;
            gap: 1rem;
            padding: 1.25rem;
            margin: 1.5rem 0;
        }

        .crypto-amount-item {
            flex: 1;
            min-width: 0;
        }

        .crypto-amount-item-value {
            font-size: 1.25rem;
        }

        .crypto-amount-item-subvalue {
            font-size: 0.8125rem;
        }

        .crypto-amount-item-label {
            font-size: 0.8125rem;
            margin-bottom: 0.5rem;
        }

        .crypto-amount-item-value {
            font-size: 1.25rem;
        }

        .crypto-amount-item-subvalue {
            font-size: 0.8125rem;
        }

        .crypto-wallet-address-section {
            margin: 1.5rem 0;
        }

        .crypto-wallet-label {
            font-size: 0.8125rem;
        }

        .crypto-wallet-address-box {
            padding: 0.75rem;
            gap: 0.75rem;
            flex-direction: column;
            align-items: stretch;
        }

        .crypto-wallet-address {
            font-size: 0.75rem;
            word-break: break-all;
        }

        .crypto-copy-btn {
            padding: 0.5rem;
            width: 100%;
            justify-content: center;
        }

        .crypto-instructions-section {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
        }

        .crypto-instructions-content {
            padding: 1.25rem;
            border-radius: 12px;
        }

        .crypto-instructions-title {
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .crypto-instructions-list {
            margin: 0 0 1.5rem 0;
        }

        .crypto-instructions-list li {
            padding: 0.625rem 0;
            padding-left: 1.25rem;
            font-size: 0.8125rem;
            line-height: 1.4;
        }

        .crypto-instructions-list li::before {
            top: 0.875rem;
            width: 5px;
            height: 5px;
        }

        .crypto-sent-payment-btn {
            padding: 1rem 1.25rem;
            font-size: 0.9375rem;
            margin-top: 1.25rem;
        }

        .crypto-submit-btn {
            padding: 1rem 1.25rem;
            font-size: 0.9375rem;
        }

        .crypto-user-wallet-form {
            margin-top: 1.5rem;
        }

        .crypto-form-group {
            margin-bottom: 1.25rem;
        }

        .crypto-form-label {
            font-size: 0.8125rem;
        }

        .crypto-form-input {
            padding: 0.875rem 1rem;
            font-size: 0.9375rem;
        }

        .crypto-back-btn {
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
        }
    }

    @media (max-width: 480px) {
        .crypto-deposit-title {
            font-size: 1.25rem;
        }

        .crypto-deposit-card {
            padding: 1.25rem;
            border-radius: 12px;
        }

        .crypto-qr-code img {
            width: 180px;
            height: 180px;
        }

        .crypto-instructions-content {
            padding: 1rem;
        }

        .crypto-instructions-title {
            font-size: 0.9375rem;
        }

        .crypto-instructions-list li {
            font-size: 0.75rem;
            padding: 0.5rem 0;
            padding-left: 1rem;
        }

        .crypto-sent-payment-btn,
        .crypto-submit-btn {
            padding: 0.875rem 1rem;
            font-size: 0.875rem;
        }

        .crypto-wallet-address {
            font-size: 0.6875rem;
        }

        .crypto-amount-conversion {
            padding: 1rem;
            flex-direction: row;
            gap: 0.75rem;
        }

        .crypto-amount-item-value {
            font-size: 1.125rem;
        }

        .crypto-amount-item-label {
            font-size: 0.75rem;
        }

        .crypto-amount-item-subvalue {
            font-size: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="crypto-deposit-page">
    {{-- <a href="{{ route('deposit.crypto.network', ['method_id' => $paymentMethod->id, 'amount' => $amount]) }}" class="crypto-back-btn">
        <i class="fas fa-arrow-left"></i> Back to Network Selection
    </a>

    <div class="crypto-deposit-header">
        <h1 class="crypto-deposit-title">
            {{ $cryptoWallet->token }} Deposit
            <span class="crypto-deposit-status">Processing</span>
        </h1>
    </div> --}}

    <div class="crypto-deposit-card">
        <p class="crypto-deposit-instruction">
            Send {{ $cryptoWallet->token }} using the {{ $cryptoWallet->network_display_name }} network.
        </p>

        <!-- QR Code -->
        <div class="crypto-qr-section">
            @if($cryptoWallet->qr_code_image)
            <div class="crypto-qr-code">
                <img src="{{ asset($cryptoWallet->qr_code_image) }}" alt="QR Code">
            </div>
            @endif
        </div>

        <!-- Wallet Address -->
        <div class="crypto-wallet-address-section">
            <div class="crypto-wallet-label">Wallet Address</div>
            <div class="crypto-wallet-address-box">
                <span class="crypto-wallet-address" id="walletAddress">{{ $cryptoWallet->wallet_address }}</span>
                <button class="crypto-copy-btn" onclick="copyWalletAddress()">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>

        <!-- Amount Conversion -->
        <div class="crypto-amount-conversion">
            <div class="crypto-amount-item">
                <div class="crypto-amount-item-label">USD Amount</div>
                <div class="crypto-amount-item-value">${{ number_format($amount, 0) }}</div>
            </div>
            <div class="crypto-amount-item">
                <div class="crypto-amount-item-label">Deposit Amount</div>
                <div class="crypto-amount-item-value">{{ number_format($amount, 2) }} USD ≈</div>
                <div class="crypto-amount-item-subvalue">{{ number_format($amount, 2) }} {{ $cryptoWallet->token }}</div>
            </div>
        </div>

        <!-- Instructions Section (at bottom) -->
        <div class="crypto-instructions-section">
            <div class="crypto-instructions-content">
                <h2 class="crypto-instructions-title">Important Instructions:</h2>
                <ul class="crypto-instructions-list">
                    <li>Send only {{ $cryptoWallet->token }} to this address. Sending any other token may result in permanent loss.</li>
                    <li>Make sure you're sending through the {{ $cryptoWallet->network_display_name }} network.</li>
                    <li>The deposit will be credited after blockchain confirmation (usually within 5-10 minutes).</li>
                </ul>
                <button class="crypto-sent-payment-btn" id="sentPaymentBtn">
                    I've Sent the Payment
                </button>
            </div>
        </div>

        <!-- User Wallet Address Form (shown after clicking "I've Sent the Payment") -->
        <div class="crypto-user-wallet-form" id="userWalletForm">
            <div class="crypto-form-group">
                <label class="crypto-form-label">Your Wallet Address / ID <span style="color: var(--danger-color);">*</span></label>
                <input type="text" 
                       class="crypto-form-input" 
                       id="userWalletAddress" 
                       placeholder="Enter the wallet address or ID you used to send the payment"
                       required>
            </div>
            <button class="crypto-submit-btn" id="submitDepositBtn">
                Submit Deposit Request
            </button>
        </div>
    </div>
</div>

<input type="hidden" id="paymentMethodId" value="{{ $paymentMethod->id }}">
<input type="hidden" id="cryptoWalletId" value="{{ $cryptoWallet->id }}">
<input type="hidden" id="amount" value="{{ $amount }}">
<input type="hidden" id="pkrAmount" value="{{ $pkrAmount }}">
<input type="hidden" id="depositStoreUrl" value="{{ route('deposit.store') }}">

@push('scripts')
<script>
    function copyWalletAddress() {
        const address = document.getElementById('walletAddress').textContent;
        const textarea = document.createElement('textarea');
        textarea.value = address;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        textarea.setSelectionRange(0, 99999);
        
        try {
            document.execCommand('copy');
            const btn = event.target.closest('.crypto-copy-btn');
            if (btn) {
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i>';
                btn.style.color = 'var(--success-color)';
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.style.color = '';
                }, 2000);
            }
        } catch (err) {
            console.error('Failed to copy:', err);
        } finally {
            document.body.removeChild(textarea);
        }
    }

    // Show user wallet form when "I've Sent the Payment" is clicked
    document.getElementById('sentPaymentBtn').addEventListener('click', function() {
        document.getElementById('userWalletForm').classList.add('active');
        // Scroll to the form smoothly
        setTimeout(() => {
            document.getElementById('userWalletForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 100);
    });

    document.getElementById('submitDepositBtn').addEventListener('click', function() {
        const userWalletAddress = document.getElementById('userWalletAddress').value.trim();
        
        if (!userWalletAddress) {
            alert('Please enter your wallet address');
            return;
        }

        const formData = new FormData();
        formData.append('payment_method_id', document.getElementById('paymentMethodId').value);
        formData.append('amount', document.getElementById('amount').value);
        formData.append('pkr_amount', document.getElementById('pkrAmount').value);
        formData.append('crypto_wallet_id', document.getElementById('cryptoWalletId').value);
        formData.append('user_wallet_address', userWalletAddress);
        formData.append('_token', '{{ csrf_token() }}');

        // Disable button
        this.disabled = true;
        this.textContent = 'Submitting...';

        fetch(document.getElementById('depositStoreUrl').value, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = data.redirect || '{{ route('deposit.index') }}';
            } else {
                alert(data.message || 'An error occurred. Please try again.');
                this.disabled = false;
                this.textContent = 'Submit Deposit Request';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            this.disabled = false;
            this.textContent = 'Submit Deposit Request';
        });
    });
</script>
@endpush
@endsection


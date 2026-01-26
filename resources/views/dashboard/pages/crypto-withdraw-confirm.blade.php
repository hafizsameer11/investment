@extends('dashboard.layouts.main')

@section('title', 'Core Mining ⛏️- AI Gold Mining ⛏️')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/dashboard.css') }}">
<style>
    .crypto-withdraw-page {
        padding: 0;
        width: 100%;
        max-width: 100%;
    }

    .crypto-withdraw-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .crypto-withdraw-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .crypto-withdraw-status {
        background: linear-gradient(135deg, #FF6B9D 0%, #C44569 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .crypto-withdraw-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .crypto-withdraw-instruction {
        color: var(--text-secondary);
        font-size: 1rem;
        margin-bottom: 2rem;
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

    .crypto-user-wallet-form {
        margin-top: 2rem;
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

    .crypto-submit-btn {
        width: 100%;
        padding: 1.375rem 1.5rem;
        background: linear-gradient(135deg, #FF6B9D 0%, #C44569 100%);
        border: none;
        border-radius: 14px;
        color: white;
        font-size: 1.1875rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 1rem;
    }

    .crypto-submit-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 30px rgba(255, 107, 157, 0.4);
    }

    .crypto-submit-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .crypto-cancel-btn {
        width: 100%;
        padding: 1rem 1.5rem;
        background: transparent;
        border: 2px solid var(--card-border);
        border-radius: 14px;
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .crypto-cancel-btn:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
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
</style>
@endpush

@section('content')
<div class="crypto-withdraw-page">
    <a href="{{ route('withdraw.crypto.network', ['method_id' => $paymentMethod->id, 'amount' => $amount]) }}" class="crypto-back-btn">
        <i class="fas fa-arrow-left"></i> Back to Network Selection
    </a>

    <div class="crypto-withdraw-header">
        <h1 class="crypto-withdraw-title">
            {{ $cryptoWallet->token }} Withdrawal
            <span class="crypto-withdraw-status">Processing</span>
        </h1>
    </div>

    <div class="crypto-withdraw-card">
        <p class="crypto-withdraw-instruction">
            Enter your {{ $cryptoWallet->token }} wallet address to receive the withdrawal via {{ $cryptoWallet->network_display_name }} network.
        </p>

        <!-- User Wallet Address Form -->
        <div class="crypto-user-wallet-form">
            <div class="crypto-form-group">
                <label class="crypto-form-label">Your {{ $cryptoWallet->token }} Wallet Address <span style="color: var(--danger-color);">*</span></label>
                <input type="text" 
                       class="crypto-form-input" 
                       id="userWalletAddress" 
                       placeholder="Enter your {{ $cryptoWallet->token }} wallet address for receiving the withdrawal"
                       required>
                <small style="color: var(--text-secondary); margin-top: 0.5rem; display: block;">
                    Make sure you're using the {{ $cryptoWallet->network_display_name }} network. Sending to the wrong network may result in permanent loss.
                </small>
            </div>
            <button class="crypto-submit-btn" id="submitWithdrawBtn">
                Submit Withdrawal Request
            </button>
            <a href="{{ route('withdraw.index') }}" class="crypto-cancel-btn">
                Cancel
            </a>
        </div>
    </div>
</div>

<input type="hidden" id="paymentMethodId" value="{{ $paymentMethod->id }}">
<input type="hidden" id="cryptoWalletId" value="{{ $cryptoWallet->id }}">
<input type="hidden" id="amount" value="{{ $amount }}">
<input type="hidden" id="withdrawStoreUrl" value="{{ route('withdraw.store') }}">

@push('scripts')
<script>
    document.getElementById('submitWithdrawBtn').addEventListener('click', function() {
        const userWalletAddress = document.getElementById('userWalletAddress').value.trim();
        
        if (!userWalletAddress) {
            alert('Please enter your wallet address');
            return;
        }

        const formData = new FormData();
        formData.append('payment_method_id', document.getElementById('paymentMethodId').value);
        formData.append('amount', document.getElementById('amount').value);
        formData.append('crypto_wallet_id', document.getElementById('cryptoWalletId').value);
        formData.append('user_wallet_address', userWalletAddress);
        formData.append('_token', '{{ csrf_token() }}');

        // Disable button
        this.disabled = true;
        this.textContent = 'Submitting...';

        fetch(document.getElementById('withdrawStoreUrl').value, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = data.redirect || '{{ route('withdraw.index') }}';
            } else {
                alert(data.message || 'An error occurred. Please try again.');
                this.disabled = false;
                this.textContent = 'Submit Withdrawal Request';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            this.disabled = false;
            this.textContent = 'Submit Withdrawal Request';
        });
    });
</script>
@endpush
@endsection


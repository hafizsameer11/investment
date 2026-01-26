@extends('dashboard.layouts.main')

@section('title', 'Core Mining ⛏️- AI Gold Mining ⛏️')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/dashboard.css') }}">
<style>
    .crypto-network-page {
        padding: 0;
        width: 100%;
        max-width: 100%;
    }

    .crypto-network-header {
        margin-bottom: 2rem;
    }

    .crypto-network-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .crypto-network-subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
        margin: 0;
    }

    .crypto-network-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .crypto-network-options {
        display: grid;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .crypto-network-option {
        background: rgba(24, 27, 39, 0.9);
        border: 2px solid rgba(255, 178, 30, 0.2);
        border-radius: 16px;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .crypto-network-option:hover {
        border-color: rgba(255, 178, 30, 0.4);
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(255, 178, 30, 0.2);
    }

    .crypto-network-option.active {
        border-color: var(--primary-color);
        background: rgba(255, 178, 30, 0.1);
        box-shadow: 0 0 20px rgba(255, 178, 30, 0.3);
    }

    .crypto-network-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        flex-shrink: 0;
    }

    .crypto-network-icon.bnb {
        background: linear-gradient(135deg, #F3BA2F 0%, #F0B90B 100%);
    }

    .crypto-network-icon.tron {
        background: linear-gradient(135deg, #EF0027 0%, #DC143C 100%);
    }

    .crypto-network-info {
        flex: 1;
    }

    .crypto-network-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.25rem 0;
    }

    .crypto-network-code {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .crypto-token-section {
        background: rgba(24, 27, 39, 0.6);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 2rem;
    }

    .crypto-token-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .crypto-token-value {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .crypto-continue-btn {
        width: 100%;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #FF8A1D 0%, #FFB21E 100%);
        border: none;
        border-radius: 12px;
        color: white;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(255, 178, 30, 0.3);
        margin-bottom: 0.75rem;
    }

    .crypto-continue-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(255, 178, 30, 0.4);
    }

    .crypto-continue-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .crypto-cancel-btn {
        width: 100%;
        padding: 1rem 1.5rem;
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        color: white;
        font-size: 0.9375rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: center;
        text-decoration: none;
        display: inline-block;
    }

    .crypto-cancel-btn:hover {
        border-color: rgba(255, 255, 255, 0.4);
        background: rgba(255, 255, 255, 0.05);
    }

    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .crypto-network-header {
            margin-bottom: 1.5rem;
            padding-left: 1rem;
        }

        .crypto-network-title {
            font-size: 1.25rem;
        }

        .crypto-network-subtitle {
            font-size: 0.875rem;
        }

        .crypto-network-card {
            padding: 1.5rem;
            border-radius: 16px;
        }

        .crypto-network-option {
            padding: 1.25rem;
            gap: 1rem;
        }

        .crypto-network-icon {
            width: 50px;
            height: 50px;
            font-size: 1.75rem;
        }

        .crypto-network-name {
            font-size: 1.125rem;
        }

        .crypto-network-code {
            font-size: 0.8125rem;
        }

        .crypto-token-section {
            padding: 0.875rem;
        }

        .crypto-token-label {
            font-size: 0.8125rem;
        }

        .crypto-token-value {
            font-size: 1rem;
        }

        .crypto-continue-btn {
            padding: 1rem 1.25rem;
            font-size: 0.9375rem;
        }

        .crypto-cancel-btn {
            padding: 0.875rem 1.25rem;
            font-size: 0.875rem;
        }
    }

    @media (max-width: 480px) {
        .crypto-network-header {
            padding-left: 0.75rem;
        }

        .crypto-network-title {
            font-size: 1.125rem;
        }

        .crypto-network-subtitle {
            font-size: 0.8125rem;
        }

        .crypto-network-card {
            padding: 1.25rem;
            border-radius: 12px;
        }

        .crypto-network-option {
            padding: 1rem;
            gap: 0.75rem;
        }

        .crypto-network-icon {
            width: 45px;
            height: 45px;
            font-size: 1.5rem;
        }

        .crypto-network-name {
            font-size: 1rem;
        }

        .crypto-network-code {
            font-size: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="crypto-network-page">
    <div class="crypto-network-header">
        <h1 class="crypto-network-title">Select Crypto Network</h1>
        <p class="crypto-network-subtitle">Choose a network to withdraw crypto</p>
    </div>

    <div class="crypto-network-card">
        <!-- Network Selection -->
        <div class="crypto-network-options">
            @foreach($cryptoWallets as $wallet)
            <div class="crypto-network-option" 
                 data-wallet-id="{{ $wallet->id }}"
                 data-network="{{ $wallet->network }}">
                <div class="crypto-network-icon {{ $wallet->network === 'bnb_smart_chain' ? 'bnb' : 'tron' }}">
                    @if($wallet->network === 'bnb_smart_chain')
                        <i class="fab fa-ethereum"></i>
                    @else
                        <i class="fas fa-coins"></i>
                    @endif
                </div>
                <div class="crypto-network-info">
                    <div class="crypto-network-name">{{ $wallet->network_display_name }}</div>
                    <div class="crypto-network-code">{{ strtoupper($wallet->network === 'bnb_smart_chain' ? 'BSC (BEP20)' : 'TRON') }}</div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Token Selection (USDT preselected) -->
        <div class="crypto-token-section">
            <div class="crypto-token-label">Select Token</div>
            <div class="crypto-token-value">USDT</div>
        </div>

        <!-- Action Buttons -->
        <button class="crypto-continue-btn" id="continueBtn" disabled>
            Continue
        </button>
        <a href="{{ route('withdraw.index') }}" class="crypto-cancel-btn">
            Cancel
        </a>
    </div>
</div>

<input type="hidden" id="paymentMethodId" value="{{ $paymentMethod->id }}">
<input type="hidden" id="amount" value="{{ $amount }}">

@push('scripts')
<script>
    let selectedWalletId = null;

    document.querySelectorAll('.crypto-network-option').forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all options
            document.querySelectorAll('.crypto-network-option').forEach(opt => opt.classList.remove('active'));
            
            // Add active class to selected option
            this.classList.add('active');
            
            // Store selected wallet ID
            selectedWalletId = this.dataset.walletId;
            
            // Enable continue button
            document.getElementById('continueBtn').disabled = false;
        });
    });

    document.getElementById('continueBtn').addEventListener('click', function() {
        if (!selectedWalletId) {
            alert('Please select a crypto network');
            return;
        }

        const paymentMethodId = document.getElementById('paymentMethodId').value;
        const amount = document.getElementById('amount').value;

        window.location.href = `{{ route('withdraw.crypto.confirm') }}?method_id=${paymentMethodId}&amount=${amount}&crypto_wallet_id=${selectedWalletId}`;
    });
</script>
@endpush
@endsection


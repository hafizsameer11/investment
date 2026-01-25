@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Claim Referral Earnings')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/referrals.css') }}">
    <style>
        .referral-earnings-claim-page {
            padding: 2rem;
            max-width: 1600px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }

        /* Earning Wallet Card */
        .referrals-wallet-section-new {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            padding: 3rem;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        .referrals-wallet-section-new::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #FFB21E 0%, #FF8A1D 100%);
        }

        .referrals-wallet-header-new {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .referrals-wallet-icon-new {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(255, 178, 30, 0.2) 0%, rgba(255, 138, 29, 0.1) 100%);
            border: 2px solid rgba(255, 178, 30, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--primary-color);
            box-shadow: 0 0 30px rgba(255, 178, 30, 0.3);
        }

        .referrals-wallet-title-section-new {
            flex: 1;
        }

        .referrals-wallet-title-new {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 0.5rem 0;
        }

        .referrals-wallet-subtitle-new {
            font-size: 1rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .referrals-wallet-body-new {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .referrals-balance-display-new {
            text-align: center;
            padding: 2.5rem;
            background: rgba(255, 178, 30, 0.05);
            border: 1px solid rgba(255, 178, 30, 0.2);
            border-radius: 20px;
        }

        .referrals-balance-amount-wrapper-new {
            display: flex;
            align-items: baseline;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .referrals-balance-value-new {
            font-size: 4rem;
            font-weight: 700;
            color: var(--primary-color);
            font-variant-numeric: tabular-nums;
            text-shadow: 0 0 30px rgba(255, 178, 30, 0.6);
        }

        .referrals-minimum-badge-new {
            padding: 0.375rem 0.75rem;
            background: rgba(255, 178, 30, 0.2);
            border: 1px solid rgba(255, 178, 30, 0.4);
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--primary-color);
            white-space: nowrap;
        }

        .referrals-minimum-needed-new {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .referrals-claim-note-new {
            text-align: center;
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .referrals-claim-btn-new {
            padding: 1.25rem 2rem;
            background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
            border: none;
            border-radius: 16px;
            color: #000;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            box-shadow: 0 4px 20px rgba(255, 178, 30, 0.4);
            width: 100%;
        }

        .referrals-claim-btn-new:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 6px 30px rgba(255, 178, 30, 0.6);
        }

        .referrals-claim-btn-new:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .referrals-commission-breakdown-new {
            margin: 1.5rem 0;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Mobile Wallet Design - Match Image */
        @media (max-width: 768px) {
            .referral-earnings-claim-page {
                padding: 1rem;
            }

            .referrals-wallet-section-new {
                padding: 1.5rem;
                border-radius: 16px;
                margin-bottom: 1.5rem;
            }

            .referrals-wallet-section-new::before {
                display: none;
            }

            .referrals-wallet-header-new {
                margin-bottom: 1.5rem;
            }

            .referrals-wallet-icon-new {
                display: none;
            }

            .referrals-wallet-title-new {
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--text-primary);
                margin: 0 0 0.5rem 0;
            }

            .referrals-wallet-subtitle-new {
                font-size: 0.875rem;
                color: rgba(255, 255, 255, 0.7);
                margin: 0;
            }

            .referrals-wallet-body-new {
                gap: 1.25rem;
            }

            .referrals-balance-display-new {
                background: transparent;
                border: none;
                padding: 0;
                text-align: left;
            }

            .referrals-balance-amount-wrapper-new {
                display: flex;
                align-items: baseline;
                gap: 0.75rem;
                margin-bottom: 0.75rem;
            }

            .referrals-balance-value-new {
                font-size: 2.5rem;
                font-weight: 700;
                color: var(--text-primary);
                text-shadow: none;
            }

            .referrals-minimum-badge-new {
                padding: 0.375rem 0.75rem;
                background: #8B4513;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 600;
                color: #fff;
                white-space: nowrap;
            }

            .referrals-minimum-needed-new {
                font-size: 0.875rem;
                color: rgba(255, 255, 255, 0.7);
                margin: 0;
                text-align: left;
            }

            .referrals-claim-note-new {
                font-size: 0.875rem;
                color: #F97316;
                text-align: left;
                margin: 0;
            }

            .referrals-claim-btn-new {
                padding: 0.875rem 1.5rem;
                background: #F97316;
                border: none;
                border-radius: 12px;
                color: #fff;
                font-weight: 600;
                font-size: 0.9375rem;
                cursor: pointer;
                transition: all 0.2s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                box-shadow: none;
                width: 100%;
            }

            .referrals-claim-btn-new:hover:not(:disabled) {
                background: #EA580C;
                transform: none;
                box-shadow: none;
            }

            .referrals-claim-btn-new:disabled {
                opacity: 0.6;
                cursor: not-allowed;
            }

            .referrals-claim-btn-new i {
                font-size: 0.875rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="referral-earnings-claim-page">
        <!-- Investment Commission Wallet Card -->
        <div class="referrals-wallet-section-new" style="margin-bottom: 2rem;">
            <div class="referrals-wallet-header-new">
                <div class="referrals-wallet-title-section-new">
                    <h3 class="referrals-wallet-title-new">Investment Commission Earnings</h3>
                    <p class="referrals-wallet-subtitle-new">Earnings from referral investments</p>
                </div>
            </div>
            <div class="referrals-wallet-body-new">
                <div class="referrals-balance-display-new">
                    <div class="referrals-balance-amount-wrapper-new">
                        <span class="referrals-balance-value-new"
                            id="pendingInvestmentAmount">${{ number_format($pendingInvestmentCommissions ?? 0, 2) }}</span>
                        <div class="referrals-minimum-badge-new">Minimum $1</div>
                    </div>
                    @if(($pendingInvestmentCommissions ?? 0) < 1)
                        <div class="referrals-minimum-needed-new">${{ number_format(max(0, 1 - ($pendingInvestmentCommissions ?? 0)), 2) }}
                            more needed to claim</div>
                    @else
                        <div class="referrals-minimum-needed-new" style="color: #10B981;">Ready to claim!</div>
                    @endif
                </div>
                @if(isset($pendingInvestmentCommissionsByLevel) && count($pendingInvestmentCommissionsByLevel) > 0)
                    <div class="referrals-commission-breakdown-new">
                        <p style="margin: 0 0 0.75rem 0; font-size: 0.875rem; color: var(--text-secondary);">Breakdown by Level:
                        </p>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 0.75rem;">
                            @for($i = 1; $i <= 5; $i++)
                                <div style="text-align: center;">
                                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Level
                                        {{ $i }}</div>
                                    <div style="font-weight: 600; color: #FFB21E;">
                                        ${{ number_format($pendingInvestmentCommissionsByLevel[$i] ?? 0, 2) }}</div>
                                </div>
                            @endfor
                        </div>
                    </div>
                @endif
                <p class="referrals-claim-note-new">You can claim investment commission earnings when balance reaches $1 or more</p>
                <button class="referrals-claim-btn-new" id="claimInvestmentBtn" data-type="investment" {{ ($pendingInvestmentCommissions ?? 0) < 1 ? 'disabled' : '' }}>
                    <i class="fas fa-gift"></i>
                    <span>Claim Investment Commission</span>
                </button>
            </div>
        </div>

        <!-- Earning Commission Wallet Card -->
        <div class="referrals-wallet-section-new">
            <div class="referrals-wallet-header-new">
                <div class="referrals-wallet-title-section-new">
                    <h3 class="referrals-wallet-title-new">Earning Commission Earnings</h3>
                    <p class="referrals-wallet-subtitle-new">Earnings from referral mining profits</p>
                </div>
            </div>
            <div class="referrals-wallet-body-new">
                <div class="referrals-balance-display-new">
                    <div class="referrals-balance-amount-wrapper-new">
                        <span class="referrals-balance-value-new"
                            id="pendingEarningAmount">${{ number_format($pendingEarningCommissions ?? 0, 2) }}</span>
                        <div class="referrals-minimum-badge-new">Minimum $0.2</div>
                    </div>
                    @if(($pendingEarningCommissions ?? 0) < 0.2)
                        <div class="referrals-minimum-needed-new">${{ number_format(max(0, 0.2 - ($pendingEarningCommissions ?? 0)), 2) }}
                            more needed to claim</div>
                    @else
                        <div class="referrals-minimum-needed-new" style="color: #10B981;">Ready to claim!</div>
                    @endif
                </div>
                @if(isset($pendingEarningCommissionsByLevel) && count($pendingEarningCommissionsByLevel) > 0)
                    <div class="referrals-commission-breakdown-new">
                        <p style="margin: 0 0 0.75rem 0; font-size: 0.875rem; color: var(--text-secondary);">Breakdown by Level:
                        </p>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 0.75rem;">
                            @for($i = 1; $i <= 5; $i++)
                                <div style="text-align: center;">
                                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Level
                                        {{ $i }}</div>
                                    <div style="font-weight: 600; color: #FFB21E;">
                                        ${{ number_format($pendingEarningCommissionsByLevel[$i] ?? 0, 2) }}</div>
                                </div>
                            @endfor
                        </div>
                    </div>
                @endif
                <p class="referrals-claim-note-new">You can claim earning commission earnings when balance reaches $0.2 or more</p>
                <button class="referrals-claim-btn-new" id="claimEarningBtn" data-type="earning" {{ ($pendingEarningCommissions ?? 0) < 0.2 ? 'disabled' : '' }}>
                    <i class="fas fa-gift"></i>
                    <span>Claim Earning Commission</span>
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Claim Earnings functionality
            document.addEventListener('DOMContentLoaded', function () {
                // Handle Investment Commission Claim
                const claimInvestmentBtn = document.getElementById('claimInvestmentBtn');
                if (claimInvestmentBtn) {
                    claimInvestmentBtn.addEventListener('click', function () {
                        if (this.disabled) {
                            return;
                        }

                        const claimType = this.getAttribute('data-type');
                        claimEarnings(claimType, this);
                    });
                }

                // Handle Earning Commission Claim
                const claimEarningBtn = document.getElementById('claimEarningBtn');
                if (claimEarningBtn) {
                    claimEarningBtn.addEventListener('click', function () {
                        if (this.disabled) {
                            return;
                        }

                        const claimType = this.getAttribute('data-type');
                        claimEarnings(claimType, this);
                    });
                }

                function claimEarnings(type, button) {
                    // Disable button and show loading state
                    button.disabled = true;
                    const originalHTML = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Claiming...</span>';

                    // Make AJAX request
                    fetch('{{ route("referrals.claim") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            type: type
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success message
                                alert('Success! ' + data.message + ' Amount claimed: $' + data.claimed_amount);

                                // Reload page to update balances
                                window.location.reload();
                            } else {
                                // Show error message
                                alert('Error: ' + (data.message || 'Failed to claim earnings. Please try again.'));

                                // Re-enable button
                                button.disabled = false;
                                button.innerHTML = originalHTML;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred. Please try again.');

                            // Re-enable button
                            button.disabled = false;
                            button.innerHTML = originalHTML;
                        });
                }
            });
        </script>
    @endpush
@endsection

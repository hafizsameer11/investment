@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Support')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/support.css') }}">
<style>
    .support-new-page {
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Hero Section */
    .support-hero-new {
        text-align: center;
        padding: 3rem 2rem;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 24px;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
    }

    .support-hero-new::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #FFB21E 0%, #FF8A1D 50%, #FFB21E 100%);
        background-size: 200% 100%;
        animation: shimmer 3s linear infinite;
    }

    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .support-hero-new::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 178, 30, 0.08) 0%, transparent 70%);
        pointer-events: none;
    }

    .support-hero-content-new {
        position: relative;
        z-index: 1;
    }

    .support-hero-title-new {
        font-size: 3rem;
        font-weight: 700;
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 1rem 0;
        letter-spacing: -2px;
    }

    .support-hero-subtitle-new {
        font-size: 1.125rem;
        color: var(--text-secondary);
        margin: 0;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Support Channels Section */
    .support-channels-section-new {
        margin-bottom: 3rem;
    }

    .support-channels-header-new {
        margin-bottom: 2rem;
    }

    .support-channels-title-new {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .support-channels-subtitle-new {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .support-channels-grid-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
    }

    .support-channel-card-new {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
        transition: var(--transition);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
    }

    .support-channel-card-new::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .support-channel-card-new:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 32px rgba(255, 178, 30, 0.2);
        border-color: var(--primary-color);
    }

    .support-channel-card-new:hover::before {
        transform: scaleX(1);
    }

    .support-channel-header-new {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .support-channel-icon-wrapper-new {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        flex-shrink: 0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        position: relative;
        overflow: hidden;
    }

    .support-channel-icon-wrapper-new::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
        animation: icon-pulse 3s ease-in-out infinite;
    }

    @keyframes icon-pulse {
        0%, 100% { opacity: 0; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.2); }
    }

    .support-channel-icon-wrapper-new i {
        position: relative;
        z-index: 1;
    }

    .support-channel-icon-whatsapp-new {
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        color: white;
    }

    .support-channel-icon-phone-new {
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
        color: #000;
    }

    .support-channel-icon-facebook-new {
        background: linear-gradient(135deg, #1877F2 0%, #166FE5 100%);
        color: white;
    }

    .support-channel-icon-telegram-new {
        background: linear-gradient(135deg, #0088cc 0%, #0066aa 100%);
        color: white;
    }

    .support-channel-name-new {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .support-channel-items-new {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .support-channel-item-new {
        padding: 1.5rem;
        background: rgba(255, 178, 30, 0.05);
        border: 1px solid rgba(255, 178, 30, 0.2);
        border-radius: 16px;
        transition: var(--transition);
    }

    .support-channel-item-new:hover {
        background: rgba(255, 178, 30, 0.1);
        border-color: rgba(255, 178, 30, 0.4);
        transform: translateX(4px);
    }

    .support-item-header-new {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        gap: 1rem;
    }

    .support-item-label-new {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .support-item-value-new {
        font-size: 0.9375rem;
        color: var(--text-primary);
        font-weight: 500;
        word-break: break-all;
        line-height: 1.5;
        flex: 1;
        text-align: right;
    }

    .support-item-actions-new {
        display: flex;
        gap: 0.75rem;
    }

    .support-copy-btn-new {
        padding: 0.75rem 1.25rem;
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
        color: #000;
        border: none;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 16px rgba(255, 178, 30, 0.3);
        flex-shrink: 0;
    }

    .support-copy-btn-new:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(255, 178, 30, 0.4);
    }

    .support-copy-btn-new.copied {
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
    }

    .support-copy-btn-new.copied i::before {
        content: '\f00c';
    }

    /* Quick Actions Section */
    .support-quick-actions-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .support-quick-action-new {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
    }

    .support-quick-action-new::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .support-quick-action-new:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 32px rgba(255, 178, 30, 0.2);
        border-color: var(--primary-color);
    }

    .support-quick-action-new:hover::before {
        transform: scaleX(1);
    }

    .support-quick-action-icon-new {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.2) 0%, rgba(255, 138, 29, 0.1) 100%);
        border: 2px solid rgba(255, 178, 30, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--primary-color);
        margin: 0 auto 1.5rem;
        box-shadow: 0 0 30px rgba(255, 178, 30, 0.3);
    }

    .support-quick-action-content-new {
        flex: 1;
        text-align: left;
    }

    .support-quick-action-title-new {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .support-quick-action-desc-new {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
        line-height: 1.5;
    }

    /* Contact Info Section */
    .support-contact-section-new {
        display: none;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
        position: relative;
        overflow: hidden;
    }

    .support-contact-section-new::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
    }

    .support-contact-header-new {
        margin-bottom: 2rem;
    }

    .support-contact-title-new {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .support-contact-subtitle-new {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .support-contact-grid-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .support-contact-card-new {
        padding: 2rem;
        background: rgba(255, 178, 30, 0.05);
        border: 1px solid rgba(255, 178, 30, 0.2);
        border-radius: 16px;
        transition: var(--transition);
    }

    .support-contact-card-new:hover {
        background: rgba(255, 178, 30, 0.1);
        border-color: rgba(255, 178, 30, 0.4);
        transform: translateY(-3px);
    }

    .support-contact-icon-new {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.2) 0%, rgba(255, 138, 29, 0.1) 100%);
        border: 2px solid rgba(255, 178, 30, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: var(--primary-color);
        margin-bottom: 1.25rem;
        box-shadow: 0 0 20px rgba(255, 178, 30, 0.3);
    }

    .support-contact-label-new {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
        font-weight: 600;
    }

    .support-contact-value-new {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        word-break: break-all;
    }

    @media (max-width: 768px) {
        .support-new-page {
            padding: 1rem;
        }

        .support-hero-new {
            display: none;
        }

        .support-channels-grid-new {
            grid-template-columns: 1fr;
        }

        .support-quick-actions-new {
            grid-template-columns: 1fr;
        }

        .support-contact-grid-new {
            grid-template-columns: 1fr;
        }

        .support-item-header-new {
            flex-direction: column;
            align-items: flex-start;
        }

        .support-item-value-new {
            text-align: left;
        }

        /* Mobile: Quick Action Cards - Horizontal Layout */
        .support-quick-action-new {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            text-align: left;
            padding: 1.5rem;
        }

        .support-quick-action-icon-new {
            margin: 0;
            flex-shrink: 0;
            width: 60px;
            height: 60px;
            font-size: 1.75rem;
        }

        .support-quick-action-content-new {
            flex: 1;
            min-width: 0;
        }

        .support-quick-action-title-new {
            font-size: 1rem;
            margin-bottom: 0.375rem;
        }

        .support-quick-action-desc-new {
            font-size: 0.8125rem;
            line-height: 1.4;
        }

        /* Mobile: Contact Channels Section */
        .support-channels-header-new {
            display: none;
        }

        .support-channels-grid-new {
            gap: 1.5rem;
        }

        .support-channel-card-new {
            padding: 1.25rem;
            border-radius: 16px;
        }

        .support-channel-header-new {
            margin-bottom: 1.25rem;
        }

        .support-channel-icon-wrapper-new {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            font-size: 1.5rem;
        }

        .support-channel-name-new {
            font-size: 1.125rem;
        }

        .support-channel-items-new {
            gap: 0.875rem;
        }

        .support-channel-item-new {
            padding: 1rem;
            border-radius: 12px;
            background: rgba(255, 178, 30, 0.05);
            border: 1px solid rgba(255, 178, 30, 0.15);
        }

        .support-item-header-new {
            margin-bottom: 0.625rem;
            flex-direction: column;
            align-items: flex-start;
        }

        .support-item-label-new {
            font-size: 0.6875rem;
            margin-bottom: 0.5rem;
        }

        .support-item-value-new {
            font-size: 0.8125rem;
            margin-bottom: 0.875rem;
            word-break: break-word;
            text-align: left;
            line-height: 1.4;
        }

        .support-item-actions-new {
            width: 100%;
        }

        .support-copy-btn-new {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.8125rem;
            justify-content: center;
            border-radius: 10px;
        }
    }
</style>
@endpush

@section('content')
<div class="support-new-page">
    <!-- Hero Section -->
    <div class="support-hero-new">
        <div class="support-hero-content-new">
            <h1 class="support-hero-title-new">Help & Support</h1>
            <p class="support-hero-subtitle-new">Get in touch with our support team through multiple channels. We're here to help you 24/7</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="support-quick-actions-new">
        <div class="support-quick-action-new">
            <div class="support-quick-action-icon-new">
                <i class="fas fa-headset"></i>
            </div>
            <div class="support-quick-action-content-new">
                <h3 class="support-quick-action-title-new">24/7 Support</h3>
                <p class="support-quick-action-desc-new">Round-the-clock assistance for all your queries</p>
            </div>
        </div>
        <div class="support-quick-action-new">
            <div class="support-quick-action-icon-new">
                <i class="fas fa-comments"></i>
            </div>
            <div class="support-quick-action-content-new">
                <h3 class="support-quick-action-title-new">Live Chat</h3>
                <p class="support-quick-action-desc-new">Instant responses via WhatsApp and Facebook</p>
            </div>
        </div>
        {{-- <div class="support-quick-action-new">
            <div class="support-quick-action-icon-new">
                <i class="fas fa-phone-alt"></i>
            </div>
            <div class="support-quick-action-content-new">
                <h3 class="support-quick-action-title-new">Phone Support</h3>
                <p class="support-quick-action-desc-new">Direct phone line for urgent matters</p>
            </div>
        </div> --}}
    </div>

    <!-- Support Channels Section -->
    <div class="support-channels-section-new">
        <div class="support-channels-header-new">
            <h2 class="support-channels-title-new">Contact Channels</h2>
            <p class="support-channels-subtitle-new">Choose your preferred method to reach our support team</p>
        </div>

        <div class="support-channels-grid-new">
            <!-- WhatsApp Card -->
            <div class="support-channel-card-new">
                <div class="support-channel-header-new">
                    <div class="support-channel-icon-wrapper-new support-channel-icon-whatsapp-new">
                            <i class="fab fa-whatsapp"></i>
                    </div>
                    <h3 class="support-channel-name-new">WhatsApp</h3>
                </div>
                <div class="support-channel-items-new">
                    <div class="support-channel-item-new">
                        <div class="support-item-header-new">
                            <div class="support-item-label-new">WhatsApp Channel</div>
                        </div>
                        <div class="support-item-value-new" id="whatsappChannel">https://whatsapp.com/channel/0029VbBThps8qIzqfqkJIu0u</div>
                        <div class="support-item-actions-new">
                            <button class="support-copy-btn-new" data-copy="whatsappChannel" title="Copy">
                                <i class="fas fa-copy"></i>
                                <span>Copy</span>
                            </button>
                        </div>
                    </div>
                    {{-- <div class="support-channel-item-new">
                        <div class="support-item-header-new">
                            <div class="support-item-label-new">WhatsApp Number</div>
                        </div>
                        <div class="support-item-value-new" id="whatsappNumber">+16474986701</div>
                        <div class="support-item-actions-new">
                            <button class="support-copy-btn-new" data-copy="whatsappNumber" title="Copy">
                                <i class="fas fa-copy"></i>
                                <span>Copy</span>
                            </button>
                        </div>
                    </div> --}}
                </div>
            </div>

            <!-- Telegram Card -->
            <div class="support-channel-card-new">
                <div class="support-channel-header-new">
                    <div class="support-channel-icon-wrapper-new support-channel-icon-telegram-new">
                            <i class="fab fa-telegram"></i>
                    </div>
                    <h3 class="support-channel-name-new">Telegram</h3>
                </div>
                <div class="support-channel-items-new">
                    <div class="support-channel-item-new">
                        <div class="support-item-header-new">
                            <div class="support-item-label-new">Telegram Channel</div>
                        </div>
                        <div class="support-item-value-new" id="telegramChannel">https://t.me/+Wu5iCxAajHdjODNk</div>
                        <div class="support-item-actions-new">
                            <button class="support-copy-btn-new" data-copy="telegramChannel" title="Copy">
                                <i class="fas fa-copy"></i>
                                <span>Copy</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Info Section -->
    <div class="support-contact-section-new">
        <div class="support-contact-header-new">
            <h2 class="support-contact-title-new">All Contact Information</h2>
            <p class="support-contact-subtitle-new">Quick access to all support contact details</p>
        </div>
        <div class="support-contact-grid-new">
            <div class="support-contact-card-new">
                <div class="support-contact-icon-new">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div class="support-contact-label-new">WhatsApp Channel</div>
                <div class="support-contact-value-new">https://whatsapp.com/channel/0029VbC0KoL5kg73qAwejl3L</div>
            </div>
            <div class="support-contact-card-new">
                <div class="support-contact-icon-new">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="support-contact-label-new">WhatsApp Number</div>
                <div class="support-contact-value-new">+16474986701</div>
            </div>
            <div class="support-contact-card-new">
                <div class="support-contact-icon-new">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="support-contact-label-new">Support Phone</div>
                <div class="support-contact-value-new">+16474986701</div>
            </div>
            <div class="support-contact-card-new">
                <div class="support-contact-icon-new">
                    <i class="fab fa-facebook-f"></i>
                </div>
                <div class="support-contact-label-new">Facebook Page</div>
                <div class="support-contact-value-new">https://www.facebook.com/Licrownpvt/</div>
            </div>
            <div class="support-contact-card-new">
                <div class="support-contact-icon-new">
                    <i class="fab fa-facebook-messenger"></i>
                </div>
                <div class="support-contact-label-new">Facebook Contact</div>
                <div class="support-contact-value-new">licrownltd</div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/dashboard/js/support.js') }}"></script>
<script>
    // Copy functionality with visual feedback
    document.querySelectorAll('[data-copy]').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-copy');
            const input = document.getElementById(targetId);
            if (input) {
                const text = input.textContent || input.innerText;

                // Create temporary textarea for copying
                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);

                // Visual feedback
                const originalHTML = this.innerHTML;
                this.classList.add('copied');
                this.innerHTML = '<i class="fas fa-check"></i><span>Copied!</span>';

                setTimeout(() => {
                    this.classList.remove('copied');
                    this.innerHTML = originalHTML;
                }, 2000);
            }
        });
    });
</script>
@endpush
@endsection

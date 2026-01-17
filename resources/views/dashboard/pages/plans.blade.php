@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Mining Plans')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/plans.css') }}">
<style>
    .plans-new-page {
        padding: 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
        width: 100%;
        box-sizing: border-box;
        overflow-x: hidden;
    }

    /* Hero Section */
    .plans-hero-new {
        text-align: center;
        margin-bottom: 2rem;
        padding: 2rem 1.5rem;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .plans-hero-new::before {
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

    .plans-hero-new::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 178, 30, 0.08) 0%, transparent 70%);
        pointer-events: none;
    }

    .plans-hero-content-new {
        position: relative;
        z-index: 1;
    }

    .plans-hero-title-new {
        font-size: 2.25rem;
        font-weight: 700;
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 0.75rem 0;
        letter-spacing: -1px;
        line-height: 1.2;
    }

    .plans-hero-subtitle-new {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0 0 2rem 0;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.5;
    }

    .plans-hero-stats-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        max-width: 900px;
        margin: 0 auto;
    }

    .plans-hero-stat-new {
        padding: 1.5rem;
        background: rgba(255, 178, 30, 0.05);
        border: 1px solid rgba(255, 178, 30, 0.2);
        border-radius: 16px;
        transition: var(--transition);
        text-align: center;
    }

    .plans-hero-stat-new:hover {
        background: rgba(255, 178, 30, 0.1);
        border-color: rgba(255, 178, 30, 0.4);
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(255, 178, 30, 0.2);
    }

    .plans-hero-stat-label-new {
        font-size: 0.875rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .plans-hero-stat-value-new {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--primary-color);
        text-shadow: 0 0 20px rgba(255, 178, 30, 0.5);
        line-height: 1.2;
    }

    /* Main Plan Card */
    .plan-main-card-new {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .plan-main-card-new::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #FFB21E 0%, #FF8A1D 100%);
    }

    .plan-main-card-new::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 178, 30, 0.05) 0%, transparent 70%);
        pointer-events: none;
    }

    .plan-card-content-new {
        position: relative;
        z-index: 1;
    }

    .plan-header-new {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1.25rem;
    }

    .plan-header-left-new {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        flex: 1;
    }

    .plan-icon-large-new {
        width: 80px;
        height: 80px;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.2) 0%, rgba(255, 138, 29, 0.1) 100%);
        border: 2px solid rgba(255, 178, 30, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 20px rgba(255, 178, 30, 0.25);
        position: relative;
        overflow: hidden;
    }

    .plan-icon-large-new::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 178, 30, 0.3) 0%, transparent 70%);
        animation: rotate 4s linear infinite;
    }

    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .plan-icon-large-new svg {
        position: relative;
        z-index: 1;
        width: 40px;
        height: 40px;
        filter: drop-shadow(0 0 8px rgba(255, 178, 30, 0.6));
    }

    .plan-title-section-new {
        flex: 1;
    }

    .plan-badge-new {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.2) 0%, rgba(255, 138, 29, 0.15) 100%);
        border: 1px solid rgba(255, 178, 30, 0.4);
        border-radius: 16px;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.75rem;
        box-shadow: 0 0 12px rgba(255, 178, 30, 0.25);
    }

    .plan-name-new {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
        letter-spacing: -0.5px;
        line-height: 1.3;
    }

    .plan-tagline-new {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0;
        line-height: 1.5;
    }

    .plan-security-badge-new {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        background: rgba(255, 178, 30, 0.08);
        border: 1px solid rgba(255, 178, 30, 0.25);
        border-radius: 16px;
        margin-bottom: 1.5rem;
    }

    .plan-security-icon-new {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.2) 0%, rgba(255, 138, 29, 0.1) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 1rem;
        flex-shrink: 0;
    }

    .plan-security-text-new {
        flex: 1;
    }

    .plan-security-title-new {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
        line-height: 1.3;
    }

    .plan-security-desc-new {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        line-height: 1.4;
    }

    /* Mobile Layout Styles */
    .plan-mobile-layout-new {
        display: none;
    }

    .plan-desktop-layout-new {
        display: block;
    }

    /* Features Grid */
    .plan-features-grid-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .plan-feature-card-new {
        background: rgba(255, 178, 30, 0.05);
        border: 1px solid rgba(255, 178, 30, 0.2);
        border-radius: 12px;
        padding: 1.5rem;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .plan-feature-card-new::before {
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

    .plan-feature-card-new:hover {
        background: rgba(255, 178, 30, 0.1);
        border-color: rgba(255, 178, 30, 0.4);
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(255, 178, 30, 0.2);
    }

    .plan-feature-card-new:hover::before {
        transform: scaleX(1);
    }

    .plan-feature-card-new.highlight {
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.15) 0%, rgba(255, 138, 29, 0.1) 100%);
        border-color: rgba(255, 178, 30, 0.5);
        box-shadow: 0 0 30px rgba(255, 178, 30, 0.3);
    }

    .plan-feature-icon-new {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.2) 0%, rgba(255, 138, 29, 0.1) 100%);
        border: 1px solid rgba(255, 178, 30, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.375rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
        transition: var(--transition);
    }

    .plan-feature-card-new:hover .plan-feature-icon-new {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 4px 20px rgba(255, 178, 30, 0.4);
    }

    .plan-feature-card-new.highlight .plan-feature-icon-new {
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
        color: #000;
        box-shadow: 0 4px 20px rgba(255, 178, 30, 0.5);
    }

    .plan-feature-label-new {
        font-size: 0.75rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.625rem;
        font-weight: 600;
        line-height: 1.3;
    }

    .plan-feature-value-new {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-variant-numeric: tabular-nums;
        line-height: 1.2;
    }

    .plan-feature-card-new.highlight .plan-feature-value-new {
        color: var(--primary-color);
        text-shadow: 0 0 20px rgba(255, 178, 30, 0.5);
    }

    .plan-feature-hint-new {
        font-size: 0.75rem;
        color: var(--text-secondary);
        line-height: 1.4;
    }

    /* Calculator Section */
    .plan-calculator-section-new {
        background: rgba(255, 178, 30, 0.05);
        border: 1px solid rgba(255, 178, 30, 0.2);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        display: block;
    }

    .plan-calculator-section-new .plan-calculator-grid-new {
        display: grid;
    }

    .plan-calculator-header-new {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 0.875rem;
    }

    .plan-calculator-title-new {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        line-height: 1.3;
    }

    .plan-calculator-toggle-new {
        padding: 0.75rem 1.25rem;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 0.625rem;
    }

    .plan-calculator-toggle-new:hover {
        background: rgba(255, 178, 30, 0.1);
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .plan-calculator-grid-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
    }

    .plan-calculator-item-new {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
    }

    .plan-calculator-label-new {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-bottom: 0.625rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        line-height: 1.3;
    }

    .plan-calculator-value-new {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        font-variant-numeric: tabular-nums;
        line-height: 1.2;
    }

    .plan-calculator-note-new {
        font-size: 0.75rem;
        color: var(--text-secondary);
        line-height: 1.4;
    }

    /* Action Buttons */
    .plan-actions-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .plan-action-btn-new {
        padding: 1rem 1.5rem;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.625rem;
        position: relative;
        overflow: hidden;
    }

    .plan-action-btn-new::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .plan-action-btn-new:hover::before {
        width: 300px;
        height: 300px;
    }

    .plan-action-btn-new span,
    .plan-action-btn-new i {
        position: relative;
        z-index: 1;
    }

    .plan-action-primary-new {
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
        color: #000;
        box-shadow: 0 4px 20px rgba(255, 178, 30, 0.4);
    }

    .plan-action-primary-new:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 30px rgba(255, 178, 30, 0.6);
    }

    .plan-action-secondary-new {
        background: var(--card-bg);
        border: 2px solid var(--card-border);
        color: var(--text-primary);
    }

    .plan-action-secondary-new:hover {
        background: rgba(255, 178, 30, 0.1);
        border-color: var(--primary-color);
        box-shadow: 0 0 20px rgba(255, 178, 30, 0.3);
    }

    /* Benefits Section */
    .plan-benefits-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 0.875rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--card-border);
    }

    .plan-benefit-item-new {
        display: flex;
        align-items: center;
        gap: 0.875rem;
        padding: 0.875rem;
        background: rgba(255, 178, 30, 0.05);
        border: 1px solid rgba(255, 178, 30, 0.15);
        border-radius: 12px;
        transition: var(--transition);
    }

    .plan-benefit-item-new:hover {
        background: rgba(255, 178, 30, 0.1);
        border-color: rgba(255, 178, 30, 0.3);
        transform: translateX(4px);
    }

    .plan-benefit-icon-new {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.2) 0%, rgba(255, 138, 29, 0.1) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 1rem;
        flex-shrink: 0;
    }

    .plan-benefit-text-new {
        font-size: 0.875rem;
        color: var(--text-primary);
        font-weight: 500;
        line-height: 1.4;
    }

    /* Coming Soon Section */
    .plans-coming-soon-new {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 24px;
        padding: 3rem;
        text-align: center;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .coming-soon-title-new {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.75rem 0;
    }

    .coming-soon-subtitle-new {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0 0 2.5rem 0;
    }

    .coming-soon-cards-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        max-width: 800px;
        margin: 0 auto;
    }

    .coming-soon-card-new {
        background: rgba(255, 178, 30, 0.05);
        border: 1px solid rgba(255, 178, 30, 0.2);
        border-radius: 20px;
        padding: 2.5rem;
        transition: var(--transition);
        opacity: 0.7;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 1.5rem;
        text-align: left;
    }

    .coming-soon-card-new::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
        opacity: 0.5;
    }

    .coming-soon-card-new:hover {
        opacity: 1;
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(255, 178, 30, 0.2);
        border-color: rgba(255, 178, 30, 0.4);
    }

    .coming-soon-icon-new {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.2) 0%, rgba(255, 138, 29, 0.1) 100%);
        border: 2px solid rgba(255, 178, 30, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--primary-color);
        flex-shrink: 0;
    }

    .coming-soon-text-wrapper-new {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
        flex: 1;
    }

    .coming-soon-name-new {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
    }

    .coming-soon-status-new {
        font-size: 0.875rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .plans-new-page {
            padding: 1rem;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .plans-hero-new {
            padding: 1.5rem 1rem;
            margin-bottom: 1.5rem;
            border-radius: 16px;
        }

        .plans-hero-title-new {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .plans-hero-subtitle-new {
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }

        /* Mobile: Single Card with Horizontal Layout */
        .plans-hero-stats-new {
            display: flex;
            flex-direction: column;
            gap: 0;
            max-width: 500px;
            margin: 0 auto;
            padding: 1.5rem;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
        }

        .plans-hero-stat-new {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            background: transparent;
            border: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 0;
            text-align: left;
        }

        .plans-hero-stat-new:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .plans-hero-stat-new:first-child {
            padding-top: 0;
        }

        .plans-hero-stat-new:hover {
            background: transparent;
            border-color: transparent;
            transform: none;
            box-shadow: none;
        }

        .plans-hero-stat-label-new {
            font-size: 0.75rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            margin-bottom: 0;
            text-align: left;
            flex: 1;
        }

        .plans-hero-stat-value-new {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            text-shadow: 0 0 20px rgba(255, 178, 30, 0.5);
            text-align: right;
            margin-left: 1rem;
        }

        .plan-main-card-new {
            padding: 1.5rem 1rem;
            margin-bottom: 1.5rem;
            border-radius: 16px;
        }

        .plan-header-new {
            flex-direction: column;
            align-items: flex-start;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .plan-header-left-new {
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 1rem;
        }

        .plan-name-new {
            font-size: 1.375rem;
        }

        .plan-tagline-new {
            font-size: 0.875rem;
        }

        .plan-icon-large-new {
            width: 64px;
            height: 64px;
        }

        .plan-icon-large-new svg {
            width: 32px;
            height: 32px;
        }

        .plan-features-grid-new {
            grid-template-columns: 1fr;
            gap: 0.875rem;
            margin-bottom: 1.5rem;
        }

        .plan-feature-card-new {
            padding: 1.25rem;
        }

        .plan-feature-value-new {
            font-size: 1.25rem;
        }

        .plan-feature-icon-new {
            width: 48px;
            height: 48px;
            font-size: 1.25rem;
        }

        .plan-calculator-section-new {
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .plan-calculator-title-new {
            font-size: 1.125rem;
        }

        .plan-calculator-grid-new {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .plan-calculator-value-new {
            font-size: 1.25rem;
        }

        .plan-actions-new {
            grid-template-columns: 1fr;
            gap: 0.875rem;
            margin-bottom: 1.5rem;
        }

        .plan-action-btn-new {
            padding: 1rem 1.5rem;
            font-size: 0.9375rem;
        }

        .plan-benefits-new {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .plans-coming-soon-new {
            padding: 2rem 1.5rem;
            border-radius: 16px;
        }

        .coming-soon-cards-new {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .plans-new-page {
            padding: 0.75rem;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .plans-hero-new {
            padding: 1.25rem 0.875rem;
            margin-bottom: 1.25rem;
            border-radius: 16px;
        }

        .plans-hero-title-new {
            font-size: 1.375rem;
            margin-bottom: 0.5rem;
        }

        .plans-hero-subtitle-new {
            font-size: 0.8125rem;
            margin-bottom: 1.25rem;
        }

        .plans-hero-stats-new {
            flex-direction: column;
            gap: 0.75rem;
            padding: 1rem;
        }

        .plans-hero-stat-new {
            padding: 0.875rem 0;
        }

        .plans-hero-stat-value-new {
            font-size: 1.125rem;
        }

        .plans-hero-stat-label-new {
            font-size: 0.6875rem;
        }

        .plan-main-card-new {
            padding: 1.25rem 0.875rem;
            margin-bottom: 1.25rem;
            border-radius: 16px;
        }

        .plan-header-new {
            margin-bottom: 1.25rem;
        }

        .plan-header-left-new {
            gap: 0.875rem;
        }

        .plan-name-new {
            font-size: 1.25rem;
        }

        .plan-tagline-new {
            font-size: 0.8125rem;
        }

        .plan-icon-large-new {
            width: 56px;
            height: 56px;
        }

        .plan-icon-large-new svg {
            width: 28px;
            height: 28px;
        }

        .plan-security-badge-new {
            padding: 0.875rem;
            margin-bottom: 1.25rem;
        }

        .plan-security-title-new {
            font-size: 0.875rem;
        }

        .plan-security-desc-new {
            font-size: 0.75rem;
        }

        .plan-features-grid-new {
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .plan-feature-card-new {
            padding: 1rem;
        }

        .plan-feature-icon-new {
            width: 44px;
            height: 44px;
            font-size: 1.125rem;
            margin-bottom: 0.875rem;
        }

        .plan-feature-label-new {
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .plan-feature-value-new {
            font-size: 1.125rem;
        }

        .plan-feature-hint-new {
            font-size: 0.75rem;
        }

        .plan-calculator-section-new {
            padding: 1rem;
            margin-bottom: 1.25rem;
        }

        .plan-calculator-header-new {
            flex-direction: column;
            align-items: stretch;
            gap: 0.875rem;
            margin-bottom: 1.25rem;
        }

        .plan-calculator-title-new {
            font-size: 1rem;
        }

        .plan-calculator-toggle-new {
            width: 100%;
            justify-content: center;
            padding: 0.75rem 1.25rem;
            font-size: 0.875rem;
        }

        .plan-calculator-value-new {
            font-size: 1.125rem;
        }

        .plan-action-btn-new {
            padding: 0.875rem 1.25rem;
            font-size: 0.875rem;
        }

        .plans-coming-soon-new {
            padding: 1.5rem 1rem;
            border-radius: 12px;
        }

        .coming-soon-title-new {
            font-size: 1.5rem;
        }

        .coming-soon-subtitle-new {
            font-size: 0.875rem;
        }

        .coming-soon-card-new {
            padding: 1.5rem;
        }

        .coming-soon-icon-new {
            width: 64px;
            height: 64px;
            font-size: 1.5rem;
        }

        .coming-soon-name-new {
            font-size: 1.25rem;
        }
    }

    @media (max-width: 400px) {
        .plans-new-page {
            padding: 0;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .plans-hero-new {
            padding: 1.25rem 0.875rem;
            margin-bottom: 1.25rem;
            border-radius: 12px;
        }

        .plans-hero-title-new {
            font-size: 1.5rem;
            letter-spacing: -1px;
        }

        .plans-hero-subtitle-new {
            font-size: 0.8125rem;
        }

        .plans-hero-stats-new {
            padding: 1rem;
        }

        .plans-hero-stat-new {
            padding: 0.75rem 0;
        }

        .plans-hero-stat-value-new {
            font-size: 1.25rem;
        }

        .plans-hero-stat-label-new {
            font-size: 0.6875rem;
        }

        .plans-hero-stat-label-new {
            font-size: 0.75rem;
        }

        .plan-main-card-new {
            padding: 1.25rem 0.875rem;
            margin-bottom: 1.25rem;
            border-radius: 12px;
        }

        .plan-header-new {
            margin-bottom: 1.25rem;
            gap: 1rem;
        }

        .plan-header-left-new {
            gap: 0.875rem;
        }

        .plan-badge-new {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
        }

        .plan-name-new {
            font-size: 1.375rem;
        }

        .plan-tagline-new {
            font-size: 0.9375rem;
        }

        .plan-icon-large-new {
            width: 60px;
            height: 60px;
        }

        .plan-icon-large-new svg {
            width: 32px;
            height: 32px;
        }

        .plan-security-badge-new {
            padding: 0.875rem;
            margin-bottom: 1.25rem;
        }

        .plan-security-icon-new {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .plan-features-grid-new {
            gap: 0.75rem;
        }

        .plan-feature-card-new {
            padding: 1.25rem;
        }

        .plan-feature-icon-new {
            width: 56px;
            height: 56px;
            font-size: 1.5rem;
        }

        .plan-feature-value-new {
            font-size: 1.5rem;
        }

        .plan-calculator-section-new {
            padding: 1rem;
        }

        .plan-calculator-title-new {
            font-size: 1.25rem;
        }

        .plan-calculator-grid-new {
            gap: 1rem;
        }

        .plan-calculator-item-new {
            padding: 1.25rem;
        }

        .plan-calculator-value-new {
            font-size: 1.75rem;
        }

        .plan-actions-new {
            gap: 0.875rem;
        }

        .plan-action-btn-new {
            padding: 1rem 1.25rem;
            font-size: 0.875rem;
        }

        .plan-benefits-new {
            gap: 0.75rem;
        }

        .plan-benefit-item-new {
            padding: 0.875rem;
        }

        .plan-benefit-icon-new {
            width: 36px;
            height: 36px;
            font-size: 1rem;
        }

        .plans-coming-soon-new {
            padding: 1.25rem 0.875rem;
            border-radius: 12px;
        }

        .coming-soon-title-new {
            font-size: 1.375rem;
        }

        .coming-soon-subtitle-new {
            font-size: 0.8125rem;
        }

        .coming-soon-cards-new {
            gap: 1rem;
        }

        .coming-soon-card-new {
            padding: 1.25rem;
            flex-direction: row;
            align-items: center;
            text-align: left;
            gap: 1rem;
        }

        .coming-soon-icon-new {
            width: 56px;
            height: 56px;
            font-size: 1.25rem;
            flex-shrink: 0;
            margin: 0;
            border-radius: 16px;
            align-self: flex-start;
        }

        .coming-soon-text-wrapper-new {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
        }

        .coming-soon-name-new {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 0.25rem 0;
        }

        .coming-soon-status-new {
            margin: 0;
            font-size: 0.75rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Mobile: Show mobile layout, hide desktop layout */
        .plan-mobile-layout-new {
            display: block;
        }

        .plan-desktop-layout-new {
            display: none;
        }

        .plan-mobile-header-new {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            margin-bottom: 0.875rem;
        }

        .plan-mobile-icon-wrapper-new {
            flex-shrink: 0;
        }

        .plan-mobile-icon-wrapper-new .plan-icon-large-new {
            width: 48px;
            height: 48px;
            border-radius: 12px;
        }

        .plan-mobile-icon-wrapper-new .plan-icon-large-new svg {
            width: 24px;
            height: 24px;
        }

        .plan-mobile-title-section-new {
            flex: 1;
        }

        .plan-mobile-name-new {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 0.25rem 0;
            line-height: 1.3;
        }

        .plan-mobile-subtitle-new {
            font-size: 0.8125rem;
            color: var(--text-secondary);
            margin: 0;
            line-height: 1.4;
        }

        .plan-mobile-policy-new {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--success-color);
            margin-bottom: 1rem;
            padding: 0.625rem 0;
        }

        .plan-mobile-details-new {
            display: flex;
            gap: 0.875rem;
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .plan-mobile-detail-col-new {
            flex: 1;
        }

        .plan-mobile-detail-label-new {
            font-size: 0.6875rem;
            color: var(--text-secondary);
            margin-bottom: 0.375rem;
            line-height: 1.3;
        }

        .plan-mobile-detail-value-new {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.3;
        }

        /* Mobile: Hide calculator section by default, show when toggled */
        .plan-calculator-section-new {
            display: none;
            margin-top: 1.5rem;
            padding: 1.25rem;
        }

        .plan-calculator-section-new.show {
            display: block;
        }

        .plan-calculator-section-new.show .plan-calculator-grid-new {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-top: 1rem;
        }

        /* Mobile: Button styles */
        .plan-actions-new {
            display: flex;
            gap: 0.75rem;
        }

        .plan-action-btn-new {
            flex: 1;
            padding: 1rem;
            font-size: 0.875rem;
        }

        .plan-action-secondary-new {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: var(--text-primary);
        }

        /* Hide benefits section on mobile */
        .plan-benefits-desktop-new {
            display: none;
        }

        /* Hide desktop header section on mobile */
        .plan-header-new {
            display: none;
        }
    }

    /* Investment Calculator Modal Styles */
    .calculator-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 9999;
        display: none;
        align-items: flex-end;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .calculator-modal-overlay.show {
        display: flex;
        opacity: 1;
    }

    .calculator-modal {
        background: #181b27;
        border: 1px solid var(--card-border);
        border-radius: 24px 24px 0 0;
        width: 100%;
        max-width: 100%;
        height: 50vh;
        
        min-height: 50vh;
        max-height: 50vh;
        overflow: hidden;
        box-shadow: 0 -8px 32px rgba(0, 0, 0, 0.6);
        position: relative;
        transform: translateY(100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }

    .calculator-modal-overlay.show .calculator-modal {
        transform: translateY(0);
    }

    .calculator-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--card-border);
        flex-shrink: 0;
    }

    .calculator-modal-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .calculator-modal-close {
        background: transparent;
        border: none;
        color: var(--text-primary);
        font-size: 1.1rem;
        cursor: pointer;
        padding: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: var(--transition);
        width: 32px;
        height: 32px;
    }

    .calculator-modal-close:hover {
        background: rgba(255, 178, 30, 0.1);
        color: var(--primary-color);
    }

    .calculator-modal-body {
        padding: 1.25rem;
        flex: 1;
        overflow-y: auto;
        min-height: 0;
        overflow-x: hidden;
    }

    /* Investment modal body specific styles */
    #investmentModalOverlay .calculator-modal-body {
        padding: 1.25rem;
        overflow-y: auto;
        overflow-x: hidden;
        -webkit-overflow-scrolling: touch;
    }

    .calculator-plan-section {
        margin-bottom: 1.5rem;
    }

    .calculator-plan-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.375rem 0;
    }

    .calculator-plan-description {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .calculator-details-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
        width: 100%;
    }

    .calculator-detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.375rem;
    }

    .calculator-detail-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .calculator-detail-value {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .calculator-input-section {
        margin-bottom: 1rem;
    }

    .calculator-input-label {
        display: block;
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .calculator-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        background: rgba(24, 27, 39, 0.8);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 0.75rem 1rem;
        transition: var(--transition);
    }

    .calculator-input-wrapper:focus-within {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(255, 178, 30, 0.1);
    }

    .calculator-input-prefix {
        color: var(--text-primary);
        font-weight: 600;
        margin-right: 0.5rem;
        font-size: 0.875rem;
    }

    .calculator-input {
        flex: 1;
        background: transparent;
        border: none;
        color: var(--text-primary);
        font-size: 0.875rem;
        outline: none;
        padding: 0;
    }

    .calculator-input::placeholder {
        color: var(--text-muted);
    }

    .calculator-input-action {
        border: none;
        cursor: default;
        padding: 0.375rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: var(--transition);
        margin-left: 0.5rem;
        width: 28px;
        height: 28px;
        pointer-events: none;
    }

    .calculator-input-action:first-of-type {
        background: rgba(255, 193, 7, 0.2);
        color: #FFC107;
    }

    .calculator-input-action:last-of-type {
        background: rgba(68, 162, 210, 0.2);
        color: #44a2d2;
    }

    /* Investment Details Card */
    .calculator-details-card {
        background: rgba(24, 27, 39, 0.6);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .calculator-card-title {
        font-size: 0.9375rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 1rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .calculator-trend-icon {
        color: #10b981;
        font-size: 0.875rem;
    }

    .calculator-details-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .calculator-detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .calculator-detail-row-label {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .calculator-detail-row-value {
        font-size: 0.8125rem;
        color: var(--text-primary);
        font-weight: 600;
    }

    .calculator-profit-value {
        color: #10b981 !important;
        font-weight: 700;
    }

    /* Profit Breakdown Grid */
    .calculator-profit-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .calculator-profit-item {
        display: flex;
        flex-direction: column;
        gap: 0.375rem;
    }

    .calculator-profit-icon {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
    }

    .calculator-profit-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .calculator-modal-footer {
        display: flex;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        border-top: 1px solid var(--card-border);
        flex-shrink: 0;
        background: #181b27;
        position: relative;
        z-index: 10;
    }

    .calculator-btn {
        flex: 1;
        padding: 0.75rem 1.25rem;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
    }

    .calculator-btn-reset {
        background: rgba(40, 40, 40, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #FFFFFF;
    }

    .calculator-btn-reset:hover {
        background: rgba(50, 50, 50, 0.9);
        border-color: rgba(255, 255, 255, 0.2);
    }

    .calculator-btn-close {
        background: #FF69B4;
        color: #FFFFFF;
        box-shadow: 0 4px 16px rgba(255, 105, 180, 0.3);
        border: none;
    }

    .calculator-btn-close:hover {
        background: #FF1493;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 105, 180, 0.4);
    }


    /* Mobile Responsive */
    @media (max-width: 768px) {
        .calculator-modal {
            max-width: 100%;
            border-radius: 20px 20px 0 0;
            height: 50vh;
            min-height: 50vh;
            max-height: 50vh;
        }

        .calculator-modal-header {
            padding: 1rem 1.25rem;
        }

        .calculator-modal-title {
            font-size: 0.9375rem;
        }

        .calculator-modal-body {
            padding: 1.25rem;
        }

        .calculator-plan-name {
            font-size: 1.125rem;
        }

        .calculator-plan-description {
            font-size: 0.8125rem;
        }

        .calculator-details-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.875rem;
            margin-bottom: 1.25rem;
        }

        .calculator-detail-label {
            font-size: 0.6875rem;
        }

        .calculator-detail-value {
            font-size: 0.8125rem;
        }

        .calculator-input-label {
            font-size: 0.6875rem;
        }

        .calculator-input {
            font-size: 0.8125rem;
        }

        .calculator-details-card {
            padding: 0.875rem;
        }

        .calculator-card-title {
            font-size: 0.875rem;
        }

        .calculator-detail-row-label,
        .calculator-detail-row-value {
            font-size: 0.75rem;
        }

        .calculator-profit-grid {
            gap: 0.625rem;
        }

        .calculator-profit-label,
        .calculator-profit-value {
            font-size: 0.6875rem;
        }

        .calculator-modal-footer {
            padding: 1rem 1.25rem;
            flex-direction: row;
        }

        .calculator-btn {
            font-size: 0.8125rem;
            padding: 0.6875rem 1rem;
        }
    }

    @media (max-width: 480px) {
        .calculator-modal {
            border-radius: 16px 16px 0 0;
            height: 50vh;
            min-height: 50vh;
            max-height: 50vh;
        }

        .calculator-modal-header {
            padding: 0.875rem 1rem;
        }

        .calculator-modal-title {
            font-size: 0.875rem;
        }

        .calculator-modal-body {
            padding: 1rem;
        }

        .calculator-plan-name {
            font-size: 1rem;
        }

        .calculator-plan-description {
            font-size: 0.75rem;
        }

        .calculator-details-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .calculator-detail-label {
            font-size: 0.625rem;
        }

        .calculator-detail-value {
            font-size: 0.75rem;
        }

        .calculator-input-label {
            font-size: 0.625rem;
        }

        .calculator-input {
            font-size: 0.75rem;
        }

        .calculator-details-card {
            padding: 0.75rem;
        }

        .calculator-card-title {
            font-size: 0.8125rem;
        }

        .calculator-detail-row-label,
        .calculator-detail-row-value {
            font-size: 0.6875rem;
        }

        .calculator-profit-grid {
            gap: 0.5rem;
        }

        .calculator-profit-label,
        .calculator-profit-value {
            font-size: 0.625rem;
        }

        .calculator-modal-footer {
            padding: 0.875rem 1rem;
        }

        .calculator-btn {
            font-size: 0.75rem;
            padding: 0.625rem 0.875rem;
        }
    }

    /* Investment Modal - Desktop Styles */
    .investment-modal {
        max-width: 500px;
        width: 100%;
        height: auto;
        max-height: 90vh;
        border-radius: 24px;
        margin: auto;
    }

    .investment-modal-body {
        padding: 2rem;
    }

    /* Alert Message */
    .investment-alert {
        background: var(--warning-color);
        border: 1px solid var(--warning-color);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: var(--text-primary);
    }

    .investment-alert i {
        font-size: 1.25rem;
    }

    .investment-alert span {
        flex: 1;
    }

    .investment-deposit-btn {
        background: var(--text-primary);
        color: var(--warning-color);
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.875rem;
        transition: var(--transition);
    }

    .investment-deposit-btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    /* Plan Name Section */
    .investment-plan-name-section {
        margin-bottom: 1.5rem;
    }

    .investment-plan-name-text {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .investment-plan-name-highlight {
        color: var(--primary-color);
    }

    /* Investment Range Section */
    .investment-range-section {
        margin-bottom: 1.5rem;
    }

    .investment-range-amount {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }

    .investment-range-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    /* Principal Return Policy */
    .investment-principal-policy {
        color: var(--success-color);
        margin-bottom: 1.5rem;
        font-weight: 500;
        font-size: 0.9375rem;
    }

    /* Balances Section */
    .investment-balances-section {
        margin-bottom: 1.5rem;
    }

    .investment-balances-title {
        font-weight: 600;
        font-size: 1rem;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .investment-balances-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .investment-balance-card {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        border-radius: 12px;
        border: 1px solid var(--card-border);
        background: var(--card-bg);
        transition: var(--transition);
    }

    .investment-balance-card:hover {
        background: var(--card-bg-hover);
        border-color: var(--card-border-hover);
    }

    .investment-balance-fund i {
        font-size: 1.5rem;
        color: var(--success-color);
    }

    .investment-balance-earning i {
        font-size: 1.5rem;
        color: var(--info-color);
    }

    .investment-balance-info {
        flex: 1;
    }

    .investment-balance-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
    }

    .investment-balance-amount {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Pay From Section */
    .investment-pay-from-section {
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 1;
    }

    .investment-pay-from-label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
        font-size: 0.9375rem;
    }

    .investment-select-wrapper {
        position: relative;
        width: 100%;
    }

    .investment-select {
        width: 100%;
        padding: 0.75rem 2.5rem 0.75rem 1rem;
        border-radius: 8px;
        border: 1px solid var(--card-border);
        background: var(--card-bg);
        color: var(--text-primary);
        font-size: 1rem;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        cursor: pointer;
        transition: var(--transition);
    }

    .investment-select:hover {
        border-color: var(--card-border-hover);
    }

    .investment-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(255, 178, 30, 0.1);
    }

    .investment-select-arrow {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    /* Investment Amount Section */
    .investment-amount-section {
        margin-bottom: 1.5rem;
    }

    .investment-amount-label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
        font-size: 0.9375rem;
    }

    .investment-input-wrapper {
        position: relative;
        width: 100%;
    }

    .investment-input-prefix {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 1rem;
        z-index: 1;
    }

    .investment-amount-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border-radius: 8px;
        border: 1px solid var(--card-border);
        background: var(--card-bg);
        color: var(--text-primary);
        font-size: 1rem;
        transition: var(--transition);
    }

    .investment-amount-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(255, 178, 30, 0.1);
    }

    .investment-amount-input::placeholder {
        color: var(--text-muted);
    }

    .investment-amount-hint {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-top: 0.5rem;
    }

    /* Modal Footer Buttons */
    .investment-modal-footer {
        display: flex;
        gap: 1rem;
        padding: 1.5rem 2rem;
    }

    .investment-btn {
        flex: 1;
        padding: 0.875rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: var(--transition);
        border: none;
    }

    .investment-btn-cancel {
        background: var(--card-bg);
        color: var(--text-primary);
        border: 1px solid var(--card-border);
    }

    .investment-btn-cancel:hover {
        background: var(--card-bg-hover);
        border-color: var(--card-border-hover);
    }

    .investment-btn-confirm {
        background: linear-gradient(135deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
        color: var(--text-primary);
    }

    .investment-btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg), var(--glow-primary);
    }

    .investment-btn-confirm:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* Fix dropdown positioning on mobile */
    #investmentModalOverlay .calculator-modal-body {
        position: relative;
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* Select dropdown container */
    #sourceBalanceSelect {
        position: relative;
        z-index: 1;
        width: 100%;
        box-sizing: border-box;
    }

    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .investment-modal {
            max-width: 100%;
            height: 85vh;
            min-height: 85vh;
            max-height: 85vh;
            border-radius: 24px 24px 0 0;
        }

        .investment-modal-body {
            padding: 1.5rem;
            padding-bottom: 2rem;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .investment-range-amount {
            font-size: 1.5rem;
        }

        .investment-balances-grid {
            gap: 0.75rem;
        }

        .investment-balance-card {
            padding: 0.875rem;
        }

        .investment-select {
            font-size: 0.9375rem;
            padding: 0.625rem 2rem 0.625rem 0.875rem;
        }

        .investment-amount-input {
            font-size: 0.9375rem;
            padding: 0.625rem 0.875rem 0.625rem 2.25rem;
        }

        .investment-modal-footer {
            padding: 1rem 1.5rem;
        }

        .investment-btn {
            font-size: 0.875rem;
            padding: 0.75rem 1rem;
        }
    }

    @media (max-width: 480px) {
        .investment-modal {
            height: 90vh;
            min-height: 90vh;
            max-height: 90vh;
        }

        .investment-modal-body {
            padding: 1.25rem;
            -webkit-overflow-scrolling: touch;
        }

        .investment-plan-name-text {
            font-size: 1.25rem;
        }

        .investment-range-amount {
            font-size: 1.25rem;
        }

        .investment-balances-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .investment-select {
            font-size: 0.875rem;
        }

        .investment-amount-input {
            font-size: 0.875rem;
        }
    }
</style>
@endpush

@section('content')
<div class="plans-new-page">
    <!-- Hero Section -->
    <div class="plans-hero-new">
        <div class="plans-hero-content-new">
            <h1 class="plans-hero-title-new">Mining Plans</h1>
            <p class="plans-hero-subtitle-new">Choose your mining plan and start earning cryptocurrency rewards with automated 24/7 mining operations</p>
            <div class="plans-hero-stats-new">
                <div class="plans-hero-stat-new">
                    <div class="plans-hero-stat-label-new">Daily ROI</div>
                    <div class="plans-hero-stat-value-new">3/4%</div>
                </div>
                <div class="plans-hero-stat-new">
                    <div class="plans-hero-stat-label-new">Minimum Investment</div>
                    <div class="plans-hero-stat-value-new">$2</div>
                </div>
                <div class="plans-hero-stat-new">
                    <div class="plans-hero-stat-label-new">Active Mining</div>
                    <div class="plans-hero-stat-value-new">24/7</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Plan Cards -->
    @forelse($plans as $plan)
    <div class="plan-main-card-new" data-plan-id="{{ $plan->id }}">
        <div class="plan-card-content-new">
            <div class="plan-header-new">
                <div class="plan-header-left-new">
                    <div class="plan-icon-large-new">
                        @if($plan->icon_class)
                            <i class="{{ $plan->icon_class }}"></i>
                        @else
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L8 6L10 8L6 12L8 14L12 10L16 14L18 12L14 8L16 6L12 2Z" fill="url(#planGradient{{ $plan->id }})" stroke="#FFB21E" stroke-width="1.5" stroke-linejoin="round"/>
                                <rect x="4" y="16" width="4" height="4" rx="1" fill="#FFB21E" opacity="0.6"/>
                                <rect x="10" y="18" width="4" height="4" rx="1" fill="#FF8A1D" opacity="0.6"/>
                                <rect x="16" y="16" width="4" height="4" rx="1" fill="#FFB21E" opacity="0.6"/>
                                <defs>
                                    <linearGradient id="planGradient{{ $plan->id }}" x1="12" y1="2" x2="12" y2="14" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#FFB21E"/>
                                        <stop offset="100%" stop-color="#FF8A1D"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        @endif
                    </div>
                    <div class="plan-title-section-new">
                        <div class="plan-badge-new">
                            <i class="fas fa-star"></i>
                            <span>Mining Plan</span>
                        </div>
                        <h2 class="plan-name-new">{{ $plan->name }}</h2>
                        <p class="plan-tagline-new">{{ $plan->tagline ?? ($plan->subtitle ?? 'Advanced Mining Plan for Maximum Returns') }}</p>
                    </div>
            </div>
        </div>

        <!-- Mobile Layout -->
        <div class="plan-mobile-layout-new">
            <!-- Mobile Header: Icon + Title + Subtitle -->
            <div class="plan-mobile-header-new">
                <div class="plan-mobile-icon-wrapper-new">
                    <div class="plan-icon-large-new">
                        @if($plan->icon_class)
                            <i class="{{ $plan->icon_class }}"></i>
                        @else
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L8 6L10 8L6 12L8 14L12 10L16 14L18 12L14 8L16 6L12 2Z" fill="url(#planGradientMobile{{ $plan->id }})" stroke="#FFB21E" stroke-width="1.5" stroke-linejoin="round"/>
                                <rect x="4" y="16" width="4" height="4" rx="1" fill="#FFB21E" opacity="0.6"/>
                                <rect x="10" y="18" width="4" height="4" rx="1" fill="#FF8A1D" opacity="0.6"/>
                                <rect x="16" y="16" width="4" height="4" rx="1" fill="#FFB21E" opacity="0.6"/>
                                <defs>
                                    <linearGradient id="planGradientMobile{{ $plan->id }}" x1="12" y1="2" x2="12" y2="14" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#FFB21E"/>
                                        <stop offset="100%" stop-color="#FF8A1D"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        @endif
                    </div>
                </div>
                <div class="plan-mobile-title-section-new">
                    <h2 class="plan-mobile-name-new">{{ $plan->name }}</h2>
                    <p class="plan-mobile-subtitle-new">{{ $plan->subtitle ?? 'Earn through mining' }}</p>
                </div>
            </div>

            <!-- Policy Text -->
            <div class="plan-mobile-policy-new">
                Principal Return Policy Will Be Returned
            </div>

            <!-- Investment Details: Two Columns -->
            <div class="plan-mobile-details-new">
                <div class="plan-mobile-detail-col-new">
                    <div class="plan-mobile-detail-label-new">Range</div>
                    <div class="plan-mobile-detail-value-new">${{ number_format($plan->min_investment, 2) }} - ${{ number_format($plan->max_investment, 2) }}</div>
                </div>
                <div class="plan-mobile-detail-col-new">
                    <div class="plan-mobile-detail-label-new">ROI {{ $plan->daily_roi_min }}% - {{ $plan->daily_roi_max }}% Daily</div>
                    <div class="plan-mobile-detail-value-new">{{ $plan->hourly_rate ?? 0 }}% / Hourly</div>
                </div>
            </div>
        </div>

        <!-- Desktop: Original Layout -->
        <div class="plan-desktop-layout-new">
            <!-- Security Badge -->
            <div class="plan-security-badge-new">
                <div class="plan-security-icon-new">
                <i class="fas fa-shield-alt"></i>
                </div>
                <div class="plan-security-text-new">
                    <div class="plan-security-title-new">Principal Return Guarantee</div>
                    <div class="plan-security-desc-new">Your initial investment will be returned at the end of the plan period</div>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="plan-features-grid-new">
                <div class="plan-feature-card-new">
                    <div class="plan-feature-icon-new">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="plan-feature-label-new">Investment Range</div>
                    <div class="plan-feature-value-new">${{ number_format($plan->min_investment, 2) }} - ${{ number_format($plan->max_investment, 2) }}</div>
                    <div class="plan-feature-hint-new">Minimum investment: ${{ number_format($plan->min_investment, 2) }}</div>
                </div>

                <div class="plan-feature-card-new highlight">
                    <div class="plan-feature-icon-new">
                    <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="plan-feature-label-new">Daily ROI</div>
                    <div class="plan-feature-value-new">{{ $plan->daily_roi_min }}% - {{ $plan->daily_roi_max }}%</div>
                    <div class="plan-feature-hint-new">Fixed daily returns guaranteed</div>
                </div>

                <div class="plan-feature-card-new">
                    <div class="plan-feature-icon-new">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="plan-feature-label-new">Hourly Rate</div>
                    <div class="plan-feature-value-new">{{ $plan->hourly_rate ?? 0 }}%</div>
                    <div class="plan-feature-hint-new">Per hour earnings</div>
                </div>
            </div>
        </div>

            <!-- Calculator Section (Hidden by default on mobile, shown when button clicked) -->
            <div class="plan-calculator-section-new" id="calculatorSection{{ $plan->id }}">
                <div class="plan-calculator-header-new">
                    <h3 class="plan-calculator-title-new">Estimated Returns</h3>
                    <button class="plan-calculator-toggle-new calculator-toggle" data-plan-id="{{ $plan->id }}">
                        <i class="fas fa-calculator"></i>
                        <span>Open Calculator</span>
                    </button>
                </div>
                <div class="plan-calculator-grid-new" id="calculatorContent{{ $plan->id }}">
                    <div class="plan-calculator-item-new">
                        <div class="plan-calculator-label-new">Daily Earnings</div>
                        <div class="plan-calculator-value-new">$0</div>
                        <div class="plan-calculator-note-new">Based on $100 investment</div>
                    </div>
                    <div class="plan-calculator-item-new">
                        <div class="plan-calculator-label-new">Monthly Earnings</div>
                        <div class="plan-calculator-value-new">$0</div>
                        <div class="plan-calculator-note-new">30 days projection</div>
                </div>
            </div>
        </div>

            <!-- Action Buttons -->
            <div class="plan-actions-new">
                <button class="plan-action-btn-new plan-action-primary-new start-investing-btn" data-plan-id="{{ $plan->id }}">
                    <i class="fas fa-rocket"></i>
                    <span>Start Investing</span>
                </button>
                <button class="plan-action-btn-new plan-action-secondary-new open-calculator-btn" data-plan-id="{{ $plan->id }}" data-plan-name="{{ $plan->name }}" data-plan-subtitle="{{ $plan->subtitle ?? 'Earn through mining' }}" data-min-investment="{{ $plan->min_investment }}" data-max-investment="{{ $plan->max_investment }}" data-daily-roi-min="{{ $plan->daily_roi_min }}" data-daily-roi-max="{{ $plan->daily_roi_max }}" data-hourly-rate="{{ $plan->hourly_rate ?? 0 }}">
                    <i class="fas fa-calculator"></i>
                    <span>Investment Calculator</span>
                </button>
            </div>

            <!-- Benefits Section (Desktop only) -->
            <div class="plan-benefits-new plan-benefits-desktop-new">
                <div class="plan-benefit-item-new">
                    <div class="plan-benefit-icon-new">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="plan-benefit-text-new">24/7 automated mining</div>
                </div>
                <div class="plan-benefit-item-new">
                    <div class="plan-benefit-icon-new">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="plan-benefit-text-new">Instant withdrawals</div>
                </div>
                <div class="plan-benefit-item-new">
                    <div class="plan-benefit-icon-new">
                        <i class="fas fa-check-circle"></i>
            </div>
                    <div class="plan-benefit-text-new">Principal protection</div>
        </div>
                <div class="plan-benefit-item-new">
                    <div class="plan-benefit-icon-new">
                <i class="fas fa-check-circle"></i>
            </div>
                    <div class="plan-benefit-text-new">Real-time mining tracking</div>
            </div>
            </div>
        </div>
    </div>
    @empty
    <div class="plan-main-card-new">
        <div class="plan-card-content-new">
            <div style="text-align: center; padding: 3rem;">
                <h2 style="color: var(--text-primary); margin-bottom: 1rem;">No Mining Plans Available</h2>
                <p style="color: var(--text-secondary);">Please check back later for available mining plans.</p>
            </div>
        </div>
    </div>
    @endforelse

    <!-- Coming Soon Section -->
    @if(isset($inactivePlans) && $inactivePlans->count() > 0)
    <div class="plans-coming-soon-new">
        <h3 class="coming-soon-title-new">More Plans Coming Soon</h3>
        <p class="coming-soon-subtitle-new">We're working on additional mining plan options for you</p>
        <div class="coming-soon-cards-new">
            @foreach($inactivePlans as $inactivePlan)
            <div class="coming-soon-card-new">
                <div class="coming-soon-icon-new">
                    @if($inactivePlan->icon_class)
                        <i class="{{ $inactivePlan->icon_class }}"></i>
                    @else
                        <i class="fas fa-gem"></i>
                    @endif
                </div>
                <div class="coming-soon-text-wrapper-new">
                    <div class="coming-soon-name-new">{{ $inactivePlan->name }}</div>
                    <div class="coming-soon-status-new">COMING SOON</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Investment Calculator Modal -->
    <div class="calculator-modal-overlay" id="calculatorModalOverlay">
        <div class="calculator-modal">
            <div class="calculator-modal-header">
                <h3 class="calculator-modal-title" id="calculatorModalTitle">Investment Profit Calculator</h3>
                <button class="calculator-modal-close" id="closeCalculatorModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="calculator-modal-body">
                <!-- Plan Section -->
                <div class="calculator-plan-section">
                    <h4 class="calculator-plan-name" id="calculatorPlanName">-</h4>
                    <p class="calculator-plan-description" id="calculatorPlanDescription">-</p>
                </div>

                <!-- Investment Details Grid -->
                <div class="calculator-details-grid">
                    <div class="calculator-detail-item">
                        <div class="calculator-detail-label">Return Rate:</div>
                        <div class="calculator-detail-value" id="calculatorReturnRate">-</div>
                    </div>
                    <div class="calculator-detail-item">
                        <div class="calculator-detail-label">Frequency:</div>
                        <div class="calculator-detail-value" id="calculatorFrequency">Every hour</div>
                    </div>
                    <div class="calculator-detail-item">
                        <div class="calculator-detail-label">Price Type:</div>
                        <div class="calculator-detail-value">Range</div>
                    </div>
                    <div class="calculator-detail-item">
                        <div class="calculator-detail-label">Investment Range:</div>
                        <div class="calculator-detail-value" id="calculatorInvestmentRange">-</div>
                    </div>
                </div>

                <!-- Investment Amount Input -->
                <div class="calculator-input-section">
                    <label class="calculator-input-label">Investment Amount</label>
                    <div class="calculator-input-wrapper">
                        <span class="calculator-input-prefix">$</span>
                        <input type="number" 
                               class="calculator-input" 
                               id="investmentAmount"
                               placeholder="Enter investment amount"
                               step="0.01">
                        <button class="calculator-input-action" type="button" title="More options" disabled>
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <button class="calculator-input-action" type="button" title="Calculator" disabled>
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>

                <!-- Investment Details Section -->
                <div class="calculator-details-card" id="investmentDetailsCard" style="display: none;">
                    <h4 class="calculator-card-title">Investment Details</h4>
                    <div class="calculator-details-list">
                        <div class="calculator-detail-row">
                            <span class="calculator-detail-row-label">Investment Amount:</span>
                            <span class="calculator-detail-row-value" id="calculatedAmount">$0.00</span>
                        </div>
                        <div class="calculator-detail-row">
                            <span class="calculator-detail-row-label">Return Rate:</span>
                            <span class="calculator-detail-row-value" id="calculatorReturnRateDetail">-</span>
                        </div>
                        <div class="calculator-detail-row">
                            <span class="calculator-detail-row-label">Profit Per Cycle:</span>
                            <span class="calculator-detail-row-value calculator-profit-value" id="profitPerCycle">$0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Profit Breakdown Section -->
                <div class="calculator-details-card" id="profitBreakdownCard" style="display: none;">
                    <h4 class="calculator-card-title">
                        <i class="fas fa-arrow-trend-up calculator-trend-icon"></i>
                        Profit Breakdown by Time Period
                    </h4>
                    <div class="calculator-profit-grid">
                        <div class="calculator-profit-item">
                            <i class="fas fa-clock calculator-profit-icon"></i>
                            <span class="calculator-profit-label">Hourly:</span>
                            <span class="calculator-profit-value" id="profitHourly">$0.00</span>
                        </div>
                        <div class="calculator-profit-item">
                            <i class="fas fa-clock calculator-profit-icon"></i>
                            <span class="calculator-profit-label">Daily:</span>
                            <span class="calculator-profit-value" id="profitDaily">$0.00</span>
                        </div>
                        <div class="calculator-profit-item">
                            <i class="fas fa-clock calculator-profit-icon"></i>
                            <span class="calculator-profit-label">Weekly:</span>
                            <span class="calculator-profit-value" id="profitWeekly">$0.00</span>
                        </div>
                        <div class="calculator-profit-item">
                            <i class="fas fa-clock calculator-profit-icon"></i>
                            <span class="calculator-profit-label">Monthly:</span>
                            <span class="calculator-profit-value" id="profitMonthly">$0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer Buttons -->
            <div class="calculator-modal-footer">
                <button class="calculator-btn calculator-btn-reset" id="resetCalculator">
                    Reset
                </button>
                <button class="calculator-btn calculator-btn-close" id="closeCalculatorModalBtn">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Investment Modal -->
    <div class="calculator-modal-overlay" id="investmentModalOverlay">
        <div class="calculator-modal investment-modal">
            <div class="calculator-modal-header">
                <h3 class="calculator-modal-title">Buy Investment Plan: <span class="investment-plan-name-highlight" id="investmentPlanName">-</span></h3>
                <button class="calculator-modal-close" id="closeInvestmentModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="calculator-modal-body investment-modal-body">
                <!-- Alert Message -->
                <div id="investmentAlert" class="investment-alert" style="display: none;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span id="investmentAlertMessage"></span>
                    <button id="depositAmountBtn" class="investment-deposit-btn" style="display: none;">Deposit Amount</button>
                </div>

                <!-- Plan Name -->
                <div class="investment-plan-name-section">
                    <h2 class="investment-plan-name-text" id="investmentPlanNameText">-</h2>
                </div>

                <!-- Investment Range -->
                <div class="investment-range-section">
                    <div class="investment-range-amount">
                        $<span id="investmentMinAmount">0</span> - $<span id="investmentMaxAmount">0</span>
                    </div>
                    <div class="investment-range-label">Minimum Investment</div>
                </div>

                <!-- Principal Return Policy -->
                <div class="investment-principal-policy">
                    Principal Return Policy Will Be Returned
                </div>

                <!-- Balances Section -->
                <div class="investment-balances-section">
                    <h4 class="investment-balances-title">Current amount in deposit and earning wallet</h4>
                    <div class="investment-balances-grid">
                        <div class="investment-balance-card investment-balance-fund">
                            <i class="fas fa-wallet"></i>
                            <div class="investment-balance-info">
                                <div class="investment-balance-label">Fund Balance</div>
                                <div class="investment-balance-amount" id="fundBalanceDisplay">$0.00</div>
                            </div>
                        </div>
                        <div class="investment-balance-card investment-balance-earning">
                            <i class="fas fa-wallet"></i>
                            <div class="investment-balance-info">
                                <div class="investment-balance-label">Earning Balance</div>
                                <div class="investment-balance-amount" id="earningBalanceDisplay">$0.00</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pay From Selection -->
                <div class="investment-pay-from-section">
                    <label class="investment-pay-from-label">Pay from:</label>
                    <div class="investment-select-wrapper">
                        <select id="sourceBalanceSelect" class="investment-select">
                            <option value="fund_wallet">Fund Balance</option>
                            <option value="earning_balance">Earning Balance</option>
                        </select>
                        <i class="fas fa-chevron-down investment-select-arrow"></i>
                    </div>
                </div>

                <!-- Investment Amount Input -->
                <div class="investment-amount-section">
                    <label class="investment-amount-label">Investment Amount</label>
                    <div class="investment-input-wrapper">
                        <span class="investment-input-prefix">$</span>
                        <input type="number" 
                               id="investmentAmountInput"
                               class="investment-amount-input" 
                               placeholder="Enter amount"
                               step="0.01"
                               min="0">
                    </div>
                    <div class="investment-amount-hint" id="investmentAmountHint">Min: $0 - Max: $0</div>
                </div>

                <!-- Modal Footer Buttons -->
                <div class="calculator-modal-footer investment-modal-footer">
                    <button class="investment-btn investment-btn-cancel" id="cancelInvestmentBtn">
                        Cancel
                    </button>
                    <button class="investment-btn investment-btn-confirm" id="confirmInvestmentBtn">
                        Confirm Investment
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/dashboard/js/plans.js') }}"></script>
@endpush
@endsection

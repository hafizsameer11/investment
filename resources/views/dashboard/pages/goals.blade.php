@extends('dashboard.layouts.main')

@section('title', 'Core Mining - Goals')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/goals.css') }}">
<style>
    .goals-new-page {
        padding: 2rem;
        max-width: 1600px;
        margin: 0 auto;
    }

    /* Hero Section */
    .goals-hero-new {
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

    .goals-hero-new::before {
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

    .goals-hero-new::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 178, 30, 0.08) 0%, transparent 70%);
        pointer-events: none;
    }

    .goals-hero-content-new {
        position: relative;
        z-index: 1;
    }

    .goals-hero-title-new {
        font-size: 3rem;
        font-weight: 700;
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 1rem 0;
        letter-spacing: -2px;
    }

    .goals-hero-subtitle-new {
        font-size: 1.125rem;
        color: var(--text-secondary);
        margin: 0;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Current Status Section */
    .goals-status-section-new {
        margin-bottom: 3rem;
    }

    .goals-status-header-new {
        margin-bottom: 2rem;
    }

    .goals-status-title-new {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .goals-status-subtitle-new {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .goals-status-cards-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .goals-status-card-new {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
        transition: var(--transition);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
    }

    .goals-status-card-new::before {
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

    .goals-status-card-new:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 32px rgba(255, 178, 30, 0.2);
        border-color: var(--primary-color);
    }

    .goals-status-card-new:hover::before {
        transform: scaleX(1);
    }

    .goals-rank-card-new {
        text-align: center;
    }

    .goals-rank-icon-wrapper-new {
        width: 120px;
        height: 120px;
        margin: 0 auto 1.5rem;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.2) 0%, rgba(255, 138, 29, 0.1) 100%);
        border: 3px solid rgba(255, 178, 30, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        box-shadow: 0 0 30px rgba(255, 178, 30, 0.3);
    }

    .goals-rank-icon-wrapper-new::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 178, 30, 0.3) 0%, transparent 70%);
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 0.5; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.1); }
    }

    .goals-rank-icon-new {
        font-size: 3rem;
        color: var(--primary-color);
        position: relative;
        z-index: 1;
        filter: drop-shadow(0 0 10px rgba(255, 178, 30, 0.8));
    }

    .goals-rank-badge-new {
        display: inline-block;
        padding: 0.625rem 1.25rem;
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.2) 0%, rgba(255, 138, 29, 0.15) 100%);
        border: 1px solid rgba(255, 178, 30, 0.4);
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--primary-color);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
        box-shadow: 0 0 16px rgba(255, 178, 30, 0.3);
    }

    .goals-rank-label-new {
        font-size: 0.9375rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .goals-rank-hint-new {
        font-size: 0.8125rem;
        color: var(--text-muted);
    }

    .goals-progress-card-new {
        position: relative;
    }

    .goals-progress-header-new {
        margin-bottom: 2rem;
    }

    .goals-progress-title-new {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 0.75rem 0;
    }

    .goals-progress-goal-name-new {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: rgba(255, 178, 30, 0.1);
        border: 1px solid rgba(255, 178, 30, 0.3);
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--primary-color);
    }

    .goals-progress-display-new {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .goals-progress-bar-wrapper-new {
        position: relative;
        background: rgba(255, 178, 30, 0.1);
        border-radius: 20px;
        height: 40px;
        overflow: hidden;
        border: 1px solid rgba(255, 178, 30, 0.2);
    }

    .goals-progress-fill-new {
        height: 100%;
        background: linear-gradient(90deg, #FFB21E 0%, #FF8A1D 100%);
        border-radius: 20px;
        transition: width 0.5s ease;
        position: relative;
        overflow: hidden;
    }

    .goals-progress-fill-new::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: progress-shine 2s infinite;
    }

    @keyframes progress-shine {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    .goals-progress-percentage-new {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 0.875rem;
        font-weight: 700;
        color: #000;
        z-index: 1;
    }

    .goals-progress-info-new {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 1.125rem;
        font-weight: 600;
    }

    .goals-progress-current-new {
        color: var(--primary-color);
    }

    .goals-progress-separator-new {
        color: var(--text-secondary);
    }

    .goals-progress-target-new {
        color: var(--text-primary);
    }

    .goals-progress-message-new {
        text-align: center;
        font-size: 0.875rem;
        color: var(--text-secondary);
        font-style: italic;
    }

    .goals-next-goal-card-new {
        position: relative;
    }

    .goals-next-goal-header-new {
        margin-bottom: 1.5rem;
    }

    .goals-next-goal-title-new {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 1rem 0;
    }

    .goals-next-goal-name-new {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .goals-next-goal-requirements-new {
        margin-bottom: 1.5rem;
    }

    .goals-requirement-item-new {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: rgba(255, 178, 30, 0.05);
        border: 1px solid rgba(255, 178, 30, 0.2);
        border-radius: 12px;
        margin-bottom: 0.75rem;
    }

    .goals-requirement-item-new i {
        color: var(--primary-color);
        font-size: 1.125rem;
    }

    .goals-requirement-item-new span {
        color: var(--text-primary);
        font-size: 0.9375rem;
    }

    .goals-requirement-item-new strong {
        color: var(--primary-color);
    }

    .goals-next-progress-bar-wrapper-new {
        position: relative;
        background: rgba(255, 178, 30, 0.1);
        border-radius: 12px;
        height: 24px;
        overflow: hidden;
        margin-bottom: 0.75rem;
        border: 1px solid rgba(255, 178, 30, 0.2);
    }

    .goals-next-progress-fill-new {
        height: 100%;
        background: linear-gradient(90deg, #FFB21E 0%, #FF8A1D 100%);
        border-radius: 12px;
        transition: width 0.5s ease;
    }

    .goals-next-goal-needed-new {
        text-align: center;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    /* Reward Levels Section */
    .goals-rewards-section-new {
        margin-bottom: 3rem;
    }

    .goals-rewards-header-new {
        margin-bottom: 2rem;
    }

    .goals-rewards-title-new {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .goals-rewards-subtitle-new {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .goals-rewards-grid-new {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .goals-reward-card-new {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
        transition: var(--transition);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
    }

    .goals-reward-card-new::before {
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

    .goals-reward-card-new:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 32px rgba(255, 178, 30, 0.2);
        border-color: var(--primary-color);
    }

    .goals-reward-card-new:hover::before {
        transform: scaleX(1);
    }

    .goals-reward-card-new.current {
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.1) 0%, rgba(255, 138, 29, 0.05) 100%);
        border: 2px solid rgba(255, 178, 30, 0.5);
        box-shadow: 0 0 40px rgba(255, 178, 30, 0.3);
    }

    .goals-reward-card-new.current::before {
        transform: scaleX(1);
    }

    .goals-reward-badge-new {
        display: none;
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
        color: #000;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 16px rgba(255, 178, 30, 0.4);
        z-index: 10;
    }

    .goals-reward-header-new {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .goals-reward-icon-new {
        width: 80px;
        height: 80px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #000;
        flex-shrink: 0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .goals-reward-icon-gold-new {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
    }

    .goals-reward-icon-orange-new {
        background: linear-gradient(135deg, #FF6B35 0%, #F7931E 100%);
    }

    .goals-reward-icon-blue-new {
        background: linear-gradient(135deg, #00AAFF 0%, #0088CC 100%);
    }

    .goals-reward-level-new {
        flex: 1;
    }

    .goals-reward-name-new {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .goals-reward-level-number-new {
        font-size: 0.9375rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .goals-reward-body-new {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .goals-reward-requirement-new,
    .goals-reward-prize-new {
        padding: 1.25rem;
        border-radius: 12px;
    }

    .goals-reward-requirement-new {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--card-border);
    }

    .goals-reward-prize-new {
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.15) 0%, rgba(255, 138, 29, 0.1) 100%);
        border: 1px solid rgba(255, 178, 30, 0.3);
        text-align: center;
    }

    .goals-reward-req-label-new,
    .goals-reward-prize-label-new {
        font-size: 0.75rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
        font-weight: 600;
    }

    .goals-reward-req-value-new {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        font-variant-numeric: tabular-nums;
    }

    .goals-reward-prize-value-new {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        font-variant-numeric: tabular-nums;
        text-shadow: 0 0 20px rgba(255, 178, 30, 0.5);
    }

    .goals-reward-progress-new {
        margin-top: 1.5rem;
    }

    .goals-reward-progress-bar-wrapper-new {
        position: relative;
        background: rgba(255, 178, 30, 0.1);
        border-radius: 12px;
        height: 28px;
        overflow: hidden;
        margin-bottom: 0.5rem;
        border: 1px solid rgba(255, 178, 30, 0.2);
    }

    .goals-reward-progress-fill-new {
        height: 100%;
        background: linear-gradient(90deg, #FFB21E 0%, #FF8A1D 100%);
        border-radius: 12px;
        transition: width 0.5s ease;
    }

    .goals-reward-progress-text-new {
        text-align: center;
        font-size: 0.8125rem;
        color: var(--text-secondary);
        font-weight: 600;
    }

    /* All Levels Section */
    .goals-all-levels-section-new {
        margin-bottom: 2rem;
    }

    .goals-all-levels-header-new {
        margin-bottom: 2rem;
    }

    .goals-all-levels-title-new {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .goals-all-levels-subtitle-new {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .goals-all-levels-grid-new {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .goals-level-card-new {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        transition: var(--transition);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
    }

    .goals-level-card-new::before {
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

    .goals-level-card-new:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(255, 178, 30, 0.2);
        border-color: var(--primary-color);
    }

    .goals-level-card-new:hover::before {
        transform: scaleX(1);
    }

    .goals-level-card-new.premium {
        background: linear-gradient(135deg, rgba(255, 178, 30, 0.1) 0%, rgba(255, 138, 29, 0.05) 100%);
        border: 2px solid rgba(255, 178, 30, 0.4);
    }

    .goals-level-badge-premium-new {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.375rem 0.875rem;
        background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
        color: #000;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(255, 178, 30, 0.4);
        z-index: 10;
    }

    .goals-level-icon-new {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: #000;
        margin: 0 auto 1.5rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
    }

    .goals-level-icon-gold-new {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
    }

    .goals-level-icon-silver-new {
        background: linear-gradient(135deg, #C0C0C0 0%, #808080 100%);
    }

    .goals-level-icon-purple-new {
        background: linear-gradient(135deg, #9B59B6 0%, #8E44AD 100%);
    }

    .goals-level-icon-red-new {
        background: linear-gradient(135deg, #E74C3C 0%, #C0392B 100%);
    }

    .goals-level-content-new {
        text-align: center;
    }

    .goals-level-name-new {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .goals-level-number-new {
        font-size: 0.875rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .goals-level-details-new {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .goals-level-detail-item-new {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: rgba(255, 178, 30, 0.05);
        border-radius: 10px;
    }

    .goals-level-detail-label-new {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .goals-level-detail-value-new {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .goals-level-reward-new {
        color: var(--primary-color);
    }

    .goals-level-progress-new {
        margin-top: 1rem;
    }

    .goals-level-progress-bar-wrapper-new {
        position: relative;
        background: rgba(255, 178, 30, 0.1);
        border-radius: 10px;
        height: 20px;
        overflow: hidden;
        margin-bottom: 0.5rem;
        border: 1px solid rgba(255, 178, 30, 0.2);
    }

    .goals-level-progress-fill-new {
        height: 100%;
        background: linear-gradient(90deg, #FFB21E 0%, #FF8A1D 100%);
        border-radius: 10px;
        transition: width 0.5s ease;
    }

    .goals-level-progress-text-new {
        text-align: center;
        font-size: 0.75rem;
        color: var(--text-secondary);
        font-weight: 600;
    }

    /* Mobile Redesign Styles */
    .goals-combined-card-new {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .goals-rank-section-mobile-new {
        text-align: center;
    }

    .goals-rank-badge-container-new {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .goals-rank-pentagon-badge-new {
        width: 80px;
        height: 80px;
        position: relative;
        background: linear-gradient(135deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
        clip-path: polygon(50% 0%, 100% 38%, 82% 100%, 18% 100%, 0% 38%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 20px rgba(255, 178, 30, 0.5);
    }

    .goals-rank-pentagon-inner-new {
        width: 65px;
        height: 65px;
        background: rgba(20, 30, 40, 0.95);
        clip-path: polygon(50% 0%, 100% 38%, 82% 100%, 18% 100%, 0% 38%);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .goals-rank-chevron-new {
        color: var(--primary-color);
        font-size: 1.5rem;
        transform: translateY(2px);
    }

    .goals-rank-text-new {
        text-align: center;
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .goals-progress-title-row-new {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .goals-progress-title-new {
        font-size: 0.9375rem;
        font-weight: 500;
        color: var(--text-primary);
        margin: 0;
    }

    .goals-progress-percent-new {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .goals-progress-goal-row-new {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .goals-progress-goal-name-new {
        font-size: 0.9375rem;
        font-weight: 500;
        color: var(--text-primary);
    }

    .goals-progress-status-new {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--success-color);
    }

    .goals-progress-bar-mobile-new {
        width: 100%;
        height: 8px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        overflow: hidden;
    }

    .goals-progress-fill-mobile-new {
        height: 100%;
        background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
        border-radius: 4px;
        transition: width 0.5s ease;
    }

    .goals-next-goal-card-inner-new {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .goals-next-goal-title-mobile-new {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1.25rem;
    }

    .goals-next-goal-progress-row-new {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .goals-next-goal-progress-label-new {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8125rem;
        color: var(--text-primary);
    }

    .goals-next-goal-progress-label-new i {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .goals-next-goal-current-new {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--success-color);
    }

    .goals-next-progress-bar-mobile-new {
        width: 100%;
        height: 8px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 0.75rem;
    }

    .goals-next-progress-fill-mobile-new {
        height: 100%;
        background: linear-gradient(90deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
        border-radius: 4px;
        transition: width 0.5s ease;
    }

    .goals-next-goal-target-row-new {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .goals-next-goal-needed-mobile-new {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--success-color);
    }

    .goals-next-goal-target-new {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .goals-progress-section-mobile-new {
        width: 100%;
    }

    .goals-next-goal-section-mobile-new {
        width: 100%;
    }

    /* Desktop: Show separate cards, hide combined card */
    .goals-mobile-card-new {
        display: none;
    }

    .goals-desktop-card-new {
        display: block;
    }

    @media (max-width: 768px) {
        .goals-new-page {
            padding: 1rem;
        }

        .goals-hero-new {
            display: none;
        }

        .goals-status-header-new {
            display: none;
        }

        /* Mobile: Hide desktop cards, show combined card */
        .goals-desktop-card-new {
            display: none;
        }

        .goals-mobile-card-new {
            display: flex;
        }

        .goals-status-cards-new {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .goals-combined-card-new {
            flex-direction: column;
            gap: 2rem;
            padding: 1.5rem;
            border-radius: 16px;
        }

        .goals-rank-section-mobile-new {
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .goals-progress-section-mobile-new {
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .goals-next-goal-section-mobile-new {
            padding-bottom: 0;
        }

        .goals-rewards-grid-new {
            grid-template-columns: 1fr;
        }

        .goals-reward-body-new {
            grid-template-columns: 1fr;
        }

        .goals-all-levels-grid-new {
            grid-template-columns: 1fr;
        }

        /* Mobile: Apply consistent smaller font sizes throughout the page */
        .goals-rewards-title-new {
            font-size: 1.25rem;
        }

        .goals-rewards-subtitle-new {
            font-size: 0.875rem;
        }

        .goals-reward-card-new {
            padding: 1.25rem;
        }

        .goals-reward-name-new {
            font-size: 1.125rem;
        }

        .goals-reward-level-number-new {
            font-size: 0.75rem;
        }

        .goals-reward-req-label-new,
        .goals-reward-prize-label-new {
            font-size: 0.75rem;
        }

        .goals-reward-req-value-new,
        .goals-reward-prize-value-new {
            font-size: 1rem;
        }

        .goals-reward-progress-text-new {
            font-size: 0.75rem;
        }

        .goals-all-levels-title-new {
            font-size: 1.25rem;
        }

        .goals-all-levels-subtitle-new {
            font-size: 0.875rem;
        }

        .goals-level-card-new {
            padding: 1.25rem;
        }

        .goals-level-name-new {
            font-size: 1.125rem;
        }

        .goals-level-number-new {
            font-size: 0.75rem;
        }

        .goals-level-detail-label-new {
            font-size: 0.75rem;
        }

        .goals-level-detail-value-new {
            font-size: 1rem;
        }

        .goals-level-progress-text-new {
            font-size: 0.75rem;
        }

        .goals-rank-label-new {
            font-size: 0.8125rem;
        }

        .goals-rank-hint-new {
            font-size: 0.75rem;
        }

        .goals-rank-badge-new {
            font-size: 0.8125rem;
        }
    }
</style>
@endpush

@section('content')
<div class="goals-new-page">
    <!-- Hero Section -->
    <div class="goals-hero-new">
        <div class="goals-hero-content-new">
            <h1 class="goals-hero-title-new">Goals & Rewards</h1>
            <p class="goals-hero-subtitle-new">Unlock higher levels and earn bigger rewards through referrals and team building</p>
        </div>
    </div>

    <!-- Current Status Section -->
    <div class="goals-status-section-new">
        <div class="goals-status-header-new">
            <h2 class="goals-status-title-new">Your Current Status</h2>
            <p class="goals-status-subtitle-new">Track your progress towards the next level and unlock exclusive rewards</p>
        </div>

        <div class="goals-status-cards-new">
            <!-- Desktop: Three Separate Cards -->
            <!-- Rank Card -->
            <div class="goals-status-card-new goals-rank-card-new goals-desktop-card-new">
                <div class="goals-rank-icon-wrapper-new">
                    <i class="fas fa-trophy goals-rank-icon-new"></i>
                </div>
                <div class="goals-rank-badge-new">No Rank</div>
                <div class="goals-rank-label-new">Current Rank</div>
                <div class="goals-rank-hint-new">Complete goals to advance</div>
            </div>

            <!-- Progress Card -->
            <div class="goals-status-card-new goals-progress-card-new goals-desktop-card-new">
                <div class="goals-progress-header-new">
                    <h3 class="goals-progress-title-new">Progress to Next Goal</h3>
                    <div class="goals-progress-goal-name-new">Team Builder</div>
                </div>
                <div class="goals-progress-display-new">
                    <div class="goals-progress-bar-wrapper-new">
                        <div class="goals-progress-fill-new" style="width: 0%"></div>
                        <div class="goals-progress-percentage-new">0%</div>
                    </div>
                    <div class="goals-progress-info-new">
                        <span class="goals-progress-current-new">$0</span>
                        <span class="goals-progress-separator-new">/</span>
                        <span class="goals-progress-target-new">$10</span>
                    </div>
                    <div class="goals-progress-message-new">Almost there! Keep going!</div>
                </div>
            </div>

            <!-- Next Goal Preview -->
            <div class="goals-status-card-new goals-next-goal-card-new goals-desktop-card-new">
                <div class="goals-next-goal-header-new">
                    <h3 class="goals-next-goal-title-new">Next Goal</h3>
                </div>
                <div class="goals-next-goal-name-new">Team Builder</div>
                <div class="goals-next-goal-requirements-new">
                    <div class="goals-requirement-item-new">
                        <i class="fas fa-users-cog"></i>
                        <span>Team Progress: <strong>$0</strong></span>
                    </div>
                </div>
                <div class="goals-next-progress-bar-wrapper-new">
                    <div class="goals-next-progress-fill-new" style="width: 0%"></div>
                </div>
                <div class="goals-next-goal-needed-new">$10 more needed</div>
            </div>

            <!-- Mobile: Combined Single Card -->
            <div class="goals-status-card-new goals-combined-card-new goals-mobile-card-new">
                <!-- Rank Section -->
                <div class="goals-rank-section-mobile-new">
                    <div class="goals-rank-icon-wrapper-new">
                        <i class="fas fa-trophy goals-rank-icon-new"></i>
                    </div>
                    <div class="goals-rank-text-new">No Rank</div>
                </div>

                <!-- Progress Section -->
                <div class="goals-progress-section-mobile-new">
                    <div class="goals-progress-title-row-new">
                        <span class="goals-progress-title-new">Progress to next goal</span>
                        <span class="goals-progress-percent-new">0%</span>
                    </div>
                    <div class="goals-progress-goal-row-new">
                        <span class="goals-progress-goal-name-new">Team Builder</span>
                        <span class="goals-progress-status-new">Almost there!</span>
                    </div>
                    <div class="goals-progress-bar-mobile-new">
                        <div class="goals-progress-fill-mobile-new" style="width: 0%"></div>
                    </div>
                </div>

                <!-- Next Goal Section -->
                <div class="goals-next-goal-section-mobile-new">
                    <div class="goals-next-goal-card-inner-new">
                        <div class="goals-next-goal-title-mobile-new">Next Goal Team Builder</div>
                        <div class="goals-next-goal-progress-row-new">
                            <div class="goals-next-goal-progress-label-new">
                                <i class="fas fa-chart-line"></i>
                                <span>Team Progress</span>
                            </div>
                            <span class="goals-next-goal-current-new">$0</span>
                        </div>
                        <div class="goals-next-progress-bar-mobile-new">
                            <div class="goals-next-progress-fill-mobile-new" style="width: 0%"></div>
                        </div>
                        <div class="goals-next-goal-target-row-new">
                            <span class="goals-next-goal-needed-mobile-new">$10 more needed</span>
                            <span class="goals-next-goal-target-new">$10</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rewards Level Section -->
    <div class="goals-all-levels-section-new">
        <div class="goals-all-levels-header-new">
            <h2 class="goals-all-levels-title-new">Rewards Level</h2>
            <p class="goals-all-levels-subtitle-new">Explore all available levels and their exclusive rewards</p>
        </div>

        <div class="goals-all-levels-grid-new">
            <!-- Level 1: Team Builder -->
            <div class="goals-level-card-new">
                <div class="goals-level-icon-new goals-level-icon-gold-new">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="goals-level-content-new">
                    <h3 class="goals-level-name-new">Team Builder</h3>
                    <div class="goals-level-number-new">Level 1</div>
                    <div class="goals-level-details-new">
                        <div class="goals-level-detail-item-new">
                            <span class="goals-level-detail-label-new">Total Referral Investment</span>
                            <span class="goals-level-detail-value-new">$10</span>
                        </div>
                        <div class="goals-level-detail-item-new">
                            <span class="goals-level-detail-label-new">Reward</span>
                            <span class="goals-level-detail-value-new goals-level-reward-new">$2</span>
                        </div>
                    </div>
                    <div class="goals-level-progress-new">
                        <div class="goals-level-progress-bar-wrapper-new">
                            <div class="goals-level-progress-fill-new" style="width: 0%"></div>
                        </div>
                        <div class="goals-level-progress-text-new">0%</div>
                    </div>
                </div>
            </div>

            <!-- Level 2: Team Leader -->
            <div class="goals-level-card-new">
                <div class="goals-level-icon-new goals-level-icon-gold-new">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="goals-level-content-new">
                    <h3 class="goals-level-name-new">Team Leader</h3>
                    <div class="goals-level-number-new">Level 2</div>
                    <div class="goals-level-details-new">
                        <div class="goals-level-detail-item-new">
                            <span class="goals-level-detail-label-new">Total Referral Investment</span>
                            <span class="goals-level-detail-value-new">$40</span>
                        </div>
                        <div class="goals-level-detail-item-new">
                            <span class="goals-level-detail-label-new">Reward</span>
                            <span class="goals-level-detail-value-new goals-level-reward-new">$5</span>
                        </div>
                    </div>
                    <div class="goals-level-progress-new">
                        <div class="goals-level-progress-bar-wrapper-new">
                            <div class="goals-level-progress-fill-new" style="width: 0%"></div>
                        </div>
                        <div class="goals-level-progress-text-new">0%</div>
                    </div>
                </div>
            </div>

            <!-- Level 3: Team Director -->
            <div class="goals-level-card-new">
                <div class="goals-level-icon-new goals-level-icon-gold-new">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="goals-level-content-new">
                    <h3 class="goals-level-name-new">Team Director</h3>
                    <div class="goals-level-number-new">Level 3</div>
                    <div class="goals-level-details-new">
                        <div class="goals-level-detail-item-new">
                            <span class="goals-level-detail-label-new">Total Referral Investment</span>
                            <span class="goals-level-detail-value-new">$120</span>
                        </div>
                        <div class="goals-level-detail-item-new">
                            <span class="goals-level-detail-label-new">Reward</span>
                            <span class="goals-level-detail-value-new goals-level-reward-new">$8</span>
                        </div>
                    </div>
                    <div class="goals-level-progress-new">
                        <div class="goals-level-progress-bar-wrapper-new">
                            <div class="goals-level-progress-fill-new" style="width: 0%"></div>
                        </div>
                        <div class="goals-level-progress-text-new">0%</div>
                    </div>
                </div>
            </div>
            <!-- Level 4: Team Master -->
            <div class="goals-level-card-new">
                <div class="goals-level-icon-new goals-level-icon-gold-new">
                    <i class="fas fa-medal"></i>
                </div>
                <div class="goals-level-content-new">
                    <h3 class="goals-level-name-new">Team Master</h3>
                    <div class="goals-level-number-new">Level 4</div>
                    <div class="goals-level-details-new">
                        <div class="goals-level-detail-item-new">
                            <span class="goals-level-detail-label-new">Total Referral Investment</span>
                            <span class="goals-level-detail-value-new">$200</span>
                        </div>
                        <div class="goals-level-detail-item-new">
                            <span class="goals-level-detail-label-new">Reward</span>
                            <span class="goals-level-detail-value-new goals-level-reward-new">$16</span>
                        </div>
                    </div>
                    <div class="goals-level-progress-new">
                        <div class="goals-level-progress-bar-wrapper-new">
                            <div class="goals-level-progress-fill-new" style="width: 0%"></div>
                        </div>
                        <div class="goals-level-progress-text-new">0%</div>
                    </div>
                </div>
            </div>

            <!-- Level 5: Team Chief -->
            <div class="goals-level-card-new">
                <div class="goals-level-icon-new goals-level-icon-silver-new">
                    <i class="fas fa-award"></i>
                </div>
                <div class="goals-level-content-new">
                    <h3 class="goals-level-name-new">Team Chief</h3>
                    <div class="goals-level-number-new">Level 5</div>
                    <div class="goals-level-details-new">
                        <div class="goals-level-detail-item-new">
                            <span class="goals-level-detail-label-new">Total Referral Investment</span>
                            <span class="goals-level-detail-value-new">$600</span>
                        </div>
                        <div class="goals-level-detail-item-new">
                            <span class="goals-level-detail-label-new">Reward</span>
                            <span class="goals-level-detail-value-new goals-level-reward-new">$50</span>
                        </div>
                    </div>
                    <div class="goals-level-progress-new">
                        <div class="goals-level-progress-bar-wrapper-new">
                            <div class="goals-level-progress-fill-new" style="width: 0%"></div>
                        </div>
                        <div class="goals-level-progress-text-new">0%</div>
                    </div>
                </div>
            </div>

            <!-- Level 6: Team Executive -->
            <div class="goals-level-card-new">
                <div class="goals-level-icon-new goals-level-icon-purple-new">
                    <i class="fas fa-gem"></i>
                </div>
                <div class="goals-level-content-new">
                    <h3 class="goals-level-name-new">Team Executive</h3>
                    <div class="goals-level-number-new">Level 6</div>
                    <div class="goals-level-details-new">
                        <div class="goals-level-detail-item-new">
                            <span class="goals-level-detail-label-new">Total Referral Investment</span>
                            <span class="goals-level-detail-value-new">$1,000</span>
                        </div>
                        <div class="goals-level-detail-item-new">
                            <span class="goals-level-detail-label-new">Reward</span>
                            <span class="goals-level-detail-value-new goals-level-reward-new">$170</span>
                        </div>
                    </div>
                    <div class="goals-level-progress-new">
                        <div class="goals-level-progress-bar-wrapper-new">
                            <div class="goals-level-progress-fill-new" style="width: 0%"></div>
                        </div>
                        <div class="goals-level-progress-text-new">0%</div>
                    </div>
                </div>
            </div>

            <!-- Level 7: Team Captain -->
            <div class="goals-level-card-new">
                <div class="goals-level-icon-new goals-level-icon-red-new">
                    <i class="fas fa-star"></i>
                </div>
                <div class="goals-level-content-new">
                    <h3 class="goals-level-name-new">Team Captain</h3>
                    <div class="goals-level-number-new">Level 7</div>
                    <div class="goals-level-details-new">
                        <div class="goals-level-detail-item-new">
                            <span class="goals-level-detail-label-new">Total Referral Investment</span>
                            <span class="goals-level-detail-value-new">$2,500</span>
                        </div>
                        <div class="goals-level-detail-item-new">
                            <span class="goals-level-detail-label-new">Reward</span>
                            <span class="goals-level-detail-value-new goals-level-reward-new">$500</span>
                        </div>
                    </div>
                    <div class="goals-level-progress-new">
                        <div class="goals-level-progress-bar-wrapper-new">
                            <div class="goals-level-progress-fill-new" style="width: 0%"></div>
                        </div>
                        <div class="goals-level-progress-text-new">0%</div>
                    </div>
                </div>
            </div>

            <!-- Level 8: Team Commander -->
            <div class="goals-level-card-new">
                <div class="goals-level-icon-new goals-level-icon-red-new">
                    <i class="fas fa-chess-king"></i>
                </div>
                <div class="goals-level-content-new">
                    <h3 class="goals-level-name-new">Team Commander</h3>
                    <div class="goals-level-number-new">Level 8</div>
                    <div class="goals-level-details-new">
                        <div class="goals-level-detail-item-new">
                            <span class="goals-level-detail-label-new">Total Referral Investment</span>
                            <span class="goals-level-detail-value-new">$8,000</span>
                        </div>
                        <div class="goals-level-detail-item-new">
                            <span class="goals-level-detail-label-new">Reward</span>
                            <span class="goals-level-detail-value-new goals-level-reward-new">$2,000</span>
                        </div>
                    </div>
                    <div class="goals-level-progress-new">
                        <div class="goals-level-progress-bar-wrapper-new">
                            <div class="goals-level-progress-fill-new" style="width: 0%"></div>
                        </div>
                        <div class="goals-level-progress-text-new">0%</div>
                    </div>
                </div>
            </div>

            <!-- Level 9: Team Head -->
            <div class="goals-level-card-new">
                <div class="goals-level-icon-new goals-level-icon-red-new">
                    <i class="fas fa-chess-queen"></i>
                </div>
                <div class="goals-level-content-new">
                    <h3 class="goals-level-name-new">Team Head</h3>
                    <div class="goals-level-number-new">Level 9</div>
                    <div class="goals-level-details-new">
                        <div class="goals-level-detail-item-new">
                            <span class="goals-level-detail-label-new">Total Referral Investment</span>
                            <span class="goals-level-detail-value-new">$15,000</span>
                        </div>
                        <div class="goals-level-detail-item-new">
                            <span class="goals-level-detail-label-new">Reward</span>
                            <span class="goals-level-detail-value-new goals-level-reward-new">$4,500</span>
                        </div>
                    </div>
                    <div class="goals-level-progress-new">
                        <div class="goals-level-progress-bar-wrapper-new">
                            <div class="goals-level-progress-fill-new" style="width: 0%"></div>
                        </div>
                        <div class="goals-level-progress-text-new">0%</div>
                    </div>
                </div>
            </div>

            <!-- Level 10: Team President -->
            <div class="goals-level-card-new premium">
                <div class="goals-level-badge-premium-new">Premium</div>
                <div class="goals-level-icon-new goals-level-icon-red-new">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="goals-level-content-new">
                    <h3 class="goals-level-name-new">Team President</h3>
                    <div class="goals-level-number-new">Level 10</div>
                    <div class="goals-level-details-new">
                        <div class="goals-level-detail-item-new">
                            <span class="goals-level-detail-label-new">Total Referral Investment</span>
                            <span class="goals-level-detail-value-new">$25,000</span>
                        </div>
                        <div class="goals-level-detail-item-new">
                            <span class="goals-level-detail-label-new">Reward</span>
                            <span class="goals-level-detail-value-new goals-level-reward-new">$8,000</span>
                        </div>
                    </div>
                    <div class="goals-level-progress-new">
                        <div class="goals-level-progress-bar-wrapper-new">
                            <div class="goals-level-progress-fill-new" style="width: 0%"></div>
                        </div>
                        <div class="goals-level-progress-text-new">0%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/dashboard/js/goals.js') }}"></script>
@endpush
@endsection

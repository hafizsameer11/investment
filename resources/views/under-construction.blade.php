<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Under Construction - Core Mining ⛏️</title>
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
    <!-- Apple Touch Icons for iOS -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
    <!-- Mobile Web App Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Core Mining ⛏️">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/login.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .construction-container {
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            overflow: hidden;
        }

        .construction-card {
            background: rgba(24, 27, 39, 0.95);
            border: 1px solid rgba(255, 178, 30, 0.2);
            border-radius: 20px;
            padding: 3rem 2.5rem;
            max-width: 600px;
            width: 100%;
            text-align: center;
            box-shadow: 0 8px 32px rgba(255, 178, 30, 0.4);
            backdrop-filter: blur(10px);
            z-index: 10;
            position: relative;
        }

        .construction-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            position: relative;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .construction-icon svg {
            width: 100%;
            height: 100%;
            filter: drop-shadow(0 4px 8px rgba(255, 178, 30, 0.3));
        }

        .construction-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #FFFFFF;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #FFB21E 0%, #FF8A1D 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .construction-subtitle {
            font-size: 1.125rem;
            color: #B0B0B0;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .construction-message {
            font-size: 1rem;
            color: #B0B0B0;
            line-height: 1.8;
            margin-bottom: 2rem;
        }

        .progress-bar-container {
            width: 100%;
            height: 8px;
            background: rgba(255, 178, 30, 0.1);
            border-radius: 10px;
            overflow: hidden;
            margin: 2rem 0;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #FFB21E 0%, #FF8A1D 100%);
            border-radius: 10px;
            animation: progressAnimation 3s ease-in-out infinite;
            width: 60%;
        }

        @keyframes progressAnimation {
            0% {
                width: 40%;
            }
            50% {
                width: 70%;
            }
            100% {
                width: 40%;
            }
        }

        .back-link {
            display: inline-block;
            margin-top: 2rem;
            color: #FFB21E;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #FF8A1D;
            transform: translateX(-5px);
        }

        @media (max-width: 768px) {
            .construction-card {
                padding: 2rem 1.5rem;
            }

            .construction-title {
                font-size: 2rem;
            }

            .construction-subtitle {
                font-size: 1rem;
            }

            .construction-icon {
                width: 100px;
                height: 100px;
            }
        }
    </style>
</head>
<body>
    <div class="construction-container">
        <!-- Background gradient overlay -->
        <div class="background-gradient"></div>

        <!-- Logo -->
        <div class="logo-container">
            <div class="logo">
                <svg class="logo-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Mining Pickaxe Icon -->
                    <path d="M12 2L8 6L10 8L6 12L8 14L12 10L16 14L18 12L14 8L16 6L12 2Z" fill="url(#coreMiningGradient)" stroke="#FFB21E" stroke-width="1.5" stroke-linejoin="round"/>
                    <!-- Mining Blocks -->
                    <rect x="4" y="16" width="4" height="4" rx="1" fill="#FFB21E" opacity="0.6"/>
                    <rect x="10" y="18" width="4" height="4" rx="1" fill="#FF8A1D" opacity="0.6"/>
                    <rect x="16" y="16" width="4" height="4" rx="1" fill="#FFB21E" opacity="0.6"/>
                    <!-- Glow Effect -->
                    <circle cx="12" cy="8" r="8" fill="url(#coreMiningGlow)" opacity="0.3"/>
                    <defs>
                        <linearGradient id="coreMiningGradient" x1="12" y1="2" x2="12" y2="14" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#FFB21E"/>
                            <stop offset="100%" stop-color="#FF8A1D"/>
                        </linearGradient>
                        <radialGradient id="coreMiningGlow" cx="50%" cy="50%">
                            <stop offset="0%" stop-color="#FFB21E" stop-opacity="0.8"/>
                            <stop offset="100%" stop-color="#FFB21E" stop-opacity="0"/>
                        </radialGradient>
                    </defs>
                </svg>
                <span class="logo-text">Core Mining</span>
            </div>
        </div>

        <!-- Under Construction Card -->
        <div class="construction-card">
            <div class="construction-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" fill="url(#constructionGradient)" stroke="#FFB21E" stroke-width="1.5" stroke-linejoin="round"/>
                    <path d="M2 17L12 22L22 17" stroke="#FFB21E" stroke-width="1.5" stroke-linejoin="round"/>
                    <path d="M2 12L12 17L22 12" stroke="#FFB21E" stroke-width="1.5" stroke-linejoin="round"/>
                    <circle cx="12" cy="7" r="2" fill="#FF8A1D" opacity="0.6"/>
                    <defs>
                        <linearGradient id="constructionGradient" x1="12" y1="2" x2="12" y2="12" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#FFB21E"/>
                            <stop offset="100%" stop-color="#FF8A1D"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>

            <h1 class="construction-title">Under Construction</h1>
            <p class="construction-subtitle">We're working on something amazing!</p>
            <p class="construction-message">
                Our team is hard at work building something special for you. 
                We'll be back soon with exciting new features and improvements.
            </p>

            <!-- Progress Bar -->
            <div class="progress-bar-container">
                <div class="progress-bar"></div>
            </div>

            <a href="{{ route('login') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Login
            </a>
        </div>
    </div>
</body>
</html>


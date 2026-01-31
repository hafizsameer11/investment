<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Core Mining ⛏️- AI Gold Mining ⛏️</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/dashboard/images/meta/logo-2.png') }}">
    <!-- Apple Touch Icons for iOS -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/dashboard/images/meta/logo-2.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/dashboard/images/meta/logo-2.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/dashboard/images/meta/logo-2.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/dashboard/images/meta/logo-2.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/dashboard/images/meta/logo-2.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/dashboard/images/meta/logo-2.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/dashboard/images/meta/logo-2.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/dashboard/images/meta/logo-2.png') }}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/dashboard/images/meta/logo-2.png') }}">
    <!-- Mobile Web App Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Core Mining ⛏️">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/login.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <!-- Background gradient overlay -->
        <div class="background-gradient"></div>

        <!-- Logo -->
        <div class="logo-container">
            <a href="{{ route('login') }}" class="logo">
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
            </a>
        </div>

        
        <!-- Forgot Password Form Card -->
        <div class="login-card">
            <div class="card-content">
                <!-- Page Header (Centered) -->
                <div class="login-header" style="margin-bottom: 10px">
                    <h1 class="welcome-title">Forgot Password</h1>
                    <p class="welcome-subtitle">Enter your email address and we'll send you a link to reset your password</p>
                </div>
                
                <form id="forgotPasswordForm" class="login-form" method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="alert alert-error" id="forgotPasswordError" style="display: block;">
                            <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span id="forgotPasswordErrorText">
                                @foreach($errors->all() as $error)
                                    {{ $error }}<br>
                                @endforeach
                            </span>
                        </div>
                    @else
                        <div class="alert alert-error" id="forgotPasswordError" style="display: none;">
                            <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span id="forgotPasswordErrorText"></span>
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="alert alert-success" style="display: block;">
                            <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ session('status') }}</span>
                            @if(session('dev_reset_url'))
                                <div style="margin-top: 10px; padding: 10px; background: rgba(255, 178, 30, 0.1); border-radius: 8px; word-break: break-all;">
                                    <strong>Development Reset Link:</strong><br>
                                    <a href="{{ session('dev_reset_url') }}" style="color: #FFB21E; text-decoration: underline;">{{ session('dev_reset_url') }}</a>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success" style="display: block;">
                            <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-input @error('email') is-invalid @enderror"
                                placeholder="Enter your email address"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                autofocus
                            >
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="login-button" id="forgotPasswordButton">
                        <span class="button-text">Send Password Reset Link</span>
                        <svg class="button-loader" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" stroke-opacity="0.25"/>
                            <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                    </button>

                    <!-- Back to Login Link -->
                    <div class="signup-link">
                        <span class="signup-text">Remember your password?</span>
                        <a href="{{ route('login') }}" class="signup-link-text">Back to Login</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="{{ asset('assets/dashboard/js/login.js') }}"></script>
</body>
</html>


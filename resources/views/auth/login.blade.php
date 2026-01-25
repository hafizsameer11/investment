<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Dashboard</title>
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

        
        <!-- Login Form Card -->
        <div class="login-card">
            <div class="card-content">
                <!-- Page Header (Centered) -->
                <div class="login-header" style="margin-bottom: 10px" >
                    <h1 class="welcome-title">Welcome Back</h1>
                    <p class="welcome-subtitle">Login to your account</p>
                </div>
                
                <form id="loginForm" class="login-form" method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="alert alert-error" id="loginError" style="display: block;">
                            <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span id="loginErrorText">
                                @foreach($errors->all() as $error)
                                    {{ $error }}<br>
                                @endforeach
                            </span>
                        </div>
                    @else
                        <div class="alert alert-error" id="loginError" style="display: none;">
                            <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span id="loginErrorText"></span>
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
                        <label for="email" class="form-label">Email</label>
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
                                placeholder="Enter your email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                autofocus
                            >
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-input @error('password') is-invalid @enderror"
                                placeholder="Enter your password"
                                required
                                autocomplete="current-password"
                            >
                            <button type="button" class="password-toggle" id="passwordToggle" aria-label="Toggle password visibility">
                                <svg class="eye-icon" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="form-options">
                        <label class="checkbox-wrapper">
                            <input type="checkbox" name="remember" id="remember" class="checkbox-input">
                            <span class="checkbox-label">Remember me</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="forgot-password-link">Forgot password?</a>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="login-button" id="loginButton">
                        <span class="button-text">Login</span>
                        <svg class="button-loader" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" stroke-opacity="0.25"/>
                            <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                    </button>

                    <!-- Sign Up Link -->
                    <div class="signup-link">
                        <span class="signup-text">Don't have an account?</span>
                        <a href="{{ route('register') }}" class="signup-link-text">Sign Up</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="{{ asset('assets/dashboard/js/login.js') }}"></script>
</body>
</html>


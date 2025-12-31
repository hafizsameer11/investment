<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create an Account - Dashboard</title>
    <link rel="stylesheet" href="{{ asset('dashboard/css/register.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="register-container">
        <!-- Background gradient overlay -->
        <div class="background-gradient"></div>

        <!-- Logo -->
        <div class="logo-container">
            <div class="logo">
                <svg class="logo-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 3h8v8H3V3zm10 0h8v8h-8V3zM3 13h8v8H3v-8zm10 0h8v8h-8v-8z" fill="url(#dashboardGradient)" stroke="#9333EA" stroke-width="1.5"/>
                    <defs>
                        <linearGradient id="dashboardGradient" x1="12" y1="3" x2="12" y2="21" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#9333EA"/>
                            <stop offset="100%" stop-color="#EC4899"/>
                        </linearGradient>
                    </defs>
                </svg>
                <span class="logo-text">Dashboard</span>
            </div>
        </div>

        <!-- Register Form Card -->
        <div class="register-card">
            <div class="card-content">
                <h1 class="welcome-title">Create an Account</h1>
                <p class="welcome-subtitle">Join the future of AI investing</p>

                <form id="registerForm" class="register-form">
                    <!-- Error Messages (Frontend Only) -->
                    <div class="alert alert-error" id="registerError" style="display: none;">
                        <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div class="alert-messages" id="registerErrorText"></div>
                    </div>

                    <!-- Success Messages (Frontend Only) -->
                    <div class="alert alert-success" id="registerSuccess" style="display: none;">
                        <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span id="registerSuccessText"></span>
                    </div>

                    <!-- Full Name Field -->
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-input"
                                placeholder="Enter your full name"
                                required
                                autocomplete="name"
                                autofocus
                            >
                        </div>
                    </div>

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
                                class="form-input"
                                placeholder="Enter your email"
                                required
                                autocomplete="email"
                            >
                        </div>
                    </div>

                    <!-- Phone Field -->
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l1.703 8.827a1 1 0 01-.54 1.06l-6.43 3.655a1 1 0 01-1.25-.518L.879 12.5a1 1 0 01.54-1.06l6.43-3.655a1 1 0 01.986.836L9.5 9.5V7a1 1 0 011-1h2a1 1 0 011 1v2.5l1.153.836a1 1 0 01.986-.836l6.43 3.655a1 1 0 01.54 1.06l-1.25 2.518a1 1 0 01-1.25.518l-6.43-3.655a1 1 0 01-.54-1.06l1.703-8.827A1 1 0 0115.153 2H17a1 1 0 011 1v14a1 1 0 01-1 1H3a1 1 0 01-1-1V3z"/>
                            </svg>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                class="form-input"
                                placeholder="+1 123 456 7890"
                                autocomplete="tel"
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
                                class="form-input"
                                placeholder="Enter your password"
                                required
                                autocomplete="new-password"
                            >
                            <button type="button" class="password-toggle" id="passwordToggle" aria-label="Toggle password visibility">
                                <svg class="eye-icon" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-input"
                                placeholder="Confirm your password"
                                required
                                autocomplete="new-password"
                            >
                            <button type="button" class="password-toggle" id="passwordConfirmationToggle" aria-label="Toggle password visibility">
                                <svg class="eye-icon" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Terms and Conditions Checkbox -->
                    <div class="form-group">
                        <label class="checkbox-wrapper">
                            <input
                                type="checkbox"
                                name="terms"
                                id="terms"
                                class="checkbox-input"
                                required
                            >
                            <span class="checkbox-custom"></span>
                            <span class="checkbox-label">I accept the <a href="#" class="terms-link">terms and conditions</a></span>
                        </label>
                    </div>

                    <!-- Sign Up Button -->
                    <button type="submit" class="signup-button" id="signupButton">
                        <span class="button-text">Sign Up</span>
                        <svg class="button-loader" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" stroke-opacity="0.25"/>
                            <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                    </button>

                    <!-- Login Link -->
                    <div class="login-link">
                        <span class="login-text">Already have an account?</span>
                        <a href="{{ route('login') }}" class="login-link-text">Login</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Live Chat Widget -->
        <div class="chat-widget">
            <div class="chat-bubble">
                <span class="chat-bubble-text">We are online!</span>
            </div>
            <div class="chat-icon-container">
                <div class="chat-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/>
                    </svg>
                </div>
                <span class="chat-text">Live Tawk</span>
            </div>
        </div>
    </div>

    <script src="{{ asset('dashboard/js/register.js') }}"></script>
</body>
</html>


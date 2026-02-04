<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Code - Dashboard</title>
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/login.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="background-gradient"></div>

        <div class="logo-container">
            <a href="{{ route('login') }}" class="logo">
                <img class="logo-icon" src="{{ asset('assets/dashboard/images/meta/logo-2.png') }}" alt="logo" style="object-fit: contain;">
                <span class="logo-text">Core Mining</span>
            </a>
        </div>

        <div class="login-card">
            <div class="card-content">
                <div class="login-header" style="margin-bottom: 10px">
                    <h1 class="welcome-title">Verify Code</h1>
                    <p class="welcome-subtitle">Enter the 6-digit verification code sent to your email</p>
                </div>

                <form class="login-form" method="POST" action="{{ route('password.otp.verify') }}">
                    @csrf

                    @if($errors->any())
                        <div class="alert alert-error" style="display: block;">
                            <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>
                                @foreach($errors->all() as $error)
                                    {{ $error }}<br>
                                @endforeach
                            </span>
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="alert alert-success" style="display: block;">
                            <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $email) }}" required readonly style="background-color: rgba(24, 27, 39, 0.5); cursor: not-allowed;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="otp" class="form-label">Verification Code</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v4a1 1 0 00.293.707l2 2a1 1 0 001.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                            </svg>
                            <input type="text" id="otp" name="otp" class="form-input @error('otp') is-invalid @enderror" placeholder="Enter 6-digit code" value="{{ old('otp') }}" required inputmode="numeric" pattern="\d{6}" maxlength="6" autocomplete="one-time-code" autofocus>
                        </div>
                        @error('otp')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="login-button">
                        <span class="button-text">Verify Code</span>
                        <svg class="button-loader" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" stroke-opacity="0.25"/>
                            <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                    </button>

                    <div class="signup-link">
                        <span class="signup-text">Didn’t receive a code?</span>
                        <a href="{{ route('password.request') }}" class="signup-link-text">Send again</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="{{ asset('assets/dashboard/js/login.js') }}"></script>
</body>
</html>

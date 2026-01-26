<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Exception;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Show the registration form.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Show the terms and conditions page.
     */
    public function showTerms()
    {
        return view('auth.terms');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
            'referral_code' => ['required', 'string', 'exists:users,refer_code'],
        ], [
            'referral_code.exists' => 'The referral code does not exist. Please enter a valid referral code.',
        ]);

        // Custom validation for Pakistani phone number
        $phone = $request->input('phone');
        // Remove spaces, dashes, and parentheses
        $cleanPhone = preg_replace('/[\s\-()]/', '', $phone);
        // Remove +92 country code if present
        $cleanPhone = preg_replace('/^\+?92/', '', $cleanPhone);

        // Check if it's a valid Pakistani number (10-11 digits)
        // Should be 11 digits if starts with 0, or 10 digits without 0
        if (!preg_match('/^(0[0-9]{10}|[0-9]{10})$/', $cleanPhone)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['phone' => 'Please enter a valid Pakistani phone number. Format: 03001234567 (11 digits starting with 0) or +92 300 1234567.']);
        }

        // Create user first (without referral code)
        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'referred_by' => $validated['referral_code'],
        ]);

        // Generate referral code using user's real name and ID
        $referCode = User::generateReferralCode($validated['name'], $user->id);
        
        // Update user with referral code
        $user->update(['refer_code' => $referCode]);
        $user->refresh();

        // Auto-login user after registration
        Auth::login($user);

        return redirect()->route('dashboard.index')->with('success', 'Registration successful! Welcome to the dashboard.');
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Try to authenticate with email only
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard.index'))->with('success', 'Welcome back!');
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show the forgot password form.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle password reset link request.
     */
    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Check if user exists with this email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }

        try {
            // Try to send password reset notification normally
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                // If using log driver, also show the link on the page for development
                if (config('mail.default') === 'log' && app()->environment(['local', 'development', 'testing'])) {
                    // Get the token from the database that was just created by sendResetLink
                    // The token in DB is hashed, so we need to create a new one to get plain token
                    $token = Str::random(64);
                    $hashedToken = hash('sha256', $token);

                    // Update the database with the new token
                    DB::table('password_reset_tokens')->updateOrInsert(
                        ['email' => $request->email],
                        [
                            'token' => $hashedToken,
                            'created_at' => now()
                        ]
                    );

                    $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->email]);

                    return back()->with('status', 'Password reset link has been generated! Check your email or use the link below:')
                                 ->with('dev_reset_url', $resetUrl);
                }

                return back()->with('status', 'We have emailed your password reset link!');
            }

            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        } catch (Exception $e) {
            // Log the error for debugging
            Log::error('Password reset email failed: ' . $e->getMessage());

            // Check if it's a mail connection error
            if (str_contains($e->getMessage(), 'Connection could not be established') ||
                str_contains($e->getMessage(), 'getaddrinfo') ||
                str_contains($e->getMessage(), 'mailpit')) {

                // For development: Generate token manually using the same method Laravel uses
                if (app()->environment(['local', 'development', 'testing'])) {
                    // Delete any existing token first to ensure we get a fresh one
                    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

                    // Create token the same way Laravel does internally
                    // Generate a random 64-character token
                    $token = Str::random(64);
                    // Hash it using SHA-256 (same as Laravel does for password reset tokens)
                    $hashedToken = hash('sha256', $token);

                    // Store the hashed token in the database
                    DB::table('password_reset_tokens')->insert([
                        'email' => $request->email,
                        'token' => $hashedToken,
                        'created_at' => now()
                    ]);

                    // Create the reset URL with the plain token
                    $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->email]);

                    return back()->with('status', 'Password reset link generated! Since mail is not configured, use the link below:')
                                 ->with('dev_reset_url', $resetUrl);
                }

                return back()->withErrors([
                    'email' => 'Unable to send password reset email. Please contact support or check your mail configuration.'
                ]);
            }

            // Re-throw other exceptions
            throw $e;
        }
    }

    /**
     * Show the password reset form.
     */
    public function showResetPasswordForm(Request $request, $token = null)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Handle password reset.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }

        // Verify the token
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenData) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'This password reset token is invalid.']);
        }

        // Check if token matches (Laravel hashes tokens with SHA-256)
        $hashedToken = hash('sha256', $request->token);
        if (!hash_equals($tokenData->token, $hashedToken)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'This password reset token is invalid.']);
        }

        // Check if token has expired (default: 60 minutes)
        $expiresAt = config('auth.passwords.users.expire', 60);
        if (now()->diffInMinutes($tokenData->created_at) > $expiresAt) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'This password reset token has expired.']);
        }

        // Update the password using query builder to bypass Eloquent casts
        // This ensures the password is hashed with bcrypt without cast interference
        DB::table('users')
            ->where('id', $user->id)
            ->update(['password' => Hash::make($request->password)]);

        // Delete the used token
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return redirect()->route('login')->with('success', 'Your password has been reset successfully. You can now login with your new password.');
    }
}


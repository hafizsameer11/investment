<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetOtpNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Exception;

class AuthController extends Controller
{
    private function normalizePakistaniPhone(?string $phone): ?string
    {
        if ($phone === null) {
            return null;
        }

        $digitsOnly = preg_replace('/\D+/', '', $phone);
        if ($digitsOnly === '') {
            return null;
        }

        // Strip leading country code 92 if present
        if (preg_match('/^92\d{10}$/', $digitsOnly)) {
            $digitsOnly = substr($digitsOnly, 2);
        }

        // Accept 10 digits (without leading 0) or 11 digits starting with 0
        if (preg_match('/^\d{10}$/', $digitsOnly)) {
            return '0' . $digitsOnly;
        }

        if (preg_match('/^0\d{10}$/', $digitsOnly)) {
            return $digitsOnly;
        }

        return null;
    }

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
        $referralCode = request()->query('ref');

        return view('auth.register', [
            'referralCode' => $referralCode,
        ]);
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
        $normalizedPhone = $this->normalizePakistaniPhone($request->input('phone'));
        if ($normalizedPhone === null) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['phone' => 'Please enter a valid Pakistani phone number. Format: 03001234567 (11 digits starting with 0) or +92 300 1234567.']);
        }

        // Validate against the normalized phone so different formats cannot bypass uniqueness
        $request->merge(['phone' => $normalizedPhone]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users', 'phone')],
            'password' => ['required', 'string', 'min:8'],
            'referral_code' => ['required', 'string', 'exists:users,refer_code'],
        ], [
            'referral_code.exists' => 'The referral code does not exist. Please enter a valid referral code.',
            'phone.unique' => 'An account with this phone number already exists.',
        ]);

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

        $email = $request->email;
        $genericStatus = 'If your email exists in our system, a verification code has been sent.';

        $user = User::where('email', $email)->first();
        if ($user) {
            $latestOtpRow = DB::table('password_reset_otps')
                ->where('email', $email)
                ->orderByDesc('id')
                ->first();

            if ($latestOtpRow && $latestOtpRow->last_sent_at) {
                $secondsSinceLastSend = now()->diffInSeconds($latestOtpRow->last_sent_at);
                if ($secondsSinceLastSend < 60) {
                    return redirect()->route('password.otp.form', ['email' => $email])->with('status', $genericStatus);
                }
            }

            $otp = (string) random_int(100000, 999999);

            DB::table('password_reset_otps')->where('email', $email)->delete();
            DB::table('password_reset_otps')->insert([
                'email' => $email,
                'otp_hash' => Hash::make($otp),
                'expires_at' => now()->addMinutes(10),
                'used_at' => null,
                'attempts' => 0,
                'last_sent_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            try {
                $user->notify(new PasswordResetOtpNotification($otp, 10));
            } catch (Exception $e) {
                Log::error('Password reset OTP email failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('password.otp.form', ['email' => $email])->with('status', $genericStatus);
    }

    public function showVerifyOtpForm(Request $request)
    {
        $email = $request->query('email');

        return view('auth.verify-otp', [
            'email' => $email,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'digits:6'],
        ]);

        $email = $request->email;
        $otp = (string) $request->otp;

        $otpRow = DB::table('password_reset_otps')
            ->where('email', $email)
            ->whereNull('used_at')
            ->orderByDesc('id')
            ->first();

        if (!$otpRow) {
            return back()->withInput($request->only('email'))->withErrors(['otp' => 'Invalid or expired verification code.']);
        }

        if (now()->greaterThan($otpRow->expires_at)) {
            return back()->withInput($request->only('email'))->withErrors(['otp' => 'Invalid or expired verification code.']);
        }

        if (($otpRow->attempts ?? 0) >= 5) {
            return back()->withInput($request->only('email'))->withErrors(['otp' => 'Invalid or expired verification code.']);
        }

        if (!Hash::check($otp, $otpRow->otp_hash)) {
            DB::table('password_reset_otps')->where('id', $otpRow->id)->update([
                'attempts' => ($otpRow->attempts ?? 0) + 1,
                'updated_at' => now(),
            ]);

            return back()->withInput($request->only('email'))->withErrors(['otp' => 'Invalid or expired verification code.']);
        }

        DB::table('password_reset_otps')->where('id', $otpRow->id)->update([
            'used_at' => now(),
            'updated_at' => now(),
        ]);

        $request->session()->put('password_reset_email', $email);
        $request->session()->put('password_reset_verified_at', now()->toISOString());

        return redirect()->route('password.reset.otp.form');
    }

    public function showResetPasswordOtpForm(Request $request)
    {
        $email = $request->session()->get('password_reset_email');
        $verifiedAt = $request->session()->get('password_reset_verified_at');

        if (!$email || !$verifiedAt) {
            return redirect()->route('password.request');
        }

        if (now()->diffInMinutes(Carbon::parse($verifiedAt)) > 10) {
            $request->session()->forget(['password_reset_email', 'password_reset_verified_at']);
            return redirect()->route('password.request');
        }

        return view('auth.reset-password-otp', [
            'email' => $email,
        ]);
    }

    public function resetPasswordWithOtp(Request $request)
    {
        $email = $request->session()->get('password_reset_email');
        $verifiedAt = $request->session()->get('password_reset_verified_at');

        if (!$email || !$verifiedAt) {
            return redirect()->route('password.request');
        }

        if (now()->diffInMinutes(Carbon::parse($verifiedAt)) > 10) {
            $request->session()->forget(['password_reset_email', 'password_reset_verified_at']);
            return redirect()->route('password.request');
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::where('email', $email)->first();
        if (!$user) {
            $request->session()->forget(['password_reset_email', 'password_reset_verified_at']);
            return redirect()->route('password.request');
        }

        DB::table('users')
            ->where('id', $user->id)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_otps')->where('email', $email)->delete();
        $request->session()->forget(['password_reset_email', 'password_reset_verified_at']);

        return redirect()->route('login')->with('success', 'Your password has been reset successfully. You can now login with your new password.');
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


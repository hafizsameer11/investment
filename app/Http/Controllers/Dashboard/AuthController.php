<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'referral_code' => ['required', 'string', 'exists:users,refer_code'],
        ], [
            'referral_code.exists' => 'The referral code does not exist. Please enter a valid referral code.',
        ]);

        // Custom validation for Pakistani phone number
        if ($request->filled('phone')) {
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
        }

        // Generate referral code for the new user
        $referCode = User::generateReferralCode($validated['name']);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'refer_code' => $referCode,
            'referred_by' => $validated['referral_code'],
        ]);

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
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Try to authenticate with email or username
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])
            ->orWhere('username', $credentials['email'])
            ->first();

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
}


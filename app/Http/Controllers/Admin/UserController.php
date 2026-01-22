<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get the current admin's referral code
        $adminReferCode = auth()->user()->refer_code;
        return view('admin.users.create', compact('adminReferCode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
            'referral_code' => ['required', 'string', 'exists:users,refer_code'],
        ], [
            'referral_code.exists' => 'The referral code does not exist. Please enter a valid referral code.',
        ]);

        // Generate referral code for the new user
        $referCode = User::generateReferralCode($validated['name']);

        // Create user
        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'refer_code' => $referCode,
            'referred_by' => $validated['referral_code'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        
        // Find referrer by matching referral code
        $referrer = null;
        if ($user->referred_by) {
            $referrer = User::where('refer_code', $user->referred_by)->first();
        }
        
        // Find referrals by matching this user's referral code
        $referrals = User::where('referred_by', $user->refer_code)->get();
        
        return view('admin.users.show', compact('user', 'referrer', 'referrals'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'string', 'in:admin,user'],
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

        // Update user
        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->role = $validated['role'];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            $userName = $user->name;
            $user->delete();
            
            $message = "User '{$userName}' has been deleted successfully.";
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $message]);
            }
            
            return redirect()->route('admin.users.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            $message = 'Failed to delete user. Please try again.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return redirect()->route('admin.users.index')
                ->with('error', $message);
        }
    }
}


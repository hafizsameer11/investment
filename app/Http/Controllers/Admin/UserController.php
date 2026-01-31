<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Investment;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
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

        if (preg_match('/^92\d{10}$/', $digitsOnly)) {
            $digitsOnly = substr($digitsOnly, 2);
        }

        if (preg_match('/^\d{10}$/', $digitsOnly)) {
            return '0' . $digitsOnly;
        }

        if (preg_match('/^0\d{10}$/', $digitsOnly)) {
            return $digitsOnly;
        }

        return null;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $q = request()->query('q');

        $usersQuery = User::query()->orderBy('created_at', 'desc');

        if (!empty($q)) {
            $usersQuery->where(function ($query) use ($q) {
                $query->where('email', 'like', '%' . $q . '%')
                    ->orWhere('phone', 'like', '%' . $q . '%');
            });
        }

        $users = $usersQuery->get();
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
        if ($request->filled('phone')) {
            $normalizedPhone = $this->normalizePakistaniPhone($request->input('phone'));
            if ($normalizedPhone === null) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['phone' => 'Please enter a valid Pakistani phone number. Format: 03001234567 (11 digits starting with 0) or +92 300 1234567.']);
            }

            $request->merge(['phone' => $normalizedPhone]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone')],
            'password' => ['required', 'string', 'min:8'],
            'referral_code' => ['required', 'string', 'exists:users,refer_code'],
        ], [
            'referral_code.exists' => 'The referral code does not exist. Please enter a valid referral code.',
            'phone.unique' => 'An account with this phone number already exists.',
        ]);

        // Create user first (without referral code)
        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'referred_by' => $validated['referral_code'],
        ]);

        // Generate referral code using user's real name and ID
        $referCode = User::generateReferralCode($validated['name'], $user->id);
        
        // Update user with referral code
        $user->update(['refer_code' => $referCode]);
        $user->refresh();

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

        $miningEarning = (float) ($user->mining_earning ?? 0);
        $referralEarning = (float) ($user->referral_earning ?? 0);
        $netBalance = (float) ($user->net_balance ?? ($miningEarning + $referralEarning));

        $totalEarnings = $miningEarning + $referralEarning;

        $totalInvested = Investment::where('user_id', $user->id)->sum('amount');

        $activeInvestments = Investment::where('user_id', $user->id)
            ->where('status', 'active')
            ->with('miningPlan')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalDeposited = Deposit::where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('amount');

        $totalWithdrawn = Withdrawal::where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('amount');

        return view('admin.users.show', compact(
            'user',
            'referrer',
            'referrals',
            'miningEarning',
            'referralEarning',
            'netBalance',
            'totalEarnings',
            'totalInvested',
            'activeInvestments',
            'totalDeposited',
            'totalWithdrawn'
        ));
    }

    public function adjustBalance(Request $request, $id)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:add,deduct'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $user = User::findOrFail($id);

        DB::transaction(function () use ($user, $validated) {
            $user->refresh();
            $user = User::where('id', $user->id)->lockForUpdate()->first();

            $current = (float) ($user->fund_wallet ?? 0);
            $amount = (float) $validated['amount'];

            if ($validated['type'] === 'add') {
                $user->fund_wallet = $current + $amount;
            } else {
                $newBalance = $current - $amount;
                if ($newBalance < 0) {
                    $newBalance = 0;
                }
                $user->fund_wallet = $newBalance;
            }

            $user->save();
        });

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User balance updated successfully.');
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

        if ($request->filled('phone')) {
            $normalizedPhone = $this->normalizePakistaniPhone($request->input('phone'));
            if ($normalizedPhone === null) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['phone' => 'Please enter a valid Pakistani phone number. Format: 03001234567 (11 digits starting with 0) or +92 300 1234567.']);
            }

            $request->merge(['phone' => $normalizedPhone]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'string', 'in:admin,user'],
        ], [
            'phone.unique' => 'An account with this phone number already exists.',
        ]);

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


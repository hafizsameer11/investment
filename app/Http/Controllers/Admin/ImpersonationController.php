<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function start(Request $request, $userId)
    {
        $admin = Auth::user();

        if (!$admin || $admin->role !== 'admin') {
            return redirect()->route('admin.login')->with('error', 'You must be an admin to impersonate a user.');
        }

        $user = User::findOrFail($userId);

        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'You cannot impersonate an admin account.');
        }

        $request->session()->put('impersonator_id', $admin->id);
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard.index');
    }

    public function stop(Request $request)
    {
        $impersonatorId = $request->session()->get('impersonator_id');

        if (!$impersonatorId) {
            return redirect()->route('dashboard.index');
        }

        $admin = User::find($impersonatorId);

        if (!$admin || $admin->role !== 'admin') {
            $request->session()->forget('impersonator_id');
            return redirect()->route('login');
        }

        $request->session()->forget('impersonator_id');
        Auth::login($admin);
        $request->session()->regenerate();

        return redirect()->route('admin.index');
    }
}

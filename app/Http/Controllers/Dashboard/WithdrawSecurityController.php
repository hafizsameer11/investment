<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class WithdrawSecurityController extends Controller
{
    /**
     * Show the withdraw security page.
     */
    public function index()
    {
        return view('dashboard.pages.withdraw-security');
    }
}


<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class WalletController extends Controller
{
    /**
     * Show the wallet page.
     */
    public function index()
    {
        return view('dashboard.pages.wallet');
    }
}


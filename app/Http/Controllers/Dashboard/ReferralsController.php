<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class ReferralsController extends Controller
{
    /**
     * Show the referrals page.
     */
    public function index()
    {
        return view('dashboard.pages.referrals');
    }
}


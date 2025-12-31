<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class PlansController extends Controller
{
    /**
     * Show the investment plans page.
     */
    public function index()
    {
        return view('dashboard.pages.plans');
    }
}


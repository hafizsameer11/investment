<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Show the dashboard home page.
     */
    public function index()
    {
        return view('dashboard.index');
    }
}


<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    /**
     * Show the settings page.
     */
    public function index()
    {
        return view('dashboard.pages.settings');
    }
}


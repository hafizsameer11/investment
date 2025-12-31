<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class TargetsController extends Controller
{
    /**
     * Show the targets page.
     */
    public function index()
    {
        return view('dashboard.pages.targets');
    }
}


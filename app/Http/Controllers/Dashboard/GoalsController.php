<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class GoalsController extends Controller
{
    /**
     * Show the goals page.
     */
    public function index()
    {
        return view('dashboard.pages.goals');
    }
}


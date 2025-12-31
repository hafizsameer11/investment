<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class SupportController extends Controller
{
    /**
     * Show the support page.
     */
    public function index()
    {
        return view('dashboard.pages.support');
    }
}


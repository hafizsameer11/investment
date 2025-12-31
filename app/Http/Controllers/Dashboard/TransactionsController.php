<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class TransactionsController extends Controller
{
    /**
     * Show the transactions page.
     */
    public function index()
    {
        return view('dashboard.pages.transactions');
    }
}


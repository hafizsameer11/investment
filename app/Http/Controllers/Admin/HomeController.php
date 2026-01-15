<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        return view('admin.index');
    }
    public function form(){
        return view('admin.pages.form');
    }
    public function table(){
        return view('admin.pages.table');
    }
}

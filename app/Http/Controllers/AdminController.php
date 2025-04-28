<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function login()
{
    return view('admin.login'); // admin/login.blade.php बनाउनुहोस्
}

public function dashboard()
{
    return view('admin.dashboard'); // admin/dashboard.blade.php बनाउनुहोस्
}
}

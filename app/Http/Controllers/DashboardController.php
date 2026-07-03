<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        if(Auth::user()->role->name === "admin"){

            return view('dashboard.admin_list');

        }else if (Auth::user()->role->name === "user"){

            return view('dashboard.user_list');
            }

    }
}

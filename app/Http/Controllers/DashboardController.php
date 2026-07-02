<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        if(Auth::user()->role->name === "admin"){

            echo "admin";

        }else if (Auth::user()->role->name === "user"){

            echo "user"; die();

            }

    }
}

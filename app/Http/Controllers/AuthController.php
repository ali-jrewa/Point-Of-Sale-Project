<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login() {
        return view('auth.login');
    }

    public function login_post(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember_me');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if(Auth::user()->role->name === "admin"){
                return redirect()->intended('/admin/dashboard');
            } elseif(Auth::user()->role->name === "user"){
                return redirect()->intended('/user/dashboard');
            }

        }

        return redirect()->back()
        ->with('error', 'Authentication failed. Please verify your entries.')
        ->withErrors(['email' => 'The provided credentials do not match our records.'])
        ->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}

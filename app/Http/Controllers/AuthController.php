<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email','password');

        // 1. Attempt to authenticate with credentials
        if (Auth::attempt($credentials)) {
            
            // 2. Check if User is Active
            // If the user is deactivated, log them out immediately.
            if (!Auth::user()->is_active) {
                Auth::logout();
                return back()->with('error', 'Your account has been deactivated. Please contact the administrator.');
            }

            // 3. Check if Pharmacy is Active (Multi-tenancy safety)
            // If the user belongs to a pharmacy, check if that pharmacy is suspended.
            if (Auth::user()->pharmacy_id && !Auth::user()->pharmacy->is_active) {
                Auth::logout();
                return back()->with('error', 'Your pharmacy account is currently suspended.');
            }

            // 4. Successful login
            return redirect()->route('dashboard');
        }

        return back()->with('error','Invalid login details');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\LogService;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Catat aktivitas login
            LogService::logLogin();
            
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Catat aktivitas logout sebelum logout
        LogService::logLogout();
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
} 
<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function tampilLogin() {
        return view('auth.login');
    }
    public function login(Request $request): RedirectResponse {
        $kredensial = $request->validate ([
            'email'=> ['required', 'email'],
            'password'=> ['required'],
        ]);
        if (!Auth::attempt($kredensial)) {
            return back()
                ->withErrors(['email'=> 'Email atau password salah.'])
                ->onlyInput('email');
        }
        $request->session()->regenerate();
        return redirect()->route('beranda');
    }
    public function logout(Request $request): RedirectResponse {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

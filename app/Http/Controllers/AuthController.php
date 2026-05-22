<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $role = strtolower($user->role ?? $user->type ?? '');

            if ($role === 'admin') {
                return redirect()->intended('/dashboard');
            }

            if ($role === 'guardian' || $role === 'parent') {
                return redirect()->intended(route('parent.dashboard'));
            }

            if ($role === 'accountant') {
                return redirect()->intended('/finance');
            }

            if ($role === 'teacher' || $user->teacher) {
                return redirect()->intended('/teacher');
            }

            // KAMA NI MWANAFUNZI NA DASHBOARD YAKE HAIPO: Mtoe nje au mpe ujumbe
            if ($role === 'student') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Dashboard ya wanafunzi bado haijakamilika kusanidiwa.',
                ]);
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(Request $request)
    {
        return view('user.login');
    }

    public function postLogin(Request $request)
    {
        if (Auth::check()) {
            return route('home');
        }

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors(['error' => 'Invalid login credentials'])->withInput();
        }

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect('/')->with('status', 'Logged out successfully');
    }

    public function profile(Request $request)
    {
        return view('user.profile', ['user' => Auth::user()]);
    }
}

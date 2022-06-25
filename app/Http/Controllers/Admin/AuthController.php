<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/admin/bank/viewAll');
        }

        return view('admin.login');
    }

    public function postLogin(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/admin/bank/viewAll');
        }

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::guard('admin')->attempt($credentials)) {
            return back()->withErrors(['error' => 'Invalid login credentials'])->withInput();
        }

        return redirect('/admin/bank/viewAll');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        return redirect('/')->with('status', 'Logged out successfully');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            if($user['role']==2){
                return redirect()->route('users.index');
            }
            else{
                return redirect()->route('home');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    public function home()
    {
        $user = Auth::user();
        return view('home', compact('user'));
    }
    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}

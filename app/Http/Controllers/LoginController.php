<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use App\Services\ManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    protected $managerService;

    public function __construct(ManagerService $managerService)
    {
        $this->managerService = $managerService;
    }

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
        $stock = $this->managerService->getManagerStocks();
        $users = $this->managerService->getManagerUser();
        return view('home', compact('stock', 'users'));
    }
    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}

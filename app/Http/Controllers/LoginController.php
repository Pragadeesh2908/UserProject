<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

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
    public function users($id)
    {
        $user = User::findOrFail($id);
        return view('profile', compact('user'));
    }
    public function showProfile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }
    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'email' => 'required|email|unique:users,email',
            'dob' => 'nullable|date',
            'phone_number' => 'nullable|max:15',
            'password' => 'required|min:8',
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'dob' => $request->dob,
            'phone_number' => $request->phone_number,
            'password' => bcrypt($request->password),
            'role' => 1,
        ]);
        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function index()
    {
        $users =Auth::user();
        $user = User::where('role', 1)->get();
        return view('index', compact('user'));
    }
    public function edit($id)
    {
        $loggedInUser = Auth::user();
        if ($loggedInUser->role == 2 && $id) {
            $user = User::findOrFail($id);
        } else {
            $user = $loggedInUser;
        }
        return view('edit', compact('user', 'loggedInUser'));
    }

    public function update(Request $request, $id)
    {
        $loggedInUser = Auth::user();

        if ($loggedInUser->role == 2 && $id) {
            $user = User::findOrFail($id);
        } else {
            $user = $loggedInUser;
        }

        $request->validate([
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'dob' => 'nullable|date',
            'phone_number' => 'nullable|max:15',
        ]);

        $user->update($request->all());
        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
    public function export()
    {
        return Excel::download(new UserExport, 'users.xlsx');
    }
    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}

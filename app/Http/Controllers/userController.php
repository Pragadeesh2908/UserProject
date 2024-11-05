<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Jobs\SendUserWelcomeEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class userController extends Controller
{
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

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'dob' => $request->dob,
            'phone_number' => $request->phone_number,
            'password' => bcrypt($request->password),
            'role' => 1,
        ]);
        SendUserWelcomeEmail::dispatch($user);
        return redirect()->route('users.index')->with('status', 'User created successfully');
    }

    public function index()
    {
        $users = Auth::user();
        $user = User::where('role', 1)->get();
        return view('index', compact('user'));
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('edit', compact('user'));
    }

    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);

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

        return redirect()->route('users.index')->with('status', 'User deleted successfully');
    }
    public function userStock()
    {
        $user_stock = DB::table('user_stock')
            ->join('users', 'user_stock.user_id', '=', 'users.id')
            ->join('stock', 'user_stock.stock_id', '=', 'stock.id')
            ->select('user_stock.id', 'users.first_name', 'stock.name as stock_name', 'user_stock.quantity')
            ->get();
        return view('userstock', compact('user_stock'));
    }
    public function export()
    {
        return Excel::download(new UserExport, 'users_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx');
    }
}

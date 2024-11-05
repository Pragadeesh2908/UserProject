<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use App\Models\stock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class managerController extends Controller
{
    public function viewManager()
    {
        $managers = Manager::with('stocks', 'users')->get();
        return view('manager.index', compact('managers'));
    }
    public function create()
    {
        $stocks = Stock::where('status', 1)->get();
        $assignedUserIds = DB::table('manager_user')->pluck('user_id');
        $users = User::whereNotIn('id', $assignedUserIds)->get();
        return view('manager.create', compact('stocks', 'users'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'dob' => 'required|date',
            'quantity' => 'required|array',
            'quantity.*' => 'integer|min:0',
            'stock_id' => 'required|array',
            'stock_id.*' => 'exists:stock,id',
            'user_id' => 'required|array',
            'user_id.*' => 'exists:users,id',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'dob' => $validatedData['dob'],
            'password' => Hash::make($validatedData['password']),
            'role' => 3,
        ]);
        $manager = Manager::create([
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'dob' => $user->dob,
            'password' => $user->password,
            'user_id' => $user->id,
        ]);

        $stockErrors = [];
        $userErrors = [];

        foreach ($validatedData['stock_id'] as $key => $stockId) {
            $quantityToAssign = $validatedData['quantity'][$key];

            $currentStock = DB::table('stock')->where('id', $stockId)->first();

            if ($currentStock && $currentStock->quantity >= $quantityToAssign) {
                $manager->stocks()->attach($stockId, ['quantity' => $quantityToAssign]);
                DB::table('stock')->where('id', $stockId)->update(['quantity' => $currentStock->quantity - $quantityToAssign]);
            } else {
                $stockErrors[] = 'Not enough stock available for ' . $currentStock->name;
            }
        }

        foreach ($validatedData['user_id'] as $userId) {
            $existingAssignment = DB::table('manager_user')->where('user_id', $userId)->first();

            if ($existingAssignment) {
                $userErrors[] = 'User with ID ' . $userId . ' is already assigned to another manager.';
            } else {
                $manager->users()->attach($userId);
            }
        }

        if (count($stockErrors) || count($userErrors)) {
            return redirect()->back()->withErrors([
                'stockErrors' => $stockErrors,
                'userErrors' => $userErrors
            ]);
        }

        return redirect()->route('manager.index')->with('status', 'Manager created successfully.');
    }

    public function edit($id)
    {
        $stocks = Stock::where('status', 1)->get();

        $manager = Manager::with('stocks', 'users')->findOrFail($id);
        $assignedUserIds = $manager->users->pluck('id')->toArray();

        $assignedUserIdsOtherManagers = DB::table('manager_user')
            ->whereNotIn('user_id', $assignedUserIds)
            ->pluck('user_id')
            ->toArray();

        $availableUsers = User::whereNotIn('id', $assignedUserIdsOtherManagers)
            ->where('role', 1)
            ->get();

        return view('manager.edit', compact('manager', 'stocks', 'availableUsers', 'assignedUserIds'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:manager,email,' . $id,
            'dob' => 'required|date',
            'quantity' => 'required|array',
            'quantity.*' => 'integer|min:0',
            'stock_id' => 'required|array',
            'stock_id.*' => 'exists:stock,id',
            'user_id' => 'required|array',
            'user_id.*' => 'exists:users,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $manager = Manager::findOrFail($id);

        $manager->update([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'dob' => $validatedData['dob'],
            'password' => isset($validatedData['password']) ? Hash::make($validatedData['password']) : $manager->password,
        ]);

        $manager->stocks()->detach();

        foreach ($validatedData['stock_id'] as $key => $stockId) {
            $quantityToAssign = $validatedData['quantity'][$key];

            $currentStock = Stock::find($stockId);

            if ($currentStock && $currentStock->quantity >= $quantityToAssign) {
                $manager->stocks()->attach($stockId, ['quantity' => $quantityToAssign]);

                $currentStock->update(['quantity' => $currentStock->quantity - $quantityToAssign]);
            } else {
                return redirect()->back()->withErrors(['quantity' => 'Not enough stock available for ' . $currentStock->name]);
            }
        }

        $manager->users()->detach();

        foreach ($validatedData['user_id'] as $userId) {
            $existingAssignment = DB::table('manager_user')->where('user_id', $userId)->first();

            if ($existingAssignment) {
                return redirect()->back()->withErrors(['user_id' => 'User is already assigned to another manager.']);
            }
            $manager->users()->attach($userId);
        }

        return redirect()->route('manager.index')->with('success', 'Manager updated successfully.');
    }


    public function destroy($id)
    {
        $manager = Manager::findOrFail($id);
        $manager->delete();

        return redirect()->route('manager.index')->with('success', 'Manager deleted successfully.');
    }
    public function assignStockToUserForm($userId)
    {
        $managerId = Manager::where('user_id', $userId)->value('id');
        $stocks = DB::table('manager_stock')
            ->where('manager_stock.manager_id', $managerId)
            ->where('manager_stock.quantity', '>', 0)
            ->join('stock', 'manager_stock.stock_id', '=', 'stock.id')
            ->select('stock.id as stock_id', 'stock.name', 'manager_stock.quantity')
            ->get();


        $users = DB::table('manager_user')
            ->where('manager_id', $managerId)
            ->join('users', 'manager_user.user_id', '=', 'users.id')
            ->select('users.id', 'users.first_name', 'users.last_name')
            ->get();
        return view('manager.assign_stock', compact('stocks', 'users', 'managerId'));
    }
    public function assignStockToUser(Request $request)
    {
        $validatedData = $request->validate([
            'manager_id' => 'required|exists:manager,id',
            'user_id' => 'required|exists:users,id',
            'stock_id' => 'required|exists:stock,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $managerId = $validatedData['manager_id'];
        $stockId = $validatedData['stock_id'];
        $userId = $validatedData['user_id'];
        $quantityToAssign = $validatedData['quantity'];

        $managerStock = DB::table('manager_stock')
            ->where('manager_id', $managerId)
            ->where('stock_id', $stockId)
            ->first();

        if ($managerStock && $managerStock->quantity >= $quantityToAssign) {
            DB::table('manager_stock')
                ->where('manager_id', $managerId)
                ->where('stock_id', $stockId)
                ->update(['quantity' => $managerStock->quantity - $quantityToAssign]);

            DB::table('user_stock')->updateOrInsert(
                ['user_id' => $userId, 'stock_id' => $stockId],
                ['quantity' => DB::raw("quantity + $quantityToAssign")]
            );

            return redirect()->route('home')->with('status', 'Stock assigned to user successfully.');
        } else {
            return redirect()->back()->withErrors(['quantity' => 'Not enough stock available for this assignment.']);
        }
    }
}

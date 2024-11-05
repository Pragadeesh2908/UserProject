<?php

namespace App\Services;

use App\Models\Manager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManagerService
{
    public function getManagerStocks()
    {
        $user = Auth::user();
        $manager_id = Manager::where('user_id', $user->id)->value('id');

        $manager_data = DB::table('manager_stock')
            ->join('stock', 'manager_stock.stock_id', '=', 'stock.id') 
            ->where('manager_stock.manager_id', $manager_id) 
            ->select(
                'manager_stock.quantity',
                'stock.name as stock_name'
            )
            ->get();

        return $manager_data;
    }

    public function getManagerUser()
    {
        $user = Auth::user();
        $manager_id = Manager::where('user_id', $user->id)->value('id');

        $users = DB::table('manager_user')
            ->join('users', 'manager_user.user_id', '=', 'users.id')
            ->where('manager_user.manager_id', $manager_id)
            ->select('manager_user.*', 'users.first_name', 'users.last_name')
            ->get();

        return $users;
    }
}

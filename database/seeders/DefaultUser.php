<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => '',
            'last_name' => '',
            'email' => 'pragadeesh11@gmail.com',
            'dob' => null,
            'password' => Hash::make('12345678'), 
            'phone_number' => '',
            'role'=>2,
            'created_at' => now(),
            'updated_at' => now(),
        ]); 
    }
}

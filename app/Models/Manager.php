<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\stock;

class Manager extends Model
{
    use HasFactory;
    protected $table = 'manager';
     protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'dob',
        'password',
    ];

    public function stocks()
    {
        return $this->belongsToMany(stock::class, 'manager_stock')
        ->withPivot('quantity')
        ->withTimestamps(); 
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'manager_user');
    }

}

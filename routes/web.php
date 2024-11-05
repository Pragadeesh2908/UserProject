<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\managerController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\stockController;
use App\Http\Controllers\userController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

//login
Route::get('/', function () {
    return view('login');
});
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('home', [LoginController::class, 'home'])->name('home');

//user
Route::get('users/{id}', [userController::class, 'users'])->name('users.show');
Route::get('user/{id}', [userController::class, 'users'])->middleware('auth');
Route::get('users', [userController::class, 'index'])->name('users.index');
Route::get('profile', [userController::class, 'showProfile'])->middleware('auth')->name('profile');
Route::get('users/{id}/edit', [userController::class, 'edit'])->name('users.edit');
Route::put('users/{id}', [userController::class, 'update'])->name('users.update');
Route::delete('users/{id}', [userController::class, 'destroy'])->name('users.destroy');
Route::get('create', [userController::class, 'create'])->name('users.create');
Route::post('users', [userController::class, 'store'])->name('users.store');
Route::get('/export-users', [userController::class, 'export'])->name('export.users');
Route::get('userstock', [userController::class, 'userStock'])->name('userstock');

//password
Route::get('/password/update', [PasswordController::class, 'showUpdateForm'])->name('password.update.form');
Route::post('/password/update', [PasswordController::class, 'update'])->name('password.update');
Route::get('forgot-password', [PasswordController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('forgot-password', [PasswordController::class, 'submitForgotPassword'])->name('forgot.password');
Route::get('reset-password/{email}', [PasswordController::class, 'resetPassword'])->name('resetPassword');
Route::post('reset-password', [PasswordController::class, 'submitResetPassword'])->name('reset.password');

//stocks
Route::get('stock', [stockController::class, 'viewStock'])->name('stock.index');
Route::get('stock/create', [stockController::class, 'create'])->name('stock.create');
Route::post('stock/store', [stockController::class, 'store'])->name('stock.store');
Route::get('stock/{id}/edit', [stockController::class, 'edit'])->name('stock.edit');
Route::delete('stock/{id}', [stockController::class, 'destroy'])->name('stock.destroy');
Route::put('stock/{id}', [stockController::class, 'update'])->name('stock.update');

//manager
Route::get('managers', [ManagerController::class, 'viewManager'])->name('manager.index');
Route::get('managers/create', [managerController::class, 'create'])->name('manager.create');
Route::post('managers/store', [ManagerController::class, 'store'])->name('manager.store');
Route::get('managers/{id}/edit', [ManagerController::class, 'edit'])->name('manager.edit');
Route::put('managers/{id}', [ManagerController::class, 'update'])->name('manager.update');
Route::delete('managers/{id}', [ManagerController::class, 'destroy'])->name('manager.destroy');
Route::get('manager/{managerId}/assign-stock', [ManagerController::class, 'assignStockToUserForm'])->name('manager.assignStockToUserForm');
Route::post('manager/assign-stock', [ManagerController::class, 'assignStockToUser'])->name('manager.assignStockToUser');
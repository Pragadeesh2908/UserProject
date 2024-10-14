<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordController;

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
Route::get('/', function () {
    return view('login');
});
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login');

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('home', [LoginController::class, 'home'])->name('home');

Route::get('users/{id}', [LoginController::class, 'users'])->name('users.show');

Route::get('user/{id}', [LoginController::class, 'users'])->middleware('auth');
Route::get('users', [LoginController::class, 'index'])->name('users.index');
Route::get('profile', [LoginController::class, 'showProfile'])->middleware('auth')->name('profile');
Route::get('users/{id}/edit', [LoginController::class, 'edit'])->name('users.edit');
Route::put('users/{id}', [LoginController::class, 'update'])->name('users.update');
Route::delete('users/{id}', [LoginController::class, 'destroy'])->name('users.destroy');

Route::get('create', [LoginController::class, 'create'])->name('users.create');
Route::post('users', [LoginController::class, 'store'])->name('users.store');

Route::get('/password/update', [PasswordController::class, 'showUpdateForm'])->name('password.update.form');
Route::post('/password/update', [PasswordController::class, 'update'])->name('password.update');


Route::get('forgot-password', [PasswordController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('forgot-password', [PasswordController::class, 'submitForgotPassword'])->name('forgot.password');
Route::get('reset-password/{email}', [PasswordController::class, 'resetPassword'])->name('resetPassword');
Route::post('reset-password', [PasswordController::class, 'submitResetPassword'])->name('reset.password');

Route::get('/export-users', [LoginController::class, 'export'])->name('export.users');

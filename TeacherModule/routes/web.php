<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/register', [AuthController::class, 'index'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/store', [AuthController::class, 'store'])->name('register.post');



Route::group(['middleware' => ['auth', 'role:1']], function () {
    // Admin routes
    Route::get('/admin-dashboard', 'AdminController@index')->name('admin.dashboard');
});

Route::group(['middleware' => ['auth', 'role:2']], function () {
    // Teacher routes
    Route::get('/teacher-dashboard', 'TeacherController@index')->name('teacher.dashboard');
});

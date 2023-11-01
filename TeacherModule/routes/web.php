<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeacherController;
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


Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Admin routes
Route::group(['middleware' => ['auth', 'checkUserRole:1']], function () {
    Route::get('/admin-dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin-roles', [AdminController::class, 'getRoles'])->name('admin.roles');
});

// Teacher routes
Route::group(['middleware' => ['auth', 'checkUserRole:2']], function () {
    Route::get('/teacher-dashboard', [TeacherController::class, 'index'])->name('teacher.dashboard');
});

Route::group(['middleware' => 'auth'], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Modify/Remove later
Route::post('/store', [AuthController::class, 'store'])->name('register.post');

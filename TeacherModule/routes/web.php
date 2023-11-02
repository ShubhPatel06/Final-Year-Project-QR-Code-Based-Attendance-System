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
    // Roles
    Route::get('/admin-roles', [AdminController::class, 'getRoles'])->name('admin.roles');
    Route::post('/create-role', [AdminController::class, 'storeRole'])->name('role.store');
    Route::get('/edit-role/{id}', [AdminController::class, 'editRole'])->name('role.edit');
    Route::delete('/delete-role/{id}', [AdminController::class, 'deleteRole'])->name('role.delete');
    // Faculty
    Route::get('/admin-faculties', [AdminController::class, 'getFaculties'])->name('admin.faculties');
    Route::post('/create-faculty', [AdminController::class, 'storeFaculty'])->name('faculty.store');
    Route::get('/edit-faculty/{id}', [AdminController::class, 'editFaculty'])->name('faculty.edit');
    Route::delete('/delete-faculty/{id}', [AdminController::class, 'deleteFaculty'])->name('faculty.delete');
    // Course
    Route::get('/admin-courses', [AdminController::class, 'getCourses'])->name('admin.courses');
    Route::post('/create-course', [AdminController::class, 'storeCourse'])->name('course.store');
    Route::get('/edit-course/{id}', [AdminController::class, 'editCourse'])->name('course.edit');
    Route::delete('/delete-course/{id}', [AdminController::class, 'deleteCourse'])->name('course.delete');
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

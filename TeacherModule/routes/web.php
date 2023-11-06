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
    // Route::delete('/delete-role/{id}', [AdminController::class, 'deleteRole'])->name('role.delete');

    // User
    Route::get('/admin-users', [AdminController::class, 'getUsers'])->name('admin.users');
    Route::post('/create-user', [AdminController::class, 'storeUser'])->name('user.store');
    Route::get('/edit-user/{id}', [AdminController::class, 'editUser'])->name('user.edit');
    // Route::delete('/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('user.delete');

    // Faculty
    Route::get('/admin-faculties', [AdminController::class, 'getFaculties'])->name('admin.faculties');
    Route::post('/create-faculty', [AdminController::class, 'storeFaculty'])->name('faculty.store');
    Route::get('/edit-faculty/{id}', [AdminController::class, 'editFaculty'])->name('faculty.edit');
    // Route::delete('/delete-faculty/{id}', [AdminController::class, 'deleteFaculty'])->name('faculty.delete');

    // Course
    Route::get('/admin-courses', [AdminController::class, 'getCourses'])->name('admin.courses');
    Route::post('/create-course', [AdminController::class, 'storeCourse'])->name('course.store');
    Route::get('/edit-course/{id}', [AdminController::class, 'editCourse'])->name('course.edit');
    // Route::delete('/delete-course/{id}', [AdminController::class, 'deleteCourse'])->name('course.delete');

    // Lecturer
    Route::get('/admin-lecturers', [AdminController::class, 'getLecturers'])->name('admin.lecturers');
    Route::post('/create-lecturer', [AdminController::class, 'storeLecturer'])->name('lecturer.store');
    Route::get('/get-lecturer/{id}', [AdminController::class, 'getLecturerByID'])->name('lecturer.get');
    Route::post('/edit-lecturer', [AdminController::class, 'editLecturer'])->name('lecturer.edit');
    // Route::delete('/delete-lecturer/{id}', [AdminController::class, 'deleteLecturer'])->name('lecturer.delete');

    // Lectures
    Route::get('/admin-lectures', [AdminController::class, 'getLectures'])->name('admin.lectures');
    Route::post('/create-lecture', [AdminController::class, 'storeLecture'])->name('lecture.store');
    Route::get('/edit-lecture/{id}', [AdminController::class, 'editLecture'])->name('lecture.edit');
    // Route::delete('/delete-lecture/{id}', [AdminController::class, 'deleteLecture'])->name('lecture.delete');

    // Groups
    Route::get('/admin-groups', [AdminController::class, 'getGroups'])->name('admin.groups');
    Route::post('/create-group', [AdminController::class, 'storeGroup'])->name('group.store');
    Route::get('/edit-group/{id}', [AdminController::class, 'editGroup'])->name('group.edit');
    // Route::delete('/delete-group/{id}', [AdminController::class, 'deleteGroup'])->name('group.delete');

    // Lecture Groups
    Route::get('/admin-lecture_groups', [AdminController::class, 'getLectureGroups'])->name('admin.lecture_groups');
    Route::post('/create-lecture_group', [AdminController::class, 'storeLectureGroup'])->name('lecture_group.store');
    Route::delete('/delete-lecture_group', [AdminController::class, 'deleteLectureGroup'])->name('lecture_group.delete');
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

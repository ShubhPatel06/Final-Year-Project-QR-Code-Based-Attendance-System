<?php

use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [StudentController::class, 'login']);

// Sanctum routes for token management
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [StudentController::class, 'logout']);
    Route::post('/update-attendance', [StudentController::class, 'updateAttendance']);
    Route::get('/get-groups/{admNo}', [StudentController::class, 'getGroups']);
    Route::get('/get-lectures/{admNo}/{groupID}', [StudentController::class, 'getLectures']);
    Route::get('/get-attendance-records/{admNo}/{lectureID}/{groupID}', [StudentController::class, 'getAttendanceRecords']);
});

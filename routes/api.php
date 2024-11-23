<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DepartmentController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Department Routes
Route::get('/departments', [DepartmentController::class, 'index']);
Route::post('/departments', [DepartmentController::class, 'store']);
Route::put('/departments/{id}', [DepartmentController::class, 'update']);
Route::delete('/departments/{id}', [DepartmentController::class, 'destroy']);

Route::group(['middleware' => ["auth:sanctum", "verified"]], function () {
    // 1 get profile
    // 2 post update profile
    Route::get('get-profile', [AuthController::class, 'getProfileDetails']);
    Route::post('update-profile', [AuthController::class, 'updateProfileDetails']);
});

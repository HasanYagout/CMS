<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

// Routes that require authentication and custom token
Route::group(['middleware' => ['auth:sanctum', 'token']], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/notifications', [HomeController::class, 'notifications']);
});

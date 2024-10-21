<?php

use App\Http\Controllers\Auth\LoginController;
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

Route::get('/', [LoginController::class,'login'])->name('login');
Route::get('/courses', [\App\Http\Controllers\Admin\CourseController::class,'index']);

    Route::post('login',[LoginController::class,'submit'])->name('submit');
    Route::get('logout', [LoginController::class,'logout'])->name('logout');
    Route::get('register', [LoginController::class,'register'])->name('register');
    Route::post('store', [LoginController::class,'store'])->name('store');


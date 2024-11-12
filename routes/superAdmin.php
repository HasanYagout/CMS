<?php

use App\Http\Controllers\Super\AdminController;
use App\Http\Controllers\Super\DashboardController as SuperDashboardController;
use App\Http\Controllers\Super\ProfileController as SuperProfileController;
use App\Http\Controllers\Super\SemesterController;
use App\Http\Controllers\Super\DepartmentController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['super'], 'prefix' => 'superAdmin', 'as' => 'superAdmin.'], function () {

    Route::controller(SuperDashboardController::class)->group(function () {
        Route::get('/', 'index')->name('dashboard');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::post('/updateStatus', 'updateStatus')->name('updateStatus');
    });
    // Dashboard Routes
    Route::controller(AdminController::class)->group(function () {
        Route::prefix('admin')->as('admin.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/update/{id}', 'update')->name('update');
            Route::post('/updateStatus', 'updateStatus')->name('updateStatus');
        });

    });

    // Profile Routes
    Route::controller(SuperProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile');
        Route::post('/profile_update', 'update')->name('profile.update');
        Route::post('/profile_password', 'password')->name('profile.password');
    });

    // Semester Routes
    Route::prefix('semesters')->as('semesters.')->controller(SemesterController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::post('/updateStatus', 'updateStatus')->name('updateStatus');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::post('/delete/{id}', 'delete')->name('delete');
    });

    // Department Routes
    Route::prefix('department')->as('department.')->controller(DepartmentController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::post('/updateStatus', 'updateStatus')->name('updateStatus');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::post('/delete/{id}', 'delete')->name('delete');
    });
});

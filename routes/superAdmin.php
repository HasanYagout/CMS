<?php


use App\Http\Controllers\Admin\InstructorController;
use App\Http\Controllers\Super\DepartmentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Super\SemesterController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['super'],'prefix'=>'superAdmin','as' => 'superAdmin.'], function () {
    Route::get('/', [\App\Http\Controllers\Super\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/store', [\App\Http\Controllers\Super\DashboardController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [\App\Http\Controllers\Super\DashboardController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [\App\Http\Controllers\Super\DashboardController::class, 'update'])->name('update');
    Route::post('/updateStatus', [\App\Http\Controllers\Super\DashboardController::class, 'updateStatus'])->name('updateStatus');
    Route::get('/profile', [\App\Http\Controllers\Super\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile_update', [\App\Http\Controllers\Super\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile_password', [\App\Http\Controllers\Super\ProfileController::class, 'password'])->name('profile.password');
    Route::group(['prefix'=>'semesters','as' => 'semesters.'], function () {
        Route::get('/', [SemesterController::class, 'index'])->name('index');
        Route::post('/store', [SemesterController::class, 'store'])->name('store');
        Route::post('/updateStatus', [SemesterController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/edit/{id}', [SemesterController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [SemesterController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [SemesterController::class, 'delete'])->name('delete');
    });
    Route::group(['prefix'=>'department','as' => 'department.'], function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('index');
        Route::post('/store', [DepartmentController::class, 'store'])->name('store');
        Route::post('/updateStatus', [DepartmentController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/edit/{id}', [DepartmentController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [DepartmentController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [DepartmentController::class, 'delete'])->name('delete');
    });
});

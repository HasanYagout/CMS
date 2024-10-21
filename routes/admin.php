<?php


use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['admin'],'prefix'=>'admin','as' => 'admin.'], function () {
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::group(['prefix'=>'courses','as' => 'courses.'], function () {
        Route::get('/', [CourseController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [CourseController::class, 'edit'])->name('edit');
        Route::get('/create', [CourseController::class, 'create'])->name('create');
        Route::get('/store', [CourseController::class, 'store'])->name('store');
    });

    Route::group(['prefix'=>'availability','as' => 'availability.'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\AvailabilityController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [CourseController::class, 'edit'])->name('edit');
        Route::get('/create', [\App\Http\Controllers\Admin\AvailabilityController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Admin\AvailabilityController::class, 'store'])->name('store');
        Route::get('/availability', [\App\Http\Controllers\Admin\AvailabilityController::class, 'getAvailabilityByInstructor'])->name('getAvailabilityByInstructor');
    });
});

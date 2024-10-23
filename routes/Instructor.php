<?php


use App\Http\Controllers\Instructor\DashboardController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['instructor'],'prefix'=>'instructor','as' => 'instructor.'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::group(['prefix'=>'courses','as' => 'courses.'], function () {
        Route::get('/', [\App\Http\Controllers\Instructor\CourseController::class, 'index'])->name('index');

        Route::group(['prefix'=>'chapters','as' => 'chapters.'], function () {
            Route::get('/', [\App\Http\Controllers\Instructor\ChapterController::class, 'index'])->name('index');
            Route::post('/store', [\App\Http\Controllers\Instructor\ChapterController::class, 'store'])->name('store');
            Route::post('/update_status/{id}', [\App\Http\Controllers\Instructor\ChapterController::class, 'status'])->name('status');

        });
    });
});

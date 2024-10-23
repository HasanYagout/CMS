<?php


use App\Http\Controllers\Student\DashboardController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['student'],'prefix'=>'student','as' => 'student.'], function () {
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::group(['prefix'=>'courses','as' => 'courses.'], function () {
    Route::get('/courses', [\App\Http\Controllers\Student\CourseController::class, 'index'])->name('index');
    Route::post('/register', [\App\Http\Controllers\Student\CourseController::class, 'store'])->name('register');
    Route::get('/info/{slug}', [\App\Http\Controllers\Student\CourseController::class, 'info'])->name('info');

});

});

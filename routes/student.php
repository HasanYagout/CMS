<?php


use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\EnrollmentController;
use App\Http\Controllers\Student\ExamController;
use App\Http\Controllers\Student\QuizController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['student'],'prefix'=>'student','as' => 'student.'], function () {
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::group(['prefix'=>'courses','as' => 'courses.'], function () {
    Route::get('/courses', [\App\Http\Controllers\Student\CourseController::class, 'index'])->name('index');
    Route::post('/register', [\App\Http\Controllers\Student\CourseController::class, 'store'])->name('register');
    Route::get('/info/{slug}', [\App\Http\Controllers\Student\CourseController::class, 'info'])->name('info');
    Route::group(['prefix'=>'chapters','as' => 'chapters.'], function () {
        Route::get('view/{id}', [\App\Http\Controllers\Student\ChapterController::class,'index'])->name('view');

    });
    Route::group(['prefix'=>'assignments','as' => 'assignments.'], function () {
        Route::post('store/{id}', [\App\Http\Controllers\Student\AssignmentController::class,'store'])->name('store');

    });

    Route::group(['prefix'=>'quiz','as' => 'quiz.'], function () {
        Route::get('/{quiz}', [QuizController::class, 'show'])->name('show');
        Route::post('/remaining_time', [QuizController::class, 'remaining_time'])->name('remaining_time');

    });
    Route::group(['prefix'=>'enrollment','as' => 'enrollment.'], function () {
        Route::get('/courses', [EnrollmentController::class,'index'])->name('index');
        Route::get('/course/{id}', [EnrollmentController::class,'view'])->name('course');
        Route::post('register/{id}', [EnrollmentController::class,'register'])->name('register');

    });
});


});

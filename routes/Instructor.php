<?php

use App\Http\Controllers\Instructor\ActivityController;
use App\Http\Controllers\Instructor\AnnouncementController;
use App\Http\Controllers\Instructor\AssignmentController;
use App\Http\Controllers\Instructor\DashboardController;
use App\Http\Controllers\Instructor\CourseController;
use App\Http\Controllers\Instructor\ChapterController;
use App\Http\Controllers\Instructor\LectureController;
use App\Http\Controllers\Instructor\MaterialController;
use App\Http\Controllers\Instructor\QuizController;
use Illuminate\Support\Facades\Route;

// Instructor routes group
Route::group(['middleware' => ['instructor'], 'prefix' => 'instructor', 'as' => 'instructor.'], function () {

    // Dashboard route
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Courses routes
    Route::group(['prefix' => 'courses', 'as' => 'courses.'], function () {

        // Course-related routes
        Route::controller(CourseController::class)->group(function () {
            Route::get('/', 'index')->name('index');
        });

        // Chapters routes within courses
        Route::group(['prefix' => 'chapters', 'as' => 'chapters.'], function () {
            Route::controller(ChapterController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::post('/update/{id}', 'status')->name('status');
                Route::get('/get/{id}', 'getChapterByCourseId')->name('get');
            });
        });
        Route::group(['prefix' => 'lectures', 'as' => 'lectures.'], function () {
            Route::controller(LectureController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::get('/get/{id}', 'getLecturesByCourseId')->name('get');

            });
            Route::group(['prefix'=>'activities','as' => 'activities.'], function () {
                Route::controller(ActivityController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/store', 'store')->name('store');

                });
            });
        });

        // Materials routes within courses
        Route::group(['prefix' => 'materials', 'as' => 'materials.'], function () {
            Route::controller(MaterialController::class)->group(function () {
                Route::get('/index', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
            });
        });

        // Assignments routes within courses
        Route::group(['prefix' => 'assignments', 'as' => 'assignments.'], function () {
            Route::controller(AssignmentController::class)->group(function () {
                Route::get('/index', 'index')->name('index');
                Route::post('/store', 'store')->name('store');

            });
        });
        Route::group(['prefix' => 'quiz', 'as' => 'quiz.'], function () {
            Route::controller(QuizController::class)->group(function () {
                Route::get('/index', 'index')->name('index');
                Route::post('/store', 'store')->name('store');

            });
        });


        Route::group(['prefix' => 'announcement', 'as' => 'announcement.'], function () {
            Route::controller(AnnouncementController::class)->group(function () {
                Route::get('/index', 'index')->name('index');
                Route::post('/store', 'store')->name('store');

            });
        });
    });
});

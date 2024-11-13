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
use App\Http\Controllers\Instructor\StudentController;
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
                Route::post('/updateStatus/{id}', 'updateStatus')->name('updateStatus');
                Route::get('/get/{id}', 'getChaptersAndAvailability')->name('get');
                Route::get('/getchapters/{id}', 'getChapters')->name('getChapters');
                Route::get('/availability/{courseId}', 'getAvailability')->name('getavailability');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/update/{id}', 'update')->name('update');
                Route::post('/delete/{id}', 'delete')->name('delete');
            });
        });

        Route::group(['prefix' => 'attendance', 'as' => 'attendance.'], function () {
            Route::controller(\App\Http\Controllers\Instructor\AttendanceController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/update', 'update')->name('update');

            });
        });

        Route::group(['prefix' => 'lectures', 'as' => 'lectures.'], function () {
            Route::controller(LectureController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::get('/get/{id}', 'getLecturesByCourseId')->name('get');
                Route::get('/latest/{course_id}', 'getLatestLecture')->name('latest');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/update/{id}', 'update')->name('update');
                Route::post('/delete/{id}', 'delete')->name('delete');
                Route::post('/updateStatus/{id}', 'updateStatus')->name('updateStatus');

            });
            Route::group(['prefix' => 'activities', 'as' => 'activities.'], function () {
                Route::controller(ActivityController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/store', 'store')->name('store');
                    Route::get('/edit/{id}', 'edit')->name('edit');
                    Route::post('/update/{id}', 'update')->name('update');
                    Route::post('/delete/{id}', 'delete')->name('delete');
                    Route::post('/updateStatus/{id}', 'updateStatus')->name('updateStatus');
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
        Route::group(['prefix' => 'forums', 'as' => 'forums.'], function () {
            Route::controller(\App\Http\Controllers\Instructor\ForumController::class)->group(function () {
                Route::get('/index', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/update/{id}', 'update')->name('update');
                Route::post('/delete/{id}', 'delete')->name('delete');
                Route::post('/updateStatus/{id}', 'updateStatus')->name('updateStatus');
            });
        });


        // Assignments routes within courses
        Route::group(['prefix' => 'assignments', 'as' => 'assignments.'], function () {
            Route::controller(AssignmentController::class)->group(function () {
                Route::get('/index', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/update/{id}', 'update')->name('update');
                Route::post('/delete/{id}', 'delete')->name('delete');
                Route::post('/updateStatus/{id}', 'updateStatus')->name('updateStatus');

            });
        });
        Route::group(['prefix' => 'quiz', 'as' => 'quiz.'], function () {
            Route::controller(QuizController::class)->group(function () {
                Route::get('/index', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/update/{id}', 'update')->name('update');
                Route::post('/delete/{id}', 'delete')->name('delete');
                Route::post('/updateStatus/{id}', 'updateStatus')->name('updateStatus');
            });
        });


        Route::group(['prefix' => 'announcement', 'as' => 'announcement.'], function () {
            Route::controller(AnnouncementController::class)->group(function () {
                Route::get('/index', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/update/{id}', 'update')->name('update');
                Route::post('/delete/{id}', 'delete')->name('delete');
                Route::post('/updateStatus/{id}', 'updateStatus')->name('updateStatus');
            });
        });
        Route::group(['prefix' => 'students', 'as' => 'students.'], function () {
            Route::controller(StudentController::class)->group(function () {
                Route::get('/index', 'index')->name('index');
                Route::post('/delete/{id}', 'delete')->name('delete');
                Route::post('/updateStatus/{id}', 'updateStatus')->name('updateStatus');

            });
        });
    });

    Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
        Route::controller(\App\Http\Controllers\Instructor\ReportController::class)->group(function () {
            Route::get('/index', 'index')->name('courses');
            Route::get('/students/{course_id}', 'students')->name('students');
        });
    });
    Route::controller(\App\Http\Controllers\Instructor\ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile');
        Route::post('/profile_update', 'update')->name('profile.update');
        Route::post('/profile_password', 'password')->name('profile.password');
    });

});

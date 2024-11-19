<?php

use App\Http\Controllers\Api\v1\AnnouncementController;
use App\Http\Controllers\Api\v1\AssignmentController;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\ChapterController;
use App\Http\Controllers\Api\v1\ContentController;
use App\Http\Controllers\Api\v1\CourseController;
use App\Http\Controllers\Api\v1\EnrollmentController;
use App\Http\Controllers\Api\v1\ForumController;
use App\Http\Controllers\Api\v1\HomeController;
use App\Http\Controllers\Api\v1\LectureController;
use App\Http\Controllers\Api\v1\MaterialController;
use App\Http\Controllers\Api\v1\QuizController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

// Routes that require authentication and custom token
Route::group(['middleware' => ['auth:sanctum', 'token']], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/notifications', [HomeController::class, 'notifications']);
    Route::get('/enrolled_courses', [HomeController::class, 'enrolled_courses']);

    Route::get('/courses', [HomeController::class, 'courses']);

    Route::group(['prefix' => 'courses', 'as' => 'courses.'], function () {
        Route::get('/', [CourseController::class, 'index'])->name('index');
        Route::post('/register', [CourseController::class, 'store'])->name('register');
        Route::get('/info/{slug}', [CourseController::class, 'info'])->name('info');

        Route::group(['prefix' => 'chapters', 'as' => 'chapters.'], function () {
            Route::get('view/{id}', [ChapterController::class, 'index'])->name('view');
        });

        Route::group(['prefix' => 'assignments', 'as' => 'assignments.'], function () {
            Route::get('/{course_id}', [AssignmentController::class, 'index'])->name('index');
            Route::post('store/{id}', [AssignmentController::class, 'store'])->name('store');
        });
        Route::group(['prefix' => 'forum', 'as' => 'forum.'], function () {
            Route::get('/{course_id}', [ForumController::class, 'index'])->name('index');
            Route::post('store', [ForumController::class, 'store'])->name('store');
        });

        Route::group(['prefix' => 'quizzes', 'as' => 'quizzes.'], function () {
            Route::get('/{course_id}', [QuizController::class, 'index'])->name('index');
            Route::get('/show/{quiz}', [QuizController::class, 'show'])->name('show');
            Route::post('/remaining_time', [QuizController::class, 'remaining_time'])->name('remaining_time');
            Route::post('/store', [QuizController::class, 'store'])->name('store');
        });

        Route::group(['prefix' => 'announcements', 'as' => 'announcements.'], function () {
            Route::get('/{course_id}', [AnnouncementController::class, 'index'])->name('index');
            Route::post('/store', [AnnouncementController::class, 'store'])->name('store');
        });

        Route::group(['prefix' => 'materials', 'as' => 'materials.'], function () {
            Route::get('/view/{id}', [MaterialController::class, 'view'])->name('view');
        });

        Route::group(['prefix' => 'content', 'as' => 'content.'], function () {
            Route::get('/{course_id}', [ContentController::class, 'index'])->name('index');
        });

        Route::group(['prefix' => 'lectures', 'as' => 'lectures.'], function () {
            Route::get('/view/{course_id}/{lecture_id}', [LectureController::class, 'view'])->name('view');
            Route::group(['prefix' => 'activities', 'as' => 'activities.'], function () {
                Route::get('/', [LectureController::class, 'view'])->name('view');
                Route::post('/', [LectureController::class, 'store'])->name('store');
            });
        });
    });

    Route::group(['prefix' => 'enrollment', 'as' => 'enrollment.'], function () {
        Route::get('/courses', [EnrollmentController::class, 'courses'])->name('courses');
        Route::get('/course/{slug}', [EnrollmentController::class, 'view'])->name('view');
        Route::get('/course/view/{id}', [EnrollmentController::class, 'info'])->name('info');
        Route::post('register/{id}', [EnrollmentController::class, 'register'])->name('register');
    });
});

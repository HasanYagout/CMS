<?php


use App\Http\Controllers\Admin\AvailabilityController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InstructorController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\StudentController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['prefix' => 'courses', 'as' => 'courses.'], function () {
        Route::get('/', [CourseController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [CourseController::class, 'edit'])->name('edit');
        Route::get('/create', [CourseController::class, 'create'])->name('create');
        Route::post('/store', [CourseController::class, 'store'])->name('store');
        Route::post('/update/{id}', [CourseController::class, 'update'])->name('update');
        Route::get('/info/{id}', [CourseController::class, 'info'])->name('info');
        Route::post('/updateStatus', [CourseController::class, 'updateStatus'])->name('updateStatus');
        Route::delete('/delete/{id}', [CourseController::class, 'destroy'])->name('delete');

        Route::group(['prefix' => 'instructors', 'as' => 'instructors.'], function () {
            Route::post('/store', [AvailabilityController::class, 'store'])->name('store');
            Route::post('/update/{id}', [AvailabilityController::class, 'update'])->name('update');
            Route::get('/info/{id}', [AvailabilityController::class, 'info'])->name('info');
            Route::post('/updateStatus', [AvailabilityController::class, 'updateStatus'])->name('updateStatus');
            Route::delete('/delete/{id}', [AvailabilityController::class, 'destroy'])->name('delete');

        });
        Route::group(['prefix' => 'students', 'as' => 'students.'], function () {
            Route::controller(StudentController::class)->group(function () {
                Route::get('/index', 'index')->name('index');
                Route::post('/delete/{id}', 'delete')->name('delete');
                Route::post('/updateStatus/{id}', 'updateStatus')->name('updateStatus');

            });
        });
        Route::group(['prefix' => 'chapters', 'as' => 'chapters.'], function () {
            Route::get('/', [CourseController::class, 'chapter'])->name('index');
            Route::post('/store', [CourseController::class, 'store_chapter'])->name('store');
            Route::get('/chapters/{id}', [CourseController::class, 'getChapterByCourseId'])->name('get');

        });


        Route::group(['prefix' => 'assignments', 'as' => 'assignments.'], function () {
            Route::get('/index', [\App\Http\Controllers\Admin\AssignmentController::class, 'index'])->name('index');
            Route::post('/store', [\App\Http\Controllers\Admin\AssignmentController::class, 'store'])->name('store');
        });
    });

    Route::group(['prefix' => 'availability', 'as' => 'availability.'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\AvailabilityController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [AvailabilityController::class, 'edit'])->name('edit');
        Route::get('/create', [\App\Http\Controllers\Admin\AvailabilityController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Admin\AvailabilityController::class, 'store'])->name('store');
        Route::get('/availability', [\App\Http\Controllers\Admin\AvailabilityController::class, 'getAvailabilityByInstructor'])->name('getAvailabilityByInstructor');
        Route::get('/availability/{id}', [\App\Http\Controllers\Admin\AvailabilityController::class, 'getInstructorAvailability'])->name('get');
        Route::post('/update/{id}', [AvailabilityController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [AvailabilityController::class, 'destroy'])->name('delete');

    });

    Route::group(['prefix' => 'instructors', 'as' => 'instructors.'], function () {
        Route::get('/', [InstructorController::class, 'index'])->name('index');
        Route::post('/store', [InstructorController::class, 'store'])->name('store');
        Route::post('/updateStatus', [InstructorController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/edit/{id}', [InstructorController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [InstructorController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [InstructorController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'news', 'as' => 'news.'], function () {
        Route::get('/', [NewsController::class, 'index'])->name('index');
        Route::post('/store', [NewsController::class, 'store'])->name('store');
        Route::post('/updateStatus', [NewsController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/edit/{id}', [NewsController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [NewsController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [NewsController::class, 'delete'])->name('delete');
    });
    Route::controller(\App\Http\Controllers\Admin\ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile');
        Route::post('/profile_update', 'update')->name('profile.update');
        Route::post('/profile_password', 'password')->name('profile.password');
    });
    Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
        Route::controller(\App\Http\Controllers\Admin\ReportController::class)->group(function () {
            Route::get('/index', 'index')->name('courses');
            Route::get('/students/{course_id}', 'students')->name('students');
            Route::get('/grades/{course_id}', 'grades')->name('grades');
        });
    });

});

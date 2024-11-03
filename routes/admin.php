<?php


use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InstructorController;
use App\Http\Controllers\Admin\NewsController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['admin'],'prefix'=>'admin','as' => 'admin.'], function () {
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['prefix'=>'courses','as' => 'courses.'], function () {
        Route::get('/', [CourseController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [CourseController::class, 'edit'])->name('edit');
        Route::get('/create', [CourseController::class, 'create'])->name('create');
        Route::post('/store', [CourseController::class, 'store'])->name('store');
        Route::post('/update', [CourseController::class, 'update'])->name('update');
        Route::get('/info/{id}', [CourseController::class, 'info'])->name('info');

        Route::group(['prefix'=>'instructors','as' => 'instructors.'], function () {
            Route::get('/', [CourseController::class, 'instructors'])->name('index');
            Route::post('/store', [\App\Http\Controllers\Admin\AvailabilityController::class, 'store'])->name('store');

        });
        Route::group(['prefix'=>'chapters','as' => 'chapters.'], function () {
            Route::get('/', [CourseController::class, 'chapter'])->name('index');
            Route::post('/store', [CourseController::class, 'store_chapter'])->name('store');
            Route::get('/chapters/{id}', [CourseController::class, 'getChapterByCourseId'])->name('get');

        });


        Route::group(['prefix'=>'assignments','as' => 'assignments.'], function () {
            Route::get('/index', [\App\Http\Controllers\Admin\AssignmentController::class, 'index'])->name('index');
            Route::post('/store', [\App\Http\Controllers\Admin\AssignmentController::class, 'store'])->name('store');
        });
    });

    Route::group(['prefix'=>'availability','as' => 'availability.'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\AvailabilityController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [CourseController::class, 'edit'])->name('edit');
        Route::get('/create', [\App\Http\Controllers\Admin\AvailabilityController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Admin\AvailabilityController::class, 'store'])->name('store');
        Route::get('/availability', [\App\Http\Controllers\Admin\AvailabilityController::class, 'getAvailabilityByInstructor'])->name('getAvailabilityByInstructor');
        Route::get('/availability/{id}', [\App\Http\Controllers\Admin\AvailabilityController::class, 'getInstructorAvailability'])->name('get');
    });

    Route::group(['prefix'=>'instructors','as' => 'instructors.'], function () {
        Route::get('/', [InstructorController::class, 'index'])->name('index');
        Route::post('/store', [InstructorController::class, 'store'])->name('store');
    });

    Route::group(['prefix'=>'news','as' => 'news.'], function () {
        Route::get('/', [NewsController::class, 'index'])->name('index');
        Route::post('/store', [NewsController::class, 'store'])->name('store');
    });
    Route::controller(\App\Http\Controllers\Admin\ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile');
        Route::post('/profile_update', 'update')->name('profile.update');
        Route::post('/profile_password', 'password')->name('profile.password');
    });

});

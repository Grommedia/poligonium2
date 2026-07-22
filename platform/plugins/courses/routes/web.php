<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Courses\Http\Controllers\AdminLocaleController;
use Botble\Courses\Http\Controllers\CourseCategoryController;
use Botble\Courses\Http\Controllers\CourseChapterController;
use Botble\Courses\Http\Controllers\CourseController;
use Botble\Courses\Http\Controllers\CourseCurriculumController;
use Botble\Courses\Http\Controllers\CourseEnrollmentController;
use Botble\Courses\Http\Controllers\CourseLessonController;
use Botble\Courses\Http\Controllers\CourseLessonFileController;
use Botble\Courses\Http\Controllers\CoursePurchaseController;
use Botble\Courses\Http\Controllers\MonopayWebhookController;
use Botble\Courses\Http\Controllers\PublicController;
use Botble\Courses\Http\Controllers\StudentAuthController;
use Botble\Courses\Http\Controllers\StudentCabinetController;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function (): void {
    Route::get('admin-locale/{admin_locale}', AdminLocaleController::class)->name('admin-locale.switch.path');
    Route::get('admin-locale', AdminLocaleController::class)->name('admin-locale.switch.get');
    Route::post('admin-locale', AdminLocaleController::class)->name('admin-locale.switch');

    Route::prefix('courses')->name('courses.')->group(function (): void {
        Route::group(['prefix' => 'categories', 'as' => 'categories.'], function (): void {
            Route::resource('', CourseCategoryController::class)->parameters(['' => 'category']);
        });

        Route::group(['prefix' => 'courses', 'as' => 'courses.'], function (): void {
            Route::prefix('{course}/curriculum')->name('curriculum.')->group(function (): void {
                Route::post('chapters', [CourseCurriculumController::class, 'storeChapter'])->name('chapters.store');
                Route::put('chapters/{chapter}', [CourseCurriculumController::class, 'updateChapter'])->name('chapters.update');
                Route::post('lessons', [CourseCurriculumController::class, 'storeLesson'])->name('lessons.store');
                Route::post('lessons/bulk', [CourseCurriculumController::class, 'bulkStoreLessons'])->name('lessons.bulk');
                Route::put('lessons/{lesson}', [CourseCurriculumController::class, 'updateLesson'])->name('lessons.update');
                Route::post('lessons/restore', [CourseCurriculumController::class, 'restoreLesson'])->name('lessons.restore');
                Route::post('lessons/{lesson}/quick-action', [CourseCurriculumController::class, 'quickLessonAction'])->name('lessons.quick-action');
                Route::delete('lessons/{lesson}/files/{file}', [CourseCurriculumController::class, 'destroyLessonFile'])->name('lessons.files.destroy');
                Route::post('reorder', [CourseCurriculumController::class, 'reorder'])->name('reorder');
            });

            Route::get('{course}/preview/{role?}', [CourseController::class, 'preview'])->name('preview');
            Route::post('{course}/autosave', [CourseController::class, 'autosave'])->name('autosave');
            Route::get('{course}/translations', [CourseController::class, 'translations'])->name('translations');
            Route::post('{course}/translations', [CourseController::class, 'saveTranslation'])->name('translations.save');
            Route::post('{course}/publish', [CourseController::class, 'publish'])->name('publish');
            Route::post('{course}/schedule', [CourseController::class, 'schedule'])->name('schedule');
            Route::post('{course}/hide', [CourseController::class, 'hide'])->name('hide');
            Route::resource('', CourseController::class)->parameters(['' => 'course']);
        });

        Route::group(['prefix' => 'chapters', 'as' => 'chapters.'], function (): void {
            Route::resource('', CourseChapterController::class)->parameters(['' => 'chapter']);
        });

        Route::group(['prefix' => 'lessons', 'as' => 'lessons.'], function (): void {
            Route::resource('', CourseLessonController::class)->parameters(['' => 'lesson']);
        });

        Route::group(['prefix' => 'files', 'as' => 'files.'], function (): void {
            Route::resource('', CourseLessonFileController::class)->parameters(['' => 'file']);
        });

        Route::group(['prefix' => 'enrollments', 'as' => 'enrollments.'], function (): void {
            Route::resource('', CourseEnrollmentController::class)->parameters(['' => 'enrollment']);
        });

        Route::group(['prefix' => 'purchases', 'as' => 'purchases.'], function (): void {
            Route::post('{purchase}/confirm', [CoursePurchaseController::class, 'confirm'])->name('confirm');
            Route::resource('', CoursePurchaseController::class)->parameters(['' => 'purchase']);
        });
    });
});

Theme::registerRoutes(function (): void {
    Route::post('courses/monopay/webhook', MonopayWebhookController::class)->name('courses.monopay.webhook');

    Route::prefix('school')->name('courses.student.')->group(function (): void {
        Route::get('login', [StudentAuthController::class, 'showLogin'])->name('login');
        Route::post('login', [StudentAuthController::class, 'login'])->name('login.submit');
        Route::get('register', [StudentAuthController::class, 'showRegister'])->name('register');
        Route::post('register', [StudentAuthController::class, 'register'])->name('register.submit');

        Route::post('logout', [StudentAuthController::class, 'logout'])->middleware('auth')->name('logout');
        Route::get('cabinet', StudentCabinetController::class)->middleware('auth')->name('cabinet');
    });

    Route::prefix('courses')->name('courses.public.')->group(function (): void {
        Route::get('', [PublicController::class, 'index'])->name('index');
        Route::get('preview/{course:slug}', [PublicController::class, 'preview'])->middleware('signed')->name('preview');
        Route::get('{course:slug}', [PublicController::class, 'show'])->name('show');
        Route::post('{course:slug}/purchase', [PublicController::class, 'purchase'])->name('purchase');
        Route::post('{course:slug}/remind', [PublicController::class, 'remind'])->name('remind');
        Route::get('{course:slug}/lessons/{lesson}/video', [PublicController::class, 'video'])->name('lesson.video');
        Route::get('{course:slug}/lessons/{lesson}', [PublicController::class, 'lesson'])->name('lesson');
    });
});

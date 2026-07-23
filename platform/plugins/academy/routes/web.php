<?php

use Botble\Academy\Http\Controllers\AcademyArticleController;
use Botble\Academy\Http\Controllers\AcademyCategoryController;
use Botble\Academy\Http\Controllers\PublicController;
use Botble\Base\Facades\AdminHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function (): void {
    Route::prefix('academy')->name('academy.')->group(function (): void {
        Route::group(['prefix' => 'articles', 'as' => 'articles.'], function (): void {
            Route::resource('', AcademyArticleController::class)->parameters(['' => 'article']);
        });

        Route::group(['prefix' => 'categories', 'as' => 'categories.'], function (): void {
            Route::resource('', AcademyCategoryController::class)->parameters(['' => 'category']);
        });
    });
});

Theme::registerRoutes(function (): void {
    Route::prefix('academy')->name('academy.public.')->group(function (): void {
        Route::get('', [PublicController::class, 'index'])->name('index');
        Route::get('articles', [PublicController::class, 'articles'])->name('articles');
        Route::get('category/{category:slug}', [PublicController::class, 'category'])->name('category');
        Route::get('{article:slug}', [PublicController::class, 'show'])->name('show');
    });
});

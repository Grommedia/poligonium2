<?php

use Botble\Base\Facades\AdminHelper;
use Botble\SeoOptimizer\Http\Controllers\SeoOptimizerDashboardController;
use Botble\SeoOptimizer\Http\Controllers\SeoOptimizationSettingController;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function (): void {
    Route::group([
        'prefix' => 'seo-optimizer',
        'as' => 'seo-optimizer.',
        'permission' => 'seo-optimizer.settings',
    ], function (): void {
        Route::get('/', [SeoOptimizerDashboardController::class, 'index'])->name('index');
    });

    Route::group([
        'prefix' => 'settings/seo-optimization',
        'as' => 'seo-optimizer.settings',
        'permission' => 'seo-optimizer.settings',
    ], function (): void {
        Route::get('/', [SeoOptimizationSettingController::class, 'edit']);
        Route::put('/', [
            'as' => '.update',
            'uses' => SeoOptimizationSettingController::class . '@update',
        ]);
    });
});

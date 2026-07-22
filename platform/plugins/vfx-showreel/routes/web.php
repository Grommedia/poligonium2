<?php

use Botble\Base\Facades\AdminHelper;
use Botble\VfxShowreel\Http\Controllers\VfxShowreelItemController;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function (): void {
    Route::prefix('vfx-showreel')->name('vfx-showreel.')->group(function (): void {
        Route::group(['prefix' => 'items', 'as' => 'items.'], function (): void {
            Route::resource('', VfxShowreelItemController::class)->parameters(['' => 'item']);
        });
    });
});

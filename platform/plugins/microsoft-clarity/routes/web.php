<?php

use Botble\Base\Facades\AdminHelper;
use Botble\MicrosoftClarity\Http\Controllers\MicrosoftClarityController;
use Botble\MicrosoftClarity\Http\Controllers\MicrosoftClaritySettingController;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function (): void {
    Route::group([
        'prefix' => 'microsoft-clarity',
        'as' => 'microsoft-clarity.',
        'permission' => 'microsoft-clarity.index',
    ], function (): void {
        Route::get('/', [MicrosoftClarityController::class, 'index'])->name('index');
        Route::post('project', [MicrosoftClarityController::class, 'updateProject'])->name('project.update');
    });

    Route::group([
        'prefix' => 'settings/microsoft-clarity',
        'as' => 'microsoft-clarity.settings',
        'permission' => 'microsoft-clarity.settings',
    ], function (): void {
        Route::get('/', [MicrosoftClaritySettingController::class, 'edit']);
        Route::put('/', [
            'as' => '.update',
            'uses' => MicrosoftClaritySettingController::class . '@update',
        ]);
    });
});

<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Campaigns\Http\Controllers\CampaignBuilderController;
use Botble\Campaigns\Http\Controllers\CampaignContributionController;
use Botble\Campaigns\Http\Controllers\CampaignController;
use Botble\Campaigns\Http\Controllers\CampaignFaqController;
use Botble\Campaigns\Http\Controllers\CampaignRewardController;
use Botble\Campaigns\Http\Controllers\CampaignSupportRequestController;
use Botble\Campaigns\Http\Controllers\CampaignTeamMemberController;
use Botble\Campaigns\Http\Controllers\CampaignUpdateController;
use Botble\Campaigns\Http\Controllers\PublicController;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function (): void {
    Route::prefix('campaigns')->name('campaigns.')->group(function (): void {
        Route::group(['prefix' => 'campaigns', 'as' => 'campaigns.'], function (): void {
            Route::prefix('{campaign}/builder')->name('builder.')->group(function (): void {
                Route::post('rewards', [CampaignBuilderController::class, 'storeReward'])->name('rewards.store');
                Route::post('updates', [CampaignBuilderController::class, 'storeUpdate'])->name('updates.store');
                Route::post('team', [CampaignBuilderController::class, 'storeTeamMember'])->name('team.store');
                Route::post('faqs', [CampaignBuilderController::class, 'storeFaq'])->name('faqs.store');
            });

            Route::resource('', CampaignController::class)->parameters(['' => 'campaign']);
        });

        Route::group(['prefix' => 'rewards', 'as' => 'rewards.'], function (): void {
            Route::resource('', CampaignRewardController::class)->parameters(['' => 'reward']);
        });

        Route::group(['prefix' => 'contributions', 'as' => 'contributions.'], function (): void {
            Route::resource('', CampaignContributionController::class)->parameters(['' => 'contribution']);
        });

        Route::group(['prefix' => 'support-requests', 'as' => 'support-requests.'], function (): void {
            Route::resource('', CampaignSupportRequestController::class)->parameters(['' => 'supportRequest']);
        });

        Route::group(['prefix' => 'updates', 'as' => 'updates.'], function (): void {
            Route::resource('', CampaignUpdateController::class)->parameters(['' => 'update']);
        });

        Route::group(['prefix' => 'team', 'as' => 'team.'], function (): void {
            Route::resource('', CampaignTeamMemberController::class)->parameters(['' => 'team']);
        });

        Route::group(['prefix' => 'faqs', 'as' => 'faqs.'], function (): void {
            Route::resource('', CampaignFaqController::class)->parameters(['' => 'faq']);
        });
    });
});

Theme::registerRoutes(function (): void {
    Route::prefix('support')->name('campaigns.public.')->group(function (): void {
        Route::get('', [PublicController::class, 'index'])->name('index');
        Route::post('{campaign:slug}/support', [PublicController::class, 'support'])->name('support');
        Route::get('{campaign:slug}', [PublicController::class, 'show'])->name('show');
    });
});

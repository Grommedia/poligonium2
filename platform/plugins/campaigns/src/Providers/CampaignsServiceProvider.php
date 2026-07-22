<?php

namespace Botble\Campaigns\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Supports\DashboardMenuItem;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Campaigns\Models\Campaign;
use Botble\Campaigns\Models\CampaignFaq;
use Botble\Campaigns\Models\CampaignReward;
use Botble\Campaigns\Models\CampaignTeamMember;
use Botble\Campaigns\Models\CampaignUpdate;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;

class CampaignsServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/campaigns')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes()
            ->loadMigrations();

        $this->registerAdminMenu();
        $this->registerLanguage();
    }

    protected function registerAdminMenu(): void
    {
        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-campaigns')
                        ->priority(26)
                        ->name('plugins/campaigns::campaigns.menu_name')
                        ->icon('ti ti-heart-handshake')
                        ->permissions(['campaigns.index'])
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-campaigns-campaigns')
                        ->priority(0)
                        ->parentId('cms-plugins-campaigns')
                        ->name('plugins/campaigns::campaigns.campaigns')
                        ->icon('ti ti-rocket')
                        ->route('campaigns.campaigns.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-campaigns-rewards')
                        ->priority(10)
                        ->parentId('cms-plugins-campaigns')
                        ->name('plugins/campaigns::campaigns.rewards')
                        ->icon('ti ti-gift')
                        ->route('campaigns.rewards.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-campaigns-contributions')
                        ->priority(20)
                        ->parentId('cms-plugins-campaigns')
                        ->name('plugins/campaigns::campaigns.contributions')
                        ->icon('ti ti-coin')
                        ->route('campaigns.contributions.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-campaigns-support-requests')
                        ->priority(25)
                        ->parentId('cms-plugins-campaigns')
                        ->name('plugins/campaigns::campaigns.support_requests')
                        ->icon('ti ti-inbox')
                        ->route('campaigns.support-requests.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-campaigns-updates')
                        ->priority(30)
                        ->parentId('cms-plugins-campaigns')
                        ->name('plugins/campaigns::campaigns.updates')
                        ->icon('ti ti-news')
                        ->route('campaigns.updates.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-campaigns-team')
                        ->priority(40)
                        ->parentId('cms-plugins-campaigns')
                        ->name('plugins/campaigns::campaigns.team')
                        ->icon('ti ti-users')
                        ->route('campaigns.team.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-campaigns-faqs')
                        ->priority(50)
                        ->parentId('cms-plugins-campaigns')
                        ->name('plugins/campaigns::campaigns.faqs')
                        ->icon('ti ti-help-circle')
                        ->route('campaigns.faqs.index')
                );
        });
    }

    protected function registerLanguage(): void
    {
        if (! defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            return;
        }

        LanguageAdvancedManager::registerModule(Campaign::class, [
            'name',
            'subtitle',
            'description',
            'content',
        ]);

        LanguageAdvancedManager::registerModule(CampaignReward::class, [
            'title',
            'description',
            'includes',
        ]);

        LanguageAdvancedManager::registerModule(CampaignUpdate::class, [
            'title',
            'content',
        ]);

        LanguageAdvancedManager::registerModule(CampaignTeamMember::class, [
            'name',
            'role',
            'bio',
        ]);

        LanguageAdvancedManager::registerModule(CampaignFaq::class, [
            'question',
            'answer',
        ]);
    }
}

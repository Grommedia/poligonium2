<?php

namespace Botble\VfxShowreel\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Supports\DashboardMenuItem;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\VfxShowreel\Models\VfxShowreelItem;

class VfxShowreelServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/vfx-showreel')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishTranslations()
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
                        ->id('cms-plugins-vfx-showreel')
                        ->priority(27)
                        ->name('plugins/vfx-showreel::vfx-showreel.menu_name')
                        ->icon('ti ti-movie')
                        ->route('vfx-showreel.items.index')
                        ->permissions(['vfx-showreel.index'])
                );
        });
    }

    protected function registerLanguage(): void
    {
        if (! defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            return;
        }

        LanguageAdvancedManager::registerModule(VfxShowreelItem::class, [
            'name',
            'type',
            'description',
        ]);
    }
}

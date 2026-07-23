<?php

namespace Botble\Academy\Providers;

use Botble\Academy\Models\AcademyArticle;
use Botble\Academy\Models\AcademyCategory;
use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Supports\DashboardMenuItem;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;

class AcademyServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/academy')
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
                        ->id('cms-plugins-academy')
                        ->priority(24)
                        ->name('plugins/academy::academy.menu_name')
                        ->icon('ti ti-books')
                        ->permissions(['academy.index'])
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-academy-articles')
                        ->priority(0)
                        ->parentId('cms-plugins-academy')
                        ->name('plugins/academy::academy.articles')
                        ->icon('ti ti-article')
                        ->route('academy.articles.index')
                        ->permissions(['academy.articles.index'])
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-academy-categories')
                        ->priority(10)
                        ->parentId('cms-plugins-academy')
                        ->name('plugins/academy::academy.categories')
                        ->icon('ti ti-folders')
                        ->route('academy.categories.index')
                        ->permissions(['academy.categories.index'])
                );
        });
    }

    protected function registerLanguage(): void
    {
        if (! defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            return;
        }

        LanguageAdvancedManager::registerModule(AcademyCategory::class, [
            'name',
            'description',
        ]);

        LanguageAdvancedManager::registerModule(AcademyArticle::class, [
            'name',
            'description',
            'content',
            'seo_title',
            'seo_description',
        ]);
    }
}

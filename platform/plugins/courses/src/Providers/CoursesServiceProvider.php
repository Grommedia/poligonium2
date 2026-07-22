<?php

namespace Botble\Courses\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Supports\DashboardMenuItem;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Courses\Models\Course;
use Botble\Courses\Models\CourseCategory;
use Botble\Courses\Models\CourseChapter;
use Botble\Courses\Models\CourseLesson;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;

class CoursesServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/courses')
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
                        ->id('cms-plugins-courses')
                        ->priority(25)
                        ->name('plugins/courses::courses.menu_name')
                        ->icon('ti ti-school')
                        ->route('courses.courses.index')
                        ->permissions(['courses.index'])
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-courses-purchases')
                        ->priority(26)
                        ->name('Покупки курсів')
                        ->icon('ti ti-receipt')
                        ->route('courses.purchases.index')
                        ->permissions(['courses.purchases.index'])
                );
        });
    }

    protected function registerLanguage(): void
    {
        if (! defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            return;
        }

        LanguageAdvancedManager::registerModule(CourseCategory::class, [
            'name',
            'description',
        ]);

        LanguageAdvancedManager::registerModule(Course::class, [
            'name',
            'description',
            'content',
        ]);

        LanguageAdvancedManager::registerModule(CourseChapter::class, [
            'name',
            'description',
        ]);

        LanguageAdvancedManager::registerModule(CourseLesson::class, [
            'name',
            'description',
            'content',
        ]);
    }
}

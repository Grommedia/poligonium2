<?php

namespace Botble\MicrosoftClarity\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Supports\DashboardMenuItem;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Setting\PanelSections\SettingOthersPanelSection;
use Illuminate\Support\Str;

class MicrosoftClarityServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/microsoft-clarity')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations();

        $this->registerAdminMenu();
        $this->registerSettingsPanel();
        $this->registerFrontendHook();
    }

    protected function registerAdminMenu(): void
    {
        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-microsoft-clarity')
                        ->priority(460)
                        ->name('plugins/microsoft-clarity::microsoft-clarity.menu_name')
                        ->icon('ti ti-chart-arcs')
                        ->route('microsoft-clarity.index')
                        ->permissions(['microsoft-clarity.index'])
                );
        });
    }

    protected function registerSettingsPanel(): void
    {
        PanelSectionManager::default()->beforeRendering(function (): void {
            PanelSectionManager::registerItem(
                SettingOthersPanelSection::class,
                fn () => PanelSectionItem::make('microsoft-clarity')
                    ->setTitle(trans('plugins/microsoft-clarity::microsoft-clarity.settings.title'))
                    ->withIcon('ti ti-chart-arcs')
                    ->withDescription(trans('plugins/microsoft-clarity::microsoft-clarity.settings.description'))
                    ->withPriority(170)
                    ->withRoute('microsoft-clarity.settings')
            );
        });
    }

    protected function registerFrontendHook(): void
    {
        add_filter(THEME_FRONT_HEADER, function (?string $html): string {
            return (string) $html . $this->renderTrackingCode();
        }, 130);
    }

    protected function renderTrackingCode(): string
    {
        if (! (bool) setting('microsoft_clarity_enabled', true)) {
            return '';
        }

        if (
            (bool) setting('microsoft_clarity_exclude_admin', true)
            && function_exists('is_in_admin')
            && is_in_admin()
        ) {
            return '';
        }

        if (setting('microsoft_clarity_tracking_mode', 'project_id') === 'custom_code') {
            $code = trim((string) setting('microsoft_clarity_tracking_code'));

            return $code ? PHP_EOL . $code . PHP_EOL : '';
        }

        $projectId = trim((string) setting('microsoft_clarity_project_id'));

        if (! $projectId || ! preg_match('/^[A-Za-z0-9_-]+$/', $projectId)) {
            return '';
        }

        $projectId = e($projectId);
        $ref = e(Str::slug(config('app.name', 'poligonium')));

        return <<<HTML

<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i+"?ref={$ref}";
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window,document,"clarity","script","{$projectId}");
</script>

HTML;
    }
}

<?php

namespace Botble\SeoOptimizer\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Supports\DashboardMenuItem;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Campaigns\Models\Campaign;
use Botble\Courses\Models\Course;
use Botble\Media\Facades\RvMedia;
use Botble\Portfolio\Models\Project;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\SeoOptimizer\Listeners\RenderingSiteMapListener;
use Botble\Setting\PanelSections\SettingCommonPanelSection;
use Botble\Theme\Events\RenderingSiteMapEvent;
use Botble\Theme\Facades\SiteMapManager;
use Botble\VfxShowreel\Models\VfxShowreelItem;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

class SeoOptimizerServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/seo-optimizer')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations();

        SiteMapManager::registerKey('poligonium');
        Event::listen(RenderingSiteMapEvent::class, RenderingSiteMapListener::class);

        $this->registerAdminMenu();
        $this->registerSettingsPanel();
        $this->registerSeoModules();
        $this->registerFrontendHooks();
    }

    protected function registerAdminMenu(): void
    {
        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-seo-optimizer')
                        ->priority(455)
                        ->name('plugins/seo-optimizer::seo-optimizer.menu_name')
                        ->icon('ti ti-seo')
                        ->route('seo-optimizer.index')
                        ->permissions(['seo-optimizer.settings'])
                );
        });
    }

    protected function registerSettingsPanel(): void
    {
        PanelSectionManager::default()->beforeRendering(function (): void {
            PanelSectionManager::registerItem(
                SettingCommonPanelSection::class,
                fn () => PanelSectionItem::make('seo_optimization')
                    ->setTitle(trans('plugins/seo-optimizer::seo-optimizer.settings.title'))
                    ->withIcon('ti ti-seo')
                    ->withDescription(trans('plugins/seo-optimizer::seo-optimizer.settings.description'))
                    ->withPriority(145)
                    ->withRoute('seo-optimizer.settings')
            );
        });
    }

    protected function registerSeoModules(): void
    {
        $modules = array_filter([
            class_exists(Project::class) ? Project::class : null,
            class_exists(Course::class) ? Course::class : null,
            class_exists(Campaign::class) ? Campaign::class : null,
            class_exists(VfxShowreelItem::class) ? VfxShowreelItem::class : null,
        ]);

        if ($modules) {
            SeoHelper::registerModule($modules);
        }
    }

    protected function registerFrontendHooks(): void
    {
        add_filter(THEME_FRONT_HEADER, function (?string $html): string {
            $html = (string) $html;
            $html = $this->normalizeOrganizationStructuredData($html);

            return $html . $this->renderHeadSeo($html);
        }, 120);

        add_filter(THEME_FRONT_BODY, function (?string $html): string {
            $extraBodyHtml = trim((string) setting('seo_extra_body_html'));

            return (string) $html . ($extraBodyHtml ? PHP_EOL . $extraBodyHtml . PHP_EOL : '');
        }, 120);
    }

    protected function renderHeadSeo(string $existingHead = ''): string
    {
        if (function_exists('is_in_admin') && is_in_admin()) {
            return '';
        }

        $html = [];

        if ($token = trim((string) setting('seo_google_site_verification'))) {
            $html[] = sprintf('<meta name="google-site-verification" content="%s">', e($token));
        }

        if ($token = trim((string) setting('seo_bing_site_verification'))) {
            $html[] = sprintf('<meta name="msvalidate.01" content="%s">', e($token));
        }

        if (
            (bool) setting('seo_enable_canonical_hreflang', true)
            && ! str_contains($existingHead, 'rel="canonical"')
            && ! str_contains($existingHead, 'hreflang=')
        ) {
            $html[] = $this->renderCanonicalAndHrefLang();
        }

        if ((bool) setting('seo_enable_structured_data', true)) {
            $html[] = $this->renderStructuredData($existingHead);
        }

        if ($extraHeadHtml = trim((string) setting('seo_extra_head_html'))) {
            $html[] = $extraHeadHtml;
        }

        return PHP_EOL . implode(PHP_EOL, array_filter($html)) . PHP_EOL;
    }

    protected function renderCanonicalAndHrefLang(): string
    {
        $path = trim(request()->path(), '/');

        if (Str::startsWith($path, 'admin') || Str::startsWith($path, 'install')) {
            return '';
        }

        $isEnglish = $path === 'en' || Str::startsWith($path, 'en/');
        $basePath = $isEnglish ? trim(Str::after($path, 'en'), '/') : $path;
        $suffix = $basePath ? '/' . $basePath : '';

        $ukUrl = url($suffix ?: '/');
        $enUrl = url('/en' . $suffix);
        $canonical = $isEnglish ? $enUrl : $ukUrl;

        return implode(PHP_EOL, [
            sprintf('<link rel="canonical" href="%s">', e($canonical)),
            sprintf('<link rel="alternate" hreflang="uk" href="%s">', e($ukUrl)),
            sprintf('<link rel="alternate" hreflang="en" href="%s">', e($enUrl)),
            sprintf('<link rel="alternate" hreflang="x-default" href="%s">', e($ukUrl)),
        ]);
    }

    protected function renderStructuredData(string $existingHead = ''): string
    {
        $schema = [];

        if (! str_contains($existingHead, 'Organization')) {
            $organizationUrl = trim((string) setting('seo_organization_url'));
            $organizationUrl = $organizationUrl ?: url('/');

            $organization = [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => setting('seo_organization_name', 'Poligonium'),
                'url' => rtrim($organizationUrl, '/'),
            ];

            if ($logo = setting('seo_organization_logo')) {
                $organization['logo'] = RvMedia::getImageUrl($logo);
            }

            if ($phone = setting('seo_organization_phone')) {
                $organization['telephone'] = $phone;
                $organization['contactPoint'] = [[
                    '@type' => 'ContactPoint',
                    'telephone' => $phone,
                    'contactType' => 'customer support',
                    'areaServed' => 'UA',
                    'availableLanguage' => ['uk', 'en', 'ru'],
                ]];
            }

            if ($email = setting('seo_organization_email')) {
                $organization['email'] = $email;
            }

            $sameAs = collect(preg_split('/\R+/', (string) setting('seo_organization_same_as')))
                ->map(fn (string $url) => trim($url))
                ->filter()
                ->values()
                ->all();

            if ($sameAs) {
                $organization['sameAs'] = $sameAs;
            }

            $schema[] = $organization;
        }

        return collect($schema)
            ->map(fn (array $item) => sprintf(
                '<script type="application/ld+json">%s</script>',
                json_encode($item, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            ))
            ->implode(PHP_EOL);
    }

    protected function normalizeOrganizationStructuredData(string $html): string
    {
        if (! str_contains($html, 'Organization')) {
            return $html;
        }

        $organizationUrl = trim((string) setting('seo_organization_url')) ?: url('/');
        $organizationUrl = rtrim($organizationUrl, '/');

        return preg_replace_callback(
            '#<script\b([^>]*)type=["\']application/ld\+json["\']([^>]*)>(.*?)</script>#is',
            function (array $matches) use ($organizationUrl): string {
                $json = trim(html_entity_decode($matches[3], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                $data = json_decode($json, true);

                if (! is_array($data) || ($data['@type'] ?? null) !== 'Organization') {
                    return $matches[0];
                }

                $data['url'] = $organizationUrl;

                return sprintf(
                    '<script%s type="application/ld+json"%s>%s</script>',
                    $matches[1],
                    $matches[2],
                    json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                );
            },
            $html
        ) ?: $html;
    }
}

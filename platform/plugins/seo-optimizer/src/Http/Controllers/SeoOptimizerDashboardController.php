<?php

namespace Botble\SeoOptimizer\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Campaigns\Models\Campaign;
use Botble\Courses\Models\Course;
use Botble\Portfolio\Models\Project;
use Botble\VfxShowreel\Models\VfxShowreelItem;
use Illuminate\Support\Facades\File;

class SeoOptimizerDashboardController extends BaseController
{
    public function index()
    {
        $this->pageTitle(trans('plugins/seo-optimizer::seo-optimizer.dashboard.title'));

        $robotsPath = public_path('robots.txt');
        $robotsContent = File::exists($robotsPath) ? File::get($robotsPath) : '';

        $checks = [
            [
                'label' => trans('plugins/seo-optimizer::seo-optimizer.dashboard.checks.robots'),
                'status' => File::exists($robotsPath) && str_contains($robotsContent, 'Sitemap:'),
                'hint' => url('/robots.txt'),
            ],
            [
                'label' => trans('plugins/seo-optimizer::seo-optimizer.dashboard.checks.sitemap'),
                'status' => true,
                'hint' => route('public.sitemap'),
            ],
            [
                'label' => trans('plugins/seo-optimizer::seo-optimizer.dashboard.checks.indexing'),
                'status' => (bool) theme_option('seo_index', true),
                'hint' => (bool) theme_option('seo_index', true) ? 'index, follow' : 'noindex, nofollow',
            ],
            [
                'label' => trans('plugins/seo-optimizer::seo-optimizer.dashboard.checks.canonical_hreflang'),
                'status' => (bool) setting('seo_enable_canonical_hreflang', true),
                'hint' => trans('plugins/seo-optimizer::seo-optimizer.dashboard.checks.uk_en'),
            ],
            [
                'label' => trans('plugins/seo-optimizer::seo-optimizer.dashboard.checks.structured_data'),
                'status' => (bool) setting('seo_enable_structured_data', true),
                'hint' => 'Organization JSON-LD',
            ],
        ];

        $contentStats = [
            [
                'label' => trans('plugins/seo-optimizer::seo-optimizer.dashboard.stats.projects'),
                'value' => Project::query()->wherePublished()->count(),
            ],
            [
                'label' => trans('plugins/seo-optimizer::seo-optimizer.dashboard.stats.vfx'),
                'value' => VfxShowreelItem::query()->where('status', BaseStatusEnum::PUBLISHED)->count(),
            ],
            [
                'label' => trans('plugins/seo-optimizer::seo-optimizer.dashboard.stats.courses'),
                'value' => Course::query()->where('status', BaseStatusEnum::PUBLISHED)->count(),
            ],
            [
                'label' => trans('plugins/seo-optimizer::seo-optimizer.dashboard.stats.campaigns'),
                'value' => Campaign::query()->where('status', BaseStatusEnum::PUBLISHED)->count(),
            ],
        ];

        return view('plugins/seo-optimizer::dashboard', compact('checks', 'contentStats', 'robotsContent'));
    }
}

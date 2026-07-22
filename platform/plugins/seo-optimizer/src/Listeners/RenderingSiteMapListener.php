<?php

namespace Botble\SeoOptimizer\Listeners;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Campaigns\Models\Campaign;
use Botble\Courses\Models\Course;
use Botble\Portfolio\Models\Project;
use Botble\Theme\Events\RenderingSiteMapEvent;
use Botble\Theme\Facades\SiteMapManager;
use Botble\VfxShowreel\Models\VfxShowreelItem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RenderingSiteMapListener
{
    public function handle(RenderingSiteMapEvent $event): void
    {
        if ($event->key && $event->key !== 'sitemap' && $event->key !== 'poligonium') {
            return;
        }

        if ($event->key === 'poligonium') {
            $this->addPoligoniumUrls();

            return;
        }

        SiteMapManager::addSitemap(SiteMapManager::route('poligonium'), $this->latestUpdatedAt());
    }

    protected function addPoligoniumUrls(): void
    {
        $now = Carbon::now()->toDateTimeString();

        foreach ([
            '/' => '1.0',
            '/services' => '0.9',
            '/projects' => '0.9',
            '/vfx-showreel' => '0.9',
            '/courses' => '0.9',
            '/support' => '0.9',
            '/contact' => '0.8',
        ] as $path => $priority) {
            $this->addLocalizedUrl($path, $now, $priority);
        }

        Project::query()
            ->wherePublished()
            ->with('slugable')
            ->select(['id', 'name', 'updated_at'])
            ->latest('updated_at')
            ->get()
            ->each(function (Project $project): void {
                $slug = $project->slugable?->key;

                if ($slug) {
                    $this->addLocalizedUrl('/projects/' . $slug, $project->updated_at, '0.8');
                }
            });

        Course::query()
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->select(['id', 'slug', 'updated_at'])
            ->latest('updated_at')
            ->get()
            ->each(fn (Course $course) => $this->addLocalizedUrl('/courses/' . $course->slug, $course->updated_at, '0.8'));

        Campaign::query()
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->select(['id', 'slug', 'updated_at'])
            ->latest('updated_at')
            ->get()
            ->each(fn (Campaign $campaign) => $this->addLocalizedUrl('/support/' . $campaign->slug, $campaign->updated_at, '0.8'));
    }

    protected function addLocalizedUrl(string $path, mixed $updatedAt, string $priority): void
    {
        $path = '/' . trim($path, '/');
        $path = $path === '/' ? '/' : $path;
        $date = $this->formatDate($updatedAt);

        SiteMapManager::add(url($path), $date, $priority);

        $englishPath = $path === '/' ? '/en' : '/en' . $path;
        SiteMapManager::add(url($englishPath), $date, $priority);
    }

    protected function latestUpdatedAt(): string
    {
        return collect([
            Project::query()->wherePublished()->latest('updated_at')->value('updated_at'),
            Course::query()->where('status', BaseStatusEnum::PUBLISHED)->latest('updated_at')->value('updated_at'),
            Campaign::query()->where('status', BaseStatusEnum::PUBLISHED)->latest('updated_at')->value('updated_at'),
            VfxShowreelItem::query()->where('status', BaseStatusEnum::PUBLISHED)->latest('updated_at')->value('updated_at'),
            DB::table('pages')->where('status', BaseStatusEnum::PUBLISHED)->latest('updated_at')->value('updated_at'),
        ])
            ->filter()
            ->map(fn (mixed $date) => $this->formatDate($date))
            ->sortDesc()
            ->first() ?: Carbon::now()->toDateTimeString();
    }

    protected function formatDate(mixed $date): string
    {
        if ($date instanceof Carbon) {
            return $date->toDateTimeString();
        }

        if ($date instanceof Model) {
            return $date->updated_at?->toDateTimeString() ?: Carbon::now()->toDateTimeString();
        }

        return $date ? Carbon::parse($date)->toDateTimeString() : Carbon::now()->toDateTimeString();
    }
}

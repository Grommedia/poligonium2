<?php

namespace Botble\Academy\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademyArticle extends BaseModel
{
    protected $table = 'plg_academy_articles';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'content',
        'cover_image',
        'difficulty',
        'software',
        'skills',
        'cta_label',
        'cta_url',
        'seo_title',
        'seo_description',
        'reading_time',
        'is_featured',
        'order',
        'published_at',
        'status',
    ];

    protected $casts = [
        'name' => SafeContent::class,
        'slug' => SafeContent::class,
        'description' => SafeContent::class,
        'content' => SafeContent::class,
        'difficulty' => SafeContent::class,
        'software' => SafeContent::class,
        'skills' => SafeContent::class,
        'cta_label' => SafeContent::class,
        'cta_url' => SafeContent::class,
        'seo_title' => SafeContent::class,
        'seo_description' => SafeContent::class,
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'status' => BaseStatusEnum::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(AcademyCategory::class, 'category_id');
    }

    public function softwareList(): array
    {
        return $this->splitTags($this->software);
    }

    public function skillsList(): array
    {
        return $this->splitTags($this->skills);
    }

    protected function splitTags(?string $value): array
    {
        if (! $value) {
            return [];
        }

        return collect(explode(',', $value))
            ->map(fn (string $item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}

<?php

namespace Botble\Courses\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Courses\Support\CourseOptions;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends BaseModel
{
    protected $table = 'plg_courses';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'content',
        'image',
        'intro_video',
        'difficulty',
        'software',
        'skills',
        'duration_minutes',
        'lesson_count',
        'visibility_mode',
        'price_type',
        'price',
        'sale_status',
        'sales_mode',
        'sales_starts_at',
        'early_access_price',
        'early_access_starts_at',
        'early_access_ends_at',
        'released_at',
        'course_access_mode',
        'timezone',
        'show_release_date_on_card',
        'gradual_access_enabled',
        'publication_state',
        'publish_scheduled_at',
        'published_at',
        'published_snapshot',
        'has_unpublished_changes',
        'early_access_slots',
        'early_access_sold',
        'currency',
        'is_featured',
        'is_free_preview',
        'order',
        'status',
    ];

    protected $casts = [
        'name' => SafeContent::class,
        'description' => SafeContent::class,
        'content' => SafeContent::class,
        'software' => 'array',
        'skills' => 'array',
        'price' => 'decimal:2',
        'sales_starts_at' => 'datetime',
        'early_access_price' => 'decimal:2',
        'early_access_starts_at' => 'datetime',
        'early_access_ends_at' => 'datetime',
        'released_at' => 'datetime',
        'show_release_date_on_card' => 'boolean',
        'gradual_access_enabled' => 'boolean',
        'publish_scheduled_at' => 'datetime',
        'published_at' => 'datetime',
        'published_snapshot' => 'array',
        'has_unpublished_changes' => 'boolean',
        'early_access_slots' => 'integer',
        'early_access_sold' => 'integer',
        'is_featured' => 'boolean',
        'is_free_preview' => 'boolean',
        'status' => BaseStatusEnum::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'category_id')->withDefault();
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(CourseChapter::class, 'course_id')->orderBy('order');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(CourseLesson::class, 'course_id')->orderBy('order');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class, 'course_id');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(CoursePurchase::class, 'course_id');
    }

    public function getDifficultyLabelAttribute(): string
    {
        return CourseOptions::levels()[$this->difficulty] ?? (string) $this->difficulty;
    }

    public function getSoftwareLabelsAttribute(): array
    {
        return $this->labelsFromOptions($this->software, CourseOptions::software());
    }

    public function getSkillLabelsAttribute(): array
    {
        return $this->labelsFromOptions($this->skills, CourseOptions::skills());
    }

    public function getSaleStatusLabelAttribute(): string
    {
        return CourseOptions::saleStatuses()[$this->sale_status] ?? (string) $this->sale_status;
    }

    public function isEarlyAccessAvailable(): bool
    {
        if (($this->sales_mode ?: $this->sale_status) !== 'preorder' && $this->sale_status !== 'early_access') {
            return false;
        }

        if (! $this->early_access_price || (float) $this->early_access_price <= 0) {
            return false;
        }

        if ($this->early_access_starts_at && $this->early_access_starts_at->isFuture()) {
            return false;
        }

        if ($this->early_access_ends_at && $this->early_access_ends_at->isPast()) {
            return false;
        }

        if ($this->early_access_slots !== null && $this->early_access_sold >= $this->early_access_slots) {
            return false;
        }

        return true;
    }

    public function isReleaseScheduled(): bool
    {
        return $this->accessOpensAt()?->isFuture() ?: false;
    }

    public function accessOpensAt()
    {
        if (! $this->released_at) {
            return null;
        }

        if (($this->course_access_mode ?: 'immediate') === 'scheduled' || $this->released_at->isFuture()) {
            return $this->released_at;
        }

        return null;
    }

    public function isCourseAccessOpen(): bool
    {
        return ! $this->isReleaseScheduled();
    }

    public function isVisibleInCatalog(): bool
    {
        return ($this->visibility_mode ?: 'catalog') === 'catalog';
    }

    public function isPublishedLive(): bool
    {
        if ($this->status->getValue() !== BaseStatusEnum::PUBLISHED) {
            return false;
        }

        $publicationState = $this->publication_state ?: 'published';

        if ($publicationState === 'hidden' || $publicationState === 'draft') {
            return false;
        }

        if ($publicationState === 'scheduled') {
            return $this->publish_scheduled_at && $this->publish_scheduled_at->isPast();
        }

        return true;
    }

    public function isPublicationScheduled(): bool
    {
        return ($this->publication_state ?: 'draft') === 'scheduled'
            && $this->publish_scheduled_at
            && $this->publish_scheduled_at->isFuture();
    }

    public function publicStatusLabel(): string
    {
        if ($this->isPublicationScheduled()) {
            return 'Запланирован';
        }

        return [
            'draft' => 'Черновик',
            'published' => 'Опубликован',
            'hidden' => 'Скрыт',
            'scheduled' => 'Опубликован',
        ][$this->publication_state ?: 'draft'] ?? 'Черновик';
    }

    public function publicVersion(): self
    {
        $snapshot = $this->published_snapshot['course'] ?? null;

        if (! $this->has_unpublished_changes || ! is_array($snapshot)) {
            return $this;
        }

        $course = clone $this;

        foreach ($snapshot as $key => $value) {
            if (in_array($key, ['id', 'status', 'publication_state'], true)) {
                continue;
            }

            $course->setAttribute($key, $value);
        }

        if (isset($this->published_snapshot['chapters']) && is_array($this->published_snapshot['chapters'])) {
            $course->setRelation('chapters', collect($this->published_snapshot['chapters'])
                ->map(function (array $chapter) {
                    $chapterModel = (new CourseChapter())->forceFill($chapter);

                    $chapterModel->setRelation('lessons', collect($chapter['lessons'] ?? [])
                        ->map(fn (array $lesson) => (new CourseLesson())->forceFill($lesson))
                        ->values());

                    return $chapterModel;
                })
                ->values());
        }

        return $course;
    }

    public function isPurchaseAllowed(): bool
    {
        $salesMode = $this->sales_mode ?: match ($this->sale_status) {
            'closed' => 'closed',
            'early_access' => 'preorder',
            default => 'immediate',
        };

        if ($salesMode === 'closed') {
            return false;
        }

        if ($salesMode === 'scheduled') {
            return ! $this->sales_starts_at || $this->sales_starts_at->isPast();
        }

        if ($salesMode === 'preorder') {
            return $this->isEarlyAccessAvailable();
        }

        return true;
    }

    public function isSalesScheduled(): bool
    {
        return ($this->sales_mode ?: 'immediate') === 'scheduled'
            && $this->sales_starts_at
            && $this->sales_starts_at->isFuture();
    }

    public function currentPrice(): string
    {
        if (($this->price_type ?: 'paid') === 'free') {
            return '0';
        }

        if ($this->isEarlyAccessAvailable()) {
            return (string) $this->early_access_price;
        }

        return (string) $this->price;
    }

    public function getCurrentPriceAttribute(): string
    {
        return $this->currentPrice();
    }

    public function getEarlyAccessSlotsLeftAttribute(): ?int
    {
        if ($this->early_access_slots === null) {
            return null;
        }

        return max(0, $this->early_access_slots - $this->early_access_sold);
    }

    protected function labelsFromOptions(?array $values, array $options): array
    {
        return collect($values ?: [])
            ->map(fn (string $value) => $options[$value] ?? $value)
            ->filter()
            ->values()
            ->all();
    }
}

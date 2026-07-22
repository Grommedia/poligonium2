<?php

namespace Botble\Courses\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseChapter extends BaseModel
{
    protected $table = 'plg_course_chapters';

    protected $fillable = [
        'course_id',
        'name',
        'description',
        'order',
        'is_free_preview',
        'status',
    ];

    protected $casts = [
        'name' => SafeContent::class,
        'description' => SafeContent::class,
        'is_free_preview' => 'boolean',
        'status' => BaseStatusEnum::class,
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id')->withDefault();
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(CourseLesson::class, 'chapter_id')->orderBy('order');
    }
}

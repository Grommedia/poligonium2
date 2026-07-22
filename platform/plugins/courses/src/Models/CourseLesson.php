<?php

namespace Botble\Courses\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseLesson extends BaseModel
{
    protected $table = 'plg_course_lessons';

    protected $fillable = [
        'course_id',
        'chapter_id',
        'name',
        'description',
        'content',
        'material_type',
        'video_path',
        'video_embed',
        'video_status',
        'duration_minutes',
        'access_mode',
        'opens_at',
        'drip_days',
        'order',
        'is_free_preview',
        'requires_access',
        'status',
    ];

    protected $casts = [
        'name' => SafeContent::class,
        'description' => SafeContent::class,
        'content' => SafeContent::class,
        'opens_at' => 'datetime',
        'drip_days' => 'integer',
        'is_free_preview' => 'boolean',
        'requires_access' => 'boolean',
        'status' => BaseStatusEnum::class,
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id')->withDefault();
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(CourseChapter::class, 'chapter_id')->withDefault();
    }

    public function files(): HasMany
    {
        return $this->hasMany(CourseLessonFile::class, 'lesson_id')->orderBy('order');
    }
}

<?php

namespace Botble\Courses\Models;

use Botble\ACL\Models\User;
use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Botble\Courses\Support\CourseOptions;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolGalleryProject extends BaseModel
{
    protected $table = 'plg_school_gallery_projects';

    protected $fillable = [
        'user_id',
        'course_id',
        'lesson_id',
        'title',
        'description',
        'image',
        'video',
        'tools',
        'status',
        'is_featured',
        'published_at',
    ];

    protected $casts = [
        'title' => SafeContent::class,
        'description' => SafeContent::class,
        'tools' => 'array',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id')->withDefault();
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(CourseLesson::class, 'lesson_id')->withDefault();
    }

    public function getStatusLabelAttribute(): string
    {
        return CourseOptions::galleryStatuses()[$this->status] ?? (string) $this->status;
    }
}

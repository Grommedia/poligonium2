<?php

namespace Botble\Courses\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseLessonFile extends BaseModel
{
    protected $table = 'plg_course_lesson_files';

    protected $fillable = [
        'course_id',
        'lesson_id',
        'name',
        'file_path',
        'file_size',
        'is_downloadable',
        'requires_access',
        'order',
        'status',
    ];

    protected $casts = [
        'name' => SafeContent::class,
        'is_downloadable' => 'boolean',
        'requires_access' => 'boolean',
        'status' => BaseStatusEnum::class,
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id')->withDefault();
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(CourseLesson::class, 'lesson_id')->withDefault();
    }
}

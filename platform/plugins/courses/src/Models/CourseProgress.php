<?php

namespace Botble\Courses\Models;

use Botble\ACL\Models\User;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseProgress extends BaseModel
{
    protected $table = 'plg_course_progress';

    protected $fillable = [
        'user_id',
        'course_id',
        'lesson_id',
        'status',
        'progress_seconds',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'progress_seconds' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
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
}

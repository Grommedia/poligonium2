<?php

namespace Botble\Courses\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Botble\ACL\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseEnrollment extends BaseModel
{
    protected $table = 'plg_course_enrollments';

    protected $fillable = [
        'user_id',
        'course_id',
        'source',
        'status',
        'starts_at',
        'ends_at',
        'notes',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'notes' => SafeContent::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id')->withDefault();
    }
}

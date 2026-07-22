<?php

namespace Botble\Courses\Models;

use Botble\Base\Models\BaseModel;
use Botble\ACL\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseOpeningReminder extends BaseModel
{
    protected $table = 'plg_course_opening_reminders';

    protected $fillable = [
        'course_id',
        'user_id',
        'email',
        'remind_at',
        'sent_at',
    ];

    protected $casts = [
        'remind_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id')->withDefault();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}

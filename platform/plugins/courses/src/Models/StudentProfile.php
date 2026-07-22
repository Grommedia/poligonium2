<?php

namespace Botble\Courses\Models;

use Botble\ACL\Models\User;
use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Botble\Courses\Support\CourseOptions;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends BaseModel
{
    protected $table = 'plg_student_profiles';

    protected $fillable = [
        'user_id',
        'display_name',
        'avatar',
        'bio',
        'rank_slug',
        'xp',
        'public_gallery_enabled',
    ];

    protected $casts = [
        'display_name' => SafeContent::class,
        'bio' => SafeContent::class,
        'xp' => 'integer',
        'public_gallery_enabled' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function getRankLabelAttribute(): string
    {
        return CourseOptions::studentRanks()[$this->rank_slug] ?? (string) $this->rank_slug;
    }
}

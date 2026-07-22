<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Botble\Courses\Models\CourseEnrollment;
use Botble\Courses\Models\CourseProgress;
use Botble\Courses\Models\CoursePurchase;
use Botble\Courses\Models\SchoolGalleryProject;
use Botble\Courses\Models\StudentProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getNameAttribute(): string
    {
        $name = trim(implode(' ', array_filter([
            $this->attributes['first_name'] ?? null,
            $this->attributes['last_name'] ?? null,
        ])));

        return $name ?: ($this->attributes['username'] ?? $this->attributes['email'] ?? '');
    }

    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class, 'user_id');
    }

    public function courseEnrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class, 'user_id');
    }

    public function coursePurchases(): HasMany
    {
        return $this->hasMany(CoursePurchase::class, 'user_id');
    }

    public function courseProgress(): HasMany
    {
        return $this->hasMany(CourseProgress::class, 'user_id');
    }

    public function schoolGalleryProjects(): HasMany
    {
        return $this->hasMany(SchoolGalleryProject::class, 'user_id');
    }
}

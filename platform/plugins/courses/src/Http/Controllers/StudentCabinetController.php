<?php

namespace Botble\Courses\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Courses\Models\CourseEnrollment;
use Botble\Courses\Models\CourseProgress;
use Botble\Courses\Models\CoursePurchase;
use Botble\Courses\Models\SchoolGalleryProject;
use Botble\Courses\Models\StudentProfile;
use Botble\Courses\Support\CourseOptions;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class StudentCabinetController extends BaseController
{
    public function __invoke(): Response
    {
        $user = Auth::guard()->user();

        $profile = StudentProfile::query()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'display_name' => $user->name,
                'rank_slug' => 'newcomer',
                'xp' => 0,
                'public_gallery_enabled' => false,
            ]
        );

        $enrollments = CourseEnrollment::query()
            ->with('course')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $purchases = CoursePurchase::query()
            ->with('course')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $progress = CourseProgress::query()
            ->with(['course', 'lesson'])
            ->where('user_id', $user->id)
            ->latest('updated_at')
            ->get();

        $galleryProjects = SchoolGalleryProject::query()
            ->with('course')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $stats = [
            'rank' => CourseOptions::studentRanks()[$profile->rank_slug] ?? $profile->rank_slug,
            'xp' => $profile->xp,
            'active_courses' => $enrollments
                ->where('status', 'active')
                ->filter(fn (CourseEnrollment $enrollment) => ! $enrollment->starts_at || $enrollment->starts_at->isPast())
                ->count(),
            'completed_lessons' => $progress->where('status', 'completed')->count(),
            'gallery_projects' => $galleryProjects->count(),
            'purchases' => $purchases->count(),
        ];

        SeoHelper::setTitle(request()->segment(1) === 'en' ? 'Student account' : 'Кабінет учня');

        return Theme::scope('school.cabinet', compact(
            'user',
            'profile',
            'enrollments',
            'purchases',
            'progress',
            'galleryProjects',
            'stats'
        ))->render();
    }
}

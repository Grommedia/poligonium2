<?php

namespace Botble\Courses\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Courses\Models\Course;
use Botble\Courses\Models\CourseEnrollment;
use Botble\Courses\Models\CourseLesson;
use Botble\Courses\Models\CourseOpeningReminder;
use Botble\Courses\Models\CoursePurchase;
use Botble\Courses\Support\CourseTranslationService;
use Botble\Courses\Support\MonopayService;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\AdminBar;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PublicController extends BaseController
{
    public function index(CourseTranslationService $translations): Response
    {
        SeoHelper::setTitle('Навчальні курси');

        $courses = Course::query()
            ->with('category')
            ->where('status', 'published')
            ->where(function ($query): void {
                $query
                    ->whereNull('publication_state')
                    ->orWhere('publication_state', 'published')
                    ->orWhere(function ($query): void {
                        $query
                            ->where('publication_state', 'scheduled')
                            ->where('publish_scheduled_at', '<=', now());
                    });
            })
            ->where(function ($query): void {
                $query->whereNull('visibility_mode')->orWhere('visibility_mode', 'catalog');
            })
            ->orderBy('order')
            ->latest()
            ->paginate(12);

        $courses->setCollection($courses->getCollection()
            ->map(fn (Course $course) => $translations->applyToCourse($course->publicVersion(), app()->getLocale())));

        return Theme::scope('courses.index', compact('courses'))->render();
    }

    public function show(Course $course, CourseTranslationService $translations): Response
    {
        abort_unless($course->isPublishedLive() || Auth::guard()->check(), 404);

        return $this->renderCourse($translations->applyToCourse($course->publicVersion(), app()->getLocale()));
    }

    public function preview(Course $course, Request $request, CourseTranslationService $translations): Response
    {
        $role = $request->query('preview_role', 'visitor');
        $role = in_array($role, ['visitor', 'buyer', 'student'], true) ? $role : 'visitor';

        return $this->renderCourse($translations->applyToCourse($course, app()->getLocale()), $role);
    }

    protected function renderCourse(Course $course, ?string $previewRole = null): Response
    {
        $course->loadMissing([
            'category',
            'chapters.lessons' => fn ($query) => $query->wherePublished()->orderBy('order'),
            'lessons.files' => fn ($query) => $query->wherePublished()->orderBy('order'),
        ]);

        $hasAccess = $this->hasAccess($course);
        $pendingPurchase = Auth::guard()->check()
            ? CoursePurchase::query()
                ->where('course_id', $course->id)
                ->where('user_id', Auth::guard()->id())
                ->where('status', 'pending')
                ->latest()
                ->first()
            : null;
        $paidPurchase = Auth::guard()->check()
            ? CoursePurchase::query()
                ->where('course_id', $course->id)
                ->where('user_id', Auth::guard()->id())
                ->where('status', 'paid')
                ->latest()
                ->first()
            : null;
        $enrollment = Auth::guard()->check()
            ? CourseEnrollment::query()
                ->where('course_id', $course->id)
                ->where('user_id', Auth::guard()->id())
                ->latest()
                ->first()
            : null;

        if ($previewRole === 'visitor') {
            $hasAccess = false;
            $pendingPurchase = null;
            $paidPurchase = null;
            $enrollment = null;
        }

        if ($previewRole === 'buyer') {
            $hasAccess = false;
            $pendingPurchase = (object) [
                'amount' => $course->current_price,
                'currency' => $course->currency ?: 'UAH',
            ];
            $paidPurchase = null;
            $enrollment = null;
        }

        if ($previewRole === 'student') {
            $hasAccess = true;
            $pendingPurchase = null;
            $paidPurchase = null;
            $enrollment = (object) [
                'starts_at' => now(),
            ];
        }

        SeoHelper::setTitle($course->name)->setDescription($course->description);

        if (function_exists('admin_bar')) {
            AdminBar::registerLink(
                'Edit course',
                route('courses.courses.edit', $course->id),
                null,
                'courses.courses.edit'
            );
        }

        return Theme::scope('courses.show', compact('course', 'hasAccess', 'pendingPurchase', 'paidPurchase', 'enrollment', 'previewRole'))->render();
    }

    public function purchase(Course $course): RedirectResponse
    {
        abort_unless($course->isPublishedLive() || Auth::guard()->check(), 404);

        if (! Auth::guard()->check()) {
            return redirect()
                ->route('courses.student.login')
                ->with('status', 'Войдите или зарегистрируйтесь, чтобы купить курс.');
        }

        if ($this->hasAccess($course)) {
            return redirect()
                ->route('courses.student.cabinet')
                ->with('status', 'Доступ к этому курсу уже открыт.');
        }

        if (! $course->isPurchaseAllowed()) {
            $message = $course->isSalesScheduled() && $course->sales_starts_at
                ? sprintf('Продаж курсу почнеться %s.', $course->sales_starts_at->format('d.m.Y H:i'))
                : 'Продаж цього курсу зараз закритий.';

            return back()->with('error_msg', $message);
        }

        $existingPaid = CoursePurchase::query()
            ->where('course_id', $course->id)
            ->where('user_id', Auth::guard()->id())
            ->where('status', 'paid')
            ->exists();

        if ($existingPaid) {
            return redirect()
                ->route('courses.student.cabinet')
                ->with('status', 'Покупка уже подтверждена.');
        }

        $purchaseType = $course->isEarlyAccessAvailable() ? 'early_access' : 'full';
        $amount = (float) $course->current_price;
        $fullPrice = (float) $course->price;

        if ($amount <= 0) {
            CourseEnrollment::query()->updateOrCreate(
                [
                    'user_id' => Auth::guard()->id(),
                    'course_id' => $course->id,
                ],
                [
                    'source' => 'free',
                    'status' => 'active',
                    'starts_at' => $course->accessOpensAt() ?: now(),
                    'ends_at' => null,
                    'notes' => 'Free course enrollment',
                ]
            );

            return redirect()
                ->route('courses.student.cabinet')
                ->with('status', 'Курс добавлен в ваш кабинет.');
        }

        try {
            $purchase = DB::transaction(function () use ($course, $purchaseType, $amount, $fullPrice): CoursePurchase {
                return CoursePurchase::query()->firstOrCreate(
                    [
                        'course_id' => $course->id,
                        'user_id' => Auth::guard()->id(),
                        'status' => 'pending',
                    ],
                    [
                        'purchase_type' => $purchaseType,
                        'amount' => $amount,
                        'full_price' => $fullPrice,
                        'discount_amount' => max(0, $fullPrice - $amount),
                        'currency' => $course->currency ?: 'UAH',
                        'payment_provider' => 'monopay',
                        'payment_reference' => 'POL-' . $course->id . '-' . Auth::guard()->id() . '-' . Str::upper(Str::random(6)),
                    ]
                );
            });

            if (! $purchase->provider_page_url) {
                $invoice = app(MonopayService::class)->createInvoice($purchase);

                $purchase->forceFill([
                    'payment_provider' => 'monopay',
                    'payment_reference' => $invoice['reference'],
                    'provider_invoice_id' => $invoice['invoice_id'],
                    'provider_page_url' => $invoice['page_url'],
                    'provider_status' => 'created',
                    'provider_payload' => $invoice['raw'],
                ])->save();
            }
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('error_msg', 'Не удалось создать счет Monopay. Проверьте MONOPAY_TOKEN или попробуйте позже.');
        }

        return redirect()->away($purchase->provider_page_url);
    }

    public function lesson(Course $course, CourseLesson $lesson, CourseTranslationService $translations): Response
    {
        abort_unless((int) $lesson->course_id === (int) $course->id, 404);
        abort_unless($course->isPublishedLive() || Auth::guard()->check(), 404);
        abort_unless($lesson->status->getValue() === 'published' || Auth::guard()->check(), 404);

        $course->load(['chapters.lessons' => fn ($query) => $query->wherePublished()->orderBy('order')]);
        $lesson->load(['chapter', 'files' => fn ($query) => $query->wherePublished()->orderBy('order')]);
        $translations->applyToCourse($course, app()->getLocale());

        $translatedLesson = $course->chapters
            ->flatMap(fn ($chapter) => $chapter->lessons)
            ->first(fn ($item) => (int) $item->getKey() === (int) $lesson->getKey());

        if ($translatedLesson) {
            $lesson = $translatedLesson;
            $lesson->loadMissing(['chapter', 'files' => fn ($query) => $query->wherePublished()->orderBy('order')]);
        }

        $hasAccess = $this->hasAccess($course);
        $canWatch = $this->canWatchLesson($course, $lesson, $hasAccess);

        SeoHelper::setTitle($lesson->name);

        return Theme::scope('courses.lesson', compact('course', 'lesson', 'hasAccess', 'canWatch'))->render();
    }

    public function video(Course $course, CourseLesson $lesson): Response
    {
        abort_unless((int) $lesson->course_id === (int) $course->id, 404);
        abort_unless($course->isPublishedLive() || Auth::guard()->check(), 404);

        $hasAccess = $this->hasAccess($course);
        $canWatch = $this->canWatchLesson($course, $lesson, $hasAccess);

        abort_unless($canWatch, 403);
        abort_unless($lesson->video_path, 404);

        $path = ltrim($lesson->video_path, '/');
        $fullPath = Storage::disk('local')->path($path);

        abort_unless(is_file($fullPath), 404);

        return response()->file($fullPath, [
            'Content-Type' => 'video/mp4',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function remind(Course $course, Request $request): RedirectResponse
    {
        abort_unless($course->isPublishedLive() || Auth::guard()->check(), 404);

        if (! $course->accessOpensAt()) {
            return back()->with('status', 'Курс уже доступний.');
        }

        if (! Auth::guard()->check()) {
            return redirect()
                ->route('courses.student.login')
                ->with('status', 'Увійдіть або зареєструйтесь, щоб отримати нагадування про старт курсу.');
        }

        CourseOpeningReminder::query()->updateOrCreate(
            [
                'course_id' => $course->getKey(),
                'user_id' => Auth::guard()->id(),
            ],
            [
                'email' => Auth::guard()->user()->email,
                'remind_at' => $course->accessOpensAt(),
                'sent_at' => null,
            ]
        );

        return back()->with('status', 'Ми нагадаємо про відкриття курсу.');
    }

    protected function hasAccess(Course $course): bool
    {
        if (! Auth::guard()->check()) {
            return false;
        }

        return CourseEnrollment::query()
            ->where('course_id', $course->id)
            ->where('user_id', Auth::guard()->id())
            ->where('status', 'active')
            ->where(function ($query): void {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query): void {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->exists();
    }

    protected function canWatchLesson(Course $course, CourseLesson $lesson, bool $hasAccess): bool
    {
        if (! $course->isCourseAccessOpen()) {
            return false;
        }

        if ($lesson->access_mode === 'scheduled' && $lesson->opens_at && $lesson->opens_at->isFuture()) {
            return false;
        }

        if ($lesson->access_mode === 'drip' && $lesson->drip_days !== null) {
            if (! Auth::guard()->check()) {
                return false;
            }

            $enrollment = CourseEnrollment::query()
                ->where('course_id', $course->id)
                ->where('user_id', Auth::guard()->id())
                ->where('status', 'active')
                ->first();

            if (! $enrollment || ! $enrollment->starts_at) {
                return false;
            }

            if ($enrollment->starts_at->copy()->addDays((int) $lesson->drip_days)->isFuture()) {
                return false;
            }
        }

        return $hasAccess || $lesson->is_free_preview || ! $lesson->requires_access;
    }
}

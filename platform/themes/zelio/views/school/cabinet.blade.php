@php
    $isEnglish = request()->segment(1) === 'en';
    $copy = $isEnglish
        ? [
            'welcome' => 'Welcome in,',
            'intro' => 'Your learning dashboard: courses, early access, purchases, progress and works for the Polygonium School gallery.',
            'logout' => 'Sign out',
            'overview' => 'Overview',
            'courses' => 'Courses',
            'progress' => 'Progress',
            'purchases' => 'Purchases',
            'gallery' => 'Gallery',
            'profile' => 'Profile',
            'account_title' => 'Personal account',
            'back_to_site' => 'Back to site',
            'rank' => 'Rank',
            'xp' => 'XP',
            'active_courses' => 'Active courses',
            'completed_lessons' => 'Completed lessons',
            'gallery_projects' => 'Gallery works',
            'current_course' => 'Current course',
            'continue' => 'Continue learning',
            'catalog' => 'Course catalog',
            'course' => 'Course',
            'lessons' => 'lessons',
            'open_access' => 'Access open',
            'opens_at' => 'Opens',
            'opens_in' => 'Opens in',
            'waiting_start' => 'Purchased, waiting for course opening',
            'no_course' => 'No active course yet',
            'no_course_text' => 'After purchase or manual access, your learning program will appear here.',
            'study_time' => 'Study time',
            'minutes' => 'min',
            'this_week' => 'Saved watch time',
            'learning_plan' => 'Learning plan',
            'next_lesson' => 'Next lesson',
            'no_progress' => 'Progress will appear when you start watching lessons.',
            'my_courses' => 'My courses',
            'school_gallery' => 'School gallery',
            'gallery_empty_title' => 'Gallery is being prepared',
            'gallery_empty_text' => 'Soon students will be able to publish works, receive status and build a school portfolio.',
            'purchases_empty' => 'No purchases yet. Early access and paid courses will appear here.',
            'status' => 'Status',
            'student' => 'Student',
            'profile_note' => 'Personal cabinet for courses and school projects.',
            'purchase_types' => [
                'early_access' => 'Early access',
                'full' => 'Full purchase',
                'manual' => 'Manual access',
                'subscription' => 'Subscription',
            ],
            'statuses' => [
                'pending' => 'Pending payment',
                'paid' => 'Paid',
                'cancelled' => 'Cancelled',
                'refunded' => 'Refunded',
                'active' => 'Active',
            ],
        ]
        : [
            'welcome' => 'Вітаю,',
            'intro' => 'Твій навчальний кабінет: курси, ранній доступ, покупки, прогрес і роботи для галереї Polygonium School.',
            'logout' => 'Вийти',
            'overview' => 'Огляд',
            'courses' => 'Курси',
            'progress' => 'Прогрес',
            'purchases' => 'Покупки',
            'gallery' => 'Галерея',
            'profile' => 'Профіль',
            'account_title' => 'Особистий кабінет',
            'back_to_site' => 'На сайт',
            'rank' => 'Ранг',
            'xp' => 'XP',
            'active_courses' => 'Активні курси',
            'completed_lessons' => 'Уроків завершено',
            'gallery_projects' => 'Робіт у галереї',
            'current_course' => 'Поточний курс',
            'continue' => 'Продовжити навчання',
            'catalog' => 'Каталог курсів',
            'course' => 'Курс',
            'lessons' => 'уроків',
            'open_access' => 'Доступ відкрито',
            'opens_at' => 'Відкриється',
            'opens_in' => 'До відкриття',
            'waiting_start' => 'Придбано, очікує відкриття курсу',
            'no_course' => 'Активного курсу ще немає',
            'no_course_text' => 'Після покупки або ручної видачі доступу тут зʼявиться твоя навчальна програма.',
            'study_time' => 'Час навчання',
            'minutes' => 'хв',
            'this_week' => 'Збережений час перегляду',
            'learning_plan' => 'План навчання',
            'next_lesson' => 'Наступний урок',
            'no_progress' => 'Прогрес зʼявиться, коли ти почнеш дивитися уроки.',
            'my_courses' => 'Мої курси',
            'school_gallery' => 'Галерея школи',
            'gallery_empty_title' => 'Галерея готується',
            'gallery_empty_text' => 'Скоро учні зможуть публікувати роботи, отримувати статус і збирати портфоліо всередині школи.',
            'purchases_empty' => 'Покупок поки немає. Ранній доступ і оплачені курси зʼявляться тут.',
            'status' => 'Статус',
            'student' => 'Учень',
            'profile_note' => 'Особистий кабінет для курсів і шкільних проєктів.',
            'purchase_types' => [
                'early_access' => 'Ранній доступ',
                'full' => 'Повна покупка',
                'manual' => 'Ручний доступ',
                'subscription' => 'Підписка',
            ],
            'statuses' => [
                'pending' => 'Очікує оплату',
                'paid' => 'Оплачено',
                'cancelled' => 'Скасовано',
                'refunded' => 'Повернення',
                'active' => 'Активний',
            ],
        ];

    $displayName = $profile->display_name ?: $user->name ?: $copy['student'];
    $initial = mb_strtoupper(mb_substr($displayName, 0, 1));
    $activeEnrollment = $enrollments->firstWhere('status', 'active') ?: $enrollments->first();
    $activeCourse = $activeEnrollment?->course;
    $activeLessonCount = (int) ($activeCourse?->lesson_count ?: 0);
    $activeCompletedLessons = $activeCourse ? $progress->where('course_id', $activeCourse->id)->where('status', 'completed')->count() : 0;
    $activeProgressPercent = $activeLessonCount > 0 ? min(100, (int) round($activeCompletedLessons / $activeLessonCount * 100)) : ($activeCourse ? 8 : 0);
    $watchMinutes = (int) floor($progress->sum('progress_seconds') / 60);
    $xpGoal = max(500, (int) ceil(((int) $stats['xp'] + 1) / 500) * 500);
    $xpPercent = min(100, (int) round(((int) $stats['xp'] / $xpGoal) * 100));
    $nextProgress = $progress->firstWhere('status', '!=', 'completed') ?: $progress->first();
    $navItems = [
        '#overview' => $copy['overview'],
        '#courses' => $copy['courses'],
        '#progress' => $copy['progress'],
        '#purchases' => $copy['purchases'],
        '#gallery' => $copy['gallery'],
        '#profile' => $copy['profile'],
    ];
@endphp

<section class="poligonium-school-page is-cabinet">
    <div class="poligonium-school-orb is-left" aria-hidden="true"></div>
    <div class="poligonium-school-orb is-right" aria-hidden="true"></div>

    <div class="poligonium-student-dashboard" id="overview">
        <nav class="poligonium-student-nav" aria-label="Student navigation">
            <a class="poligonium-student-brand" href="{{ route('courses.student.cabinet') }}">
                <strong>Polygonium</strong>
                <span>{{ $copy['account_title'] }}</span>
            </a>

            <div class="poligonium-student-tabs">
                @foreach ($navItems as $href => $label)
                    <a @class(['is-active' => $loop->first]) href="{{ $href }}">{{ $label }}</a>
                @endforeach
            </div>

            <div class="poligonium-student-actions">
                <a class="poligonium-student-site-link" href="{{ BaseHelper::getHomepageUrl() }}">{{ $copy['back_to_site'] }}</a>
                <form method="POST" action="{{ route('courses.student.logout') }}">
                    @csrf
                    <button type="submit">{{ $copy['logout'] }}</button>
                </form>
                <span class="poligonium-student-avatar">{{ $initial }}</span>
            </div>
        </nav>

        <div class="poligonium-student-hero">
            <section class="poligonium-student-welcome">
                <span class="poligonium-student-label">Polygonium School</span>
                <h1>{{ $copy['welcome'] }} {{ $displayName }}</h1>
                <p>{{ $copy['intro'] }}</p>

                <div class="poligonium-student-meter" aria-label="{{ $copy['xp'] }}">
                    <span style="width: {{ $xpPercent }}%"></span>
                </div>
                <div class="poligonium-student-meter-row">
                    <span>{{ $copy['rank'] }}: {{ $stats['rank'] }}</span>
                    <strong>{{ $stats['xp'] }} / {{ $xpGoal }} {{ $copy['xp'] }}</strong>
                </div>
            </section>

            <section class="poligonium-student-profile-card" id="profile">
                <div class="poligonium-student-portrait">{{ $initial }}</div>
                <strong>{{ $displayName }}</strong>
                <span>{{ $stats['rank'] }}</span>
                <p>{{ $copy['profile_note'] }}</p>
            </section>

            <section class="poligonium-student-progress-card">
                <div class="poligonium-student-circle" style="--student-progress: {{ $activeProgressPercent }}%">
                    <strong>{{ $activeProgressPercent }}%</strong>
                    <span>{{ $copy['progress'] }}</span>
                </div>
                <div>
                    <span class="poligonium-student-label">{{ $copy['current_course'] }}</span>
                    <strong>{{ $activeCourse?->name ?: $copy['no_course'] }}</strong>
                    <p>{{ $activeCourse ? $activeCompletedLessons . ' / ' . $activeLessonCount . ' ' . $copy['lessons'] : $copy['no_course_text'] }}</p>
                </div>
            </section>
        </div>

        <div class="poligonium-student-stats">
            <article>
                <span>{{ $copy['active_courses'] }}</span>
                <strong>{{ $stats['active_courses'] }}</strong>
            </article>
            <article>
                <span>{{ $copy['completed_lessons'] }}</span>
                <strong>{{ $stats['completed_lessons'] }}</strong>
            </article>
            <article>
                <span>{{ $copy['study_time'] }}</span>
                <strong>{{ $watchMinutes }}</strong>
                <small>{{ $copy['minutes'] }}</small>
            </article>
            <article>
                <span>{{ $copy['gallery_projects'] }}</span>
                <strong>{{ $stats['gallery_projects'] }}</strong>
            </article>
        </div>

        <div class="poligonium-student-grid">
            <section class="poligonium-student-panel is-large" id="courses">
                <div class="poligonium-student-panel-head">
                    <div>
                        <span>{{ $copy['my_courses'] }}</span>
                        <strong>{{ $copy['learning_plan'] }}</strong>
                    </div>
                    <a href="{{ route('courses.public.index') }}">{{ $copy['catalog'] }}</a>
                </div>

                <div class="poligonium-student-course-list">
                    @forelse ($enrollments as $enrollment)
                        @php
                            $course = $enrollment->course;
                            $courseLessons = (int) ($course->lesson_count ?: 0);
                            $courseCompleted = $progress->where('course_id', $course->id)->where('status', 'completed')->count();
                            $coursePercent = $courseLessons > 0 ? min(100, (int) round($courseCompleted / $courseLessons * 100)) : 0;
                            $startsAt = $enrollment->starts_at;
                            $isWaitingStart = $enrollment->status === 'active' && $startsAt && $startsAt->isFuture();
                            $status = $isWaitingStart ? $copy['waiting_start'] : ($copy['statuses'][$enrollment->status] ?? $enrollment->status);
                        @endphp
                        <a class="poligonium-student-course-row" href="{{ $course->slug ? route('courses.public.show', $course->slug) : '#' }}">
                            <span class="poligonium-student-course-thumb">
                                @if ($course->image)
                                    <img src="{{ RvMedia::getImageUrl($course->image) }}" alt="{{ $course->name }}">
                                @else
                                    {{ mb_strtoupper(mb_substr($course->name ?: $copy['course'], 0, 1)) }}
                                @endif
                            </span>
                            <span class="poligonium-student-course-main">
                                <strong>{{ $course->name ?: $copy['course'] }}</strong>
                                <small>
                                    {{ $status }} · {{ $courseLessons }} {{ $copy['lessons'] }}
                                    @if ($isWaitingStart)
                                        · {{ $copy['opens_at'] }} {{ $startsAt->format('d.m.Y H:i') }}
                                    @endif
                                </small>
                                <span class="poligonium-student-mini-meter"><i style="width: {{ $coursePercent }}%"></i></span>
                                @if ($isWaitingStart)
                                    <span class="poligonium-student-countdown" data-student-countdown data-release-at="{{ $startsAt->toIso8601String() }}">
                                        {{ $copy['opens_in'] }}:
                                        <b data-countdown-days>--</b>д
                                        <b data-countdown-hours>--</b>г
                                        <b data-countdown-minutes>--</b>хв
                                    </span>
                                @endif
                            </span>
                            <em>{{ $coursePercent }}%</em>
                        </a>
                    @empty
                        <div class="poligonium-student-empty">
                            <strong>{{ $copy['no_course'] }}</strong>
                            <p>{{ $copy['no_course_text'] }}</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <aside class="poligonium-student-panel is-dark" id="progress">
                <div class="poligonium-student-panel-head">
                    <div>
                        <span>{{ $copy['next_lesson'] }}</span>
                        <strong>{{ $copy['continue'] }}</strong>
                    </div>
                </div>

                @if ($nextProgress)
                    <div class="poligonium-student-next">
                        <strong>{{ $nextProgress->lesson->name ?: $copy['next_lesson'] }}</strong>
                        <span>{{ $nextProgress->course->name ?: $copy['course'] }}</span>
                        <p>{{ $copy['this_week'] }}: {{ (int) floor($nextProgress->progress_seconds / 60) }} {{ $copy['minutes'] }}</p>
                    </div>
                @else
                    <p class="poligonium-student-muted">{{ $copy['no_progress'] }}</p>
                @endif
            </aside>

            <section class="poligonium-student-panel" id="purchases">
                <div class="poligonium-student-panel-head">
                    <div>
                        <span>{{ $copy['purchases'] }}</span>
                        <strong>{{ $stats['purchases'] }}</strong>
                    </div>
                </div>

                <div class="poligonium-student-list">
                    @forelse ($purchases->take(5) as $purchase)
                        @php
                            $purchaseType = $copy['purchase_types'][$purchase->purchase_type] ?? $purchase->purchase_type;
                            $purchaseStatus = $copy['statuses'][$purchase->status] ?? $purchase->status;
                        @endphp
                        <article>
                            <strong>{{ $purchase->course->name ?: $copy['course'] }}</strong>
                            <span>{{ $purchaseType }} · {{ $purchaseStatus }}</span>
                            <em>{{ number_format((float) $purchase->amount, 0, '.', ' ') }} {{ $purchase->currency }}</em>
                        </article>
                    @empty
                        <p class="poligonium-student-muted">{{ $copy['purchases_empty'] }}</p>
                    @endforelse
                </div>
            </section>

            <section class="poligonium-student-panel is-large" id="gallery">
                <div class="poligonium-student-panel-head">
                    <div>
                        <span>{{ $copy['school_gallery'] }}</span>
                        <strong>{{ $stats['gallery_projects'] }}</strong>
                    </div>
                </div>

                <div class="poligonium-student-gallery">
                    @forelse ($galleryProjects->take(4) as $project)
                        <article>
                            @if ($project->image)
                                <img src="{{ RvMedia::getImageUrl($project->image) }}" alt="{{ $project->title }}">
                            @endif
                            <strong>{{ $project->title }}</strong>
                            <span>{{ $project->status_label }}</span>
                        </article>
                    @empty
                        <div class="poligonium-student-empty">
                            <strong>{{ $copy['gallery_empty_title'] }}</strong>
                            <p>{{ $copy['gallery_empty_text'] }}</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</section>

<script>
    (() => {
        const countdowns = Array.from(document.querySelectorAll('[data-student-countdown]'))
            .map((element) => ({ element, target: new Date(element.dataset.releaseAt) }))
            .filter((item) => !Number.isNaN(item.target.getTime()));

        if (!countdowns.length) {
            return;
        }

        const update = () => {
            countdowns.forEach(({ element, target }) => {
                const diff = Math.max(0, target.getTime() - Date.now());
                const totalSeconds = Math.floor(diff / 1000);

                element.querySelector('[data-countdown-days]').textContent = String(Math.floor(totalSeconds / 86400));
                element.querySelector('[data-countdown-hours]').textContent = String(Math.floor((totalSeconds % 86400) / 3600)).padStart(2, '0');
                element.querySelector('[data-countdown-minutes]').textContent = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
            });
        };

        update();
        window.setInterval(update, 1000);
    })();
</script>

@include('theme.zelio::views.school.partials.styles')

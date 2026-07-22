@php
    $softwareLabels = $course->software_labels;
    $skillLabels = $course->skill_labels;
    $isEarlyAccess = $course->isEarlyAccessAvailable();
    $isReleaseScheduled = $course->isReleaseScheduled();
    $releaseAt = $course->accessOpensAt();
    $releaseDate = $releaseAt ? $releaseAt->format('d.m.Y H:i') : null;
    $releaseIso = $releaseAt ? $releaseAt->toIso8601String() : null;
    $activePrice = (float) $course->current_price;
    $price = $activePrice > 0 ? number_format($activePrice, 0, '.', ' ') . ' ' . $course->currency : 'Безкоштовно';
    $fullPrice = (float) $course->price > 0 ? number_format((float) $course->price, 0, '.', ' ') . ' ' . $course->currency : null;
    $isPurchasedWaiting = $isReleaseScheduled && ($paidPurchase || $enrollment);
    $canBuy = $course->isPurchaseAllowed() && ! $hasAccess && ! $pendingPurchase && ! $paidPurchase && ! $enrollment;
    $lessonTimedLocks = $course->chapters
        ->flatMap(fn ($chapter) => $chapter->lessons)
        ->contains(function ($lesson) use ($enrollment) {
            if ($lesson->access_mode === 'scheduled' && $lesson->opens_at && $lesson->opens_at->isFuture()) {
                return true;
            }

            return $lesson->access_mode === 'drip'
                && $enrollment
                && $enrollment->starts_at
                && $enrollment->starts_at->copy()->addDays((int) $lesson->drip_days)->isFuture();
        });
    $hasTimedLocks = $isReleaseScheduled || $lessonTimedLocks;
@endphp

<section class="poligonium-courses-page">
    <div class="poligonium-courses-wrap">
        <div class="poligonium-course-detail">
            <div>
                <p class="poligonium-courses-kicker">{{ $course->category->name ?: 'Polygonium School' }}</p>
                <h1>{{ $course->name }}</h1>
                <p>{{ $course->description }}</p>

                @if (session('status'))
                    <div class="poligonium-course-notice is-success">{{ session('status') }}</div>
                @endif

                @if (session('error_msg'))
                    <div class="poligonium-course-notice is-error">{{ session('error_msg') }}</div>
                @endif

                <div class="poligonium-course-stats">
                    <span>{{ $course->difficulty_label ?: 'Production' }}</span>
                    <span>{{ $course->lesson_count }} уроків</span>
                    <span>{{ $course->duration_minutes ? round($course->duration_minutes / 60, 1) . ' год' : 'тривалість уточнюється' }}</span>
                    <span>{{ $hasAccess ? 'Доступ відкрито' : $price }}</span>
                    @if ($isReleaseScheduled)
                        <span>Відкриється {{ $releaseDate }}</span>
                    @endif
                    @if ($isEarlyAccess)
                        <span>Ранній доступ</span>
                    @endif
                </div>

                @if ($softwareLabels || $skillLabels)
                    <div class="poligonium-course-tags is-detail">
                        @foreach (array_merge($softwareLabels, $skillLabels) as $label)
                            <span>{{ $label }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="poligonium-course-side">
                <strong>
                    @if ($isReleaseScheduled)
                        Курс відкриється скоро
                    @elseif ($hasAccess)
                        Курс доступний
                    @elseif ($pendingPurchase)
                        Заявка створена
                    @elseif ($isEarlyAccess)
                        Ранній доступ до курсу
                    @else
                        Доступ до курсу
                    @endif
                </strong>

                @if ($isPurchasedWaiting)
                    <p>Курс придбано. Матеріали відкриються {{ $releaseDate }}.</p>
                    <div class="poligonium-course-detail-countdown" data-course-countdown data-release-at="{{ $releaseIso }}">
                        <span><strong data-countdown-days>--</strong>д</span>
                        <span><strong data-countdown-hours>--</strong>г</span>
                        <span><strong data-countdown-minutes>--</strong>хв</span>
                        <span><strong data-countdown-seconds>--</strong>с</span>
                    </div>
                    <a class="poligonium-course-buy-link" href="{{ route('courses.student.cabinet') }}">Перейти в кабінет</a>
                @elseif ($isReleaseScheduled)
                    <p>Матеріали курсу відкриються {{ $releaseDate }}. Курс можна придбати зараз, а після покупки він зʼявиться у вашому кабінеті з таймером до старту.</p>
                    <div class="poligonium-course-detail-countdown" data-course-countdown data-release-at="{{ $releaseIso }}">
                        <span><strong data-countdown-days>--</strong>д</span>
                        <span><strong data-countdown-hours>--</strong>г</span>
                        <span><strong data-countdown-minutes>--</strong>хв</span>
                        <span><strong data-countdown-seconds>--</strong>с</span>
                    </div>
                    <form method="POST" action="{{ route('courses.public.remind', $course->slug) }}">
                        @csrf
                        <button class="poligonium-course-buy-link is-muted" type="submit">Нагадати про відкриття</button>
                    </form>
                @elseif ($hasAccess)
                    <p>Можна переходити до відкритих уроків курсу. Усі доступні матеріали вже прив'язані до вашого кабінету.</p>
                    <a class="poligonium-course-buy-link" href="{{ route('courses.student.cabinet') }}">Перейти в кабінет</a>
                @elseif ($pendingPurchase)
                    <p>Ми зафіксували вашу заявку на суму {{ number_format((float) $pendingPurchase->amount, 0, '.', ' ') }} {{ $pendingPurchase->currency }}. Після підтвердження оплати курс відкриється автоматично.</p>
                    <a class="poligonium-course-buy-link" href="{{ route('courses.student.cabinet') }}">Подивитися в кабінеті</a>
                @elseif ($isEarlyAccess)
                    <p>Курс ще створюється, тому зараз доступний за нижчою ціною. Після релізу повна ціна буде {{ $fullPrice ?: 'вища' }}.</p>
                    @if ($course->early_access_slots_left !== null)
                        <p>Залишилось місць раннього доступу: {{ $course->early_access_slots_left }}.</p>
                    @endif
                @else
                    <p>Безкоштовні уроки відкриті одразу. Платні розділи відкриваються після покупки або ручної видачі доступу.</p>
                @endif

                @if ($canBuy)
                    <form method="POST" action="{{ route('courses.public.purchase', $course->slug) }}">
                        @csrf
                        <button class="poligonium-course-buy-button" type="submit">
                            {{ $activePrice > 0 ? ($isEarlyAccess ? 'Забронювати ранній доступ' : 'Купити курс') : 'Додати курс' }}
                            <span>{{ $price }}</span>
                        </button>
                    </form>
                    <small class="poligonium-course-buy-note">
                        @if ($isReleaseScheduled)
                            Після оплати курс зʼявиться в кабінеті. Матеріали відкриються {{ $releaseDate }}.
                        @else
                            Оплата зараз підтверджується вручну. Після підтвердження доступ з'явиться в кабінеті.
                        @endif
                    </small>
                @endif
            </div>
        </div>

        @if ($course->content)
            <div class="poligonium-course-content ck-content">{!! BaseHelper::clean($course->content) !!}</div>
        @endif

        <div class="poligonium-curriculum">
            <h2>Програма курсу</h2>
            @foreach ($course->chapters as $chapter)
                <section class="poligonium-chapter">
                    <h3>{{ $chapter->name }}</h3>
                    @if ($chapter->description)
                        <p>{{ $chapter->description }}</p>
                    @endif
                    <div class="poligonium-lessons">
                        @foreach ($chapter->lessons as $lesson)
                            @php
                                $lessonOpensAt = $releaseAt;

                                if (! $lessonOpensAt && $lesson->access_mode === 'scheduled' && $lesson->opens_at && $lesson->opens_at->isFuture()) {
                                    $lessonOpensAt = $lesson->opens_at;
                                }

                                if (! $lessonOpensAt && $lesson->access_mode === 'drip' && $enrollment && $enrollment->starts_at) {
                                    $dripOpensAt = $enrollment->starts_at->copy()->addDays((int) $lesson->drip_days);
                                    $lessonOpensAt = $dripOpensAt->isFuture() ? $dripOpensAt : null;
                                }

                                $isTimedLocked = $lessonOpensAt && $lessonOpensAt->isFuture();
                                $isOpen = ! $isTimedLocked && ($hasAccess || $lesson->is_free_preview || ! $lesson->requires_access);
                            @endphp
                            @if ($isTimedLocked)
                                <a class="poligonium-lesson-row is-disabled" href="{{ route('courses.public.lesson', [$course->slug, $lesson->id]) }}" data-course-locked-lesson data-course-release="{{ $lessonOpensAt->toIso8601String() }}" data-course-release-date="{{ $lessonOpensAt->format('d.m.Y H:i') }}">
                                    <span>{{ $lesson->name }}</span>
                                    <strong>Відкриється {{ $lessonOpensAt->format('d.m.Y H:i') }}</strong>
                                </a>
                            @else
                                <a class="poligonium-lesson-row" href="{{ route('courses.public.lesson', [$course->slug, $lesson->id]) }}">
                                    <span>{{ $lesson->name }}</span>
                                    <strong>{{ $isOpen ? ($lesson->is_free_preview ? 'Безкоштовний перегляд' : 'Відкрито') : 'Закрито' }}</strong>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </div>

    @if ($hasTimedLocks)
        <div class="poligonium-course-release-modal" data-course-release-modal aria-hidden="true">
            <div class="poligonium-course-release-modal__backdrop" data-course-release-close></div>
            <section class="poligonium-course-release-modal__dialog" role="dialog" aria-modal="true">
                <button type="button" class="poligonium-course-release-modal__close" data-course-release-close aria-label="Закрити">×</button>
                <span class="poligonium-course-release-modal__kicker">Polygonium School</span>
                <h2>Курс ще не відкрито</h2>
                <p data-course-release-message>Матеріали стануть доступні {{ $releaseDate }}. Ми повідомимо про старт, якщо ви увімкнете нагадування.</p>
                <div class="poligonium-course-release-modal__timer" data-course-modal-countdown>
                    <span><strong data-countdown-days>--</strong>д</span>
                    <span><strong data-countdown-hours>--</strong>г</span>
                    <span><strong data-countdown-minutes>--</strong>хв</span>
                    <span><strong data-countdown-seconds>--</strong>с</span>
                </div>
                @if ($releaseAt)
                    <form method="POST" action="{{ route('courses.public.remind', $course->slug) }}">
                        @csrf
                        <button class="poligonium-course-release-modal__button" type="submit">Нагадати мені</button>
                    </form>
                @else
                    <button type="button" class="poligonium-course-release-modal__button" data-course-release-close>Зрозуміло</button>
                @endif
            </section>
        </div>
    @endif
</section>

@if ($hasTimedLocks)
    <script>
        (() => {
            let modalTarget = null;
            const modal = document.querySelector('[data-course-release-modal]');
            const modalMessage = document.querySelector('[data-course-release-message]');
            const modalTimer = document.querySelector('[data-course-modal-countdown]');

            const formatCountdown = (target) => {
                const diff = Math.max(0, target.getTime() - Date.now());
                const totalSeconds = Math.floor(diff / 1000);

                return {
                    days: String(Math.floor(totalSeconds / 86400)),
                    hours: String(Math.floor((totalSeconds % 86400) / 3600)).padStart(2, '0'),
                    minutes: String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0'),
                    seconds: String(totalSeconds % 60).padStart(2, '0'),
                };
            };

            const applyCountdown = (container, target) => {
                if (!container || !target || Number.isNaN(target.getTime())) {
                    return;
                }

                const values = formatCountdown(target);
                container.querySelectorAll('[data-countdown-days]').forEach((item) => item.textContent = values.days);
                container.querySelectorAll('[data-countdown-hours]').forEach((item) => item.textContent = values.hours);
                container.querySelectorAll('[data-countdown-minutes]').forEach((item) => item.textContent = values.minutes);
                container.querySelectorAll('[data-countdown-seconds]').forEach((item) => item.textContent = values.seconds);
            };

            const openModal = () => {
                modal?.setAttribute('aria-hidden', 'false');
                document.documentElement.classList.add('has-course-release-modal');
                applyCountdown(modalTimer, modalTarget);
            };

            const closeModal = () => {
                modal?.setAttribute('aria-hidden', 'true');
                document.documentElement.classList.remove('has-course-release-modal');
            };

            document.querySelectorAll('[data-course-countdown]').forEach((container) => {
                applyCountdown(container, new Date(container.dataset.releaseAt));
            });
            window.setInterval(() => {
                document.querySelectorAll('[data-course-countdown]').forEach((container) => {
                    applyCountdown(container, new Date(container.dataset.releaseAt));
                });
                applyCountdown(modalTimer, modalTarget);
            }, 1000);

            document.querySelectorAll('[data-course-locked-lesson]').forEach((link) => {
                link.addEventListener('click', (event) => {
                    event.preventDefault();
                    modalTarget = new Date(link.dataset.courseRelease);
                    if (modalMessage) {
                        modalMessage.textContent = `Матеріали стануть доступні ${link.dataset.courseReleaseDate}. Ми повідомимо про старт, якщо ви увімкнете нагадування.`;
                    }
                    openModal();
                });
            });

            document.querySelectorAll('[data-course-release-close]').forEach((button) => button.addEventListener('click', closeModal));
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeModal();
                }
            });
        })();
    </script>
@endif

@include('theme.zelio::views.courses.partials.styles')

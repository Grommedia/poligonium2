@php
    $isEnglish = request()->segment(1) === 'en';
    $copy = $isEnglish
        ? [
            'title' => 'Courses',
            'catalog_title' => 'Starter courses',
            'catalog_text' => 'First practical programs in 3D modeling, animation and production workflow.',
            'preparing' => 'in preparation',
            'free' => 'Free',
            'early_access' => 'Early access',
            'then' => 'then',
            'watch' => 'View course',
            'locked_cta' => 'Course opens soon',
            'details' => 'Details',
            'starts_in' => 'Starts in',
            'opens_at' => 'Opens',
            'day_one' => 'day',
            'day_few' => 'days',
            'day_many' => 'days',
            'hours' => 'hours',
            'minutes' => 'minutes',
            'seconds' => 'seconds',
            'modal_title' => 'Course is not available yet',
            'modal_text' => 'The course “:course” will open on :date. Come back when the timer reaches zero.',
            'modal_close' => 'Got it',
            'empty_title' => 'Courses are being prepared',
            'empty_text' => 'The section is already connected to the admin panel. Add the first course, chapters and lessons in admin.',
        ]
        : [
            'title' => 'Курси',
            'catalog_title' => 'Початкові курси',
            'catalog_text' => 'Перші практичні програми з 3D-моделювання, анімації та production-процесу.',
            'preparing' => 'у підготовці',
            'free' => 'Безкоштовно',
            'early_access' => 'Ранній доступ',
            'then' => 'потім',
            'watch' => 'Дивитися курс',
            'locked_cta' => 'Курс скоро відкриється',
            'details' => 'Детальніше',
            'starts_in' => 'Старт через',
            'opens_at' => 'Відкриється',
            'day_one' => 'день',
            'day_few' => 'дні',
            'day_many' => 'днів',
            'hours' => 'годин',
            'minutes' => 'хвилин',
            'seconds' => 'секунд',
            'modal_title' => 'Курс ще недоступний',
            'modal_text' => 'Курс «:course» буде доступний :date. Поверніться, коли таймер дійде до нуля.',
            'modal_close' => 'Зрозуміло',
            'empty_title' => 'Курси готуються',
            'empty_text' => 'Розділ уже підключений до адмін-панелі. Додайте перший курс, глави та уроки в адмінці.',
        ];
@endphp

<section class="poligonium-courses-page">
    <div class="poligonium-courses-orb is-one" aria-hidden="true"></div>
    <div class="poligonium-courses-orb is-two" aria-hidden="true"></div>

    <div class="poligonium-courses-wrap">
        <div class="poligonium-courses-hero">
            <div>
                <p class="poligonium-courses-kicker">Polygonium School</p>
                <h1>{{ $copy['title'] }}</h1>
            </div>

            <aside class="poligonium-courses-catalog" aria-label="{{ $copy['catalog_title'] }}">
                <span>01</span>
                <strong>{{ $copy['catalog_title'] }}</strong>
                <p>{{ $copy['catalog_text'] }}</p>
            </aside>
        </div>

        <div class="poligonium-course-grid">
            @forelse ($courses as $course)
                @php
                    $softwareLabels = $course->software_labels;
                    $skillLabels = $course->skill_labels;
                    $courseUrl = route('courses.public.show', $course->slug);
                    $hours = $course->duration_minutes ? round($course->duration_minutes / 60, 1) . ' ' . ($isEnglish ? 'h' : 'год') : $copy['preparing'];
                    $isEarlyAccess = $course->isEarlyAccessAvailable();
                    $isReleaseScheduled = $course->isReleaseScheduled();
                    $releaseIso = $isReleaseScheduled ? $course->accessOpensAt()->toIso8601String() : null;
                    $releaseDate = $isReleaseScheduled ? $course->accessOpensAt()->format('d.m.Y H:i') : null;
                    $activePrice = (float) $course->current_price;
                    $price = $activePrice > 0 ? number_format($activePrice, 0, '.', ' ') . ' ' . $course->currency : $copy['free'];
                    $fullPrice = (float) $course->price > 0 ? number_format((float) $course->price, 0, '.', ' ') . ' ' . $course->currency : null;
                    $modalText = $isReleaseScheduled ? str_replace([':course', ':date'], [$course->name, $releaseDate], $copy['modal_text']) : null;
                @endphp

                <a
                    href="{{ $courseUrl }}"
                    @class(['poligonium-course-card', 'is-release-locked' => $isReleaseScheduled])
                    data-course-card
                >
                    <span class="poligonium-course-card-glow" aria-hidden="true"></span>

                    @if ($isReleaseScheduled)
                        <span class="poligonium-course-release-badge" data-course-countdown data-release-at="{{ $releaseIso }}">
                            <small>{{ $copy['starts_in'] }}</small>
                            <strong data-countdown-days>--</strong>
                            <span class="poligonium-course-release-badge__days" data-countdown-days-label>{{ $copy['day_many'] }}</span>
                            <em>
                                <span data-countdown-hours>--</span> {{ $copy['hours'] }}
                                <span data-countdown-minutes>--</span> {{ $copy['minutes'] }}
                            </em>
                        </span>
                    @endif

                    <span class="poligonium-course-media">
                        @if ($course->image)
                            <img src="{{ RvMedia::getImageUrl($course->image) }}" alt="{{ $course->name }}">
                        @endif
                        <span class="poligonium-course-media-label">
                            {{ $isReleaseScheduled ? $copy['opens_at'] . ' ' . $releaseDate : ($isEarlyAccess ? $copy['early_access'] : ($course->difficulty_label ?: 'Course')) }}
                        </span>
                    </span>

                    <span class="poligonium-course-body">
                        <span class="poligonium-course-meta">
                            <span>{{ $course->category->name ?: 'Poligonium' }}</span>
                            @if ($course->difficulty)
                                <span>{{ $course->difficulty_label }}</span>
                            @endif
                            <span>{{ $hours }}</span>
                        </span>

                        @if ($softwareLabels || $skillLabels)
                            <span class="poligonium-course-tags">
                                @foreach (array_slice(array_merge($softwareLabels, $skillLabels), 0, 6) as $label)
                                    <span>{{ $label }}</span>
                                @endforeach
                            </span>
                        @endif

                        <span class="poligonium-course-title">{{ $course->name }}</span>
                        <span class="poligonium-course-description">{{ $course->description }}</span>

                        <span class="poligonium-course-bottom">
                            <strong>
                                {{ $price }}
                                @if ($isEarlyAccess && $fullPrice)
                                    <small>{{ $copy['then'] }} {{ $fullPrice }}</small>
                                @endif
                            </strong>
                            <span>{{ $isReleaseScheduled ? $copy['details'] : $copy['watch'] }}</span>
                        </span>
                    </span>
                </a>
            @empty
                <div class="poligonium-course-empty">
                    <h2>{{ $copy['empty_title'] }}</h2>
                    <p>{{ $copy['empty_text'] }}</p>
                </div>
            @endforelse
        </div>

        {{ $courses->links() }}
    </div>

    <div class="poligonium-course-release-modal" data-course-release-modal aria-hidden="true">
        <div class="poligonium-course-release-modal__backdrop" data-course-release-close></div>
        <section class="poligonium-course-release-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="course-release-modal-title">
            <button type="button" class="poligonium-course-release-modal__close" data-course-release-close aria-label="{{ $copy['modal_close'] }}">×</button>
            <span class="poligonium-course-release-modal__kicker">Polygonium School</span>
            <h2 id="course-release-modal-title">{{ $copy['modal_title'] }}</h2>
            <p data-course-release-message></p>
            <div class="poligonium-course-release-modal__timer" data-course-modal-countdown>
                <span><strong data-countdown-days>--</strong><span data-countdown-days-label>{{ $copy['day_many'] }}</span></span>
                <span><strong data-countdown-hours>--</strong>{{ $copy['hours'] }}</span>
                <span><strong data-countdown-minutes>--</strong>{{ $copy['minutes'] }}</span>
                <span><strong data-countdown-seconds>--</strong>{{ $copy['seconds'] }}</span>
            </div>
            <button type="button" class="poligonium-course-release-modal__button" data-course-release-close>{{ $copy['modal_close'] }}</button>
        </section>
    </div>
</section>

<script>
    (() => {
        const dayLabels = {
            one: @json($copy['day_one']),
            few: @json($copy['day_few']),
            many: @json($copy['day_many']),
        };

        const pluralizeDays = (days) => {
            const normalized = Math.abs(days) % 100;
            const lastDigit = normalized % 10;

            if (normalized > 10 && normalized < 20) {
                return dayLabels.many;
            }

            if (lastDigit === 1) {
                return dayLabels.one;
            }

            if (lastDigit >= 2 && lastDigit <= 4) {
                return dayLabels.few;
            }

            return dayLabels.many;
        };

        const formatCountdown = (targetDate) => {
            const diff = Math.max(0, targetDate.getTime() - Date.now());
            const totalSeconds = Math.floor(diff / 1000);
            const days = Math.floor(totalSeconds / 86400);
            const hours = Math.floor((totalSeconds % 86400) / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;

            return {
                days: String(days),
                daysLabel: pluralizeDays(days),
                hours: String(hours).padStart(2, '0'),
                minutes: String(minutes).padStart(2, '0'),
                seconds: String(seconds).padStart(2, '0'),
                finished: diff <= 0,
            };
        };

        const applyCountdown = (container, values) => {
            container.querySelectorAll('[data-countdown-days]').forEach((item) => item.textContent = values.days);
            container.querySelectorAll('[data-countdown-days-label]').forEach((item) => item.textContent = values.daysLabel);
            container.querySelectorAll('[data-countdown-hours]').forEach((item) => item.textContent = values.hours);
            container.querySelectorAll('[data-countdown-minutes]').forEach((item) => item.textContent = values.minutes);
            container.querySelectorAll('[data-countdown-seconds]').forEach((item) => item.textContent = values.seconds);
        };

        const countdowns = Array.from(document.querySelectorAll('[data-course-countdown]'))
            .map((element) => ({ element, target: new Date(element.dataset.releaseAt) }))
            .filter((item) => !Number.isNaN(item.target.getTime()));

        const updateCountdowns = () => {
            countdowns.forEach(({ element, target }) => {
                const values = formatCountdown(target);
                applyCountdown(element, values);

                if (values.finished) {
                    window.location.reload();
                }
            });
        };

        if (countdowns.length) {
            updateCountdowns();
            window.setInterval(updateCountdowns, 1000);
        }

        document.querySelectorAll('[data-course-card]').forEach((card) => {
            let lastSpark = 0;

            card.addEventListener('pointermove', (event) => {
                const now = Date.now();

                if (now - lastSpark < 70) {
                    return;
                }

                lastSpark = now;

                const rect = card.getBoundingClientRect();
                const spark = document.createElement('span');

                spark.className = 'poligonium-course-spark';
                spark.style.left = `${event.clientX - rect.left}px`;
                spark.style.top = `${event.clientY - rect.top}px`;
                spark.style.setProperty('--spark-x', `${(Math.random() - .5) * 54}px`);
                spark.style.setProperty('--spark-y', `${-32 - Math.random() * 54}px`);

                card.appendChild(spark);
                window.setTimeout(() => spark.remove(), 760);
            });
        });

        const modal = document.querySelector('[data-course-release-modal]');
        const modalMessage = modal?.querySelector('[data-course-release-message]');
        const modalTimer = modal?.querySelector('[data-course-modal-countdown]');
        let modalTarget = null;

        const closeModal = () => {
            modal?.setAttribute('aria-hidden', 'true');
            document.documentElement.classList.remove('has-course-release-modal');
            modalTarget = null;
        };

        document.querySelectorAll('[data-course-release-close]').forEach((button) => {
            button.addEventListener('click', closeModal);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        document.querySelectorAll('[data-course-locked]').forEach((card) => {
            card.addEventListener('click', (event) => {
                event.preventDefault();

                modalTarget = new Date(card.dataset.courseRelease);

                if (modalMessage) {
                    modalMessage.textContent = card.dataset.courseModalText || '';
                }

                if (modal && modalTimer && !Number.isNaN(modalTarget.getTime())) {
                    applyCountdown(modalTimer, formatCountdown(modalTarget));
                    modal.setAttribute('aria-hidden', 'false');
                    document.documentElement.classList.add('has-course-release-modal');
                }
            });
        });

        window.setInterval(() => {
            if (modalTarget && modalTimer) {
                applyCountdown(modalTimer, formatCountdown(modalTarget));
            }
        }, 1000);
    })();
</script>

@include('theme.zelio::views.courses.partials.styles')

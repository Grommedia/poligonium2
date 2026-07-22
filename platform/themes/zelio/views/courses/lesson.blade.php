@php
    $releaseAt = $course->accessOpensAt();
    $isReleaseScheduled = $course->isReleaseScheduled();
    $lessonScheduledAt = $lesson->access_mode === 'scheduled' && $lesson->opens_at && $lesson->opens_at->isFuture() ? $lesson->opens_at : null;
    $lockedOpensAt = $releaseAt ?: $lessonScheduledAt;
    $releaseIso = $releaseAt ? $releaseAt->toIso8601String() : null;
    $releaseDate = $releaseAt ? $releaseAt->format('d.m.Y H:i') : null;
    $lockedIso = $lockedOpensAt ? $lockedOpensAt->toIso8601String() : null;
    $lockedDate = $lockedOpensAt ? $lockedOpensAt->format('d.m.Y H:i') : null;
@endphp

<section class="poligonium-courses-page">
    <div class="poligonium-courses-wrap">
        <div class="poligonium-lesson-layout">
            <main class="poligonium-lesson-main">
                <a class="poligonium-back-link" href="{{ route('courses.public.show', $course->slug) }}">← {{ $course->name }}</a>
                <h1>{{ $lesson->name }}</h1>
                <p>{{ $lesson->description }}</p>

                <div class="poligonium-player">
                    @if ($canWatch)
                        @if ($lesson->video_embed)
                            {!! $lesson->video_embed !!}
                        @elseif ($lesson->video_path)
                            <video controls controlsList="nodownload noplaybackrate" preload="metadata">
                                <source src="{{ route('courses.public.lesson.video', [$course->slug, $lesson->id]) }}" type="video/mp4">
                            </video>
                        @else
                            <div class="poligonium-player-empty">Відео ще не додано.</div>
                        @endif
                    @else
                        <div class="poligonium-player-locked">
                            <strong>{{ $lockedOpensAt ? ($isReleaseScheduled ? 'Курс ще не відкрито' : 'Урок ще не відкрито') : 'Урок закрито' }}</strong>
                            @if ($lockedOpensAt)
                                <p>Матеріали стануть доступні {{ $lockedDate }}.</p>
                                <div class="poligonium-course-detail-countdown" data-course-countdown data-release-at="{{ $lockedIso }}">
                                    <span><strong data-countdown-days>--</strong>д</span>
                                    <span><strong data-countdown-hours>--</strong>г</span>
                                    <span><strong data-countdown-minutes>--</strong>хв</span>
                                    <span><strong data-countdown-seconds>--</strong>с</span>
                                </div>
                                @if ($releaseAt)
                                    <form method="POST" action="{{ route('courses.public.remind', $course->slug) }}">
                                        @csrf
                                        <button class="poligonium-course-buy-link is-muted" type="submit">Нагадати про відкриття</button>
                                    </form>
                                @endif
                            @else
                                <p>Цей матеріал відкривається після покупки курсу або ручної видачі доступу адміністратором.</p>
                            @endif
                        </div>
                    @endif
                </div>

                @if ($lesson->content)
                    <div class="poligonium-course-content ck-content">{!! BaseHelper::clean($lesson->content) !!}</div>
                @endif

                @if ($lesson->files->isNotEmpty())
                    <div class="poligonium-files">
                        <h2>Файли уроку</h2>
                        @foreach ($lesson->files as $file)
                            <div class="poligonium-file-row">
                                <span>{{ $file->name }}</span>
                                <strong>{{ ($hasAccess || ! $file->requires_access) ? 'Доступно' : 'Після доступу' }}</strong>
                            </div>
                        @endforeach
                    </div>
                @endif
            </main>

            <aside class="poligonium-lesson-sidebar">
                @foreach ($course->chapters as $chapter)
                    <h3>{{ $chapter->name }}</h3>
                    @foreach ($chapter->lessons as $item)
                        <a @class(['is-active' => $item->id === $lesson->id]) href="{{ route('courses.public.lesson', [$course->slug, $item->id]) }}">
                            {{ $item->name }}
                        </a>
                    @endforeach
                @endforeach
            </aside>
        </div>
    </div>
</section>

@if ($lockedOpensAt)
    <script>
        (() => {
            const target = new Date(@json($lockedIso));

            if (Number.isNaN(target.getTime())) {
                return;
            }

            const update = () => {
                const diff = Math.max(0, target.getTime() - Date.now());
                const totalSeconds = Math.floor(diff / 1000);
                const values = {
                    days: String(Math.floor(totalSeconds / 86400)),
                    hours: String(Math.floor((totalSeconds % 86400) / 3600)).padStart(2, '0'),
                    minutes: String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0'),
                    seconds: String(totalSeconds % 60).padStart(2, '0'),
                };

                document.querySelectorAll('[data-countdown-days]').forEach((item) => item.textContent = values.days);
                document.querySelectorAll('[data-countdown-hours]').forEach((item) => item.textContent = values.hours);
                document.querySelectorAll('[data-countdown-minutes]').forEach((item) => item.textContent = values.minutes);
                document.querySelectorAll('[data-countdown-seconds]').forEach((item) => item.textContent = values.seconds);
            };

            update();
            window.setInterval(update, 1000);
        })();
    </script>
@endif

@include('theme.zelio::views.courses.partials.styles')

@php
    $isEnglish = request()->segment(1) === 'en';
    $copy = $isEnglish
        ? [
            'kicker' => '3D Modeling Academy',
            'title' => 'Create more than you imagine',
            'lead' => 'Practical training in 3D modeling, characters, animation and VFX from real production practice.',
            'courses' => 'Courses',
            'showreel' => 'Watch showreel',
            'articles' => 'Articles',
            'projects' => 'Our projects',
            'all_courses' => 'View all courses',
            'benefit_practice' => 'Practice from the first lesson',
            'benefit_practice_text' => 'Learning is built around useful skills, not abstract theory.',
            'benefit_mentor' => 'Mentor support',
            'benefit_mentor_text' => 'Feedback and help at the key stages of work.',
            'benefit_flexible' => 'Flexible learning',
            'benefit_flexible_text' => 'Study at your own rhythm and return to materials.',
            'benefit_certificate' => 'Portfolio result',
            'benefit_certificate_text' => 'A finished work that can strengthen your portfolio.',
            'courses_title' => 'Our courses',
            'cta_title' => 'Need 3D graphics for your project?',
            'cta_text' => 'We create 3D models, animation, visualization and VFX for games, advertising, film and business.',
            'cta_button' => 'Order a service',
            'featured' => 'Academy articles',
            'featured_text' => 'Useful materials that grow the site around 3D modeling and lead people into courses.',
            'latest' => 'Fresh articles',
            'latest_text' => 'Useful materials for search, learning and a clear path into Poligonium courses.',
            'empty' => 'Academy articles will appear here after publication in admin.',
        ]
        : [
            'kicker' => 'Академія 3D-моделювання',
            'title' => 'Створи більше, ніж уявляєш',
            'lead' => 'Практичне навчання 3D-моделюванню, персонажам, анімації та VFX від практики індустрії.',
            'courses' => 'Курси',
            'showreel' => 'Дивитися шоу-рил',
            'articles' => 'Статті',
            'projects' => 'Наші проєкти',
            'all_courses' => 'Переглянути всі курси',
            'benefit_practice' => 'Практика з першого уроку',
            'benefit_practice_text' => 'Навчання орієнтоване на створення корисних навичок.',
            'benefit_mentor' => 'Підтримка ментора',
            'benefit_mentor_text' => 'Фідбек та допомога на кожному важливому етапі.',
            'benefit_flexible' => 'Гнучке навчання',
            'benefit_flexible_text' => 'Вчіться у своєму ритмі у будь-який час.',
            'benefit_certificate' => 'Результат у портфоліо',
            'benefit_certificate_text' => 'Наприкінці курсу ви маєте роботу, яку не соромно показати.',
            'courses_title' => 'Наші курси',
            'cta_title' => 'Потрібна 3D-графіка для вашого проєкту?',
            'cta_text' => 'Ми створюємо 3D-моделі, анімацію, візуалізації та VFX для ігор, реклами, кіно та бізнесу.',
            'cta_button' => 'Замовити послугу',
            'featured' => 'Статті Академії',
            'featured_text' => 'Корисні матеріали, які розвивають сайт навколо 3D-моделювання і ведуть людей до курсів.',
            'latest' => 'Нові матеріали',
            'latest_text' => 'Корисні статті для пошуку, навчання і зрозумілого переходу до курсів Polygonium.',
            'empty' => 'Статті Академії зʼявляться тут після публікації в адмін-панелі.',
        ];
    $coursesUrl = \Illuminate\Support\Facades\Route::has('courses.public.index') ? route('courses.public.index') : url(($isEnglish ? '/en' : '') . '/courses');
    $projectsUrl = \Illuminate\Support\Facades\Route::has('campaigns.public.index') ? route('campaigns.public.index') : url(($isEnglish ? '/en' : '') . '/support');
    $showreelUrl = url(($isEnglish ? '/en' : '') . '/vfx-showreel');
    $servicesUrl = ($isEnglish ? url('/en') : BaseHelper::getHomepageUrl()) . '#services';
    $academyAsset = fn (string $file) => Theme::asset()->url('images/academy/' . $file);
    $fallbackCourses = [
        [
            'name' => $isEnglish ? '3D Modeling Basics' : 'Основи 3D-моделювання',
            'description' => $isEnglish ? 'Blender, Houdini and a professional approach to 3D from zero.' : 'Blender, Houdini та професійний підхід до 3D з нуля.',
            'image' => $academyAsset('course-modeling.svg'),
            'lessons' => '12',
            'level' => $isEnglish ? 'Beginner' : 'Початковий',
            'url' => $coursesUrl,
        ],
        [
            'name' => $isEnglish ? '3D Characters' : '3D-персонажі',
            'description' => $isEnglish ? 'Sculpting, topology, rigging, texturing and animation.' : 'Скульптинг, топологія, риг, текстурування та анімація.',
            'image' => $academyAsset('course-character.svg'),
            'lessons' => '11',
            'level' => $isEnglish ? 'Middle' : 'Середній',
            'url' => $coursesUrl,
        ],
        [
            'name' => $isEnglish ? 'VFX and Effects' : 'VFX та ефекти',
            'description' => $isEnglish ? 'Simulations, particles, smoke, destruction and volume.' : 'Симуляції, частинки, дим, руйнування та обʼєм.',
            'image' => $academyAsset('course-vfx.svg'),
            'lessons' => '16',
            'level' => $isEnglish ? 'Middle' : 'Середній',
            'url' => $showreelUrl,
        ],
        [
            'name' => $isEnglish ? '3D Interiors and Visualization' : '3D-інтерʼєри та візуалізація',
            'description' => $isEnglish ? 'Modeling, materials, lighting and photoreal render.' : 'Моделювання, матеріали, світло та фотореалістичний рендер.',
            'image' => $academyAsset('course-interior.svg'),
            'lessons' => '14',
            'level' => $isEnglish ? 'Advanced' : 'Просунутий',
            'url' => $coursesUrl,
        ],
    ];
@endphp

<section class="poligonium-academy-page">
    <div class="poligonium-academy-wrap">
        <div class="poligonium-academy-hero">
            <div class="poligonium-academy-hero__copy">
                <p class="poligonium-academy-kicker">{{ $copy['kicker'] }}</p>
                <h1>{{ $copy['title'] }}</h1>
                <p>{{ $copy['lead'] }}</p>
                <div class="poligonium-academy-actions">
                    <a class="poligonium-academy-button is-primary" href="{{ $coursesUrl }}">{{ $copy['courses'] }}</a>
                    <a class="poligonium-academy-button" href="{{ $showreelUrl }}"><i class="ti ti-player-play"></i>{{ $copy['showreel'] }}</a>
                </div>
            </div>

            <div class="poligonium-academy-hero__visual">
                <img src="{{ $academyAsset('academy-hero.svg') }}" alt="{{ $copy['title'] }}">
            </div>
        </div>

        <div class="poligonium-academy-benefits">
            <article>
                <i class="ti ti-cube"></i>
                <strong>{{ $copy['benefit_practice'] }}</strong>
                <span>{{ $copy['benefit_practice_text'] }}</span>
            </article>
            <article>
                <i class="ti ti-users"></i>
                <strong>{{ $copy['benefit_mentor'] }}</strong>
                <span>{{ $copy['benefit_mentor_text'] }}</span>
            </article>
            <article>
                <i class="ti ti-rocket"></i>
                <strong>{{ $copy['benefit_flexible'] }}</strong>
                <span>{{ $copy['benefit_flexible_text'] }}</span>
            </article>
            <article>
                <i class="ti ti-briefcase"></i>
                <strong>{{ $copy['benefit_certificate'] }}</strong>
                <span>{{ $copy['benefit_certificate_text'] }}</span>
            </article>
        </div>

        <section class="poligonium-academy-section is-courses">
            <div class="poligonium-academy-section__head">
                <div>
                    <h2>{{ $copy['courses_title'] }}</h2>
                </div>
                <a class="poligonium-academy-link" href="{{ $coursesUrl }}">{{ $copy['all_courses'] }} <i class="ti ti-arrow-right"></i></a>
            </div>

            <div class="poligonium-academy-course-grid">
                @if ($courses->isNotEmpty())
                    @foreach ($courses as $course)
                        @php
                            $courseUrl = route('courses.public.show', $course->slug);
                            $courseImage = $course->image ? RvMedia::getImageUrl($course->image) : $fallbackCourses[$loop->index % count($fallbackCourses)]['image'];
                            $lessonCount = $course->lesson_count ?: $course->lessons()->count();
                        @endphp
                        <a class="poligonium-academy-course-card" href="{{ $courseUrl }}">
                            <span class="poligonium-academy-course-card__image">
                                <img loading="lazy" src="{{ $courseImage }}" alt="{{ $course->name }}">
                            </span>
                            <span class="poligonium-academy-course-card__body">
                                <strong>{{ $course->name }}</strong>
                                <span>{{ $course->description }}</span>
                                <em>
                                    <span>{{ $lessonCount ?: '—' }} {{ $isEnglish ? 'lessons' : 'уроків' }}</span>
                                    @if ($course->difficulty)
                                        <small>{{ $course->difficulty_label }}</small>
                                    @endif
                                </em>
                            </span>
                        </a>
                    @endforeach
                @else
                    @foreach ($fallbackCourses as $course)
                        <a class="poligonium-academy-course-card" href="{{ $course['url'] }}">
                            <span class="poligonium-academy-course-card__image">
                                <img loading="lazy" src="{{ $course['image'] }}" alt="{{ $course['name'] }}">
                            </span>
                            <span class="poligonium-academy-course-card__body">
                                <strong>{{ $course['name'] }}</strong>
                                <span>{{ $course['description'] }}</span>
                                <em><span>{{ $course['lessons'] }} {{ $isEnglish ? 'lessons' : 'уроків' }}</span><small>{{ $course['level'] }}</small></em>
                            </span>
                        </a>
                    @endforeach
                @endif
            </div>
        </section>

        <section class="poligonium-academy-service-strip">
            <div>
                <h2>{{ $copy['cta_title'] }}</h2>
                <p>{{ $copy['cta_text'] }}</p>
                <a class="poligonium-academy-button is-primary" href="{{ $servicesUrl }}">{{ $copy['cta_button'] }}</a>
            </div>
            <img src="{{ $academyAsset('academy-cta.svg') }}" alt="">
        </section>

        <div class="poligonium-academy-proof">
            <span><i class="ti ti-school"></i><strong>Polygonium School</strong><em>курси та інтенсиви</em></span>
            <span><i class="ti ti-users"></i><strong>менторство</strong><em>підтримка під час навчання</em></span>
            <span><i class="ti ti-star"></i><strong>production-підхід</strong><em>досвід 3D та VFX</em></span>
            <span><i class="ti ti-rocket"></i><strong>портфоліо</strong><em>роботи після практики</em></span>
        </div>

        <section class="poligonium-academy-section">
            <div class="poligonium-academy-section__head">
                <div>
                    <h2>{{ $copy['featured'] }}</h2>
                    <p>{{ $copy['featured_text'] }}</p>
                </div>
                <a class="poligonium-academy-button" href="{{ route('academy.public.articles') }}">{{ $copy['articles'] }}</a>
            </div>
            <div class="poligonium-academy-grid">
                @forelse ($featuredArticles as $article)
                    @include('theme.zelio::views.academy.partials.article-card', ['article' => $article])
                @empty
                    <div class="poligonium-academy-empty">{{ $copy['empty'] }}</div>
                @endforelse
            </div>
        </section>

        @if ($latestArticles->isNotEmpty())
            <section class="poligonium-academy-section">
                <div class="poligonium-academy-section__head">
                    <div>
                        <h2>{{ $copy['latest'] }}</h2>
                        <p>{{ $copy['latest_text'] }}</p>
                    </div>
                </div>
                <div class="poligonium-academy-grid">
                    @foreach ($latestArticles as $article)
                        @include('theme.zelio::views.academy.partials.article-card', ['article' => $article])
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</section>

@include('theme.zelio::views.academy.partials.styles')

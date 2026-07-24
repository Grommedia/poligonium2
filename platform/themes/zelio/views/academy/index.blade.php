@php
    $isEnglish = request()->segment(1) === 'en';
    $academyAsset = fn (string $file) => Theme::asset()->url('images/academy/' . $file);
    $coursesUrl = \Illuminate\Support\Facades\Route::has('courses.public.index') ? route('courses.public.index') : url(($isEnglish ? '/en' : '') . '/courses');
    $articlesUrl = \Illuminate\Support\Facades\Route::has('academy.public.articles') ? route('academy.public.articles') : url(($isEnglish ? '/en' : '') . '/academy/articles');
    $showreelUrl = url(($isEnglish ? '/en' : '') . '/vfx-showreel');
    $servicesUrl = ($isEnglish ? url('/en') : BaseHelper::getHomepageUrl()) . '#services';

    $copy = $isEnglish
        ? [
            'kicker' => '3D Modeling Academy',
            'title' => 'Create what you imagine',
            'lead' => 'Practical training in 3D modeling, characters, animation and VFX from real industry practice.',
            'courses' => 'Courses',
            'showreel' => 'Watch showreel',
            'all_courses' => 'View all courses',
            'courses_title' => 'Our courses',
            'cta_title' => 'Need 3D graphics for your project?',
            'cta_text' => 'We create 3D models, animation, visualization and VFX for games, advertising, film and business.',
            'cta_button' => 'Order a service',
            'articles_title' => 'Academy Articles',
            'articles_text' => 'Useful materials that grow the site around 3D modeling and lead people into courses.',
            'articles_button' => 'Articles',
            'lessons' => 'lessons',
            'read' => 'Read',
            'minutes' => 'min read',
        ]
        : [
            'kicker' => 'Академія 3D-моделювання',
            'title' => 'Створюй те, що представляєш',
            'lead' => 'Практичне навчання 3D-моделювання, персонажів, анімації та VFX від практики індустрії.',
            'courses' => 'Курси',
            'showreel' => 'Дивитися шоу-ріл',
            'all_courses' => 'Переглянути всі курси',
            'courses_title' => 'Наші курси',
            'cta_title' => 'Потрібна 3D-графіка для вашого проєкту?',
            'cta_text' => 'Ми створюємо 3D-моделі, анімацію, візуалізації та VFX для ігор, реклами, кіно та бізнесу.',
            'cta_button' => 'Замовити послугу',
            'articles_title' => 'Статті Академії',
            'articles_text' => 'Корисні матеріали, які розвивають сайт навколо 3D-моделювання і ведуть людей до курсів.',
            'articles_button' => 'Статті',
            'lessons' => 'уроків',
            'read' => 'Читати',
            'minutes' => 'хв читання',
        ];

    $icons = [
        'rocket' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.63 8.41m5.96 5.96a14.93 14.93 0 0 1-5.96 5.96m0-11.92a6 6 0 0 0-7.38 5.84h4.8m2.58-5.84a14.93 14.93 0 0 0-5.96 5.96m6.06 6.06L4.5 19.5m1.5-6h4.5v4.5" /></svg>',
        'users' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 18.72a9.09 9.09 0 0 0 3.74-.72 3 3 0 0 0-3.74-3m0 3.72v-.72a3 3 0 0 0-3-3H9a3 3 0 0 0-3 3v.72m12 0a9 9 0 0 1-12 0M15 7.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm-12 0a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm6 0a3 3 0 1 1 6 0 3 3 0 0 1-6 0Z" /></svg>',
        'clock' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 6v6h4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>',
        'trophy' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16.5 18.75h-9m9 0a3 3 0 0 0-3-3h-3a3 3 0 0 0-3 3m9 0h1.5a2.25 2.25 0 0 0 2.25-2.25V5.25H3.75V16.5A2.25 2.25 0 0 0 6 18.75h1.5m9-13.5h3.75v3.375a3.375 3.375 0 0 1-3.375 3.375H16.5m-9-6.75H3.75v3.375A3.375 3.375 0 0 0 7.125 12H7.5" /></svg>',
        'play' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /><path d="M10.5 8.25 15.75 12l-5.25 3.75v-7.5Z" /></svg>',
        'arrow' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>',
        'book' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 6.04A7.5 7.5 0 0 0 4.5 4.5v13.5A7.5 7.5 0 0 1 12 19.54m0-13.5A7.5 7.5 0 0 1 19.5 4.5v13.5A7.5 7.5 0 0 0 12 19.54m0-13.5v13.5" /></svg>',
        'briefcase' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.25 14.15v4.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25v-4.25m16.5 0A2.25 2.25 0 0 0 22.5 11.9V7.5A2.25 2.25 0 0 0 20.25 5.25H3.75A2.25 2.25 0 0 0 1.5 7.5v4.4a2.25 2.25 0 0 0 2.25 2.25m16.5 0H3.75m5.25-8.9V3.75A1.5 1.5 0 0 1 10.5 2.25h3A1.5 1.5 0 0 1 15 3.75v1.5" /></svg>',
    ];

    $benefits = $isEnglish
        ? [
            ['icon' => 'rocket', 'title' => 'Practice from the first lesson', 'text' => 'Learning is focused on building useful skills.'],
            ['icon' => 'users', 'title' => 'Mentor support', 'text' => 'Feedback and help at every stage of learning.'],
            ['icon' => 'clock', 'title' => 'Flexible learning', 'text' => 'Study at your own pace whenever it is convenient.'],
            ['icon' => 'trophy', 'title' => 'Portfolio result', 'text' => 'By the end of the course you have work worth showing.'],
        ]
        : [
            ['icon' => 'rocket', 'title' => 'Практика з першого уроку', 'text' => 'Навчання орієнтоване на створення корисних навичок.'],
            ['icon' => 'users', 'title' => 'Підтримка ментора', 'text' => 'Фідбек та допомога на кожному етапі навчання.'],
            ['icon' => 'clock', 'title' => 'Гнучке навчання', 'text' => 'Вчіться у своєму ритмі в будь-який зручний час.'],
            ['icon' => 'trophy', 'title' => 'Результат у портфоліо', 'text' => 'Наприкінці курсу ви маєте роботу, яку не соромно показати.'],
        ];

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
            'lessons' => '18',
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

    $courseCards = [];
    foreach ($courses->take(4) as $course) {
        $lessonCount = $course->lesson_count ?: $course->lessons()->count();
        $courseCards[] = [
            'name' => $course->name,
            'description' => $course->description,
            'image' => $course->image ? RvMedia::getImageUrl($course->image) : $fallbackCourses[count($courseCards) % count($fallbackCourses)]['image'],
            'lessons' => $lessonCount ?: $fallbackCourses[count($courseCards) % count($fallbackCourses)]['lessons'],
            'level' => $course->difficulty ? $course->difficulty_label : $fallbackCourses[count($courseCards) % count($fallbackCourses)]['level'],
            'url' => route('courses.public.show', $course->slug),
        ];
    }

    while (count($courseCards) < 4) {
        $courseCards[] = $fallbackCourses[count($courseCards)];
    }

    $fallbackArticles = $isEnglish
        ? [
            ['title' => 'How to Start Learning 3D Modeling', 'description' => 'A practical route for beginners who want to move from first forms to finished work.', 'image' => $academyAsset('course-modeling.svg'), 'minutes' => 8, 'url' => $articlesUrl],
            ['title' => 'What Makes a 3D Character Production Ready', 'description' => 'Topology, textures, rigging and small decisions that make a character usable.', 'image' => $academyAsset('course-character.svg'), 'minutes' => 10, 'url' => $articlesUrl],
            ['title' => 'Blender, Houdini or ZBrush', 'description' => 'How to choose software for modeling, animation, effects and production tasks.', 'image' => $academyAsset('course-vfx.svg'), 'minutes' => 7, 'url' => $articlesUrl],
            ['title' => 'Portfolio Work That Clients Understand', 'description' => 'How to present 3D projects so a client sees the value and the process.', 'image' => $academyAsset('course-interior.svg'), 'minutes' => 6, 'url' => $articlesUrl],
        ]
        : [
            ['title' => 'Як почати вивчати 3D-моделювання', 'description' => 'Практичний маршрут для початківців: від простих форм до завершеної роботи.', 'image' => $academyAsset('course-modeling.svg'), 'minutes' => 8, 'url' => $articlesUrl],
            ['title' => 'Що робить 3D-персонажа готовим до продакшну', 'description' => 'Топологія, текстури, ригінг і деталі, які роблять персонажа придатним до роботи.', 'image' => $academyAsset('course-character.svg'), 'minutes' => 10, 'url' => $articlesUrl],
            ['title' => 'Blender, Houdini чи ZBrush', 'description' => 'Як обрати програму під моделювання, анімацію, ефекти та виробничі задачі.', 'image' => $academyAsset('course-vfx.svg'), 'minutes' => 7, 'url' => $articlesUrl],
            ['title' => 'Портфоліо, яке зрозуміє клієнт', 'description' => 'Як показувати 3D-проєкти, щоб клієнт бачив цінність, етапи та результат.', 'image' => $academyAsset('course-interior.svg'), 'minutes' => 6, 'url' => $articlesUrl],
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
                    <a class="poligonium-academy-button is-primary" href="{{ $coursesUrl }}">
                        <span>{{ $copy['courses'] }}</span>
                    </a>
                    <a class="poligonium-academy-button" href="{{ $showreelUrl }}">
                        <span class="poligonium-academy-button__icon">{!! $icons['play'] !!}</span>
                        <span>{{ $copy['showreel'] }}</span>
                    </a>
                </div>
            </div>

            <div class="poligonium-academy-hero__visual">
                <video autoplay muted loop playsinline preload="metadata" poster="{{ $academyAsset('academy-hero.svg') }}" aria-label="{{ $copy['title'] }}">
                    <source src="{{ $academyAsset('academy-hero-animation.webm') }}" type="video/webm">
                </video>
            </div>
        </div>

        <div class="poligonium-academy-benefits" aria-label="{{ $isEnglish ? 'Academy benefits' : 'Переваги академії' }}">
            @foreach ($benefits as $benefit)
                <article>
                    <span class="poligonium-academy-icon">{!! $icons[$benefit['icon']] !!}</span>
                    <strong>{{ $benefit['title'] }}</strong>
                    <span>{{ $benefit['text'] }}</span>
                </article>
            @endforeach
        </div>

        <section class="poligonium-academy-section is-courses">
            <div class="poligonium-academy-section__head">
                <h2>{{ $copy['courses_title'] }}</h2>
                <a class="poligonium-academy-link" href="{{ $coursesUrl }}">
                    <span>{{ $copy['all_courses'] }}</span>
                    {!! $icons['arrow'] !!}
                </a>
            </div>

            <div class="poligonium-academy-course-grid">
                @foreach ($courseCards as $course)
                    <a class="poligonium-academy-course-card" href="{{ $course['url'] }}">
                        <span class="poligonium-academy-course-card__image">
                            <img loading="lazy" src="{{ $course['image'] }}" alt="{{ $course['name'] }}">
                        </span>
                        <span class="poligonium-academy-course-card__body">
                            <strong>{{ $course['name'] }}</strong>
                            <span>{{ \Illuminate\Support\Str::limit(strip_tags((string) $course['description']), 96) }}</span>
                            <em>
                                <span>{{ $course['lessons'] }} {{ $copy['lessons'] }}</span>
                                <small>{{ $course['level'] }}</small>
                            </em>
                        </span>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="poligonium-academy-service-strip">
            <div class="poligonium-academy-service-strip__copy">
                <span class="poligonium-academy-icon">{!! $icons['briefcase'] !!}</span>
                <h2>{{ $copy['cta_title'] }}</h2>
                <p>{{ $copy['cta_text'] }}</p>
                <a class="poligonium-academy-button is-primary" href="{{ $servicesUrl }}">
                    <span>{{ $copy['cta_button'] }}</span>
                </a>
            </div>
            <img loading="lazy" src="{{ $academyAsset('academy-cta.svg') }}" alt="">
        </section>

        <section class="poligonium-academy-section is-articles">
            <div class="poligonium-academy-section__head">
                <div>
                    <h2>{{ $copy['articles_title'] }}</h2>
                    <p>{{ $copy['articles_text'] }}</p>
                </div>
                <a class="poligonium-academy-link" href="{{ $articlesUrl }}">
                    <span>{{ $copy['articles_button'] }}</span>
                    {!! $icons['arrow'] !!}
                </a>
            </div>

            <div class="poligonium-academy-grid">
                @php
                    $visibleArticles = $featuredArticles->take(4);
                    $visibleArticleCount = $visibleArticles->count();
                @endphp

                @foreach ($visibleArticles as $article)
                    @include('theme.zelio::views.academy.partials.article-card', ['article' => $article, 'copy' => $copy, 'icons' => $icons])
                @endforeach

                @for ($articleIndex = $visibleArticleCount; $articleIndex < 4; $articleIndex++)
                    @php($article = $fallbackArticles[$articleIndex])
                        <a class="poligonium-academy-card" href="{{ $article['url'] }}">
                            <span class="poligonium-academy-card__media">
                                <img loading="lazy" src="{{ $article['image'] }}" alt="{{ $article['title'] }}">
                            </span>
                            <span class="poligonium-academy-card__body">
                                <h3>{{ $article['title'] }}</h3>
                                <p>{{ $article['description'] }}</p>
                                <span class="poligonium-academy-card__read">
                                    <span>{{ $article['minutes'] }} {{ $copy['minutes'] }}</span>
                                    <span>{{ $copy['read'] }} {!! $icons['arrow'] !!}</span>
                                </span>
                            </span>
                        </a>
                @endfor
            </div>
        </section>
    </div>
</section>

@include('theme.zelio::views.academy.partials.styles')

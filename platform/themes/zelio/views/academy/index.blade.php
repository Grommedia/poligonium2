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
            'title' => 'Створюй те, що уявляєш',
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
        'rocket' => $academyAsset('icons/rocket-launch.svg'),
        'users' => $academyAsset('icons/users.svg'),
        'clock' => $academyAsset('icons/clock.svg'),
        'trophy' => $academyAsset('icons/trophy.svg'),
        'play' => $academyAsset('icons/play-circle.svg'),
        'arrow' => $academyAsset('icons/arrow-right.svg'),
        'book' => $academyAsset('icons/book-open.svg'),
        'briefcase' => $academyAsset('icons/briefcase.svg'),
    ];

    $visuals = [
        'modeling' => $academyAsset('visual/browser-window-3d-pixel-icon.svg'),
        'character' => $academyAsset('visual/user-mention-3d-pixel-icon.svg'),
        'vfx' => $academyAsset('visual/webhook-3d-pixel-icon.svg'),
        'interior' => $academyAsset('visual/desktop-3d-pixel-icon.svg'),
        'cta' => $academyAsset('visual/trend-up-3d-pixel-icon.svg'),
        'article_image' => $academyAsset('visual/image-file-3d-pixel-icon.svg'),
        'article_document' => $academyAsset('visual/document-file-3d-pixel-icon.svg'),
        'article_certificate' => $academyAsset('visual/certificate-3d-pixel-icon.svg'),
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
            'image' => $visuals['modeling'],
            'lessons' => '12',
            'level' => $isEnglish ? 'Beginner' : 'Початковий',
            'url' => $coursesUrl,
        ],
        [
            'name' => $isEnglish ? '3D Characters' : '3D-персонажі',
            'description' => $isEnglish ? 'Sculpting, topology, rigging, texturing and animation.' : 'Скульптинг, топологія, риг, текстурування та анімація.',
            'image' => $visuals['character'],
            'lessons' => '18',
            'level' => $isEnglish ? 'Middle' : 'Середній',
            'url' => $coursesUrl,
        ],
        [
            'name' => $isEnglish ? 'VFX and Effects' : 'VFX та ефекти',
            'description' => $isEnglish ? 'Simulations, particles, smoke, destruction and volume.' : 'Симуляції, частинки, дим, руйнування та обʼєм.',
            'image' => $visuals['vfx'],
            'lessons' => '16',
            'level' => $isEnglish ? 'Middle' : 'Середній',
            'url' => $showreelUrl,
        ],
        [
            'name' => $isEnglish ? '3D Interiors and Visualization' : '3D-інтерʼєри та візуалізація',
            'description' => $isEnglish ? 'Modeling, materials, lighting and photoreal render.' : 'Моделювання, матеріали, світло та фотореалістичний рендер.',
            'image' => $visuals['interior'],
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
            ['title' => 'How to Start Learning 3D Modeling', 'description' => 'A practical route for beginners who want to move from first forms to finished work.', 'image' => $visuals['article_image'], 'minutes' => 8, 'url' => $articlesUrl],
            ['title' => 'What Makes a 3D Character Production Ready', 'description' => 'Topology, textures, rigging and small decisions that make a character usable.', 'image' => $visuals['character'], 'minutes' => 10, 'url' => $articlesUrl],
            ['title' => 'Blender, Houdini or ZBrush', 'description' => 'How to choose software for modeling, animation, effects and production tasks.', 'image' => $visuals['vfx'], 'minutes' => 7, 'url' => $articlesUrl],
            ['title' => 'Portfolio Work That Clients Understand', 'description' => 'How to present 3D projects so a client sees the value and the process.', 'image' => $visuals['article_document'], 'minutes' => 6, 'url' => $articlesUrl],
        ]
        : [
            ['title' => 'Як почати вивчати 3D-моделювання', 'description' => 'Практичний маршрут для початківців: від простих форм до завершеної роботи.', 'image' => $visuals['article_image'], 'minutes' => 8, 'url' => $articlesUrl],
            ['title' => 'Що робить 3D-персонажа готовим до продакшну', 'description' => 'Топологія, текстури, ригінг і деталі, які роблять персонажа придатним до роботи.', 'image' => $visuals['character'], 'minutes' => 10, 'url' => $articlesUrl],
            ['title' => 'Blender, Houdini чи ZBrush', 'description' => 'Як обрати програму під моделювання, анімацію, ефекти та виробничі задачі.', 'image' => $visuals['vfx'], 'minutes' => 7, 'url' => $articlesUrl],
            ['title' => 'Портфоліо, яке зрозуміє клієнт', 'description' => 'Як показувати 3D-проєкти, щоб клієнт бачив цінність, етапи та результат.', 'image' => $visuals['article_document'], 'minutes' => 6, 'url' => $articlesUrl],
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
                        <span class="poligonium-academy-button__icon" style="--academy-icon: url('{{ $icons['play'] }}')" aria-hidden="true"></span>
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
                    <span class="poligonium-academy-icon" style="--academy-icon: url('{{ $icons[$benefit['icon']] }}')" aria-hidden="true"></span>
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
                    <span class="poligonium-academy-arrow-icon" style="--academy-icon: url('{{ $icons['arrow'] }}')" aria-hidden="true"></span>
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
                <span class="poligonium-academy-icon" style="--academy-icon: url('{{ $icons['briefcase'] }}')" aria-hidden="true"></span>
                <h2>{{ $copy['cta_title'] }}</h2>
                <p>{{ $copy['cta_text'] }}</p>
                <a class="poligonium-academy-button is-primary" href="{{ $servicesUrl }}">
                    <span>{{ $copy['cta_button'] }}</span>
                </a>
            </div>
            <img loading="lazy" src="{{ $visuals['cta'] }}" alt="">
        </section>

        <section class="poligonium-academy-section is-articles">
            <div class="poligonium-academy-section__head">
                <div>
                    <h2>{{ $copy['articles_title'] }}</h2>
                    <p>{{ $copy['articles_text'] }}</p>
                </div>
                <a class="poligonium-academy-link" href="{{ $articlesUrl }}">
                    <span>{{ $copy['articles_button'] }}</span>
                    <span class="poligonium-academy-arrow-icon" style="--academy-icon: url('{{ $icons['arrow'] }}')" aria-hidden="true"></span>
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
                                    <span>{{ $copy['read'] }} <span class="poligonium-academy-arrow-icon" style="--academy-icon: url('{{ $icons['arrow'] }}')" aria-hidden="true"></span></span>
                                </span>
                            </span>
                        </a>
                @endfor
            </div>
        </section>
    </div>
</section>

@include('theme.zelio::views.academy.partials.styles')

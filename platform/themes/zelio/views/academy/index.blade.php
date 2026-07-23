@php
    $isEnglish = request()->segment(1) === 'en';
    $copy = $isEnglish
        ? [
            'kicker' => 'Polygonium knowledge hub',
            'title' => '3D Modeling Academy Polygonium',
            'lead' => 'A practical knowledge base for people who want to understand 3D modeling, characters, animation, VFX and the real production path from idea to finished work.',
            'courses' => 'Courses',
            'articles' => 'Articles',
            'projects' => 'Our projects',
            'routes_title' => 'Study route',
            'routes_note' => 'Courses, articles and author projects are connected into one system.',
            'featured' => 'Start here',
            'featured_text' => 'Core materials that explain how 3D production works and where to move next.',
            'latest' => 'Fresh articles',
            'latest_text' => 'Useful materials for search, learning and a clear path into Poligonium courses.',
            'empty' => 'Academy articles will appear here after publication in admin.',
        ]
        : [
            'kicker' => 'База знань Polygonium',
            'title' => 'Академія 3D-моделювання Polygonium',
            'lead' => 'Практичний розділ для тих, хто хоче розібратися у 3D-моделюванні, персонажах, анімації, VFX і реальному виробничому шляху від ідеї до готової роботи.',
            'courses' => 'Курси',
            'articles' => 'Статті',
            'projects' => 'Наші проєкти',
            'routes_title' => 'Маршрут навчання',
            'routes_note' => 'Курси, статті та авторські проєкти зібрані в одну систему.',
            'featured' => 'З чого почати',
            'featured_text' => 'Базові матеріали, які пояснюють, як працює 3D-виробництво і куди рухатися далі.',
            'latest' => 'Нові матеріали',
            'latest_text' => 'Корисні статті для пошуку, навчання і зрозумілого переходу до курсів Polygonium.',
            'empty' => 'Статті Академії зʼявляться тут після публікації в адмін-панелі.',
        ];
    $coursesUrl = \Illuminate\Support\Facades\Route::has('courses.public.index') ? route('courses.public.index') : url(($isEnglish ? '/en' : '') . '/courses');
    $projectsUrl = \Illuminate\Support\Facades\Route::has('campaigns.public.index') ? route('campaigns.public.index') : url(($isEnglish ? '/en' : '') . '/support');
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
                    <a class="poligonium-academy-button" href="{{ route('academy.public.articles') }}">{{ $copy['articles'] }}</a>
                    <a class="poligonium-academy-button" href="{{ $projectsUrl }}">{{ $copy['projects'] }}</a>
                </div>
            </div>

            <aside class="poligonium-academy-panel">
                <div class="poligonium-academy-panel__title">
                    <div>
                        <span>Polygonium</span>
                        <strong>{{ $copy['routes_title'] }}</strong>
                    </div>
                    <span>3D</span>
                </div>
                <div class="poligonium-academy-routes">
                    <a class="poligonium-academy-route" href="{{ $coursesUrl }}">
                        <i class="ti ti-school"></i>
                        <span><strong>{{ $copy['courses'] }}</strong><span>Blender, Houdini, персонажі, ригінг, анімація.</span></span>
                    </a>
                    <a class="poligonium-academy-route" href="{{ route('academy.public.articles') }}">
                        <i class="ti ti-article"></i>
                        <span><strong>{{ $copy['articles'] }}</strong><span>{{ $copy['routes_note'] }}</span></span>
                    </a>
                    <a class="poligonium-academy-route" href="{{ $projectsUrl }}">
                        <i class="ti ti-rocket"></i>
                        <span><strong>{{ $copy['projects'] }}</strong><span>Мультфільми, світи та авторські ідеї Polygonium.</span></span>
                    </a>
                </div>
            </aside>
        </div>

        @if ($categories->isNotEmpty())
            <div class="poligonium-academy-categories">
                @foreach ($categories as $category)
                    <a class="poligonium-academy-category" href="{{ route('academy.public.category', $category->slug) }}">
                        <i class="{{ $category->icon ?: 'ti ti-folder' }}" style="background: {{ $category->color ?: '#111827' }}"></i>
                        <span>
                            <strong>{{ $category->name }}</strong>
                            <span>{{ $category->description }} · {{ $category->articles_count }} матеріалів</span>
                        </span>
                    </a>
                @endforeach
            </div>
        @endif

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

@php
    $isEnglish = request()->segment(1) === 'en';
    $title = isset($category)
        ? $category->name
        : ($isEnglish ? 'Academy Articles' : 'Статті Академії');
    $lead = isset($category)
        ? $category->description
        : ($isEnglish
            ? 'Useful materials about 3D modeling, characters, VFX, animation and the Polygonium production workflow.'
            : 'Корисні матеріали про 3D-моделювання, персонажів, VFX, анімацію та виробничий пайплайн Polygonium.');
    $coursesUrl = \Illuminate\Support\Facades\Route::has('courses.public.index') ? route('courses.public.index') : url(($isEnglish ? '/en' : '') . '/courses');
@endphp

<section class="poligonium-academy-page">
    <div class="poligonium-academy-wrap">
        <div class="poligonium-academy-hero">
            <div class="poligonium-academy-hero__copy">
                <p class="poligonium-academy-kicker">Polygonium Academy</p>
                <h1>{{ $title }}</h1>
                <p>{{ $lead }}</p>
                <div class="poligonium-academy-actions">
                    <a class="poligonium-academy-button" href="{{ route('academy.public.index') }}">
                        {{ $isEnglish ? 'Academy main page' : 'Головна Академії' }}
                    </a>
                    <a class="poligonium-academy-button is-primary" href="{{ $coursesUrl }}">
                        {{ $isEnglish ? 'Courses' : 'Курси' }}
                    </a>
                </div>
            </div>

            <aside class="poligonium-academy-panel">
                <div class="poligonium-academy-panel__title">
                    <div>
                        <span>Library</span>
                        <strong>{{ $isEnglish ? 'Categories' : 'Категорії' }}</strong>
                    </div>
                </div>
                <div class="poligonium-academy-routes">
                    @foreach ($categories as $item)
                        <a class="poligonium-academy-route" href="{{ route('academy.public.category', $item->slug) }}">
                            <i class="{{ $item->icon ?: 'ti ti-folder' }}" style="background: {{ $item->color ?: '#111827' }}"></i>
                            <span><strong>{{ $item->name }}</strong><span>{{ $item->description }}</span></span>
                        </a>
                    @endforeach
                </div>
            </aside>
        </div>

        <div class="poligonium-academy-grid">
            @forelse ($articles as $article)
                @include('theme.zelio::views.academy.partials.article-card', ['article' => $article])
            @empty
                <div class="poligonium-academy-empty">
                    {{ $isEnglish ? 'No published articles yet.' : 'Поки немає опублікованих статей.' }}
                </div>
            @endforelse
        </div>

        {{ $articles->links() }}
    </div>
</section>

@include('theme.zelio::views.academy.partials.styles')

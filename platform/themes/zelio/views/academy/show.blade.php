@php
    $isEnglish = request()->segment(1) === 'en';
    $tags = array_merge($article->softwareList(), $article->skillsList());
    $ctaLabel = $article->cta_label ?: ($isEnglish ? 'Open courses' : 'Перейти до курсів');
    $fallbackCoursesUrl = \Illuminate\Support\Facades\Route::has('courses.public.index') ? route('courses.public.index') : url(($isEnglish ? '/en' : '') . '/courses');
    $ctaUrl = $article->cta_url ?: $fallbackCoursesUrl;
@endphp

<section class="poligonium-academy-page">
    <div class="poligonium-academy-wrap">
        <article class="poligonium-academy-article">
            <header class="poligonium-academy-article__hero">
                <p class="poligonium-academy-kicker">
                    {{ $article->category?->name ?: 'Polygonium Academy' }}
                </p>
                <h1>{{ $article->name }}</h1>
                <p>{{ $article->description }}</p>
                <div class="poligonium-academy-tags" style="margin-top: 22px;">
                    @if ($article->reading_time)
                        <span>{{ $article->reading_time }} хв</span>
                    @endif
                    @if ($article->difficulty)
                        <span>{{ $article->difficulty }}</span>
                    @endif
                    @foreach (array_slice($tags, 0, 8) as $tag)
                        <span>{{ $tag }}</span>
                    @endforeach
                </div>
            </header>

            @if ($article->cover_image)
                <div class="poligonium-academy-card__media" style="height: min(58vw, 520px); margin: 24px 0 0; border-radius: 18px; overflow: hidden;">
                    <img loading="lazy" src="{{ RvMedia::getImageUrl($article->cover_image) }}" alt="{{ $article->name }}">
                </div>
            @endif

            <div class="poligonium-academy-content">
                {!! BaseHelper::clean($article->content) !!}
            </div>

            <div class="poligonium-academy-cta">
                <strong>{{ $isEnglish ? 'Want to move from article to practice?' : 'Хочете перейти від статті до практики?' }}</strong>
                <a class="poligonium-academy-button is-primary" href="{{ url($ctaUrl) }}">{{ $ctaLabel }}</a>
            </div>
        </article>

        @if ($relatedArticles->isNotEmpty())
            <section class="poligonium-academy-section">
                <div class="poligonium-academy-section__head">
                    <div>
                        <h2>{{ $isEnglish ? 'Read next' : 'Читати далі' }}</h2>
                        <p>{{ $isEnglish ? 'Related materials from the same academy path.' : 'Повʼязані матеріали з цього ж навчального напрямку.' }}</p>
                    </div>
                </div>
                <div class="poligonium-academy-grid">
                    @foreach ($relatedArticles as $relatedArticle)
                        @include('theme.zelio::views.academy.partials.article-card', ['article' => $relatedArticle])
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</section>

@include('theme.zelio::views.academy.partials.styles')

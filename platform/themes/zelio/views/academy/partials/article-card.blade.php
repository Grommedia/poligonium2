@php
    $articleUrl = route('academy.public.show', $article->slug);
    $isEnglish = request()->segment(1) === 'en';
    $copy = $copy ?? [
        'read' => $isEnglish ? 'Read' : 'Читати',
        'minutes' => $isEnglish ? 'min read' : 'хв читання',
    ];
    $arrowIcon = $icons['arrow'] ?? null;
    $fallbackImage = Theme::asset()->url('images/academy/visual/image-file-3d-pixel-icon.svg');
    $readingTime = $article->reading_time ?: 8;
@endphp

<a class="poligonium-academy-card" href="{{ $articleUrl }}">
    <span class="poligonium-academy-card__media">
        <img loading="lazy" src="{{ $article->cover_image ? RvMedia::getImageUrl($article->cover_image) : $fallbackImage }}" alt="{{ $article->name }}">
    </span>
    <span class="poligonium-academy-card__body">
        <h3>{{ $article->name }}</h3>
        <p>{{ \Illuminate\Support\Str::limit(strip_tags((string) $article->description), 110) }}</p>
        <span class="poligonium-academy-card__read">
            <span>{{ $readingTime }} {{ $copy['minutes'] }}</span>
            <span>
                {{ $copy['read'] }}
                @if ($arrowIcon)
                    <span class="poligonium-academy-arrow-icon" style="--academy-icon: url('{{ $arrowIcon }}')" aria-hidden="true"></span>
                @else
                    →
                @endif
            </span>
        </span>
    </span>
</a>

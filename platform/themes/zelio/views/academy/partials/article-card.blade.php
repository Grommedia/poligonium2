@php
    $articleUrl = route('academy.public.show', $article->slug);
    $tags = array_slice(array_merge($article->softwareList(), $article->skillsList()), 0, 4);
@endphp

<a class="poligonium-academy-card" href="{{ $articleUrl }}">
    <span class="poligonium-academy-card__body">
        <span class="poligonium-academy-card__media">
            @if ($article->cover_image)
                <img loading="lazy" src="{{ RvMedia::getImageUrl($article->cover_image) }}" alt="{{ $article->name }}">
            @endif
        </span>
        <span class="poligonium-academy-card__meta">
            @if ($article->category)
                <span>{{ $article->category->name }}</span>
            @endif
            @if ($article->reading_time)
                <span>{{ $article->reading_time }} хв</span>
            @endif
            @if ($article->difficulty)
                <span>{{ $article->difficulty }}</span>
            @endif
        </span>
        <h3>{{ $article->name }}</h3>
        <p>{{ $article->description }}</p>
        @if ($tags)
            <span class="poligonium-academy-tags">
                @foreach ($tags as $tag)
                    <span>{{ $tag }}</span>
                @endforeach
            </span>
        @endif
    </span>
</a>

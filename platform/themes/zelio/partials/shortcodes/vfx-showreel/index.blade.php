@php
    $mediaUrl = function (?string $path): ?string {
        $path = trim((string) $path);

        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return RvMedia::url($path);
    };

    $items = collect($items)->filter(fn ($item) => ! empty($item['name']))->values();
    $sectionId = 'poligonium-vfx-showreel-' . Str::random(8);
@endphp

<section class="poligonium-vfx-showreel" id="{{ $sectionId }}">
    <div class="poligonium-vfx-showreel__scene" aria-hidden="true">
        <span></span>
        <i></i>
    </div>

    <div class="poligonium-vfx-showreel__wrap">
        <header class="poligonium-vfx-showreel__head">
            <div>
                @if($shortcode->subtitle)
                    <span class="poligonium-section-kicker">{!! BaseHelper::clean($shortcode->subtitle) !!}</span>
                @endif

                @if($shortcode->title)
                    <h1>{!! BaseHelper::clean($shortcode->title) !!}</h1>
                @endif
            </div>

            @if($shortcode->description)
                <p>{!! BaseHelper::clean($shortcode->description) !!}</p>
            @endif
        </header>

        <div class="poligonium-vfx-showreel__grid">
            @foreach($items as $item)
                @php
                    $poster = $mediaUrl($item['poster'] ?? null);
                    $video = $mediaUrl($item['preview_video'] ?? null);
                    $url = $item['url'] ?? null;
                    $contactUrl = $url ?: ($shortcode->cta_link ?: '/contact');
                    $tagList = collect(explode(',', $item['tools'] ?? ''))
                        ->map(fn ($tag) => trim($tag))
                        ->filter()
                        ->take(4);
                @endphp

                <article class="poligonium-vfx-card" style="--vfx-index: {{ $loop->index }}">
                    <a
                        href="#{{ $sectionId }}-player"
                        data-vfx-card="{{ $loop->index }}"
                        data-vfx-title="{{ e($item['name']) }}"
                        data-vfx-type="{{ e($item['type'] ?? '') }}"
                        data-vfx-video="{{ e($video ?? '') }}"
                        data-vfx-poster="{{ e($poster ?? '') }}"
                        data-vfx-contact="{{ e($contactUrl) }}"
                        @class(['is-disabled' => ! $video])
                    >
                        <div class="poligonium-vfx-card__media">
                            @if($poster)
                                <img src="{{ $poster }}" alt="{{ $item['name'] }}">
                            @endif

                            @if($video)
                                <video muted loop playsinline preload="metadata" poster="{{ $poster }}">
                                    <source src="{{ $video }}" type="video/mp4">
                                </video>
                            @endif
                        </div>

                        <div class="poligonium-vfx-card__content">
                            <div class="poligonium-vfx-card__meta">
                                @if($item['type'] ?? null)
                                    <span>{{ $item['type'] }}</span>
                                @endif

                                @if($item['year'] ?? null)
                                    <small>{{ $item['year'] }}</small>
                                @endif
                            </div>

                            <h2>{!! BaseHelper::clean($item['name']) !!}</h2>

                            @if($item['description'] ?? null)
                                <p>{!! BaseHelper::clean($item['description']) !!}</p>
                            @endif

                            @if($tagList->isNotEmpty())
                                <ul>
                                    @foreach($tagList as $tag)
                                        <li>{{ $tag }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </a>
                </article>
            @endforeach
        </div>

        @if($shortcode->cta_text)
            <div class="poligonium-vfx-showreel__cta">
                <a href="{{ $shortcode->cta_link ?: '/contact' }}">{{ $shortcode->cta_text }} <span>-&gt;</span></a>
            </div>
        @endif
    </div>

    <div class="poligonium-vfx-modal" data-vfx-modal hidden aria-hidden="true">
        <div class="poligonium-vfx-modal__backdrop" data-vfx-close></div>
        <div class="poligonium-vfx-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="{{ $sectionId }}-title">
            <button class="poligonium-vfx-modal__close" type="button" data-vfx-close aria-label="Закрити">×</button>
            <button class="poligonium-vfx-modal__arrow poligonium-vfx-modal__arrow--prev" type="button" data-vfx-prev aria-label="Попередній ролик">
                <span aria-hidden="true">‹</span>
            </button>
            <button class="poligonium-vfx-modal__arrow poligonium-vfx-modal__arrow--next" type="button" data-vfx-next aria-label="Наступний ролик">
                <span aria-hidden="true">›</span>
            </button>

            <div class="poligonium-vfx-modal__video">
                <video id="{{ $sectionId }}-player" data-vfx-player controls playsinline preload="metadata"></video>
            </div>

            <div class="poligonium-vfx-modal__bar">
                <div>
                    <span data-vfx-type></span>
                    <h3 id="{{ $sectionId }}-title" data-vfx-title></h3>
                </div>

                <div class="poligonium-vfx-modal__actions">
                    <a href="/contact" data-vfx-contact>Зв’язатися з нами</a>
                </div>
            </div>
        </div>
    </div>
</section>

@php
    Theme::set('pageTitle', $project->name);

    $tools = collect(poligonium_portfolio_effective_tools($project));
    $label = poligonium_portfolio_effective_card_label($project);
    $subtitle = poligonium_portfolio_effective_card_subtitle($project);
    $portrait = $project->getMetaData('portrait_image', true);
    $gallery = collect($project->images ?: [])->filter()->values();
    $projectVideo = $project->getMetaData('project_video', true);
    $presentationSlides = collect();

    foreach ($gallery->take(3) as $image) {
        $presentationSlides->push(['type' => 'image', 'src' => $image]);
    }

    if ($presentationSlides->isEmpty() && $project->image) {
        $presentationSlides->push(['type' => 'image', 'src' => $project->image]);
    }

    if ($projectVideo) {
        $presentationSlides->push(['type' => 'video', 'src' => $projectVideo]);
    }

    $categoryIds = $project->getMetaData('category_ids', true) ?: [];
    $categories = $categoryIds
        ? \Botble\Portfolio\Models\ServiceCategory::query()->whereIn('id', $categoryIds)->pluck('name')->all()
        : [];
@endphp

<section class="poligonium-project-detail">
    <div class="poligonium-project-detail__grid" aria-hidden="true"></div>

    <div class="container position-relative">
        <a class="poligonium-project-detail__back" href="{{ url('/projects') }}">← {{ __('Back to projects') }}</a>

        <header @class(['poligonium-project-hero', 'has-portrait' => $portrait, 'has-no-portrait' => ! $portrait])>
            <div class="poligonium-project-hero__corners" aria-hidden="true"></div>

            @if ($portrait)
                <div class="poligonium-project-hero__portrait">
                    {{ RvMedia::image($portrait, $project->name) }}
                </div>
            @endif

            <div class="poligonium-project-hero__label">
                <i class="ti ti-folder"></i>
                <span>{{ Str::upper($label) }}</span>
            </div>

            <div class="poligonium-project-hero__layout">
                <div>
                    <h1>{!! BaseHelper::clean($project->name) !!}</h1>

                    @if ($subtitle)
                        <strong>{{ Str::upper($subtitle) }}</strong>
                    @endif

                    @if($project->description)
                        <p>{!! BaseHelper::clean($project->description) !!}</p>
                    @endif
                </div>

                <aside class="poligonium-project-hero__tools">
                    <div>
                        <i class="ti ti-settings"></i>
                        <span>{{ __('Tools & Technologies') }}</span>
                    </div>

                    @forelse($tools as $tool)
                        @php($toolData = poligonium_portfolio_tool_data($tool))
                        <span>
                            @if($toolData['icon_url'])
                                <img src="{{ $toolData['icon_url'] }}" alt="{{ $toolData['label'] }}">
                            @else
                                <b>{{ Str::upper($toolData['short']) }}</b>
                            @endif
                            {{ $toolData['label'] }}
                        </span>
                    @empty
                        @foreach(['Blender', 'Houdini', 'ZBrush'] as $defaultTool)
                            @php($toolData = poligonium_portfolio_tool_data($defaultTool))
                            <span>
                                @if($toolData['icon_url'])
                                    <img src="{{ $toolData['icon_url'] }}" alt="{{ $toolData['label'] }}">
                                @else
                                    <b>{{ Str::upper($toolData['short']) }}</b>
                                @endif
                                {{ $toolData['label'] }}
                            </span>
                        @endforeach
                    @endforelse
                </aside>
            </div>

            <div class="poligonium-project-hero__meta">
                <span>
                    <i class="ti ti-calendar"></i>
                    <small>{{ __('Created') }}</small>
                    {{ Str::upper(($project->start_date ?: $project->created_at)->translatedFormat('M Y')) }}
                </span>

                <span>
                    <i class="ti ti-building"></i>
                    <small>{{ __('Client / Project') }}</small>
                    {{ $project->client ?: 'Poligonium' }}
                </span>

                @if ($categories)
                    <span>
                        <i class="ti ti-tags"></i>
                        <small>{{ __('Category') }}</small>
                        {{ implode(', ', $categories) }}
                    </span>
                @endif
            </div>
        </header>

        @if($presentationSlides->isNotEmpty())
            <section class="poligonium-project-presentation" data-poligonium-presentation>
                <div class="poligonium-project-presentation__copy">
                    <span>Presentation Frame</span>
                    <strong>{{ $presentationSlides->count() }} {{ __('items') }}</strong>
                </div>

                <div class="poligonium-project-presentation__cards">
                    @foreach($presentationSlides as $index => $slide)
                        <button @class(['poligonium-project-presentation-card', 'poligonium-project-presentation-card--video' => $slide['type'] === 'video']) type="button" data-poligonium-open="{{ $index }}">
                            @if($slide['type'] === 'video')
                                <span class="poligonium-project-presentation-card__video">
                                    <video src="{{ RvMedia::url($slide['src']) }}" playsinline muted preload="metadata"></video>
                                    <span class="poligonium-project-presentation-card__play" aria-hidden="true"></span>
                                </span>
                            @else
                                <img src="{{ RvMedia::getImageUrl($slide['src']) }}" alt="{{ $project->name }}">
                            @endif
                            <em>{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</em>
                        </button>
                    @endforeach
                </div>

                <div class="poligonium-project-modal" data-poligonium-modal aria-hidden="true">
                    <button class="poligonium-project-modal__backdrop" type="button" data-poligonium-close aria-label="{{ __('Close') }}"></button>

                    <div class="poligonium-project-modal__dialog" role="dialog" aria-modal="true" aria-label="Project media viewer">
                        <button class="poligonium-project-modal__close" type="button" data-poligonium-close aria-label="{{ __('Close') }}">
                            <i class="ti ti-x"></i>
                            <span>Закрити</span>
                        </button>

                        <button class="poligonium-project-modal__nav poligonium-project-modal__nav--prev" type="button" data-poligonium-prev aria-label="{{ __('Previous') }}">
                            <i class="ti ti-arrow-left"></i>
                        </button>

                        <div class="poligonium-project-modal__stage">
                            @foreach($presentationSlides as $index => $slide)
                                <figure @class(['poligonium-project-modal__slide', 'is-active' => $index === 0]) data-poligonium-slide="{{ $index }}">
                                    @if($slide['type'] === 'video')
                                        <video src="{{ RvMedia::url($slide['src']) }}" controls playsinline preload="metadata"></video>
                                    @else
                                        <img src="{{ RvMedia::getImageUrl($slide['src']) }}" alt="{{ $project->name }}">
                                    @endif
                                </figure>
                            @endforeach
                        </div>

                        <button class="poligonium-project-modal__nav poligonium-project-modal__nav--next" type="button" data-poligonium-next aria-label="{{ __('Next') }}">
                            <i class="ti ti-arrow-right"></i>
                        </button>

                        <div class="poligonium-project-modal__counter">
                            <span data-poligonium-current>1</span> / {{ $presentationSlides->count() }}
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if($project->content)
            <section class="poligonium-project-content">
                <div class="ck-content">{!! BaseHelper::clean($project->content) !!}</div>
            </section>
        @endif

        <section class="poligonium-project-share">
            <span>{{ __('Share') }}</span>
            {!! Theme::renderSocialSharing($project->url, SeoHelper::getDescription(), $project->image) !!}
        </section>

        {!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, null, $project) !!}
    </div>
</section>

<style>
    .poligonium-project-detail {
        position: relative;
        overflow: hidden;
        min-height: 100vh;
        padding: 110px 0 76px;
        background: #f4f4f1;
        color: #22252a;
    }

    body > header {
        position: sticky !important;
        z-index: 5000 !important;
        pointer-events: auto !important;
    }

    body > header * {
        pointer-events: auto;
    }

    html.poligonium-modal-open {
        overflow: hidden;
    }

    .poligonium-project-detail__grid {
        position: absolute;
        inset: 0;
        opacity: .58;
        background-image:
            linear-gradient(rgba(35, 39, 45, .08) 1px, transparent 1px),
            linear-gradient(90deg, rgba(35, 39, 45, .08) 1px, transparent 1px);
        background-size: 28px 28px;
        pointer-events: none;
    }

    .poligonium-project-detail__back {
        display: inline-flex;
        margin-bottom: 18px;
        color: #2669a8;
        font-weight: 900;
    }

    .poligonium-project-hero,
    .poligonium-project-content,
    .poligonium-project-share {
        position: relative;
        border: 1px solid rgba(32, 36, 42, .22);
        border-radius: 8px;
        background:
            linear-gradient(rgba(250, 250, 247, .9), rgba(250, 250, 247, .9)),
            repeating-linear-gradient(0deg, transparent 0 27px, rgba(32, 36, 42, .06) 27px 28px),
            repeating-linear-gradient(90deg, transparent 0 27px, rgba(32, 36, 42, .06) 27px 28px);
        box-shadow: 0 22px 54px rgba(32, 36, 42, .16);
    }

    .poligonium-project-hero {
        z-index: 1;
        max-width: 1180px;
        min-height: 470px;
        margin-inline: auto;
        padding: 54px 38px 24px;
    }

    .poligonium-project-hero__corners,
    .poligonium-project-hero__corners::before {
        position: absolute;
        inset: 18px;
        border: 1px solid rgba(32, 36, 42, .34);
        pointer-events: none;
    }

    .poligonium-project-hero__corners::before {
        content: "";
        inset: -1px;
        border-color: rgba(32, 36, 42, .62);
        clip-path: polygon(0 0, 12% 0, 0 12%, 0 100%, 12% 100%, 0 88%, 100% 88%, 88% 100%, 100% 100%, 100% 0, 88% 0, 100% 12%);
    }

    .poligonium-project-hero__portrait {
        position: absolute;
        left: 50%;
        top: -58px;
        z-index: 2;
        width: min(260px, 30vw);
        aspect-ratio: 1 / 1;
        translate: -50% 0;
        display: grid;
        place-items: end center;
        pointer-events: none;
    }

    .poligonium-project-hero__portrait img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        filter:
            drop-shadow(0 10px 0 #fff)
            drop-shadow(10px 0 0 #fff)
            drop-shadow(-10px 0 0 #fff)
            drop-shadow(0 -10px 0 #fff)
            drop-shadow(0 22px 28px rgba(32, 36, 42, .28));
    }

    .poligonium-project-hero.has-no-portrait {
        min-height: 390px;
    }

    .poligonium-project-hero.has-no-portrait .poligonium-project-hero__layout {
        padding-top: 46px;
    }

    .poligonium-project-hero__label {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        min-height: 42px;
        padding: 0 16px;
        border: 1px solid rgba(32, 36, 42, .28);
        background: rgba(255, 255, 255, .62);
        color: #32363c;
        font-weight: 900;
        clip-path: polygon(8% 0, 92% 0, 100% 50%, 92% 100%, 8% 100%, 0 50%);
    }

    .poligonium-project-hero__layout {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(280px, .44fr);
        gap: 28px;
        align-items: end;
        min-height: 260px;
        padding-top: 96px;
    }

    .poligonium-project-hero h1 {
        max-width: 720px;
        margin: 0;
        color: #20242a;
        font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
        font-size: clamp(44px, 6.2vw, 88px);
        line-height: .88;
        letter-spacing: .03em;
        text-transform: uppercase;
    }

    .poligonium-project-hero strong {
        display: block;
        margin-top: 10px;
        color: #9a7947;
        font-size: 18px;
        letter-spacing: .08em;
    }

    .poligonium-project-hero p {
        max-width: 560px;
        margin: 18px 0 0;
        padding-top: 16px;
        border-top: 1px solid rgba(32, 36, 42, .24);
        color: #3e444c;
        font-size: 17px;
        line-height: 1.5;
    }

    .poligonium-project-hero__tools {
        display: grid;
        gap: 8px;
    }

    .poligonium-project-hero__tools > div {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 4px;
        color: #32363c;
        font-weight: 900;
        text-transform: uppercase;
    }

    .poligonium-project-hero__tools span {
        display: flex;
        align-items: center;
        gap: 11px;
        min-height: 44px;
        padding: 0 14px;
        border: 1px solid rgba(32, 36, 42, .25);
        background: rgba(255, 255, 255, .72);
        box-shadow: 0 8px 18px rgba(32, 36, 42, .1);
        color: #32363c;
        font-weight: 900;
        text-transform: uppercase;
        clip-path: polygon(0 0, 100% 0, 94% 50%, 100% 100%, 0 100%);
    }

    .poligonium-project-hero__tools b,
    .poligonium-project-hero__tools img {
        display: grid;
        place-items: center;
        width: 30px;
        height: 30px;
        flex: 0 0 30px;
    }

    .poligonium-project-hero__tools b {
        border-radius: 6px;
        background: #20242a;
        color: #f49a21;
        font-size: 13px;
    }

    .poligonium-project-hero__tools img {
        object-fit: contain;
    }

    .poligonium-project-hero__meta {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-top: 22px;
        padding: 13px 16px;
        border: 1px solid rgba(32, 36, 42, .32);
        border-radius: 6px;
        background: rgba(255, 255, 255, .38);
    }

    .poligonium-project-hero__meta span {
        display: grid;
        grid-template-columns: 38px 1fr;
        column-gap: 10px;
        align-items: center;
        color: #20242a;
        font-size: 15px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .poligonium-project-hero__meta i {
        grid-row: span 2;
        font-size: 26px;
    }

    .poligonium-project-hero__meta small {
        color: #535a63;
        font-size: 12px;
        letter-spacing: .08em;
    }

    .poligonium-project-presentation {
        position: relative;
        margin-top: 24px;
        padding: 24px;
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 8px;
        background:
            linear-gradient(rgba(18, 20, 24, .94), rgba(18, 20, 24, .94)),
            repeating-linear-gradient(0deg, transparent 0 27px, rgba(255, 255, 255, .055) 27px 28px),
            repeating-linear-gradient(90deg, transparent 0 27px, rgba(255, 255, 255, .055) 27px 28px);
        box-shadow: 0 24px 58px rgba(16, 18, 22, .28);
    }

    .poligonium-project-presentation__copy {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
        color: #f7f3ea;
    }

    .poligonium-project-presentation__copy span {
        color: #f7f3ea;
        font-size: 18px;
        font-weight: 900;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .poligonium-project-presentation__copy strong {
        min-height: 32px;
        padding: 0 12px;
        display: inline-flex;
        align-items: center;
        border: 1px solid rgba(255, 255, 255, .18);
        border-radius: 999px;
        color: #d5d9df;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .poligonium-project-presentation__cards {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
    }

    .poligonium-project-presentation-card {
        position: relative;
        overflow: hidden;
        display: block;
        width: 100%;
        aspect-ratio: 9 / 16;
        padding: 10px;
        border: 1px solid rgba(213, 217, 223, .24);
        border-radius: 8px;
        background: #0d0f13;
        box-shadow: 0 18px 40px rgba(0, 0, 0, .34);
        cursor: pointer;
        pointer-events: auto;
        transition: transform .22s ease, border-color .22s ease, box-shadow .22s ease;
    }

    .poligonium-project-presentation-card:focus {
        outline: none;
    }

    .poligonium-project-presentation-card:focus-visible {
        border-color: rgba(244, 154, 33, .78);
        box-shadow: 0 0 0 3px rgba(244, 154, 33, .18), 0 26px 54px rgba(0, 0, 0, .42);
    }

    .poligonium-project-presentation-card > * {
        pointer-events: none;
    }

    .poligonium-project-presentation-card--video {
        align-self: center;
        aspect-ratio: 9 / 16;
    }

    .poligonium-project-presentation-card::before {
        content: "";
        position: absolute;
        inset: 9px;
        z-index: 2;
        border: 1px solid rgba(255, 255, 255, .16);
        border-radius: 5px;
        pointer-events: none;
    }

    .poligonium-project-presentation-card::after {
        content: "";
        position: absolute;
        inset: 10px;
        z-index: 1;
        background: linear-gradient(180deg, transparent 58%, rgba(0, 0, 0, .55));
        pointer-events: none;
    }

    .poligonium-project-presentation-card:hover {
        border-color: rgba(244, 154, 33, .78);
        box-shadow: 0 26px 54px rgba(0, 0, 0, .42);
        transform: translateY(-4px);
    }

    .poligonium-project-presentation-card img,
    .poligonium-project-presentation-card video {
        width: 100%;
        height: 100%;
        border-radius: 4px;
        object-fit: cover;
        background: #111;
    }

    .poligonium-project-presentation-card__video {
        position: relative;
        display: block;
        width: 100%;
        height: 100%;
    }

    .poligonium-project-presentation-card__play {
        position: absolute;
        left: 50%;
        top: 50%;
        z-index: 3;
        display: grid;
        place-items: center;
        width: 58px;
        height: 58px;
        border-radius: 50%;
        background: rgba(244, 154, 33, .94);
        color: #111318;
        font-size: 26px;
        transform: translate(-50%, -50%);
        box-shadow: 0 16px 34px rgba(0, 0, 0, .28);
    }

    .poligonium-project-presentation-card__play::before {
        content: "";
        display: block;
        width: 0;
        height: 0;
        margin-left: 4px;
        border-top: 11px solid transparent;
        border-bottom: 11px solid transparent;
        border-left: 17px solid currentColor;
    }

    .poligonium-project-presentation-card em {
        position: absolute;
        left: 18px;
        bottom: 16px;
        z-index: 4;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 28px;
        padding: 0 8px;
        border: 1px solid rgba(255, 255, 255, .18);
        border-radius: 999px;
        background: rgba(13, 15, 19, .72);
        color: #f7f3ea;
        font-size: 11px;
        font-style: normal;
        font-weight: 900;
    }

    .poligonium-project-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: none;
        place-items: center;
        padding: 28px;
        opacity: 0;
        pointer-events: none;
        transition: opacity .2s ease;
    }

    .poligonium-project-modal:not(.is-open) {
        display: none !important;
    }

    .poligonium-project-modal.is-open {
        display: grid;
        opacity: 1;
        pointer-events: auto;
    }

    .poligonium-project-modal__backdrop {
        position: absolute;
        inset: 0;
        border: 0;
        background: rgba(7, 8, 10, .82);
        backdrop-filter: blur(9px);
        cursor: pointer;
    }

    .poligonium-project-modal__dialog {
        position: relative;
        z-index: 1;
        display: grid;
        place-items: center;
        width: min(92vw, 620px);
        max-height: calc(100vh - 56px);
    }

    .poligonium-project-modal__stage {
        position: relative;
        width: min(100%, 470px);
        aspect-ratio: 9 / 16;
        padding: 12px;
        border: 1px solid rgba(255, 255, 255, .22);
        border-radius: 10px;
        background: #0d0f13;
        box-shadow: 0 34px 74px rgba(0, 0, 0, .5);
    }

    .poligonium-project-modal__dialog.is-video .poligonium-project-modal__stage {
        width: min(100%, 470px);
        aspect-ratio: 9 / 16;
    }

    .poligonium-project-modal__stage::before {
        content: "";
        position: absolute;
        inset: 10px;
        z-index: 4;
        border: 1px solid rgba(255, 255, 255, .15);
        border-radius: 6px;
        pointer-events: none;
    }

    .poligonium-project-modal__slide {
        position: absolute;
        inset: 12px;
        display: grid;
        place-items: center;
        margin: 0;
        opacity: 0;
        transform: scale(.985);
        transition: opacity .22s ease, transform .22s ease;
        pointer-events: none;
    }

    .poligonium-project-modal__slide.is-active {
        opacity: 1;
        transform: scale(1);
        pointer-events: auto;
    }

    .poligonium-project-modal__slide img,
    .poligonium-project-modal__slide video {
        width: 100%;
        height: 100%;
        border-radius: 5px;
        object-fit: contain;
        background: #111;
    }

    .poligonium-project-modal__close,
    .poligonium-project-modal__nav {
        position: absolute;
        z-index: 5;
        display: grid;
        place-items: center;
        border: 1px solid rgba(255, 255, 255, .18);
        border-radius: 50%;
        background: rgba(13, 15, 19, .86);
        color: #fff;
        cursor: pointer;
        transition: background .18s ease, transform .18s ease;
    }

    .poligonium-project-modal__close:hover,
    .poligonium-project-modal__nav:hover {
        background: #f49a21;
        color: #101216;
        transform: translateY(-1px);
    }

    .poligonium-project-modal__close {
        right: -18px;
        top: -18px;
        width: auto;
        height: 42px;
        grid-auto-flow: column;
        gap: 7px;
        padding: 0 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .poligonium-project-modal__nav {
        top: 50%;
        width: 46px;
        height: 46px;
        transform: translateY(-50%);
    }

    .poligonium-project-modal__nav:hover {
        transform: translateY(calc(-50% - 1px));
    }

    .poligonium-project-modal__nav--prev {
        left: -62px;
    }

    .poligonium-project-modal__nav--next {
        right: -62px;
    }

    .poligonium-project-modal__counter {
        position: absolute;
        left: 50%;
        bottom: -42px;
        z-index: 5;
        min-width: 72px;
        padding: 6px 12px;
        border: 1px solid rgba(255, 255, 255, .16);
        border-radius: 999px;
        background: rgba(13, 15, 19, .86);
        color: #f7f3ea;
        font-size: 12px;
        font-weight: 900;
        text-align: center;
        transform: translateX(-50%);
    }

    .poligonium-project-content {
        margin-top: 34px;
        padding: 42px;
        color: #303640;
        font-size: 18px;
        line-height: 1.75;
    }

    .poligonium-project-share {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 14px;
        margin-top: 24px;
        padding: 22px;
    }

    .poligonium-project-share span {
        color: #20242a;
        font-weight: 900;
        text-transform: uppercase;
    }

    @media (max-width: 991px) {
        .poligonium-project-hero__layout,
        .poligonium-project-hero__meta {
            grid-template-columns: 1fr;
        }

        .poligonium-project-hero {
            min-height: 0;
            padding: 58px 24px 24px;
        }

        .poligonium-project-hero__layout {
            min-height: 0;
            padding-top: 120px;
        }

        .poligonium-project-presentation__cards {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .poligonium-project-modal__nav--prev {
            left: 10px;
        }

        .poligonium-project-modal__nav--next {
            right: 10px;
        }
    }

    @media (max-width: 575px) {
        .poligonium-project-detail {
            padding-top: 110px;
        }

        .poligonium-project-hero__portrait {
            width: min(210px, 66vw);
            top: -46px;
        }

        .poligonium-project-hero h1 {
            font-size: 46px;
        }

        .poligonium-project-content {
            padding: 24px;
        }

        .poligonium-project-presentation {
            padding: 16px;
        }

        .poligonium-project-presentation__cards {
            gap: 12px;
        }

        .poligonium-project-modal {
            padding: 18px;
        }

        .poligonium-project-modal__stage {
            width: min(100%, 360px);
        }

        .poligonium-project-modal__close {
            right: 0;
            top: -52px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-poligonium-presentation]').forEach(function (presentation) {
            var slides = Array.prototype.slice.call(presentation.querySelectorAll('[data-poligonium-slide]'));
            var openers = Array.prototype.slice.call(presentation.querySelectorAll('[data-poligonium-open]'));
            var modal = presentation.querySelector('[data-poligonium-modal]');
            var dialog = modal ? modal.querySelector('.poligonium-project-modal__dialog') : null;
            var current = presentation.querySelector('[data-poligonium-current]');
            var previous = presentation.querySelector('[data-poligonium-prev]');
            var next = presentation.querySelector('[data-poligonium-next]');
            var closers = Array.prototype.slice.call(presentation.querySelectorAll('[data-poligonium-close]'));
            var activeIndex = 0;
            var quietVideoVolume = 0.12;

            if (! slides.length || ! modal) {
                return;
            }

            var pauseInactiveVideos = function () {
                slides.forEach(function (slide, slideIndex) {
                    if (slideIndex !== activeIndex) {
                        slide.querySelectorAll('video').forEach(function (video) {
                            video.pause();
                        });
                    }
                });
            };

            slides.forEach(function (slide) {
                slide.querySelectorAll('video').forEach(function (video) {
                    video.volume = quietVideoVolume;
                });
            });

            var setActive = function (index) {
                activeIndex = (index + slides.length) % slides.length;

                slides.forEach(function (slide, slideIndex) {
                    slide.classList.toggle('is-active', slideIndex === activeIndex);
                });

                if (dialog) {
                    dialog.classList.toggle('is-video', slides[activeIndex]?.querySelector('video'));
                }

                slides[activeIndex]?.querySelectorAll('video').forEach(function (video) {
                    video.volume = quietVideoVolume;
                });

                if (current) {
                    current.textContent = activeIndex + 1;
                }

                pauseInactiveVideos();
            };

            var openModal = function (index) {
                setActive(index);
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.documentElement.classList.add('poligonium-modal-open');
            };

            var closeModal = function () {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                document.documentElement.classList.remove('poligonium-modal-open');

                slides.forEach(function (slide) {
                    slide.querySelectorAll('video').forEach(function (video) {
                        video.pause();
                    });
                });
            };

            openers.forEach(function (opener, index) {
                opener.addEventListener('click', function () {
                    var requestedIndex = parseInt(opener.dataset.poligoniumOpen || index, 10);

                    openModal(Number.isNaN(requestedIndex) ? index : requestedIndex);
                });
            });

            closers.forEach(function (closer) {
                closer.addEventListener('click', closeModal);
            });

            if (previous) {
                previous.addEventListener('click', function () {
                    setActive(activeIndex - 1);
                });
            }

            if (next) {
                next.addEventListener('click', function () {
                    setActive(activeIndex + 1);
                });
            }

            document.addEventListener('keydown', function (event) {
                if (! modal.classList.contains('is-open')) {
                    return;
                }

                if (event.key === 'Escape') {
                    closeModal();
                }

                if (event.key === 'ArrowLeft') {
                    setActive(activeIndex - 1);
                }

                if (event.key === 'ArrowRight') {
                    setActive(activeIndex + 1);
                }
            });

            setActive(0);
        });
    });
</script>

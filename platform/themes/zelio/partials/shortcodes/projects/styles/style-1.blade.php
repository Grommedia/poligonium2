@php
    $portfolioDate = function ($project): string {
        return $project->start_date ? $project->start_date->translatedFormat('M Y') : $project->created_at->translatedFormat('M Y');
    };

    $portfolioTools = fn ($project): array => poligonium_portfolio_effective_tools($project);
@endphp

<section id="projects" class="poligonium-portfolio-board">
    <div class="poligonium-portfolio-board__scene" aria-hidden="true">
        <span class="poligonium-portfolio-board__orbit"><i></i></span>
    </div>

    <div class="poligonium-portfolio-wrap">
        <div class="poligonium-portfolio-board__head">
            <div class="poligonium-portfolio-board__title">
                <span>{{ $shortcode->title ? BaseHelper::clean(strip_tags($shortcode->title)) : 'Портфоліо' }}</span>
            </div>

            <div class="poligonium-portfolio-filters filter-button-group filter-menu-active">
                <button class="active" data-filter="*">Усі проєкти</button>
                @foreach($categories as $category)
                    <button data-filter=".portfolio-category-{{ Str::slug($category->name) }}">{{ $category->name }}</button>
                @endforeach
            </div>
        </div>

        <div class="poligonium-portfolio-list">
            @foreach($projects as $project)
                @php
                    $label = poligonium_portfolio_effective_card_label($project);
                    $subtitle = poligonium_portfolio_effective_card_subtitle($project);
                    $portrait = $project->getMetaData('portrait_image', true);
                    $tools = $portfolioTools($project);
                @endphp

                <article @class([
                    'poligonium-portfolio-card filter-item',
                    $project->categories->map(fn ($item) => 'portfolio-category-' . Str::slug($item->name))->join(' '),
                ]) style="--portfolio-card-index: {{ $loop->index }}">
                    <a @class(['poligonium-portfolio-card__inner', 'has-portrait' => $portrait, 'has-no-portrait' => ! $portrait]) href="{{ $project->url }}">
                        <div class="poligonium-portfolio-card__corners" aria-hidden="true"></div>

                        @if ($portrait)
                            <div class="poligonium-portfolio-card__portrait">
                                {{ RvMedia::image($portrait, $project->name) }}
                            </div>
                        @endif

                        <div class="poligonium-portfolio-card__label">
                            <i class="ti ti-folder"></i>
                            <span>{{ Str::upper($label) }}</span>
                        </div>

                        <div class="poligonium-portfolio-card__content">
                            <div class="poligonium-portfolio-card__copy">
                                <h3>{!! BaseHelper::clean($project->name) !!}</h3>

                                @if ($subtitle)
                                    <strong>{{ Str::upper($subtitle) }}</strong>
                                @endif

                                @if($project->description)
                                    <p>{!! BaseHelper::clean($project->description) !!}</p>
                                @endif
                            </div>

                            <div class="poligonium-portfolio-card__tools">
                                <div>
                                    <i class="ti ti-settings"></i>
                                    <span>{{ __('Tools & Technologies') }}</span>
                                </div>

                                @foreach($tools as $tool)
                                    @php($toolData = poligonium_portfolio_tool_data($tool))
                                    <span class="poligonium-portfolio-tool">
                                        @if($toolData['icon_url'])
                                            <img src="{{ $toolData['icon_url'] }}" alt="{{ $toolData['label'] }}">
                                        @else
                                            <b>{{ Str::upper($toolData['short']) }}</b>
                                        @endif
                                        {{ $toolData['label'] }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div class="poligonium-portfolio-card__meta">
                            <span>
                                <i class="ti ti-calendar"></i>
                                <small>{{ __('Created') }}</small>
                                {{ Str::upper($portfolioDate($project)) }}
                            </span>

                            <span>
                                <i class="ti ti-building"></i>
                                <small>{{ __('Client / Project') }}</small>
                                {{ $project->client ?: 'Poligonium' }}
                            </span>

                            <em aria-hidden="true">Pg</em>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>

        @if($shortcode->bottom_action_text)
            <div class="poligonium-portfolio-board__bottom">
                <a href="{{ $shortcode->bottom_action_link }}">{{ $shortcode->bottom_action_text }}</a>
            </div>
        @endif
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.poligonium-portfolio-board').forEach((board) => {
            const buttons = board.querySelectorAll('.poligonium-portfolio-filters button')
            const cards = board.querySelectorAll('.poligonium-portfolio-card')
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches

            buttons.forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.preventDefault()

                    buttons.forEach((item) => item.classList.remove('active'))
                    button.classList.add('active')

                    const filter = button.dataset.filter

                    cards.forEach((card) => {
                        card.hidden = filter !== '*' && !card.matches(filter)
                    })
                })
            })

            if (prefersReducedMotion) {
                return
            }

            cards.forEach((card) => {
                const inner = card.querySelector('.poligonium-portfolio-card__inner')

                if (!inner) {
                    return
                }

                let lastSparkAt = 0

                const createSpark = () => {
                    const spark = document.createElement('span')
                    const size = Math.round(5 + Math.random() * 7)
                    const startX = Math.round(22 + Math.random() * 62)
                    const startY = Math.round(16 + Math.random() * 34)
                    const driftX = Math.round(-34 + Math.random() * 68)
                    const liftY = Math.round(72 + Math.random() * 72)
                    const delay = Math.round(Math.random() * 140)
                    const duration = Math.round(760 + Math.random() * 520)

                    spark.className = 'poligonium-portfolio-spark'
                    spark.style.setProperty('--spark-size', `${size}px`)
                    spark.style.setProperty('--spark-x', `${startX}%`)
                    spark.style.setProperty('--spark-y', `${startY}%`)
                    spark.style.setProperty('--spark-drift-x', `${driftX}px`)
                    spark.style.setProperty('--spark-lift-y', `${liftY}px`)
                    spark.style.setProperty('--spark-delay', `${delay}ms`)
                    spark.style.setProperty('--spark-duration', `${duration}ms`)

                    inner.appendChild(spark)
                    spark.addEventListener('animationend', () => spark.remove(), { once: true })
                }

                const burst = () => {
                    const now = Date.now()

                    if (now - lastSparkAt < 280) {
                        return
                    }

                    lastSparkAt = now

                    for (let index = 0; index < 11; index += 1) {
                        createSpark()
                    }
                }

                inner.addEventListener('mouseenter', burst)
                inner.addEventListener('focus', burst)
            })
        })
    })
</script>

<style>
    @font-face {
        font-family: 'Unbounded';
        font-style: normal;
        font-weight: 500;
        font-display: swap;
        src: url('/themes/zelio/fonts/unbounded/unbounded-500.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Unbounded';
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: url('/themes/zelio/fonts/unbounded/unbounded-700.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Unbounded';
        font-style: normal;
        font-weight: 800;
        font-display: swap;
        src: url('/themes/zelio/fonts/unbounded/unbounded-800.ttf') format('truetype');
    }

    .poligonium-portfolio-board {
        --portfolio-line: rgba(32, 36, 42, .34);
        --portfolio-line-soft: rgba(32, 36, 42, .18);
        position: relative;
        overflow: hidden;
        width: 100vw;
        margin-right: calc(50% - 50vw);
        margin-left: calc(50% - 50vw);
        margin-top: -1px;
        background:
            linear-gradient(90deg, rgba(17, 24, 39, .055) 1px, transparent 1px),
            linear-gradient(0deg, rgba(17, 24, 39, .055) 1px, transparent 1px),
            #fbfaf8;
        background-size: 36px 36px, 36px 36px, auto;
        color: #22252a;
        animation: poligoniumPortfolioGrid 18s linear infinite;
        isolation: isolate;
    }

    .pb-150.container:has(> .ck-content > .poligonium-portfolio-board) {
        width: 100%;
        max-width: none;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        padding-right: 0 !important;
        padding-left: 0 !important;
    }

    .pb-150.container:has(> .ck-content > .poligonium-portfolio-board) > .mb-8.text-center {
        display: none !important;
    }

    .pb-150.container:has(> .ck-content > .poligonium-portfolio-board) > .ck-content {
        width: 100%;
    }

    .poligonium-portfolio-board::before {
        position: absolute;
        inset: 0;
        z-index: 0;
        content: "";
        background:
            radial-gradient(circle at 17% 18%, rgba(110, 78, 242, .14), transparent 26%),
            radial-gradient(circle at 84% 22%, rgba(20, 184, 166, .13), transparent 25%),
            radial-gradient(circle at 70% 88%, rgba(245, 158, 11, .13), transparent 30%);
        pointer-events: none;
    }

    .poligonium-portfolio-board__scene {
        position: absolute;
        inset: 0;
        z-index: 0;
        pointer-events: none;
    }

    .poligonium-portfolio-wrap {
        position: relative;
        z-index: 1;
        width: min(100% - 48px, 1320px);
        margin: 0 auto;
        padding: 22px 0 96px;
    }

    .poligonium-portfolio-board__orbit {
        position: absolute;
        top: 118px;
        right: 7%;
        width: 286px;
        height: 286px;
        border: 1px solid rgba(110, 78, 242, .17);
        border-radius: 50%;
        animation: poligoniumPortfolioOrbit 24s linear infinite;
    }

    .poligonium-portfolio-board__orbit::before,
    .poligonium-portfolio-board__orbit::after,
    .poligonium-portfolio-board__orbit i {
        position: absolute;
        content: "";
        border-radius: 50%;
    }

    .poligonium-portfolio-board__orbit::before {
        inset: 42px;
        border: 1px dashed rgba(17, 24, 39, .13);
    }

    .poligonium-portfolio-board__orbit::after {
        top: 18%;
        right: 9%;
        width: 11px;
        height: 11px;
        background: var(--primary-color);
        box-shadow: 0 0 0 7px rgba(110, 78, 242, .12);
    }

    .poligonium-portfolio-board__orbit i {
        left: 16%;
        bottom: 9%;
        width: 10px;
        height: 10px;
        background: #14b8a6;
        box-shadow: 0 0 0 7px rgba(20, 184, 166, .12);
    }

    .poligonium-portfolio-board__head {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: auto minmax(0, 1fr);
        align-items: center;
        gap: 18px;
        margin-bottom: 74px;
        padding: 10px;
        border: 1px solid rgba(17, 24, 39, .08);
        border-radius: 18px;
        background: rgba(255, 255, 255, .58);
        box-shadow: 0 18px 48px rgba(17, 24, 39, .06);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }

    .poligonium-portfolio-board__title span {
        display: inline-flex;
        align-items: center;
        min-height: 40px;
        padding: 0 16px;
        border: 1px solid rgba(32, 36, 42, .22);
        border-radius: 999px;
        background: rgba(255, 255, 255, .82);
        color: #20242a;
        font-size: 13px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .poligonium-portfolio-board__bottom a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        margin-top: 18px;
        padding: 0 18px;
        border: 1px solid rgba(32, 36, 42, .28);
        border-radius: 6px;
        background: rgba(255, 255, 255, .7);
        color: #20242a;
        font-weight: 900;
        transition: transform .22s ease, border-color .22s ease, box-shadow .22s ease;
    }

    .poligonium-portfolio-board__bottom a:hover {
        border-color: rgba(110, 78, 242, .34);
        box-shadow: 0 12px 28px rgba(17, 24, 39, .1);
        transform: translateY(-2px);
    }

    .poligonium-portfolio-filters {
        position: relative;
        z-index: 1;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-end;
    }

    .poligonium-portfolio-filters button {
        min-height: 38px;
        padding: 0 14px;
        border: 1px solid rgba(32, 36, 42, .24);
        border-radius: 999px;
        background: rgba(255, 255, 255, .72);
        color: #32363c;
        font-weight: 900;
        text-transform: uppercase;
        transition: transform .2s ease, color .2s ease, background .2s ease, border-color .2s ease, box-shadow .2s ease;
    }

    .poligonium-portfolio-filters button.active,
    .poligonium-portfolio-filters button:hover {
        border-color: rgba(17, 24, 39, .68);
        background: #20242a;
        box-shadow: 0 10px 24px rgba(17, 24, 39, .12);
        color: #fff;
        transform: translateY(-1px);
    }

    .poligonium-portfolio-list {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 58px 24px;
    }

    .poligonium-portfolio-card {
        min-width: 0;
        animation: poligoniumPortfolioCardIn .64s cubic-bezier(.2, .8, .2, 1) both;
        animation-delay: calc(var(--portfolio-card-index, 0) * 90ms);
    }

    .poligonium-portfolio-card__inner {
        position: relative;
        display: block;
        overflow: visible;
        min-height: 420px;
        padding: 42px 18px 15px;
        border: 1px solid var(--portfolio-line-soft);
        border-radius: 8px;
        background:
            radial-gradient(circle at 92% 8%, rgba(110, 78, 242, .09), transparent 30%),
            radial-gradient(circle at 14% 86%, rgba(20, 184, 166, .08), transparent 31%),
            linear-gradient(rgba(255, 255, 255, .78), rgba(255, 255, 255, .7)),
            repeating-linear-gradient(0deg, transparent 0 20px, rgba(32, 36, 42, .055) 20px 21px),
            repeating-linear-gradient(90deg, transparent 0 20px, rgba(32, 36, 42, .055) 20px 21px);
        box-shadow: 0 16px 36px rgba(32, 36, 42, .14);
        color: inherit;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: transform .24s ease, border-color .24s ease, box-shadow .24s ease;
    }

    .poligonium-portfolio-card__inner:hover {
        border-color: rgba(110, 78, 242, .22);
        transform: translateY(-7px);
        box-shadow: 0 24px 54px rgba(32, 36, 42, .18);
    }

    .poligonium-portfolio-spark {
        position: absolute;
        left: var(--spark-x);
        top: var(--spark-y);
        z-index: 20;
        width: var(--spark-size);
        height: var(--spark-size);
        color: #f49a21;
        pointer-events: none;
        transform: translate(-50%, -50%) scale(.4) rotate(0deg);
        animation: poligoniumPortfolioSpark var(--spark-duration) ease-out var(--spark-delay) forwards;
        filter: drop-shadow(0 0 8px rgba(244, 154, 33, .72));
    }

    .poligonium-portfolio-spark::before,
    .poligonium-portfolio-spark::after {
        position: absolute;
        inset: 0;
        content: "";
        background: currentColor;
        clip-path: polygon(50% 0, 61% 38%, 100% 50%, 61% 62%, 50% 100%, 39% 62%, 0 50%, 39% 38%);
    }

    .poligonium-portfolio-spark::after {
        background: #fff4cf;
        transform: scale(.42);
        opacity: .9;
    }

    .poligonium-portfolio-card__corners {
        position: absolute;
        inset: 15px;
        border: 1px solid var(--portfolio-line);
        border-radius: 4px;
        pointer-events: none;
    }

    .poligonium-portfolio-card__corners::before {
        content: "";
        position: absolute;
        inset: -1px;
        background:
            linear-gradient(var(--portfolio-line), var(--portfolio-line)) left top / 24px 1px no-repeat,
            linear-gradient(var(--portfolio-line), var(--portfolio-line)) left top / 1px 24px no-repeat,
            linear-gradient(var(--portfolio-line), var(--portfolio-line)) right top / 24px 1px no-repeat,
            linear-gradient(var(--portfolio-line), var(--portfolio-line)) right top / 1px 24px no-repeat,
            linear-gradient(var(--portfolio-line), var(--portfolio-line)) left bottom / 24px 1px no-repeat,
            linear-gradient(var(--portfolio-line), var(--portfolio-line)) left bottom / 1px 24px no-repeat,
            linear-gradient(var(--portfolio-line), var(--portfolio-line)) right bottom / 24px 1px no-repeat,
            linear-gradient(var(--portfolio-line), var(--portfolio-line)) right bottom / 1px 24px no-repeat;
    }

    .poligonium-portfolio-card__portrait {
        position: absolute;
        right: 0;
        top: -54px;
        z-index: 2;
        width: min(190px, 48%);
        aspect-ratio: 1 / 1;
        display: grid;
        place-items: end center;
        pointer-events: none;
    }

    .poligonium-portfolio-card__portrait img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        filter:
            drop-shadow(0 8px 0 #fff)
            drop-shadow(8px 0 0 #fff)
            drop-shadow(-8px 0 0 #fff)
            drop-shadow(0 -8px 0 #fff)
            drop-shadow(0 12px 18px rgba(32, 36, 42, .24));
        transition: transform .28s ease;
    }

    .poligonium-portfolio-card__inner:hover .poligonium-portfolio-card__portrait img {
        transform: translateY(-4px) rotate(-1deg);
    }

    .poligonium-portfolio-card__inner.has-no-portrait {
        padding-top: 28px;
    }

    .poligonium-portfolio-card__inner.has-no-portrait .poligonium-portfolio-card__content {
        padding-top: 38px;
    }

    .poligonium-portfolio-card__inner.has-portrait .poligonium-portfolio-card__content {
        padding-top: 128px;
    }

    .poligonium-portfolio-card__label {
        position: relative;
        z-index: 4;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 34px;
        padding: 0 13px;
        border: 1px solid var(--portfolio-line);
        background: rgba(255, 255, 255, .62);
        color: #32363c;
        font-weight: 900;
        clip-path: polygon(8% 0, 92% 0, 100% 50%, 92% 100%, 8% 100%, 0 50%);
        transition: border-color .22s ease, box-shadow .22s ease, transform .22s ease;
    }

    .poligonium-portfolio-card__inner:hover .poligonium-portfolio-card__label {
        border-color: rgba(110, 78, 242, .32);
        box-shadow: 0 10px 22px rgba(17, 24, 39, .08);
        transform: translateY(-2px);
    }

    .poligonium-portfolio-card__content {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(118px, .62fr);
        gap: 14px;
        align-items: end;
        min-height: 262px;
        padding-top: 105px;
    }

    html[lang="uk"] .poligonium-portfolio-card__copy h3,
    .poligonium-portfolio-card__copy h3 {
        margin: 0;
        color: #20242a;
        font-family: 'Unbounded', 'Noto Sans', Arial, sans-serif;
        font-size: clamp(22px, 2.2vw, 35px);
        font-weight: 800;
        line-height: 1.05;
        letter-spacing: 0;
        text-transform: none;
    }

    .poligonium-portfolio-card__copy strong {
        display: block;
        margin-top: 8px;
        color: #9a7947;
        font-size: 13px;
        letter-spacing: .08em;
    }

    .poligonium-portfolio-card__copy p {
        max-width: 100%;
        margin: 17px 0 0;
        padding-top: 14px;
        border-top: 1px solid var(--portfolio-line-soft);
        color: #3e444c;
        font-size: 13px;
        line-height: 1.45;
    }

    .poligonium-portfolio-card__tools {
        display: grid;
        gap: 7px;
    }

    .poligonium-portfolio-card__tools > div {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 2px;
        color: #32363c;
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .poligonium-portfolio-tool {
        position: relative;
        display: flex;
        align-items: center;
        gap: 8px;
        min-height: 34px;
        padding: 0 10px;
        border: 1px solid var(--portfolio-line);
        background: rgba(255, 255, 255, .72);
        box-shadow: 0 5px 12px rgba(32, 36, 42, .08);
        color: #32363c;
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
        clip-path: polygon(0 0, 100% 0, 94% 50%, 100% 100%, 0 100%);
        transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
    }

    .poligonium-portfolio-card__inner:hover .poligonium-portfolio-tool {
        animation: poligoniumPortfolioToolFloat 3.4s ease-in-out infinite;
    }

    .poligonium-portfolio-card__inner:hover .poligonium-portfolio-tool:nth-child(3) {
        animation-delay: .18s;
    }

    .poligonium-portfolio-card__inner:hover .poligonium-portfolio-tool:nth-child(4) {
        animation-delay: .36s;
    }

    .poligonium-portfolio-tool b,
    .poligonium-portfolio-tool img {
        display: grid;
        place-items: center;
        width: 24px;
        height: 24px;
        flex: 0 0 24px;
    }

    .poligonium-portfolio-tool b {
        border-radius: 4px;
        background: #20242a;
        color: #f49a21;
        font-size: 10px;
    }

    .poligonium-portfolio-tool img {
        object-fit: contain;
    }

    .poligonium-portfolio-card__meta {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: minmax(92px, .52fr) minmax(0, 1fr) 40px;
        gap: 10px;
        align-items: center;
        margin-top: 15px;
        padding: 10px 12px;
        border: 1px solid var(--portfolio-line);
        border-radius: 4px;
        background: rgba(255, 255, 255, .38);
    }

    .poligonium-portfolio-card__meta span {
        display: grid;
        grid-template-columns: 22px 1fr;
        column-gap: 7px;
        align-items: center;
        color: #20242a;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .poligonium-portfolio-card__meta i {
        grid-row: span 2;
        font-size: 20px;
    }

    .poligonium-portfolio-card__meta small {
        color: #535a63;
        font-size: 9px;
        letter-spacing: .08em;
    }

    .poligonium-portfolio-card__meta em {
        justify-self: end;
        color: #20242a;
        font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
        font-size: 20px;
        font-style: normal;
    }

    .poligonium-portfolio-board__bottom {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: center;
        margin-top: 54px;
    }

    @keyframes poligoniumPortfolioGrid {
        to {
            background-position: 36px 36px, 36px 36px, 0 0;
        }
    }

    @keyframes poligoniumPortfolioOrbit {
        to {
            transform: rotate(360deg);
        }
    }

    @keyframes poligoniumPortfolioCardIn {
        from {
            opacity: 0;
            transform: translateY(18px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes poligoniumPortfolioToolFloat {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-3px);
        }
    }

    @keyframes poligoniumPortfolioSpark {
        0% {
            opacity: 0;
            transform: translate(-50%, -50%) scale(.35) rotate(0deg);
        }
        14% {
            opacity: 1;
        }
        100% {
            opacity: 0;
            transform: translate(calc(-50% + var(--spark-drift-x)), calc(-50% - var(--spark-lift-y))) scale(1.18) rotate(155deg);
        }
    }

    @media (max-width: 1199px) {
        .poligonium-portfolio-list {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .poligonium-portfolio-board__head {
            grid-template-columns: 1fr;
        }

        .poligonium-portfolio-filters {
            justify-content: flex-start;
        }
    }

    @media (max-width: 767px) {
        .poligonium-portfolio-wrap {
            width: min(100% - 24px, 1320px);
            padding-top: 10px;
            padding-bottom: 40px;
        }

        .pb-150.container:has(> .ck-content > .poligonium-portfolio-board) {
            padding-bottom: 42px !important;
        }

        .poligonium-portfolio-board__orbit {
            display: none;
        }

        .poligonium-portfolio-board__head {
            gap: 12px;
            margin-bottom: 18px;
            padding: 10px 0;
        }

        .poligonium-portfolio-board__title span {
            font-size: clamp(30px, 11vw, 44px);
        }

        .poligonium-portfolio-filters {
            gap: 8px;
        }

        .poligonium-portfolio-filters button {
            min-height: 34px;
            padding: 7px 10px;
            font-size: 12px;
        }

        .poligonium-portfolio-list {
            gap: 34px;
        }

        .poligonium-portfolio-card__inner {
            min-height: 0;
            padding: 38px 14px 14px;
        }

        .poligonium-portfolio-card__portrait {
            right: -2px;
            top: -34px;
            width: min(140px, 46%);
        }

        .poligonium-portfolio-card__corners {
            inset: 12px;
        }

        .poligonium-portfolio-card__content,
        .poligonium-portfolio-card__meta {
            grid-template-columns: 1fr;
        }

        .poligonium-portfolio-card__content {
            min-height: 0;
            gap: 12px;
            padding-top: 96px;
        }

        .poligonium-portfolio-card__copy h3 {
            font-size: 27px;
        }

        .poligonium-portfolio-card__copy p {
            margin-top: 11px;
            padding-top: 10px;
            font-size: 14px;
            line-height: 1.38;
        }

        .poligonium-portfolio-card__tools {
            gap: 6px;
        }

        .poligonium-portfolio-tool {
            min-height: 30px;
            padding: 0 9px;
            font-size: 10px;
        }

        .poligonium-portfolio-tool b,
        .poligonium-portfolio-tool img {
            width: 22px;
            height: 22px;
            flex-basis: 22px;
        }

        .poligonium-portfolio-card__meta {
            gap: 7px;
            margin-top: 12px;
            padding: 8px 10px;
        }

        .poligonium-portfolio-card__meta span {
            grid-template-columns: 20px 1fr;
            font-size: 11px;
        }

        .poligonium-portfolio-card__meta i {
            font-size: 18px;
        }

        .poligonium-portfolio-board__bottom {
            margin-top: 30px;
        }
    }

    @media (max-width: 575.98px) {
        .poligonium-portfolio-list {
            grid-template-columns: 1fr;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .poligonium-portfolio-board,
        .poligonium-portfolio-board__orbit,
        .poligonium-portfolio-card,
        .poligonium-portfolio-card__inner:hover .poligonium-portfolio-tool,
        .poligonium-portfolio-spark {
            animation: none;
        }

        .poligonium-portfolio-card__inner,
        .poligonium-portfolio-filters button,
        .poligonium-portfolio-board__bottom a {
            transition: none;
        }
    }
</style>

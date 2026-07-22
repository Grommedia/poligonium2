@php
    $formatMoney = function ($amount, $currency = 'UAH') {
        $amount = number_format((float) $amount, 0, '.', ' ');

        return $currency === 'USD' ? '$' . $amount : $amount . ' ' . $currency;
    };
@endphp

<section class="poligonium-campaigns-page">
    <div class="poligonium-campaigns-grid-bg" aria-hidden="true"></div>

    <div class="poligonium-campaigns-wrap">
        <header class="poligonium-campaigns-hero">
            <div>
                <p class="poligonium-campaigns-kicker">Polygonium Originals</p>
                <h1>Підтримати українську анімацію</h1>
                <p>Авторські мультфільми, VFX-проєкти та 3D-історії, які можна створювати разом з глядачами: прозорий бюджет, етапи виробництва і пакети підтримки для тих, хто допомагає проєкту ожити.</p>
            </div>
            <div class="poligonium-campaigns-hero-object" aria-hidden="true">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </header>

        <div class="poligonium-campaigns-list">
            @forelse ($campaigns as $campaign)
                <article class="poligonium-campaign-card">
                    <a class="poligonium-campaign-card-media" href="{{ route('campaigns.public.show', $campaign->slug) }}">
                        @if ($campaign->image)
                            <img src="{{ RvMedia::getImageUrl($campaign->image) }}" alt="{{ $campaign->name }}">
                        @else
                            <span>{{ $campaign->stage_label }}</span>
                        @endif
                    </a>
                    <div class="poligonium-campaign-card-body">
                        <div class="poligonium-campaign-card-meta">
                            <span>{{ $campaign->state_label }}</span>
                            <span>{{ $campaign->stage_label }}</span>
                        </div>
                        <h2><a href="{{ route('campaigns.public.show', $campaign->slug) }}">{{ $campaign->name }}</a></h2>
                        @if ($campaign->subtitle)
                            <p class="poligonium-campaign-card-subtitle">{{ $campaign->subtitle }}</p>
                        @endif
                        <p>{{ $campaign->description }}</p>
                        <div class="poligonium-campaign-progress">
                            <div>
                                <strong>{{ $formatMoney($campaign->collected_amount, $campaign->currency) }}</strong>
                                <span>зібрано з {{ $formatMoney($campaign->target_amount, $campaign->currency) }}</span>
                            </div>
                            <b>{{ $campaign->progress_percent }}%</b>
                            <i style="--progress: {{ $campaign->progress_percent }}%"></i>
                        </div>
                        <a class="poligonium-campaign-link" href="{{ route('campaigns.public.show', $campaign->slug) }}">Деталі проєкту</a>
                    </div>
                </article>
            @empty
                <div class="poligonium-campaign-empty">
                    <h2>Проєкти готуються</h2>
                    <p>Розділ уже підключений до адмін-панелі. Додайте перший мультфільм або VFX-проєкт у розділі “Проєкти підтримки”.</p>
                </div>
            @endforelse
        </div>

        {{ $campaigns->links() }}
    </div>
</section>

@include('theme.zelio::views.campaigns.partials.styles')

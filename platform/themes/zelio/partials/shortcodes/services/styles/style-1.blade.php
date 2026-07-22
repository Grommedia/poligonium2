<section class="section-service-1 poligonium-service-lab">
    <div class="container">
        <div class="poligonium-animated-panel">
            <div class="poligonium-orbit poligonium-service-orbit"></div>
            <div class="poligonium-floating-cube cube-a"></div>
            <div class="poligonium-floating-cube cube-b"></div>

            <div class="position-relative z-1">
                <div class="row align-items-end mb-5">
                    @if($shortcode->title || $shortcode->subtitle)
                        <div class="col-lg-8 me-auto">
                            @if($shortcode->subtitle)
                                <span class="poligonium-section-kicker">
                                    {!! BaseHelper::clean(nl2br($shortcode->subtitle)) !!}
                                </span>
                            @endif
                            @if($shortcode->title)
                                <h3 class="ds-3 mt-3 mb-3 text-dark">{!! BaseHelper::clean($shortcode->title) !!}</h3>
                            @endif
                        </div>
                    @endif
                    @if($shortcode->action_text)
                        <div class="col-lg-auto">
                            <a href="{{ $shortcode->action_link }}" class="btn btn-gradient mt-lg-0 mt-4 ms-lg-auto">
                                {{ $shortcode->action_text }}
                                <i class="ri-arrow-right-up-line"></i>
                            </a>
                        </div>
                    @endif
                </div>

                <div class="poligonium-service-grid">
                    @foreach($services as $service)
                        <article
                            class="poligonium-service-card @if ($shortcode->show_image_on_hover != 'no') tg-img-reveal-item @endif"
                            data-fx="1"
                            @if ($service->image && ($shortcode->show_image_on_hover != 'no')) data-img="{{ RvMedia::getImageUrl($service->image) }}" @endif
                        >
                            <div>
                                <span class="poligonium-service-number">{{ Str::padLeft($loop->iteration, 2, 0) }}</span>
                                <h3 class="poligonium-service-title">
                                    <a href="{{ $service->url }}">
                                        {{ $service->name }}
                                    </a>
                                </h3>
                                @if ($service->description)
                                    <p class="poligonium-service-text mb-0">
                                        {!! BaseHelper::clean(nl2br($service->description)) !!}
                                    </p>
                                @endif
                            </div>
                            <a href="{{ $service->url }}" class="poligonium-service-link" aria-label="{{ $service->name }}">
                                <i class="ri-arrow-right-up-line"></i>
                            </a>
                        </article>
                    @endforeach
                </div>

                @if($shortcode->bottom_text)
                    <div class="poligonium-service-note">
                        <div class="fs-5 fw-bold text-dark">
                            {!! BaseHelper::clean($shortcode->bottom_text) !!}
                        </div>
                        <a href="/contact" class="btn btn-outline-secondary d-inline-flex align-items-center">
                            <span>{{ __('Get in touch') }}</span>
                            <i class="ri-arrow-right-up-line ms-2"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

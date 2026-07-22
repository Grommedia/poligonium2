<section
    id="resume"
    class="section-resume-1 poligonium-resume-lab position-relative overflow-hidden"
    @if($shortcode->background_image)
        data-background="{{ RvMedia::getImageUrl($shortcode->background_image) }}"
    @endif
>
    <div class="container">
        <div class="poligonium-animated-panel">
            <div class="poligonium-orbit poligonium-resume-orbit"></div>
            <div class="poligonium-floating-cube cube-a"></div>
            <div class="poligonium-floating-cube cube-b"></div>

            <div class="position-relative z-1">
                @if($shortcode->title || $shortcode->subtitle || $shortcode->action_text)
                    <div class="row align-items-end mb-5">
                        @if($shortcode->title || $shortcode->subtitle)
                            <div class="col-lg-7 me-auto">
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
                                    {!! BaseHelper::clean($shortcode->action_text) !!}
                                    <i class="ri-arrow-right-up-line"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                <div @class(['row row-cols-1 g-4', 'row-cols-lg-1' => count($resumes) === 1, 'row-cols-lg-2' => count($resumes) > 1])>
                    @foreach($resumes as $resume)
                        @php
                            $iconKey = "resume_{$loop->iteration}_title_icon";
                            $titleKey = "resume_{$loop->iteration}_title";
                        @endphp
                        <div class="col">
                            <div class="poligonium-resume-card">
                                <div class="poligonium-resume-head">
                                    <span class="poligonium-resume-icon">
                                        <x-core::icon :name="$shortcode->{$iconKey}" />
                                    </span>
                                    <h3>
                                        {!! BaseHelper::clean($shortcode->{$titleKey}) !!}
                                    </h3>
                                </div>
                                <div class="poligonium-timeline">
                                    @foreach($resume as $item)
                                        <article class="poligonium-timeline-item">
                                            @if($item['time'])
                                                <div class="poligonium-timeline-time">{{ $item['time'] }}</div>
                                            @endif
                                            @if($item['title'])
                                                <h5 class="poligonium-timeline-title">{{ $item['title'] }}</h5>
                                            @endif
                                            @if($item['description'])
                                                <p class="text-300 mb-2">{{ $item['description'] }}</p>
                                            @endif
                                            @if($item['subtitle'])
                                                <span class="poligonium-timeline-subtitle">{{ $item['subtitle'] }}</span>
                                            @endif
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($shortcode->bottom_text)
                    <div class="scroll-move-right position-relative pt-80">
                        <div class="d-flex align-items-center gap-5 wow img-custom-anim-top position-absolute top-50 start-50 translate-middle">
                            <h3 class="stroke fs-150 text-uppercase text-white">{!! BaseHelper::clean($shortcode->bottom_text) !!}</h3>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

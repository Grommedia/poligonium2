<section id="portfolio" class="section-experience poligonium-experience-lab">
    <div class="container">
        <div class="poligonium-animated-panel">
            <div class="poligonium-orbit poligonium-experience-orbit"></div>
            <div class="poligonium-floating-cube cube-a"></div>
            <div class="poligonium-floating-cube cube-b"></div>

            <div class="position-relative z-1">
                <div class="poligonium-experience-inner">
                    @if($shortcode->subtitle)
                        <span class="poligonium-section-kicker">
                            {!! BaseHelper::clean($shortcode->subtitle) !!}
                        </span>
                    @endif
                    @if($shortcode->title)
                        <h3 class="poligonium-experience-title">{!! BaseHelper::clean($shortcode->title) !!}</h3>
                    @endif
                    <div class="row g-4 mt-4 align-items-stretch">
                        @if($experiences)
                            <div class="col-lg-4">
                                <div class="poligonium-experience-stack">
                                    @foreach($experiences as $experience)
                                        <article class="poligonium-experience-chip">
                                            <div class="d-flex align-items-center gap-3">
                                                {{ RvMedia::image($experience['logo'], $experience['title']) }}
                                                <div class="d-flex flex-column">
                                                    <h5 class="mb-1">{{ $experience['title'] }}</h5>
                                                    <span>{{ $experience['date'] }}</span>
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div @class(['col-lg-8' => $experiences, 'col-lg-12' => ! $experiences])>
                            <div class="poligonium-experience-role">
                            <h6>{!! BaseHelper::clean($shortcode->role_title) !!}</h6>
                            @php
                                $descriptionItems = array_filter(explode("\n", $shortcode->role_description));
                            @endphp
                            @if($descriptionItems)
                                <ul>
                                    @foreach($descriptionItems as $descriptionItem)
                                        <li>{!! BaseHelper::clean($descriptionItem) !!}</li>
                                    @endforeach
                                </ul>
                            @endif
                            @if($skills)
                                <div class="poligonium-experience-skills">
                                    @foreach($skills as $skill)
                                        <span>{{ $skill['name'] }}</span>
                                    @endforeach
                                </div>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-projects-2 pt-5">
    <div class="container">
        <div class="rounded-3 border border-1 position-relative overflow-hidden">
            <div class="box-linear-animation position-relative z-1">
                <div class="p-lg-8 p-md-6 p-3 position-relative z-1">
                    @if($shortcode->subtitle)
                        <div class="d-flex align-items-center">
                            <svg class="text-primary-2 me-2" xmlns="http://www.w3.org/2000/svg" width="5" height="6" viewBox="0 0 5 6" fill="none">
                                <circle cx="2.5" cy="3" r="2.5" fill="var(--primary-color)" />
                            </svg>
                            <span class="text-linear-4 d-flex align-items-center">{!! BaseHelper::clean($shortcode->subtitle) !!}</span>
                        </div>
                    @endif
                    @if($shortcode->title)
                        <h3>{!! BaseHelper::clean($shortcode->title) !!}</h3>
                    @endif
                    <div class="position-relative">
                        <div class="swiper slider-two pb-3 position-relative">
                            <div class="swiper-wrapper">
                                @foreach($projects->loadMissing('metadata') as $project)
                                    <div class="swiper-slide">
                                        <div class="p-lg-5 p-md-4 p-3 border border-1 mt-5 bg-3">
                                            <div class="row">
                                                @if ($project->image)
                                                    <div class="col-lg-5">
                                                        {{ RvMedia::image($project->image, $project->name, attributes: ['class' => 'w-100']) }}
                                                    </div>
                                                @endif
                                                <div @class(['ps-lg-5 mt-5 mt-lg-0', 'col-lg-7' => $project->image, 'col-lg-12' => ! $project->image])>
                                                    <h4 class="text-linear-4">
                                                        <a href="{{ $project->url }}">{!! BaseHelper::clean($project->name) !!}</a>
                                                    </h4>
                                                    @if($project->description)
                                                        <p>{!! BaseHelper::clean(Str::limit($project->description)) !!}</p>
                                                    @endif
                                                    <ul class="mt-4 list-unstyled">
                                                        <li class="text-secondary-2 mb-3 border-bottom pb-3">{{ __('Project Info') }}</li>
                                                        @if($project->categories->isNotEmpty())
                                                            <li class="text-dark mb-3 border-bottom pb-3">
                                                                <div class="d-flex justify-content-between">
                                                                    <p class="text-dark mb-0 text-end">{{ __('Category') }}</p>
                                                                    <p class="text-300 mb-0 text-end">{{ $project->categories->map(fn ($item) => $item->name)->join(', ') }}</p>
                                                                </div>
                                                            </li>
                                                        @endif
                                                        @if ($project->client)
                                                            <li class="text-dark mb-3 border-bottom pb-3">
                                                                <div class="d-flex justify-content-between">
                                                                    <p class="text-dark mb-0 text-end">{{ __('Client') }}</p>
                                                                    <p class="text-300 mb-0 text-end">{{ $project->client }}</p>
                                                                </div>
                                                            </li>
                                                        @endif
                                                        @if ($project->start_date)
                                                            <li class="text-dark mb-3 border-bottom pb-3">
                                                                <div class="d-flex justify-content-between">
                                                                    <p class="text-dark mb-0 text-end">{{ __('Start Date') }}</p>
                                                                    <p class="text-300 mb-0 text-end">{{ Theme::formatDate($project->start_date) }}</p>
                                                                </div>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                    <div class="d-flex flex-wrap align-items-center gap-3 mt-7">
                                                        <a href="{{ $project->url }}" class="text-300 border-bottom border-1 px-2 pb-2 link-hover">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
                                                                <path d="M11.0037 3.91421L2.39712 12.5208L0.98291 11.1066L9.5895 2.5H2.00373V0.5H13.0037V11.5H11.0037V3.91421Z" fill="#8F8F92" />
                                                            </svg>
                                                            {{ __('Відкрити проєкт') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @if($projects->count() > 1)
                            <div class="position-absolute bottom-0 end-0 gap-2 pb-7 pe-5 d-none d-md-flex">
                                <div class="swiper-button-prev end-0 shadow position-relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M7.82843 10.9999H20V12.9999H7.82843L13.1924 18.3638L11.7782 19.778L4 11.9999L11.7782 4.22168L13.1924 5.63589L7.82843 10.9999Z" fill="white" />
                                    </svg>
                                </div>
                                <div class="swiper-button-next end-0 shadow position-relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z" fill="var(--primary-color)" />
                                    </svg>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @if($shortcode->background_image)
                    {{ RvMedia::image($shortcode->background_image, $shortcode->title, attributes: ['class' => 'position-absolute top-0 start-0 z-0']) }}
                @endif
            </div>
        </div>
    </div>
</section>

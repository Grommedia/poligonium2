@php
    $footerStyle = get_footer_style();
@endphp

@if($footerStyle != 3)
    <a class="d-flex main-logo align-items-center d-inline-flex" href="{{ BaseHelper::getHomepageUrl() }}">
        {{ Theme::getLogoImage(logoKey: 'logo_dark', maxHeight: 40) }}
        @if($siteName = theme_option('site_name'))
            <span class="fs-4 ms-2 site-name-text poligonium-brand-wordmark text-white-keep">{{ $siteName }}</span>
        @endif
    </a>
@else
    <a class="d-flex main-logo align-items-center justify-content-center ms-lg-0 ms-md-5 ms-3" href="{{ BaseHelper::getHomepageUrl() }}">
        @if($siteName = theme_option('site_name'))
            <span class="fs-4 mb-0 me-2 site-name-text poligonium-brand-wordmark">{{ $siteName }}</span>
        @endif
        {{ Theme::getLogoImage(logoKey: 'logo_dark', maxHeight: 32) }}
    </a>
@endif

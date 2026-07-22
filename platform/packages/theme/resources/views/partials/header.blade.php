{!! SeoHelper::render() !!}

@php
    $favicon = theme_option('favicon');
    $faviconUrl = $favicon ? RvMedia::getImageUrl($favicon) : asset('favicon.png');
    $faviconMimeType = 'image/png';

    if ($favicon) {
        $faviconPath = RvMedia::getRealPath($favicon);
        $faviconMimeType = $faviconPath && File::exists($faviconPath)
            ? rescue(fn () => File::mimeType($faviconPath), 'image/png', report: false)
            : 'image/png';
    }
@endphp

<link rel="icon" href="{{ $faviconUrl }}" type="{{ $faviconMimeType }}">
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

@if (Theme::has('headerMeta'))
    {!! Theme::get('headerMeta') !!}
@endif

{!! apply_filters('theme_front_meta', null) !!}

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "{{ rescue(fn() => SeoHelper::openGraph()->getProperty('site_name')) }}",
  "url": "{{ url('') }}"
}
</script>

{!! Theme::typography()->renderCssVariables() !!}

{!! Theme::asset()->container('before_header')->styles() !!}
{!! Theme::asset()->styles() !!}
{!! Theme::asset()->container('after_header')->styles() !!}
{!! Theme::asset()->container('header')->scripts() !!}

{!! apply_filters(THEME_FRONT_HEADER, null) !!}

<script>
    window.siteUrl = "{{ rescue(fn() => BaseHelper::getHomepageUrl()) }}";
</script>

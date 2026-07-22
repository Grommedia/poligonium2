{!! SeoHelper::render() !!}

@if ($favicon = theme_option('favicon'))
    @php
        $faviconPath = RvMedia::getRealPath($favicon);
        $faviconMimeType = $faviconPath && File::exists($faviconPath)
            ? rescue(fn () => File::mimeType($faviconPath), 'image/x-icon', report: false)
            : 'image/x-icon';
    @endphp

    {{ Html::favicon(RvMedia::getImageUrl($favicon), ['type' => $faviconMimeType]) }}
@endif

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

@php
    $adminLocales = [
        'uk' => 'UK',
    ];

    $currentLocale = app()->getLocale();
@endphp

<div class="nav-item dropdown me-2">
    <a
        href="#"
        class="px-2 nav-link dropdown-toggle"
        data-bs-toggle="dropdown"
        title="{{ trans('core/base::layouts.admin_language') }}"
        aria-label="{{ trans('core/base::layouts.admin_language') }}"
    >
        {{ $adminLocales[$currentLocale] ?? strtoupper($currentLocale) }}
    </a>

    <div class="dropdown-menu dropdown-menu-end">
        @foreach ($adminLocales as $locale => $label)
            <a
                href="{{ route('admin-locale.switch.path', ['admin_locale' => $locale]) }}"
                @class(['dropdown-item', 'active' => $currentLocale === $locale])
            >
                {{ $label }} - {!! match ($locale) {
                    'uk' => '&#1059;&#1082;&#1088;&#1072;&#1111;&#1085;&#1089;&#1100;&#1082;&#1072;',
                    default => '&#1059;&#1082;&#1088;&#1072;&#1111;&#1085;&#1089;&#1100;&#1082;&#1072;',
                } !!}
            </a>
        @endforeach
    </div>
</div>

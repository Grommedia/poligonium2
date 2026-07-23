@php
    $menuAttributes = (string) $options;
    $isRootMenu = str_contains($menuAttributes, 'navbar-nav') || str_contains($menuAttributes, 'mobile-menu');
    $isEnglish = request()->segment(1) === 'en';
    $academyEnabled = $isRootMenu && \Illuminate\Support\Facades\Route::has('academy.public.index');
    $academyActive = in_array(request()->segment($isEnglish ? 2 : 1), ['academy', 'courses', 'support'], true);
    $academyItems = [
        [
            'label' => $isEnglish ? '3D Modeling Academy' : 'Академія 3D-моделювання',
            'url' => $academyEnabled ? route('academy.public.index') : url('/academy'),
        ],
        [
            'label' => $isEnglish ? 'Courses' : 'Курси',
            'url' => \Illuminate\Support\Facades\Route::has('courses.public.index') ? route('courses.public.index') : url('/courses'),
        ],
        [
            'label' => $isEnglish ? 'Our projects' : 'Наші проєкти',
            'url' => \Illuminate\Support\Facades\Route::has('campaigns.public.index') ? route('campaigns.public.index') : url('/support'),
        ],
        [
            'label' => $isEnglish ? 'Articles' : 'Статті',
            'url' => $academyEnabled && \Illuminate\Support\Facades\Route::has('academy.public.articles') ? route('academy.public.articles') : url('/academy/articles'),
        ],
    ];
@endphp

<ul{!! BaseHelper::clean($options) !!}>
    @if($academyEnabled)
        <li @class(['nav-item has-children poligonium-academy-menu', 'active' => $academyActive])>
            <a @class(['nav-link', 'active' => $academyActive]) href="{{ route('academy.public.index') }}">
                {{ $isEnglish ? '3D Academy' : 'Академія 3D' }}
            </a>
            <ul class="sub-menu">
                @foreach($academyItems as $academyItem)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $academyItem['url'] }}">{{ $academyItem['label'] }}</a>
                    </li>
                @endforeach
            </ul>
        </li>
    @endif

    @foreach($menu_nodes as $key => $row)
        @php
            $rowPath = parse_url($row->url, PHP_URL_PATH) ?: $row->url;
            $rowPath = '/' . trim(preg_replace('#^/(en|uk|ru)(?=/|$)#', '', $rowPath), '/');
            $skipTopAcademyDuplicate = $academyEnabled && in_array($rowPath, ['/courses', '/support', '/academy', '/academy/articles'], true);
        @endphp

        @continue($skipTopAcademyDuplicate)

        <li @class(['nav-item', 'has-children' => $row->has_child])>
            <a @class(['nav-link', 'active' => $row->active, $row->css_class]) href="{{ $row->url }}" target="{{ $row->target }}">
                {!! $row->icon_html !!}
                {{ $row->title }}
            </a>

            @if($row->has_child)
                {!! Menu::renderMenuLocation('main-menu', [
                    'view' => 'main-menu',
                    'menu' => $menu,
                    'menu_nodes' => $row->child,
                    'options' => ['class' => 'sub-menu'],
                ]) !!}
            @endif
        </li>
    @endforeach
</ul>

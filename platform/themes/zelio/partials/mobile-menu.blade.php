@php
    $isEnglish = request()->segment(1) === 'en';
    $academyEnabled = \Illuminate\Support\Facades\Route::has('academy.public.index');
    $academyActive = in_array(request()->segment($isEnglish ? 2 : 1), ['academy', 'courses', 'support'], true);
    $academyRendered = false;
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
    @foreach($menu_nodes as $key => $row)
        @php
            $rowUrl = (string) $row->url;
            $rowPath = parse_url($row->url, PHP_URL_PATH) ?: $row->url;
            $rowPath = '/' . trim(preg_replace('#^/(en|uk|ru)(?=/|$)#', '', $rowPath), '/');
            $rowTitle = mb_strtolower(trim((string) $row->title));
            $isContactRow = str_contains($rowUrl, '#contact') || str_contains($rowTitle, 'контакт') || str_contains($rowTitle, 'contact');
            $skipTopAcademyDuplicate = $academyEnabled && in_array($rowPath, ['/courses', '/support', '/academy', '/academy/articles'], true);
        @endphp

        @continue($skipTopAcademyDuplicate)

        @if($academyEnabled && ! $academyRendered && $isContactRow)
            <li @class(['nav-item has-children poligonium-academy-menu', 'active' => $academyActive])>
                <a class="nav-link" href="{{ route('academy.public.index') }}">
                    {{ $isEnglish ? '3D Academy' : 'Академія 3D' }}
                </a>
                <span class="menu-expand"><i class="arrow-small-down"></i></span>
                <ul class="sub-menu">
                    @foreach($academyItems as $academyItem)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ $academyItem['url'] }}">{{ $academyItem['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>
            @php $academyRendered = true; @endphp
        @endif

        <li @class(['nav-item', 'has-children' => $row->has_child, 'active' => $row->active])>
            <a @class(['nav-link', $row->css_class]) href="{{ $row->url }}" target="{{ $row->target }}">
                {!! BaseHelper::clean($row->icon_html) !!}
                {{ $row->title }}
            </a>

            @if($row->has_child)
                <span class="menu-expand"><i class="arrow-small-down"></i></span>

                {!! Menu::renderMenuLocation('main-menu', [
                    'view' => 'main-menu',
                    'menu' => $menu,
                    'menu_nodes' => $row->child,
                    'options' => ['class' => 'sub-menu'],
                ]) !!}
            @endif
        </li>
    @endforeach

    @if($academyEnabled && ! $academyRendered)
        <li @class(['nav-item has-children poligonium-academy-menu', 'active' => $academyActive])>
            <a class="nav-link" href="{{ route('academy.public.index') }}">
                {{ $isEnglish ? '3D Academy' : 'Академія 3D' }}
            </a>
            <span class="menu-expand"><i class="arrow-small-down"></i></span>
            <ul class="sub-menu">
                @foreach($academyItems as $academyItem)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $academyItem['url'] }}">{{ $academyItem['label'] }}</a>
                    </li>
                @endforeach
            </ul>
        </li>
    @endif
</ul>

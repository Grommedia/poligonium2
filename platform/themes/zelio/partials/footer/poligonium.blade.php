@php
    $isEnglish = request()->segment(1) === 'en';
    $prefix = $isEnglish ? '/en' : '';
    $homeUrl = $isEnglish ? url('/en') : BaseHelper::getHomepageUrl();
    $siteName = theme_option('site_name') ?: 'Poligonium.com';
    $year = now()->year;

    $copy = $isEnglish ? [
        'tagline' => '3D production, school, and original projects that turn ideas into living visual stories.',
        'cta_title' => 'Have a character, course, or animated promo in mind?',
        'cta_text' => 'Tell me what needs to be created, and I will help shape it into a clear production plan.',
        'cta_button' => 'Contact Poligonium',
        'navigation' => 'Navigation',
        'directions' => 'Directions',
        'contacts' => 'Contacts',
        'rights' => 'All rights reserved.',
        'made' => 'Created in Ukraine',
        'owner' => 'Owner',
        'phone' => 'Phone',
        'telegram' => 'Telegram',
        'location' => 'Location',
    ] : [
        'tagline' => '3D-виробництво, школа та авторські проєкти, які перетворюють ідеї на живу візуальну історію.',
        'cta_title' => 'Є персонаж, курс або рекламний ролик у роботі?',
        'cta_text' => 'Напишіть, що потрібно створити, і я допоможу скласти зрозумілий план виробництва.',
        'cta_button' => 'Зв’язатися з Poligonium',
        'navigation' => 'Навігація',
        'directions' => 'Напрями',
        'contacts' => 'Контакти',
        'rights' => 'Усі права захищено.',
        'made' => 'Створено в Україні',
        'owner' => 'Автор',
        'phone' => 'Телефон',
        'telegram' => 'Telegram',
        'location' => 'Локація',
    ];

    $navigation = $isEnglish ? [
        ['label' => 'Home', 'url' => $homeUrl],
        ['label' => 'Services', 'url' => $homeUrl . '#services'],
        ['label' => '3D Characters', 'url' => url($prefix . '/projects')],
        ['label' => 'VFX Showreel', 'url' => url($prefix . '/vfx-showreel')],
        ['label' => 'Courses', 'url' => url($prefix . '/courses')],
        ['label' => 'Our Projects', 'url' => route('campaigns.public.index')],
        ['label' => 'Contacts', 'url' => $homeUrl . '#contact'],
    ] : [
        ['label' => 'Головна', 'url' => $homeUrl],
        ['label' => 'Послуги', 'url' => $homeUrl . '#services'],
        ['label' => '3D персонажі', 'url' => url('/projects')],
        ['label' => 'VFX Showreel', 'url' => url('/vfx-showreel')],
        ['label' => 'Курси', 'url' => url('/courses')],
        ['label' => 'Наші проєкти', 'url' => route('campaigns.public.index')],
        ['label' => 'Контакти', 'url' => $homeUrl . '#contact'],
    ];

    $directionsFallback = $isEnglish ? [
        'Character modeling',
        'Rigging and animation',
        'Product commercials',
        'VFX and cinematics',
        'VR / Unreal Engine',
        'Poligonium School',
    ] : [
        '3D-моделювання персонажів',
        'Ригінг та анімація',
        'Рекламні ролики продуктів',
        'VFX та синематики',
        'VR / Unreal Engine',
        'Poligonium School',
    ];

    $footerOwner = theme_option('poligonium_footer_owner_' . ($isEnglish ? 'en' : 'uk')) ?: ($isEnglish ? 'Biletskyi Andrii' : 'Білецький Андрій');
    $footerPhone = theme_option('poligonium_footer_phone') ?: '+380-98-223-2974';
    $footerPhoneHref = preg_replace('/[^\d+]/', '', $footerPhone);
    $footerTelegram = theme_option('poligonium_footer_telegram') ?: '@BeleckiyAndrey3D';
    $footerTelegramUsername = ltrim($footerTelegram, '@');
    $footerLocation = theme_option('poligonium_footer_location_' . ($isEnglish ? 'en' : 'uk')) ?: ($isEnglish ? 'Kremenchuk / Kyiv, Ukraine' : 'Кременчук / Київ, Україна');
    $copy['rights'] = theme_option('poligonium_footer_rights_' . ($isEnglish ? 'en' : 'uk')) ?: $copy['rights'];
    $copy['made'] = theme_option('poligonium_footer_made_' . ($isEnglish ? 'en' : 'uk')) ?: $copy['made'];

    $directionsText = theme_option('poligonium_footer_directions_' . ($isEnglish ? 'en' : 'uk'));
    $directions = collect(preg_split('/\r\n|\r|\n/', $directionsText ?: implode("\n", $directionsFallback)))
        ->map(fn ($direction) => trim($direction))
        ->filter()
        ->values()
        ->all();
@endphp

<footer class="poligonium-site-footer" id="site-footer">
    <style>
        .poligonium-site-footer {
            position: relative;
            overflow: hidden;
            color: #171717;
            background:
                linear-gradient(rgba(22, 22, 22, .055) 1px, transparent 1px),
                linear-gradient(90deg, rgba(22, 22, 22, .055) 1px, transparent 1px),
                linear-gradient(135deg, rgba(255, 255, 255, .94), rgba(244, 241, 232, .88) 52%, rgba(231, 238, 242, .9));
            background-size: 24px 24px, 24px 24px, auto;
            border-top: 1px solid rgba(20, 20, 20, .12);
        }

        .poligonium-site-footer::before,
        .poligonium-site-footer::after {
            content: "";
            position: absolute;
            pointer-events: none;
            opacity: .7;
        }

        .poligonium-site-footer::before {
            inset: 0;
            background: linear-gradient(115deg, rgba(255, 255, 255, .65), transparent 42%, rgba(255, 255, 255, .36));
        }

        .poligonium-site-footer::after {
            top: 34px;
            right: max(24px, 5vw);
            width: 220px;
            height: 58px;
            border-top: 1px solid rgba(238, 112, 18, .38);
            border-bottom: 1px solid rgba(30, 102, 184, .28);
            transform: skewX(-18deg);
            animation: poligoniumFooterScan 7s ease-in-out infinite;
        }

        .poligonium-site-footer__wrap {
            position: relative;
            z-index: 1;
            width: min(100% - 40px, 1320px);
            margin: 0 auto;
            padding: 28px 0 16px;
        }

        .poligonium-site-footer__grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1.15fr;
            gap: 22px;
            padding: 0 0 20px;
            border-bottom: 1px solid rgba(20, 20, 20, .12);
        }

        .poligonium-site-footer__title {
            margin: 0 0 14px;
            color: #151515;
            font-size: .82rem;
            font-weight: 900;
            text-transform: uppercase;
        }

        .poligonium-site-footer address {
            margin: 0;
            font-style: normal;
        }

        .poligonium-site-footer__links,
        .poligonium-site-footer__directions,
        .poligonium-site-footer__contacts {
            display: grid;
            gap: 9px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .poligonium-site-footer__links a,
        .poligonium-site-footer__contacts a {
            color: rgba(23, 23, 23, .72);
            text-decoration: none;
            transition: color .18s ease, transform .18s ease;
        }

        .poligonium-site-footer__links a:hover,
        .poligonium-site-footer__contacts a:hover {
            color: #d95709;
            transform: translateX(4px);
        }

        .poligonium-site-footer__directions li,
        .poligonium-site-footer__contacts li {
            color: rgba(23, 23, 23, .72);
            line-height: 1.5;
        }

        .poligonium-site-footer__contact-label {
            display: block;
            color: rgba(23, 23, 23, .46);
            font-size: .76rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .poligonium-site-footer__bottom {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 12px 24px;
            padding-top: 16px;
            color: rgba(23, 23, 23, .62);
            font-size: .9rem;
        }

        .poligonium-site-footer__bottom strong {
            color: #151515;
            font-weight: 800;
        }

        @keyframes poligoniumFooterScan {
            0%, 100% {
                transform: translateX(0) skewX(-18deg);
            }
            50% {
                transform: translateX(-28px) skewX(-18deg);
            }
        }

        @media (max-width: 991px) {
            .poligonium-site-footer__grid {
                grid-template-columns: 1fr;
            }

            .poligonium-site-footer__wrap {
                width: min(100% - 28px, 760px);
                padding-top: 24px;
            }
        }

        @media (max-width: 575px) {
            .poligonium-site-footer__bottom {
                font-size: .82rem;
            }
        }
    </style>

    <div class="poligonium-site-footer__wrap">
        <div class="poligonium-site-footer__grid">
            <nav aria-label="{{ $copy['navigation'] }}">
                <h2 class="poligonium-site-footer__title">{{ $copy['navigation'] }}</h2>
                <ul class="poligonium-site-footer__links">
                    @foreach($navigation as $item)
                        <li><a href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
                    @endforeach
                </ul>
            </nav>

            <div>
                <h2 class="poligonium-site-footer__title">{{ $copy['directions'] }}</h2>
                <ul class="poligonium-site-footer__directions">
                    @foreach($directions as $direction)
                        <li>{{ $direction }}</li>
                    @endforeach
                </ul>
            </div>

            <address>
                <h2 class="poligonium-site-footer__title">{{ $copy['contacts'] }}</h2>
                <ul class="poligonium-site-footer__contacts">
                    <li>
                        <span class="poligonium-site-footer__contact-label">{{ $copy['owner'] }}</span>
                        <span>{{ $footerOwner }}</span>
                    </li>
                    <li>
                        <span class="poligonium-site-footer__contact-label">{{ $copy['phone'] }}</span>
                        <a href="tel:{{ $footerPhoneHref }}">{{ $footerPhone }}</a>
                    </li>
                    <li>
                        <span class="poligonium-site-footer__contact-label">{{ $copy['telegram'] }}</span>
                        <a href="https://t.me/{{ $footerTelegramUsername }}" target="_blank" rel="noopener">{{ $footerTelegram }}</a>
                    </li>
                    <li>
                        <span class="poligonium-site-footer__contact-label">{{ $copy['location'] }}</span>
                        <span>{{ $footerLocation }}</span>
                    </li>
                </ul>
            </address>
        </div>

        <div class="poligonium-site-footer__bottom">
            <span>© {{ $year }} <strong>{{ $siteName }}</strong>. {{ $copy['rights'] }}</span>
            <span>{{ $copy['made'] }}</span>
        </div>
    </div>
</footer>

<div class="btn-scroll-top style-btn-2">
    <svg class="progress-square svg-content" width="100%" height="100%" viewBox="0 0 40 40">
        <path d="M8 1H32C35.866 1 39 4.13401 39 8V32C39 35.866 35.866 39 32 39H8C4.13401 39 1 35.866 1 32V8C1 4.13401 4.13401 1 8 1Z" />
    </svg>
</div>

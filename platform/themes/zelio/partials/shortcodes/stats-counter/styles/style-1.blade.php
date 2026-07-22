@php
    $isEnglish = request()->segment(1) === 'en';

    $proof = $isEnglish ? [
        'title' => 'What Stands Behind Poligonium',
        'subtitle' => 'Not just numbers, but a clear production base: characters, animation, school, VFX, and original stories.',
        'items' => [
            [
                'icon' => 'ri-compasses-2-line',
                'title' => 'Deep 3D Practice',
                'text' => 'From CAD modeling to characters, VFX, advertising, and Unreal Engine production.',
            ],
            [
                'icon' => 'ri-briefcase-4-line',
                'title' => 'Commercial Production',
                'text' => 'Mascots, product videos, explain animations, process visualization, and business visuals.',
            ],
            [
                'icon' => 'ri-node-tree',
                'title' => 'Full Pipeline',
                'text' => 'Concept, sculpting, retopology, textures, rigging, animation, rendering, and compositing.',
            ],
            [
                'icon' => 'ri-movie-2-line',
                'title' => 'Characters for Stories',
                'text' => 'Stylized heroes for cartoons, advertising campaigns, cinematics, and brand communication.',
            ],
            [
                'icon' => 'ri-graduation-cap-line',
                'title' => 'Educational Direction',
                'text' => 'Author courses in Blender, Houdini, 3D modeling, rigging, animation, and VFX workflows.',
            ],
            [
                'icon' => 'ri-sparkling-2-line',
                'title' => 'Original Projects',
                'text' => 'Animated films, worlds, characters, and stories developed inside the Poligonium ecosystem.',
            ],
        ],
    ] : [
        'title' => 'Що стоїть за Poligonium',
        'subtitle' => 'Не сухі цифри, а реальна виробнича база: персонажі, анімація, школа, VFX та авторські історії.',
        'items' => [
            [
                'icon' => 'ri-compasses-2-line',
                'title' => 'Глибока 3D-практика',
                'text' => 'Від CAD-моделювання до персонажів, VFX, реклами та виробництва в Unreal Engine.',
            ],
            [
                'icon' => 'ri-briefcase-4-line',
                'title' => 'Комерційні проєкти',
                'text' => 'Маскоти, продуктові ролики, explain-анімація, візуалізація процесів і графіка для бізнесу.',
            ],
            [
                'icon' => 'ri-node-tree',
                'title' => 'Повний цикл виробництва',
                'text' => 'Концепт, скульптинг, ретопологія, текстури, риг, анімація, рендер і композитинг.',
            ],
            [
                'icon' => 'ri-movie-2-line',
                'title' => 'Персонажі для історій',
                'text' => 'Стилізовані герої для мультфільмів, рекламних кампаній, синематиків і брендової комунікації.',
            ],
            [
                'icon' => 'ri-graduation-cap-line',
                'title' => 'Освітній напрям',
                'text' => 'Авторські курси з Blender, Houdini, 3D-моделювання, ригінгу, анімації та VFX-процесів.',
            ],
            [
                'icon' => 'ri-sparkling-2-line',
                'title' => 'Власні оригінальні проєкти',
                'text' => 'Мультфільми, світи, персонажі та історії, які розвиваються всередині екосистеми Poligonium.',
            ],
        ],
    ];
@endphp

<section class="poligonium-proof-board">
    <style>
        .poligonium-proof-board {
            position: relative;
            overflow: hidden;
            padding: clamp(44px, 5.4vw, 68px) 0;
            color: #171717;
            background:
                radial-gradient(circle at 12% 20%, rgba(255, 171, 51, .16), transparent 24%),
                radial-gradient(circle at 88% 8%, rgba(42, 114, 255, .11), transparent 28%),
                linear-gradient(rgba(20, 20, 20, .052) 1px, transparent 1px),
                linear-gradient(90deg, rgba(20, 20, 20, .052) 1px, transparent 1px),
                #f4f2ec;
            background-size: auto, auto, 24px 24px, 24px 24px, auto;
            border-top: 1px solid rgba(20, 20, 20, .08);
            border-bottom: 1px solid rgba(20, 20, 20, .08);
        }

        .poligonium-proof-board::before,
        .poligonium-proof-board::after {
            content: "";
            position: absolute;
            pointer-events: none;
        }

        .poligonium-proof-board::before {
            inset: 0;
            background: linear-gradient(115deg, rgba(255, 255, 255, .72), transparent 46%, rgba(255, 255, 255, .42));
        }

        .poligonium-proof-board::after {
            top: 54px;
            right: max(32px, 7vw);
            width: 180px;
            height: 180px;
            border: 1px solid rgba(20, 20, 20, .14);
            border-radius: 999px;
            box-shadow: inset 0 0 0 28px rgba(255, 255, 255, .22);
            animation: poligoniumProofFloat 9s ease-in-out infinite;
        }

        .poligonium-proof-board__wrap {
            position: relative;
            z-index: 1;
            width: min(100% - 40px, 1320px);
            margin: 0 auto;
        }

        .poligonium-proof-board__head {
            display: grid;
            grid-template-columns: minmax(240px, .76fr) minmax(280px, 1fr);
            gap: 28px;
            align-items: end;
            margin-bottom: 24px;
        }

        .poligonium-proof-board__kicker {
            display: inline-flex;
            width: max-content;
            margin-bottom: 12px;
            color: #d95709;
            font-size: .78rem;
            font-weight: 900;
            text-transform: uppercase;
        }

        .poligonium-proof-board__title {
            margin: 0;
            color: #151515;
            font-size: clamp(1.8rem, 1.42rem + 1.45vw, 3.25rem);
            line-height: 1.04;
            letter-spacing: 0;
        }

        .poligonium-proof-board__subtitle {
            max-width: 710px;
            margin: 0;
            color: rgba(23, 23, 23, .72);
            font-size: clamp(1rem, .94rem + .25vw, 1.18rem);
            line-height: 1.62;
        }

        .poligonium-proof-board__grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .poligonium-proof-card {
            position: relative;
            min-height: 174px;
            padding: 18px;
            overflow: hidden;
            border: 1px solid rgba(20, 20, 20, .14);
            background:
                linear-gradient(rgba(20, 20, 20, .035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(20, 20, 20, .035) 1px, transparent 1px),
                rgba(255, 255, 255, .62);
            background-size: 18px 18px, 18px 18px, auto;
            box-shadow: 0 18px 40px rgba(20, 20, 20, .07);
            transition: transform .25s ease, border-color .25s ease, box-shadow .25s ease;
        }

        .poligonium-proof-card::before {
            content: "";
            position: absolute;
            inset: 0;
            opacity: 0;
            background: linear-gradient(115deg, transparent 0 36%, rgba(255, 255, 255, .72) 48%, transparent 60% 100%);
            transform: translateX(-38%);
            transition: opacity .25s ease, transform .55s ease;
        }

        .poligonium-proof-card:hover {
            transform: translateY(-6px);
            border-color: rgba(238, 112, 18, .42);
            box-shadow: 0 24px 54px rgba(20, 20, 20, .1);
        }

        .poligonium-proof-card:hover::before {
            opacity: 1;
            transform: translateX(38%);
        }

        .poligonium-proof-card__icon {
            position: relative;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            aspect-ratio: 1;
            margin-bottom: 16px;
            color: #151515;
            background: #fff;
            border: 1px solid rgba(20, 20, 20, .16);
            box-shadow: 6px 6px 0 rgba(238, 112, 18, .18);
        }

        .poligonium-proof-card__icon i {
            font-size: 1.35rem;
            line-height: 1;
        }

        .poligonium-proof-card h3 {
            position: relative;
            z-index: 1;
            margin: 0 0 10px;
            color: #151515;
            font-size: clamp(1.05rem, 1rem + .2vw, 1.22rem);
            line-height: 1.25;
            letter-spacing: 0;
        }

        .poligonium-proof-card p {
            position: relative;
            z-index: 1;
            margin: 0;
            color: rgba(23, 23, 23, .72);
            font-size: .92rem;
            line-height: 1.54;
        }

        @keyframes poligoniumProofFloat {
            0%, 100% {
                transform: translate3d(0, 0, 0) rotate(0deg);
            }
            50% {
                transform: translate3d(-18px, 12px, 0) rotate(8deg);
            }
        }

        @media (max-width: 1199px) {
            .poligonium-proof-board__grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767px) {
            .poligonium-proof-board {
                padding: 38px 0;
            }

            .poligonium-proof-board__wrap {
                width: min(100% - 28px, 680px);
            }

            .poligonium-proof-board__head,
            .poligonium-proof-board__grid {
                grid-template-columns: 1fr;
            }

            .poligonium-proof-card {
                min-height: auto;
            }
        }
    </style>

    <div class="poligonium-proof-board__wrap">
        <header class="poligonium-proof-board__head">
            <div>
                <span class="poligonium-proof-board__kicker">Poligonium</span>
                <h2 class="poligonium-proof-board__title">{{ $proof['title'] }}</h2>
            </div>
            <p class="poligonium-proof-board__subtitle">{{ $proof['subtitle'] }}</p>
        </header>

        <div class="poligonium-proof-board__grid">
            @foreach($proof['items'] as $item)
                <article class="poligonium-proof-card">
                    <span class="poligonium-proof-card__icon" aria-hidden="true">
                        <i class="{{ $item['icon'] }}"></i>
                    </span>
                    <h3>{{ $item['title'] }}</h3>
                    <p>{{ $item['text'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

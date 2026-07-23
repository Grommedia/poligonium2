<style>
    .poligonium-academy-page {
        position: relative;
        overflow: hidden;
        min-height: 100vh;
        padding: 118px 0 80px;
        background:
            linear-gradient(rgba(37, 99, 235, .08) 1px, transparent 1px),
            linear-gradient(90deg, rgba(37, 99, 235, .08) 1px, transparent 1px),
            radial-gradient(circle at 18% 12%, rgba(249, 115, 22, .16), transparent 28%),
            radial-gradient(circle at 82% 22%, rgba(22, 163, 74, .12), transparent 30%),
            #f7f8fb;
        background-size: 32px 32px, 32px 32px, auto, auto, auto;
        color: #161922;
    }

    .poligonium-academy-page::before {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        background:
            linear-gradient(115deg, transparent 0 46%, rgba(255, 255, 255, .7) 48%, transparent 52%),
            repeating-linear-gradient(135deg, transparent 0 18px, rgba(17, 24, 39, .035) 19px 20px);
        opacity: .55;
    }

    .poligonium-academy-wrap {
        position: relative;
        z-index: 1;
        width: min(1560px, calc(100% - 56px));
        margin: 0 auto;
    }

    .poligonium-academy-hero {
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(320px, .8fr);
        gap: 28px;
        align-items: stretch;
        margin-bottom: 28px;
    }

    .poligonium-academy-hero__copy {
        position: relative;
        overflow: hidden;
        min-height: 390px;
        padding: 46px;
        border: 1px solid rgba(17, 24, 39, .14);
        border-radius: 18px;
        background: rgba(255, 255, 255, .74);
        box-shadow: 0 22px 60px rgba(15, 23, 42, .09);
        backdrop-filter: blur(16px);
    }

    .poligonium-academy-hero__copy::after,
    .poligonium-academy-panel::after {
        content: "";
        position: absolute;
        inset: auto -15% -35% auto;
        width: 310px;
        height: 310px;
        border: 1px solid rgba(37, 99, 235, .16);
        transform: rotate(18deg);
        animation: academyFloat 7s ease-in-out infinite;
    }

    .poligonium-academy-kicker {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 18px;
        color: #2563eb;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0;
    }

    .poligonium-academy-kicker::before {
        content: "";
        width: 30px;
        height: 2px;
        background: #f97316;
    }

    .poligonium-academy-hero h1 {
        max-width: 760px;
        margin: 0;
        color: #111827;
        font-size: clamp(42px, 5vw, 82px);
        line-height: .92;
        letter-spacing: 0;
    }

    .poligonium-academy-hero p {
        max-width: 760px;
        margin: 24px 0 0;
        color: rgba(17, 24, 39, .72);
        font-size: 18px;
        line-height: 1.65;
    }

    .poligonium-academy-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 30px;
    }

    .poligonium-academy-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 46px;
        padding: 0 20px;
        border: 1px solid rgba(17, 24, 39, .18);
        border-radius: 999px;
        color: #111827;
        font-weight: 800;
        background: rgba(255, 255, 255, .78);
        transition: transform .2s ease, border-color .2s ease, background .2s ease;
    }

    .poligonium-academy-button:hover {
        transform: translateY(-2px);
        border-color: #f97316;
        background: #fff;
        color: #111827;
    }

    .poligonium-academy-button.is-primary {
        border-color: #111827;
        color: #fff;
        background: #111827;
    }

    .poligonium-academy-panel {
        position: relative;
        overflow: hidden;
        padding: 26px;
        border: 1px solid rgba(17, 24, 39, .14);
        border-radius: 18px;
        background: rgba(255, 255, 255, .68);
        box-shadow: 0 22px 60px rgba(15, 23, 42, .08);
        backdrop-filter: blur(16px);
    }

    .poligonium-academy-panel__title {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 22px;
    }

    .poligonium-academy-panel__title strong {
        display: block;
        color: #111827;
        font-size: 24px;
        line-height: 1.12;
    }

    .poligonium-academy-panel__title span {
        color: rgba(17, 24, 39, .55);
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .poligonium-academy-routes,
    .poligonium-academy-categories {
        position: relative;
        z-index: 1;
        display: grid;
        gap: 12px;
    }

    .poligonium-academy-route,
    .poligonium-academy-category {
        display: grid;
        grid-template-columns: 42px 1fr;
        gap: 14px;
        align-items: center;
        padding: 16px;
        border: 1px solid rgba(17, 24, 39, .12);
        border-radius: 14px;
        color: #111827;
        background:
            linear-gradient(rgba(17, 24, 39, .04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(17, 24, 39, .04) 1px, transparent 1px),
            rgba(255, 255, 255, .66);
        background-size: 18px 18px;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }

    .poligonium-academy-route:hover,
    .poligonium-academy-category:hover {
        transform: translateX(4px);
        border-color: rgba(249, 115, 22, .52);
        box-shadow: 0 16px 32px rgba(15, 23, 42, .08);
        color: #111827;
    }

    .poligonium-academy-route i,
    .poligonium-academy-category i {
        display: grid;
        place-items: center;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        color: #fff;
        background: #111827;
        font-size: 21px;
    }

    .poligonium-academy-route strong,
    .poligonium-academy-category strong {
        display: block;
        font-size: 16px;
        line-height: 1.2;
    }

    .poligonium-academy-route span,
    .poligonium-academy-category span {
        display: block;
        margin-top: 4px;
        color: rgba(17, 24, 39, .6);
        font-size: 13px;
        line-height: 1.35;
    }

    .poligonium-academy-section {
        margin-top: 34px;
    }

    .poligonium-academy-section__head {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 18px;
    }

    .poligonium-academy-section__head h2 {
        margin: 0;
        color: #111827;
        font-size: clamp(28px, 3vw, 44px);
        line-height: 1;
        letter-spacing: 0;
    }

    .poligonium-academy-section__head p {
        max-width: 620px;
        margin: 8px 0 0;
        color: rgba(17, 24, 39, .68);
        line-height: 1.55;
    }

    .poligonium-academy-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .poligonium-academy-card {
        position: relative;
        overflow: hidden;
        display: flex;
        min-height: 330px;
        padding: 22px;
        border: 1px solid rgba(17, 24, 39, .14);
        border-radius: 18px;
        color: #111827;
        background:
            linear-gradient(rgba(37, 99, 235, .055) 1px, transparent 1px),
            linear-gradient(90deg, rgba(37, 99, 235, .055) 1px, transparent 1px),
            rgba(255, 255, 255, .8);
        background-size: 22px 22px;
        box-shadow: 0 18px 48px rgba(15, 23, 42, .07);
        transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }

    .poligonium-academy-card:hover {
        transform: translateY(-6px);
        border-color: rgba(249, 115, 22, .58);
        box-shadow: 0 26px 70px rgba(15, 23, 42, .13);
        color: #111827;
    }

    .poligonium-academy-card::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(110deg, transparent 10%, rgba(255, 255, 255, .75) 35%, transparent 58%);
        transform: translateX(-120%);
        transition: transform .7s ease;
        pointer-events: none;
    }

    .poligonium-academy-card:hover::after {
        transform: translateX(120%);
    }

    .poligonium-academy-card__body {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    .poligonium-academy-card__media {
        height: 150px;
        margin: -22px -22px 18px;
        background: linear-gradient(135deg, #111827, #2563eb);
    }

    .poligonium-academy-card__media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .poligonium-academy-card__meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 14px;
        color: rgba(17, 24, 39, .62);
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .poligonium-academy-card h3 {
        margin: 0;
        color: #111827;
        font-size: 24px;
        line-height: 1.08;
        letter-spacing: 0;
    }

    .poligonium-academy-card p {
        margin: 14px 0 18px;
        color: rgba(17, 24, 39, .68);
        line-height: 1.55;
    }

    .poligonium-academy-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: auto;
    }

    .poligonium-academy-tags span {
        padding: 6px 10px;
        border: 1px solid rgba(17, 24, 39, .12);
        border-radius: 999px;
        background: rgba(255, 255, 255, .7);
        color: rgba(17, 24, 39, .72);
        font-size: 12px;
        font-weight: 800;
    }

    .poligonium-academy-article {
        max-width: 1100px;
        margin: 0 auto;
    }

    .poligonium-academy-article__hero {
        padding: 42px;
        border: 1px solid rgba(17, 24, 39, .14);
        border-radius: 18px;
        background: rgba(255, 255, 255, .82);
        box-shadow: 0 22px 60px rgba(15, 23, 42, .08);
    }

    .poligonium-academy-article__hero h1 {
        margin: 12px 0;
        color: #111827;
        font-size: clamp(38px, 5vw, 72px);
        line-height: .98;
        letter-spacing: 0;
    }

    .poligonium-academy-article__hero p {
        max-width: 760px;
        margin: 0;
        color: rgba(17, 24, 39, .7);
        font-size: 18px;
        line-height: 1.6;
    }

    .poligonium-academy-content {
        margin-top: 24px;
        padding: 42px;
        border: 1px solid rgba(17, 24, 39, .12);
        border-radius: 18px;
        background: rgba(255, 255, 255, .88);
        color: #20242f;
        font-size: 18px;
        line-height: 1.75;
    }

    .poligonium-academy-content h2,
    .poligonium-academy-content h3 {
        margin-top: 1.5em;
        color: #111827;
        letter-spacing: 0;
    }

    .poligonium-academy-content a {
        color: #2563eb;
        font-weight: 800;
    }

    .poligonium-academy-cta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        margin-top: 24px;
        padding: 24px;
        border: 1px solid rgba(249, 115, 22, .35);
        border-radius: 18px;
        background:
            linear-gradient(rgba(249, 115, 22, .07) 1px, transparent 1px),
            linear-gradient(90deg, rgba(249, 115, 22, .07) 1px, transparent 1px),
            rgba(255, 255, 255, .82);
        background-size: 20px 20px;
    }

    .poligonium-academy-cta strong {
        color: #111827;
        font-size: 22px;
    }

    .poligonium-academy-empty {
        grid-column: 1 / -1;
        padding: 36px;
        border: 1px dashed rgba(17, 24, 39, .2);
        border-radius: 18px;
        background: rgba(255, 255, 255, .66);
        color: rgba(17, 24, 39, .68);
    }

    @keyframes academyFloat {
        0%, 100% {
            transform: translateY(0) rotate(18deg);
        }
        50% {
            transform: translateY(-12px) rotate(12deg);
        }
    }

    html[data-bs-theme="dark"] .poligonium-academy-page {
        background:
            linear-gradient(rgba(148, 163, 184, .08) 1px, transparent 1px),
            linear-gradient(90deg, rgba(148, 163, 184, .08) 1px, transparent 1px),
            #f7f8fb;
        color: #161922;
    }

    @media (max-width: 991px) {
        .poligonium-academy-page {
            padding: 92px 0 52px;
        }

        .poligonium-academy-wrap {
            width: min(100% - 28px, 760px);
        }

        .poligonium-academy-hero,
        .poligonium-academy-grid {
            grid-template-columns: 1fr;
        }

        .poligonium-academy-hero__copy,
        .poligonium-academy-panel,
        .poligonium-academy-article__hero,
        .poligonium-academy-content {
            padding: 24px;
            border-radius: 14px;
        }

        .poligonium-academy-hero__copy {
            min-height: auto;
        }
    }

    @media (max-width: 575px) {
        .poligonium-academy-page {
            padding-top: 78px;
        }

        .poligonium-academy-wrap {
            width: min(100% - 20px, 520px);
        }

        .poligonium-academy-actions,
        .poligonium-academy-section__head {
            display: grid;
            align-items: start;
        }

        .poligonium-academy-button {
            width: 100%;
        }

        .poligonium-academy-card {
            min-height: 300px;
            padding: 18px;
        }

        .poligonium-academy-card__media {
            margin: -18px -18px 16px;
            height: 132px;
        }
    }
</style>

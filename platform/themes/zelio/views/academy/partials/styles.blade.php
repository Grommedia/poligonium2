<style>
    @import url("https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap");

    .poligonium-academy-page {
        --academy-text: #0f172a;
        --academy-muted: #5b6475;
        --academy-border: #e6eaf2;
        --academy-bg: #f8fafc;
        --academy-card: #ffffff;
        --academy-primary: #5b61ff;
        --academy-primary-hover: #474de6;
        --academy-dark: #0f172a;
        position: relative;
        overflow: hidden;
        padding: 48px 0 88px;
        color: var(--academy-text);
        font-family: "Manrope", "Inter", "Noto Sans", "Segoe UI", Arial, sans-serif;
        background:
            linear-gradient(rgba(91, 97, 255, .045) 1px, transparent 1px),
            linear-gradient(90deg, rgba(91, 97, 255, .045) 1px, transparent 1px),
            linear-gradient(180deg, #ffffff 0%, var(--academy-bg) 56%, #ffffff 100%);
        background-size: 32px 32px, 32px 32px, auto;
    }

    .poligonium-academy-page *,
    .poligonium-academy-page *::before,
    .poligonium-academy-page *::after {
        box-sizing: border-box;
    }

    .poligonium-academy-page a {
        text-decoration: none;
    }

    html[lang="uk"] .poligonium-academy-page,
    html[lang="uk"] .poligonium-academy-page h1,
    html[lang="uk"] .poligonium-academy-page h2,
    html[lang="uk"] .poligonium-academy-page h3,
    html[lang="uk"] .poligonium-academy-page p,
    html[lang="uk"] .poligonium-academy-page a,
    html[lang="uk"] .poligonium-academy-page strong,
    html[lang="uk"] .poligonium-academy-page span,
    html[lang="uk"] .poligonium-academy-page small,
    html[lang="uk"] .poligonium-academy-page em {
        font-family: "Manrope", "Inter", "Noto Sans", "Segoe UI", Arial, sans-serif;
    }

    .poligonium-academy-wrap {
        position: relative;
        z-index: 1;
        width: min(1280px, calc(100% - 80px));
        margin: 0 auto;
    }

    .poligonium-academy-hero {
        display: grid;
        grid-template-columns: minmax(0, 5fr) minmax(420px, 7fr);
        gap: 24px;
        align-items: center;
        min-height: 640px;
        padding: 32px 0 40px;
    }

    .poligonium-academy-hero__copy {
        max-width: 580px;
    }

    .poligonium-academy-kicker {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        margin: 0 0 20px;
        color: var(--academy-primary);
        font-size: 13px;
        font-weight: 800;
        line-height: 1;
        text-transform: uppercase;
        letter-spacing: 0;
    }

    .poligonium-academy-kicker::before {
        content: "";
        width: 28px;
        height: 2px;
        border-radius: 999px;
        background: var(--academy-primary);
    }

    .poligonium-academy-hero h1 {
        max-width: 580px;
        margin: 0;
        color: var(--academy-text);
        font-size: 64px;
        font-weight: 800;
        line-height: 1.04;
        letter-spacing: 0;
    }

    html[lang="uk"] .poligonium-academy-hero h1 {
        line-height: 1.04;
    }

    .poligonium-academy-hero__copy > p:not(.poligonium-academy-kicker) {
        max-width: 560px;
        margin: 24px 0 0;
        color: var(--academy-muted);
        font-size: 22px;
        font-weight: 400;
        line-height: 1.5;
    }

    .poligonium-academy-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 32px;
    }

    .poligonium-academy-button,
    .poligonium-academy-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        color: var(--academy-text);
        font-size: 15px;
        font-weight: 800;
        line-height: 1;
        letter-spacing: 0;
        transition: color .2s ease, background .2s ease, border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }

    .poligonium-academy-button {
        min-height: 54px;
        padding: 0 24px;
        border: 1px solid #d8ddea;
        border-radius: 14px;
        background: rgba(255, 255, 255, .88);
        box-shadow: 0 14px 34px rgba(15, 23, 42, .06);
    }

    .poligonium-academy-button.is-primary {
        border-color: var(--academy-dark);
        color: #fff;
        background: var(--academy-dark);
        box-shadow: 0 18px 36px rgba(15, 23, 42, .16);
    }

    .poligonium-academy-button:hover,
    .poligonium-academy-link:hover {
        color: var(--academy-primary-hover);
        transform: translateY(-2px);
    }

    .poligonium-academy-button.is-primary:hover {
        color: #fff;
        background: #111c33;
    }

    .poligonium-academy-button__icon,
    .poligonium-academy-arrow-icon {
        display: inline-grid;
        place-items: center;
        width: 20px;
        height: 20px;
        flex: 0 0 auto;
    }

    .poligonium-academy-button__icon::before,
    .poligonium-academy-arrow-icon::before,
    .poligonium-academy-icon::before {
        content: "";
        display: block;
        width: 100%;
        height: 100%;
        background: currentColor;
        mask: var(--academy-icon) center / contain no-repeat;
        -webkit-mask: var(--academy-icon) center / contain no-repeat;
    }

    .poligonium-academy-hero__visual {
        position: relative;
        display: grid;
        place-items: center;
        min-height: 620px;
        isolation: isolate;
    }

    .poligonium-academy-hero__visual::before {
        content: "";
        position: absolute;
        z-index: -1;
        width: min(84%, 560px);
        aspect-ratio: 1;
        border-radius: 50%;
        background: rgba(91, 97, 255, .12);
        filter: blur(.2px);
    }

    .poligonium-academy-hero__visual video,
    .poligonium-academy-hero__visual img {
        position: relative;
        z-index: 1;
        display: block;
        width: min(100%, 700px);
        height: auto;
        aspect-ratio: 1;
        object-fit: contain;
        transform: none !important;
        animation: none !important;
    }

    .poligonium-academy-benefits {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 24px;
        margin-top: 24px;
    }

    .poligonium-academy-benefits article,
    .poligonium-academy-course-card,
    .poligonium-academy-card,
    .poligonium-academy-service-strip {
        border: 1px solid var(--academy-border);
        background: rgba(255, 255, 255, .9);
        box-shadow: 0 18px 42px rgba(15, 23, 42, .055);
    }

    .poligonium-academy-benefits article {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        min-height: 190px;
        padding: 24px;
        border-radius: 20px;
        transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
    }

    .poligonium-academy-benefits article:hover,
    .poligonium-academy-course-card:hover,
    .poligonium-academy-card:hover {
        border-color: rgba(91, 97, 255, .32);
        box-shadow: 0 24px 56px rgba(15, 23, 42, .095);
        transform: translateY(-4px);
    }

    .poligonium-academy-icon {
        display: grid;
        place-items: center;
        flex: 0 0 auto;
        width: 44px;
        height: 44px;
        border: 1px solid rgba(91, 97, 255, .12);
        border-radius: 14px;
        color: var(--academy-primary);
        background: #f1f3ff;
    }

    .poligonium-academy-benefits strong {
        display: block;
        margin-top: 22px;
        color: var(--academy-text);
        font-size: 18px;
        font-weight: 700;
        line-height: 1.28;
    }

    .poligonium-academy-benefits span:not(.poligonium-academy-icon) {
        display: block;
        margin-top: 10px;
        color: var(--academy-muted);
        font-size: 15px;
        font-weight: 500;
        line-height: 1.5;
    }

    .poligonium-academy-section {
        margin-top: 92px;
    }

    .poligonium-academy-section__head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 28px;
    }

    .poligonium-academy-section__head h2,
    .poligonium-academy-service-strip h2 {
        margin: 0;
        color: var(--academy-text);
        font-size: 40px;
        font-weight: 700;
        line-height: 1.15;
        letter-spacing: 0;
    }

    html[lang="uk"] .poligonium-academy-section__head h2,
    html[lang="uk"] .poligonium-academy-service-strip h2 {
        line-height: 1.15;
    }

    .poligonium-academy-section__head p {
        max-width: 680px;
        margin: 10px 0 0;
        color: var(--academy-muted);
        font-size: 17px;
        line-height: 1.55;
    }

    .poligonium-academy-link {
        min-height: 40px;
        padding: 0;
        color: var(--academy-primary);
        white-space: nowrap;
    }

    .poligonium-academy-link .poligonium-academy-arrow-icon {
        width: 18px;
        height: 18px;
    }

    .poligonium-academy-course-grid,
    .poligonium-academy-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 24px;
    }

    .poligonium-academy-course-card {
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 390px;
        padding: 20px;
        border-radius: 20px;
        color: var(--academy-text);
        transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
    }

    .poligonium-academy-course-card__image {
        overflow: hidden;
        position: relative;
        display: block;
        aspect-ratio: 16 / 10;
        border-radius: 16px;
        background:
            radial-gradient(circle at 75% 22%, rgba(91, 97, 255, .16), transparent 34%),
            linear-gradient(135deg, #f7f8ff, #ffffff);
    }

    .poligonium-academy-course-card__image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 18px;
        transition: transform .35s ease;
    }

    .poligonium-academy-course-card:hover .poligonium-academy-course-card__image img {
        transform: scale(1.035);
    }

    .poligonium-academy-course-card__body {
        display: flex;
        flex: 1;
        flex-direction: column;
        padding-top: 16px;
    }

    .poligonium-academy-course-card__body strong {
        display: -webkit-box;
        overflow: hidden;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        color: var(--academy-text);
        font-size: 22px;
        font-weight: 700;
        line-height: 1.22;
    }

    .poligonium-academy-course-card__body > span {
        display: -webkit-box;
        overflow: hidden;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
        margin-top: 12px;
        color: var(--academy-muted);
        font-size: 16px;
        font-weight: 500;
        line-height: 1.5;
    }

    .poligonium-academy-course-card__body em {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: auto;
        padding-top: 18px;
        color: #475569;
        font-size: 14px;
        font-style: normal;
        font-weight: 700;
    }

    .poligonium-academy-course-card__body small {
        display: inline-flex;
        align-items: center;
        min-height: 28px;
        padding: 0 12px;
        border-radius: 999px;
        color: var(--academy-primary);
        background: #f1f3ff;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .poligonium-academy-service-strip {
        position: relative;
        overflow: hidden;
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(330px, .78fr);
        gap: 24px;
        align-items: center;
        min-height: 300px;
        margin-top: 92px;
        padding: 40px;
        border-radius: 24px;
        background:
            linear-gradient(rgba(91, 97, 255, .045) 1px, transparent 1px),
            linear-gradient(90deg, rgba(91, 97, 255, .045) 1px, transparent 1px),
            linear-gradient(135deg, rgba(244, 247, 255, .96), rgba(255, 255, 255, .92));
        background-size: 28px 28px, 28px 28px, auto;
    }

    .poligonium-academy-service-strip__copy {
        position: relative;
        z-index: 1;
        max-width: 650px;
    }

    .poligonium-academy-service-strip h2 {
        margin-top: 18px;
    }

    .poligonium-academy-service-strip p {
        max-width: 620px;
        margin: 16px 0 28px;
        color: var(--academy-muted);
        font-size: 17px;
        line-height: 1.55;
    }

    .poligonium-academy-service-strip img {
        align-self: end;
        justify-self: end;
        width: min(100%, 440px);
        height: auto;
        pointer-events: none;
    }

    .poligonium-academy-card {
        overflow: hidden;
        display: grid;
        grid-template-rows: 118px 1fr;
        height: 285px;
        border-radius: 18px;
        color: var(--academy-text);
        transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
    }

    .poligonium-academy-card__media {
        overflow: hidden;
        display: block;
        background:
            radial-gradient(circle at 78% 20%, rgba(91, 97, 255, .15), transparent 34%),
            linear-gradient(135deg, #f7f8ff, #ffffff);
    }

    .poligonium-academy-card__media img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 14px;
        transition: transform .35s ease;
    }

    .poligonium-academy-card:hover .poligonium-academy-card__media img {
        transform: scale(1.035);
    }

    .poligonium-academy-card__body {
        display: flex;
        flex-direction: column;
        padding: 16px;
    }

    .poligonium-academy-card h3 {
        display: -webkit-box;
        overflow: hidden;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        margin: 0;
        color: var(--academy-text);
        font-size: 18px;
        font-weight: 700;
        line-height: 1.28;
        letter-spacing: 0;
    }

    .poligonium-academy-card p {
        display: -webkit-box;
        overflow: hidden;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        margin: 10px 0 0;
        color: var(--academy-muted);
        font-size: 14px;
        line-height: 1.5;
    }

    .poligonium-academy-card__read {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: auto;
        padding-top: 16px;
        color: #667085;
        font-size: 13px;
        font-weight: 700;
    }

    .poligonium-academy-card__read span:last-child {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--academy-primary);
    }

    .poligonium-academy-card__read .poligonium-academy-arrow-icon {
        width: 15px;
        height: 15px;
    }

    html[data-bs-theme="dark"] .poligonium-academy-page {
        color: var(--academy-text);
        background:
            linear-gradient(rgba(91, 97, 255, .045) 1px, transparent 1px),
            linear-gradient(90deg, rgba(91, 97, 255, .045) 1px, transparent 1px),
            linear-gradient(180deg, #ffffff 0%, var(--academy-bg) 56%, #ffffff 100%);
        background-size: 32px 32px, 32px 32px, auto;
    }

    @media (max-width: 1199px) {
        .poligonium-academy-wrap {
            width: min(100% - 56px, 1040px);
        }

        .poligonium-academy-hero {
            grid-template-columns: minmax(0, 1fr) minmax(360px, .95fr);
            min-height: 580px;
        }

        .poligonium-academy-hero h1 {
            font-size: 56px;
        }

        .poligonium-academy-hero__copy > p:not(.poligonium-academy-kicker) {
            font-size: 20px;
        }

        .poligonium-academy-benefits,
        .poligonium-academy-course-grid,
        .poligonium-academy-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 991px) {
        .poligonium-academy-page {
            padding: 40px 0 72px;
        }

        .poligonium-academy-wrap {
            width: min(100% - 40px, 760px);
        }

        .poligonium-academy-hero {
            grid-template-columns: 1fr;
            gap: 12px;
            min-height: auto;
            padding-bottom: 16px;
        }

        .poligonium-academy-hero__copy {
            max-width: 680px;
        }

        .poligonium-academy-hero__visual {
            min-height: auto;
        }

        .poligonium-academy-hero__visual video,
        .poligonium-academy-hero__visual img {
            width: min(100%, 620px);
        }

        .poligonium-academy-section,
        .poligonium-academy-service-strip {
            margin-top: 72px;
        }

        .poligonium-academy-service-strip {
            grid-template-columns: 1fr;
        }

        .poligonium-academy-service-strip img {
            justify-self: center;
            width: min(100%, 420px);
        }
    }

    @media (max-width: 575px) {
        .poligonium-academy-page {
            padding: 28px 0 56px;
        }

        .poligonium-academy-wrap {
            width: min(100% - 28px, 520px);
        }

        .poligonium-academy-hero h1 {
            font-size: 42px;
            line-height: 1.05;
        }

        .poligonium-academy-hero__copy > p:not(.poligonium-academy-kicker) {
            font-size: 18px;
        }

        .poligonium-academy-actions,
        .poligonium-academy-section__head,
        .poligonium-academy-benefits,
        .poligonium-academy-course-grid,
        .poligonium-academy-grid {
            display: grid;
            grid-template-columns: 1fr;
        }

        .poligonium-academy-button {
            width: 100%;
        }

        .poligonium-academy-benefits article {
            min-height: auto;
        }

        .poligonium-academy-section,
        .poligonium-academy-service-strip {
            margin-top: 56px;
        }

        .poligonium-academy-section__head h2,
        .poligonium-academy-service-strip h2 {
            font-size: 32px;
        }

        .poligonium-academy-course-card {
            height: 390px;
        }

        .poligonium-academy-card {
            height: 285px;
        }

        .poligonium-academy-service-strip {
            padding: 24px;
        }
    }
</style>

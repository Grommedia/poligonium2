<style>
    .poligonium-academy-page {
        position: relative;
        overflow: hidden;
        min-height: 100vh;
        padding: 96px 0 70px;
        color: #111827;
        background:
            linear-gradient(rgba(37, 99, 235, .055) 1px, transparent 1px),
            linear-gradient(90deg, rgba(37, 99, 235, .055) 1px, transparent 1px),
            #fbfcff;
        background-size: 32px 32px;
    }

    .poligonium-academy-page::before {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        background:
            radial-gradient(circle at 72% 9%, rgba(99, 102, 241, .15), transparent 25%),
            radial-gradient(circle at 58% 53%, rgba(249, 115, 22, .10), transparent 28%),
            linear-gradient(180deg, rgba(255, 255, 255, .72), rgba(255, 255, 255, .2));
    }

    .poligonium-academy-wrap {
        position: relative;
        z-index: 1;
        width: min(1560px, calc(100% - 70px));
        margin: 0 auto;
    }

    .poligonium-academy-hero {
        display: grid;
        grid-template-columns: minmax(0, .86fr) minmax(420px, 1fr);
        gap: 36px;
        align-items: center;
        min-height: 520px;
    }

    .poligonium-academy-hero__copy {
        padding: 26px 0 34px;
    }

    .poligonium-academy-kicker {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 20px;
        color: #4f46e5;
        font-size: 13px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0;
    }

    .poligonium-academy-kicker::before {
        content: "";
        width: 22px;
        height: 2px;
        border-radius: 999px;
        background: #4f46e5;
    }

    .poligonium-academy-hero h1 {
        max-width: 620px;
        margin: 0;
        color: #101827;
        font-size: clamp(48px, 5.5vw, 86px);
        line-height: .98;
        letter-spacing: 0;
    }

    .poligonium-academy-hero p {
        max-width: 560px;
        margin: 24px 0 0;
        color: #596174;
        font-size: 17px;
        line-height: 1.58;
    }

    .poligonium-academy-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 30px;
    }

    .poligonium-academy-button,
    .poligonium-academy-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 48px;
        padding: 0 22px;
        border-radius: 10px;
        color: #111827;
        font-weight: 850;
        line-height: 1;
        letter-spacing: 0;
        transition: transform .22s ease, border-color .22s ease, box-shadow .22s ease, background .22s ease;
    }

    .poligonium-academy-button {
        border: 1px solid rgba(15, 23, 42, .13);
        background: rgba(255, 255, 255, .86);
        box-shadow: 0 10px 24px rgba(15, 23, 42, .05);
    }

    .poligonium-academy-button.is-primary {
        border-color: #111827;
        color: #fff;
        background: #111827;
        box-shadow: 0 14px 32px rgba(17, 24, 39, .18);
    }

    .poligonium-academy-button:hover,
    .poligonium-academy-link:hover {
        transform: translateY(-2px);
        color: #111827;
    }

    .poligonium-academy-button.is-primary:hover {
        color: #fff;
        background: #0b1220;
    }

    .poligonium-academy-link {
        min-height: auto;
        padding: 0;
        color: #4f46e5;
        box-shadow: none;
        background: transparent;
    }

    .poligonium-academy-hero__visual {
        position: relative;
        display: grid;
        place-items: center;
        min-height: 500px;
    }

    .poligonium-academy-hero__visual::before {
        content: "";
        position: absolute;
        width: min(78%, 570px);
        aspect-ratio: 1;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(99, 102, 241, .34), rgba(255, 255, 255, .15));
        filter: blur(.2px);
        animation: academySoftPulse 6s ease-in-out infinite;
    }

    .poligonium-academy-hero__visual img {
        position: relative;
        z-index: 1;
        width: min(100%, 720px);
        height: auto;
        aspect-ratio: 1;
        object-fit: contain;
        transform: translateY(-8px);
        animation: academyFloatImage 7s ease-in-out infinite;
    }

    .poligonium-academy-hero__visual video {
        position: relative;
        z-index: 1;
        display: block;
        width: min(100%, 720px);
        height: auto;
        aspect-ratio: 1;
        object-fit: contain;
        transform: none;
        animation: none;
    }

    .poligonium-academy-benefits {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 28px;
        margin-top: 8px;
    }

    .poligonium-academy-benefits article {
        display: grid;
        grid-template-columns: 52px 1fr;
        gap: 14px;
        align-items: center;
        min-height: 104px;
        padding: 20px;
        border: 1px solid rgba(79, 70, 229, .11);
        border-radius: 12px;
        background: rgba(255, 255, 255, .82);
        box-shadow: 0 16px 42px rgba(15, 23, 42, .055);
        transition: transform .22s ease, border-color .22s ease, box-shadow .22s ease;
    }

    .poligonium-academy-benefits article:hover {
        transform: translateY(-4px);
        border-color: rgba(79, 70, 229, .28);
        box-shadow: 0 24px 52px rgba(15, 23, 42, .09);
    }

    .poligonium-academy-benefits i {
        display: grid;
        place-items: center;
        width: 48px;
        height: 48px;
        border-radius: 12px;
        color: #4f46e5;
        background: #eef2ff;
        font-size: 23px;
    }

    .poligonium-academy-benefits strong {
        display: block;
        color: #161b28;
        font-size: 15px;
        line-height: 1.25;
    }

    .poligonium-academy-benefits span {
        display: block;
        margin-top: 4px;
        color: #6b7280;
        font-size: 13px;
        line-height: 1.35;
    }

    .poligonium-academy-section {
        margin-top: 42px;
    }

    .poligonium-academy-section.is-courses {
        margin-top: 44px;
    }

    .poligonium-academy-section__head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 20px;
    }

    .poligonium-academy-section__head h2 {
        margin: 0;
        color: #101827;
        font-size: clamp(27px, 2.6vw, 38px);
        line-height: 1;
        letter-spacing: 0;
    }

    .poligonium-academy-section__head p {
        max-width: 650px;
        margin: 8px 0 0;
        color: #667085;
        line-height: 1.55;
    }

    .poligonium-academy-course-grid,
    .poligonium-academy-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 28px;
    }

    .poligonium-academy-course-card,
    .poligonium-academy-card {
        position: relative;
        overflow: hidden;
        display: grid;
        min-height: 360px;
        border: 1px solid rgba(15, 23, 42, .10);
        border-radius: 14px;
        color: #111827;
        background: rgba(255, 255, 255, .9);
        box-shadow: 0 18px 42px rgba(15, 23, 42, .07);
        transition: transform .24s ease, box-shadow .24s ease, border-color .24s ease;
    }

    .poligonium-academy-course-card:hover,
    .poligonium-academy-card:hover {
        transform: translateY(-6px);
        border-color: rgba(79, 70, 229, .28);
        box-shadow: 0 26px 60px rgba(15, 23, 42, .12);
        color: #111827;
    }

    .poligonium-academy-course-card::after,
    .poligonium-academy-card::after {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        background: linear-gradient(110deg, transparent 8%, rgba(255, 255, 255, .72) 38%, transparent 58%);
        transform: translateX(-120%);
        transition: transform .72s ease;
    }

    .poligonium-academy-course-card:hover::after,
    .poligonium-academy-card:hover::after {
        transform: translateX(120%);
    }

    .poligonium-academy-course-card__image,
    .poligonium-academy-card__media {
        display: block;
        height: 178px;
        background: #f7f8ff;
    }

    .poligonium-academy-course-card__image img,
    .poligonium-academy-card__media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .poligonium-academy-course-card__body,
    .poligonium-academy-card__body {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        padding: 22px;
    }

    .poligonium-academy-course-card__body strong,
    .poligonium-academy-card h3 {
        margin: 0;
        color: #111827;
        font-size: 20px;
        line-height: 1.18;
        letter-spacing: 0;
    }

    .poligonium-academy-course-card__body > span,
    .poligonium-academy-card p {
        display: block;
        margin: 12px 0 20px;
        color: #606a7d;
        font-size: 14px;
        line-height: 1.5;
    }

    .poligonium-academy-course-card__body em {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: auto;
        color: #475569;
        font-style: normal;
        font-size: 13px;
        font-weight: 800;
    }

    .poligonium-academy-course-card__body small {
        padding: 7px 10px;
        border-radius: 999px;
        color: #4f46e5;
        background: #eef2ff;
        font-size: 12px;
        font-weight: 850;
    }

    .poligonium-academy-service-strip {
        position: relative;
        overflow: hidden;
        display: grid;
        grid-template-columns: minmax(0, .9fr) minmax(380px, .75fr);
        align-items: center;
        min-height: 210px;
        margin-top: 28px;
        padding: 30px 44px;
        border: 1px solid rgba(79, 70, 229, .12);
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(238, 242, 255, .96), rgba(255, 247, 237, .9));
        box-shadow: 0 18px 46px rgba(15, 23, 42, .06);
    }

    .poligonium-academy-service-strip h2 {
        max-width: 700px;
        margin: 0;
        color: #111827;
        font-size: clamp(28px, 2.8vw, 42px);
        line-height: 1.08;
        letter-spacing: 0;
    }

    .poligonium-academy-service-strip p {
        max-width: 640px;
        margin: 12px 0 22px;
        color: #596174;
        line-height: 1.55;
    }

    .poligonium-academy-service-strip img {
        position: absolute;
        right: -2%;
        bottom: -22%;
        width: min(48%, 590px);
        height: auto;
        pointer-events: none;
    }

    .poligonium-academy-proof {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        margin-top: 28px;
        border: 1px solid rgba(15, 23, 42, .10);
        border-radius: 14px;
        background: rgba(255, 255, 255, .86);
        box-shadow: 0 14px 34px rgba(15, 23, 42, .045);
    }

    .poligonium-academy-proof span {
        display: grid;
        grid-template-columns: 34px 1fr;
        gap: 10px;
        align-items: center;
        min-height: 92px;
        padding: 18px 24px;
        border-right: 1px solid rgba(15, 23, 42, .09);
    }

    .poligonium-academy-proof span:last-child {
        border-right: 0;
    }

    .poligonium-academy-proof i {
        display: grid;
        place-items: center;
        width: 32px;
        height: 32px;
        border-radius: 9px;
        color: #4f46e5;
        background: #eef2ff;
        font-size: 19px;
    }

    .poligonium-academy-proof strong {
        display: block;
        color: #111827;
        font-size: 16px;
        line-height: 1.1;
    }

    .poligonium-academy-proof em {
        display: block;
        margin-top: 4px;
        color: #667085;
        font-size: 12px;
        font-style: normal;
        line-height: 1.3;
    }

    .poligonium-academy-card {
        min-height: 330px;
    }

    .poligonium-academy-card__media {
        margin: 0;
    }

    .poligonium-academy-card__meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
        color: #4f46e5;
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .poligonium-academy-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: auto;
    }

    .poligonium-academy-tags span {
        padding: 6px 10px;
        border: 1px solid rgba(79, 70, 229, .12);
        border-radius: 999px;
        background: #f5f7ff;
        color: #4f46e5;
        font-size: 12px;
        font-weight: 800;
    }

    .poligonium-academy-article {
        max-width: 1100px;
        margin: 0 auto;
    }

    .poligonium-academy-article__hero,
    .poligonium-academy-content,
    .poligonium-academy-cta,
    .poligonium-academy-empty {
        border: 1px solid rgba(15, 23, 42, .10);
        border-radius: 16px;
        background: rgba(255, 255, 255, .88);
        box-shadow: 0 16px 42px rgba(15, 23, 42, .055);
    }

    .poligonium-academy-article__hero {
        padding: 42px;
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
        color: #596174;
        font-size: 18px;
        line-height: 1.6;
    }

    .poligonium-academy-content {
        margin-top: 24px;
        padding: 42px;
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
        color: #4f46e5;
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
    }

    .poligonium-academy-cta strong {
        color: #111827;
        font-size: 22px;
    }

    .poligonium-academy-empty {
        grid-column: 1 / -1;
        padding: 36px;
        color: #667085;
    }

    @keyframes academyFloatImage {
        0%, 100% { transform: translateY(-8px); }
        50% { transform: translateY(6px); }
    }

    @keyframes academySoftPulse {
        0%, 100% { transform: scale(1); opacity: .78; }
        50% { transform: scale(1.035); opacity: .92; }
    }

    html[data-bs-theme="dark"] .poligonium-academy-page {
        color: #111827;
        background:
            linear-gradient(rgba(37, 99, 235, .055) 1px, transparent 1px),
            linear-gradient(90deg, rgba(37, 99, 235, .055) 1px, transparent 1px),
            #fbfcff;
        background-size: 32px 32px;
    }

    @media (max-width: 1199px) {
        .poligonium-academy-wrap {
            width: min(100% - 42px, 1180px);
        }

        .poligonium-academy-hero {
            grid-template-columns: 1fr .95fr;
            gap: 20px;
        }

        .poligonium-academy-benefits,
        .poligonium-academy-course-grid,
        .poligonium-academy-grid,
        .poligonium-academy-proof {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .poligonium-academy-proof span:nth-child(2) {
            border-right: 0;
        }
    }

    @media (max-width: 991px) {
        .poligonium-academy-page {
            padding: 82px 0 50px;
        }

        .poligonium-academy-wrap {
            width: min(100% - 28px, 760px);
        }

        .poligonium-academy-hero {
            grid-template-columns: 1fr;
            min-height: auto;
        }

        .poligonium-academy-hero__visual {
            min-height: auto;
            margin-top: -20px;
        }

        .poligonium-academy-hero__visual img,
        .poligonium-academy-hero__visual video {
            width: min(100%, 680px);
        }

        .poligonium-academy-service-strip {
            grid-template-columns: 1fr;
            padding: 26px;
        }

        .poligonium-academy-service-strip img {
            position: relative;
            right: auto;
            bottom: auto;
            width: 100%;
            margin: 6px 0 -22px;
        }
    }

    @media (max-width: 575px) {
        .poligonium-academy-page {
            padding-top: 72px;
        }

        .poligonium-academy-wrap {
            width: min(100% - 20px, 520px);
        }

        .poligonium-academy-hero h1 {
            font-size: 42px;
        }

        .poligonium-academy-actions,
        .poligonium-academy-section__head,
        .poligonium-academy-benefits,
        .poligonium-academy-course-grid,
        .poligonium-academy-grid,
        .poligonium-academy-proof {
            display: grid;
            grid-template-columns: 1fr;
        }

        .poligonium-academy-button {
            width: 100%;
        }

        .poligonium-academy-benefits article,
        .poligonium-academy-proof span {
            min-height: 84px;
            padding: 16px;
        }

        .poligonium-academy-proof span {
            border-right: 0;
            border-bottom: 1px solid rgba(15, 23, 42, .09);
        }

        .poligonium-academy-proof span:last-child {
            border-bottom: 0;
        }

        .poligonium-academy-course-card,
        .poligonium-academy-card {
            min-height: 310px;
        }

        .poligonium-academy-course-card__image,
        .poligonium-academy-card__media {
            height: 150px;
        }
    }
</style>

<style>
    .poligonium-school-page {
        position: relative;
        min-height: calc(100vh - 96px);
        overflow: hidden;
        padding: 48px max(18px, 2.4vw) 76px;
        color: #151515;
        background:
            radial-gradient(circle at 18% 10%, rgba(255, 205, 80, .26), transparent 26%),
            radial-gradient(circle at 84% 12%, rgba(26, 108, 255, .12), transparent 28%),
            linear-gradient(rgba(22, 22, 22, .052) 1px, transparent 1px),
            linear-gradient(90deg, rgba(22, 22, 22, .052) 1px, transparent 1px),
            #f2f0ea;
        background-size: auto, auto, 24px 24px, 24px 24px, auto;
    }

    .poligonium-school-page::before {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        background: linear-gradient(120deg, rgba(255,255,255,.68), transparent 45%, rgba(255,255,255,.32));
    }

    body:has(.poligonium-school-page.is-cabinet) > header {
        display: none !important;
    }

    .poligonium-school-page.is-cabinet {
        min-height: 100vh;
        padding-top: 24px;
    }

    .poligonium-school-orb {
        position: absolute;
        width: 280px;
        aspect-ratio: 1;
        border: 1px solid rgba(20, 20, 20, .16);
        border-radius: 999px;
        pointer-events: none;
        animation: schoolOrbit 12s ease-in-out infinite;
    }

    .poligonium-school-orb::after {
        content: "";
        position: absolute;
        inset: 46%;
        border-radius: 999px;
        background: #ff8a1f;
        box-shadow: 0 0 28px rgba(255, 138, 31, .50);
    }

    .poligonium-school-orb.is-left {
        left: -96px;
        top: 130px;
    }

    .poligonium-school-orb.is-right {
        right: -118px;
        bottom: 110px;
        animation-delay: -5s;
    }

    .poligonium-school-auth,
    .poligonium-student-dashboard {
        position: relative;
        z-index: 1;
        margin: 0 auto;
    }

    .poligonium-school-auth {
        max-width: 1240px;
    }

    .poligonium-school-auth {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(340px, 430px);
        gap: 34px;
        align-items: center;
    }

    .poligonium-school-kicker,
    .poligonium-student-label {
        margin: 0 0 12px;
        color: #d86100;
        font-size: 12px;
        font-weight: 900;
        letter-spacing: 0;
        text-transform: uppercase;
    }

    .poligonium-school-auth-copy h1 {
        margin: 0;
        max-width: 760px;
        color: #121212;
        font-size: clamp(42px, 6vw, 86px);
        line-height: .95;
        letter-spacing: 0;
    }

    .poligonium-school-auth-copy p {
        max-width: 560px;
        margin: 22px 0 0;
        color: rgba(20, 20, 20, .72);
        font-size: 18px;
        line-height: 1.65;
    }

    .poligonium-school-auth-points {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 30px;
    }

    .poligonium-school-auth-points span {
        border: 1px solid rgba(20, 20, 20, .16);
        border-radius: 999px;
        padding: 9px 14px;
        background: rgba(255,255,255,.58);
        font-size: 13px;
        font-weight: 800;
    }

    .poligonium-school-form {
        border: 1px solid rgba(20, 20, 20, .16);
        border-radius: 16px;
        padding: 26px;
        background:
            linear-gradient(rgba(20,20,20,.035) 1px, transparent 1px),
            linear-gradient(90deg, rgba(20,20,20,.035) 1px, transparent 1px),
            rgba(255, 255, 255, .72);
        background-size: 18px 18px;
        box-shadow: 0 24px 60px rgba(20, 20, 20, .12);
        backdrop-filter: blur(18px);
    }

    .poligonium-school-form-head {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: center;
        margin-bottom: 20px;
    }

    .poligonium-school-form-head span {
        color: #d86100;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .poligonium-school-form-head strong,
    .poligonium-school-form-head small {
        color: #161616;
        font-size: 14px;
        font-weight: 900;
    }

    .poligonium-school-form label {
        display: grid;
        gap: 8px;
        margin-top: 15px;
        color: #242424;
        font-size: 13px;
        font-weight: 900;
    }

    .poligonium-school-form label small {
        color: rgba(20, 20, 20, .56);
        font-size: 12px;
        font-weight: 700;
        line-height: 1.45;
    }

    .poligonium-school-form input {
        width: 100%;
        border: 1px solid rgba(20, 20, 20, .18);
        border-radius: 12px;
        padding: 13px 14px;
        background: rgba(255,255,255,.82);
        color: #171717;
        outline: none;
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }

    .poligonium-school-form input:focus {
        border-color: #ff8a1f;
        box-shadow: 0 0 0 4px rgba(255, 138, 31, .16);
        transform: translateY(-1px);
    }

    .poligonium-school-check {
        display: flex !important;
        grid-template-columns: none !important;
        align-items: center;
        gap: 10px !important;
    }

    .poligonium-school-check input {
        width: 18px;
        height: 18px;
    }

    .poligonium-school-form button {
        width: 100%;
        border: 0;
        border-radius: 12px;
        margin-top: 20px;
        padding: 14px 18px;
        background: #171717;
        color: #fff;
        font-weight: 900;
        cursor: pointer;
        transition: transform .2s ease, background .2s ease;
    }

    .poligonium-school-form button:hover {
        background: #ff8a1f;
        transform: translateY(-2px);
    }

    .poligonium-school-switch,
    .poligonium-school-alert {
        margin: 16px 0 0;
        color: rgba(20, 20, 20, .68);
        font-size: 14px;
    }

    .poligonium-school-switch a {
        color: #d86100;
        font-weight: 900;
    }

    .poligonium-school-alert {
        border: 1px solid rgba(255, 67, 43, .28);
        border-radius: 12px;
        padding: 11px 12px;
        background: rgba(255, 67, 43, .10);
        color: #a21c0d;
        font-weight: 800;
    }

    .poligonium-school-alert.is-success {
        border-color: rgba(28, 143, 85, .28);
        background: rgba(28, 143, 85, .10);
        color: #11683d;
    }

    .poligonium-student-dashboard {
        width: 100%;
        max-width: 1680px;
        border: 1px solid rgba(19, 20, 20, .16);
        border-radius: 28px;
        padding: 18px;
        background:
            radial-gradient(circle at 76% 18%, rgba(255, 217, 95, .58), transparent 30%),
            radial-gradient(circle at 8% 94%, rgba(255, 138, 31, .16), transparent 28%),
            rgba(250, 249, 245, .88);
        box-shadow: 0 34px 90px rgba(21, 23, 26, .20);
        backdrop-filter: blur(20px);
    }

    .poligonium-student-nav {
        display: grid;
        grid-template-columns: auto minmax(0, 1fr) auto;
        gap: 14px;
        align-items: center;
        margin-bottom: 26px;
    }

    .poligonium-student-brand,
    .poligonium-student-tabs,
    .poligonium-student-actions button,
    .poligonium-student-site-link,
    .poligonium-student-avatar {
        border: 1px solid rgba(16, 18, 20, .12);
        background: rgba(255, 255, 255, .72);
        box-shadow: 0 10px 28px rgba(16, 18, 20, .08);
    }

    .poligonium-student-brand {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        min-height: 42px;
        border-radius: 999px;
        padding: 0 16px;
        color: #171717;
        font-weight: 900;
        text-decoration: none;
    }

    .poligonium-student-brand strong {
        color: #111;
        font-size: 18px;
        line-height: 1;
    }

    .poligonium-student-brand span {
        border-left: 1px solid rgba(17, 17, 17, .16);
        padding-left: 9px;
        color: rgba(17, 17, 17, .62);
        font-size: 12px;
        line-height: 1;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .poligonium-student-tabs {
        display: flex;
        justify-content: center;
        gap: 4px;
        width: fit-content;
        max-width: 100%;
        margin: 0 auto;
        border-radius: 999px;
        padding: 5px;
        overflow-x: auto;
    }

    .poligonium-student-tabs a {
        flex: 0 0 auto;
        border-radius: 999px;
        padding: 9px 14px;
        color: rgba(18, 18, 18, .72);
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
        transition: background .2s ease, color .2s ease, transform .2s ease;
    }

    .poligonium-student-tabs a:hover,
    .poligonium-student-tabs a.is-active {
        background: #181818;
        color: #fff;
        transform: translateY(-1px);
    }

    .poligonium-student-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
    }

    .poligonium-student-actions form {
        margin: 0;
    }

    .poligonium-student-actions button,
    .poligonium-student-site-link {
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        padding: 0 15px;
        color: #171717;
        font-size: 13px;
        font-weight: 900;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s ease, color .2s ease, transform .2s ease;
    }

    .poligonium-student-actions button:hover,
    .poligonium-student-site-link:hover {
        background: #181818;
        color: #fff;
        transform: translateY(-1px);
    }

    .poligonium-student-avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 999px;
        color: #171717;
        font-weight: 900;
    }

    .poligonium-student-hero {
        display: grid;
        grid-template-columns: minmax(0, 1.4fr) minmax(230px, .55fr) minmax(260px, .75fr);
        gap: 16px;
        align-items: stretch;
    }

    .poligonium-student-welcome,
    .poligonium-student-profile-card,
    .poligonium-student-progress-card,
    .poligonium-student-stats article,
    .poligonium-student-panel {
        border: 1px solid rgba(18, 18, 18, .10);
        border-radius: 24px;
        background:
            linear-gradient(rgba(24, 24, 24, .032) 1px, transparent 1px),
            linear-gradient(90deg, rgba(24, 24, 24, .032) 1px, transparent 1px),
            rgba(255, 255, 255, .70);
        background-size: 18px 18px;
        box-shadow: 0 18px 46px rgba(16, 18, 20, .10);
    }

    .poligonium-student-welcome {
        min-height: 258px;
        padding: 28px 30px;
    }

    .poligonium-student-welcome h1 {
        max-width: 680px;
        margin: 0;
        color: #111;
        font-size: clamp(42px, 5vw, 74px);
        line-height: .98;
        letter-spacing: 0;
    }

    .poligonium-student-welcome p {
        max-width: 650px;
        margin: 18px 0 0;
        color: rgba(17, 17, 17, .66);
        font-size: 17px;
        line-height: 1.6;
    }

    .poligonium-student-meter,
    .poligonium-student-mini-meter {
        display: block;
        overflow: hidden;
        border-radius: 999px;
        background: repeating-linear-gradient(135deg, rgba(23, 23, 23, .09) 0 5px, rgba(23, 23, 23, .03) 5px 10px);
    }

    .poligonium-student-meter {
        height: 14px;
        margin-top: 26px;
    }

    .poligonium-student-meter span,
    .poligonium-student-mini-meter i {
        display: block;
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #181818, #ffbc3d);
    }

    .poligonium-student-meter-row {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        margin-top: 10px;
        color: rgba(17, 17, 17, .68);
        font-size: 13px;
        font-weight: 800;
    }

    .poligonium-student-meter-row strong {
        color: #171717;
    }

    .poligonium-student-profile-card {
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        min-height: 258px;
        padding: 20px;
        color: #fff;
        background:
            linear-gradient(180deg, rgba(255,255,255,.04), rgba(0,0,0,.34)),
            radial-gradient(circle at 50% 20%, rgba(255, 193, 67, .70), transparent 35%),
            #202020;
    }

    .poligonium-student-portrait {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 118px;
        height: 118px;
        margin: 0 auto 22px;
        border: 6px solid rgba(255,255,255,.82);
        border-radius: 28px;
        background: linear-gradient(145deg, #ffbc3d, #ff7d1a);
        color: #151515;
        font-size: 62px;
        font-weight: 1000;
        box-shadow: 0 18px 34px rgba(0,0,0,.26);
    }

    .poligonium-student-profile-card strong,
    .poligonium-student-profile-card span,
    .poligonium-student-profile-card p {
        position: relative;
        z-index: 1;
    }

    .poligonium-student-profile-card strong {
        font-size: 20px;
        font-weight: 900;
    }

    .poligonium-student-profile-card span,
    .poligonium-student-profile-card p {
        color: rgba(255,255,255,.78);
        font-size: 13px;
        line-height: 1.45;
    }

    .poligonium-student-profile-card p {
        margin: 10px 0 0;
    }

    .poligonium-student-progress-card {
        display: grid;
        grid-template-columns: 132px minmax(0, 1fr);
        gap: 18px;
        align-items: center;
        min-height: 258px;
        padding: 22px;
    }

    .poligonium-student-circle {
        display: grid;
        place-items: center;
        width: 132px;
        aspect-ratio: 1;
        border-radius: 999px;
        background:
            radial-gradient(circle, #fbfaf6 0 55%, transparent 56%),
            conic-gradient(#ffbd3d var(--student-progress), rgba(23,23,23,.12) 0);
        box-shadow: inset 0 0 0 1px rgba(20,20,20,.08);
    }

    .poligonium-student-circle strong,
    .poligonium-student-circle span {
        grid-area: 1 / 1;
        text-align: center;
    }

    .poligonium-student-circle strong {
        margin-top: -12px;
        color: #111;
        font-size: 31px;
        line-height: 1;
    }

    .poligonium-student-circle span {
        margin-top: 34px;
        color: rgba(17,17,17,.62);
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .poligonium-student-progress-card > div:last-child > strong {
        display: block;
        color: #151515;
        font-size: 21px;
        line-height: 1.15;
    }

    .poligonium-student-progress-card p {
        margin: 10px 0 0;
        color: rgba(17, 17, 17, .62);
        font-size: 13px;
        line-height: 1.5;
    }

    .poligonium-student-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin: 16px 0;
    }

    .poligonium-student-stats article {
        padding: 22px;
    }

    .poligonium-student-stats span,
    .poligonium-student-panel-head span {
        display: block;
        color: rgba(17,17,17,.56);
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .poligonium-student-stats strong {
        display: inline-block;
        margin-top: 8px;
        color: #111;
        font-size: 42px;
        line-height: .9;
        font-weight: 500;
    }

    .poligonium-student-stats small {
        color: rgba(17,17,17,.62);
        font-size: 13px;
        font-weight: 900;
    }

    .poligonium-student-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.35fr) minmax(300px, .65fr);
        gap: 16px;
    }

    .poligonium-student-panel {
        padding: 22px;
    }

    .poligonium-student-panel.is-large {
        min-height: 292px;
    }

    .poligonium-student-panel.is-dark {
        color: #fff;
        background:
            linear-gradient(rgba(255,255,255,.045) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.045) 1px, transparent 1px),
            #202020;
        background-size: 18px 18px;
    }

    .poligonium-student-panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
    }

    .poligonium-student-panel-head strong {
        display: block;
        margin-top: 4px;
        color: #151515;
        font-size: 21px;
        line-height: 1.15;
    }

    .poligonium-student-panel.is-dark .poligonium-student-panel-head span,
    .poligonium-student-panel.is-dark .poligonium-student-muted {
        color: rgba(255,255,255,.58);
    }

    .poligonium-student-panel.is-dark .poligonium-student-panel-head strong {
        color: #fff;
    }

    .poligonium-student-panel-head a {
        border-radius: 999px;
        padding: 9px 13px;
        background: #181818;
        color: #fff;
        font-size: 12px;
        font-weight: 900;
        text-decoration: none;
        transition: background .2s ease, transform .2s ease;
    }

    .poligonium-student-panel-head a:hover {
        background: #ff8a1f;
        transform: translateY(-1px);
    }

    .poligonium-student-course-list,
    .poligonium-student-list,
    .poligonium-student-gallery {
        display: grid;
        gap: 12px;
    }

    .poligonium-student-course-row,
    .poligonium-student-list article,
    .poligonium-student-empty,
    .poligonium-student-next,
    .poligonium-student-gallery article {
        border: 1px solid rgba(17,17,17,.10);
        border-radius: 18px;
        background: rgba(255,255,255,.62);
    }

    .poligonium-student-course-row {
        display: grid;
        grid-template-columns: 74px minmax(0, 1fr) auto;
        gap: 14px;
        align-items: center;
        padding: 12px;
        color: #171717;
        text-decoration: none;
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }

    .poligonium-student-course-row:hover {
        border-color: rgba(255, 138, 31, .70);
        box-shadow: 0 14px 30px rgba(255, 138, 31, .12);
        transform: translateY(-2px);
    }

    .poligonium-student-course-thumb {
        display: grid;
        place-items: center;
        overflow: hidden;
        width: 74px;
        aspect-ratio: 1;
        border-radius: 16px;
        background: linear-gradient(145deg, #171717, #ffbd3d);
        color: #fff;
        font-size: 28px;
        font-weight: 1000;
    }

    .poligonium-student-course-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .poligonium-student-course-main strong,
    .poligonium-student-list strong,
    .poligonium-student-next strong,
    .poligonium-student-empty strong,
    .poligonium-student-gallery strong {
        display: block;
        color: #151515;
        font-size: 16px;
        font-weight: 900;
        line-height: 1.25;
    }

    .poligonium-student-course-main small,
    .poligonium-student-countdown,
    .poligonium-student-list span,
    .poligonium-student-list em,
    .poligonium-student-next span,
    .poligonium-student-next p,
    .poligonium-student-empty p,
    .poligonium-student-gallery span,
    .poligonium-student-muted {
        display: block;
        margin: 6px 0 0;
        color: rgba(17,17,17,.62);
        font-size: 13px;
        font-style: normal;
        line-height: 1.45;
    }

    .poligonium-student-mini-meter {
        height: 8px;
        margin-top: 12px;
    }

    .poligonium-student-countdown {
        margin-top: 8px;
        color: #9a4a08;
        font-size: 12px;
        font-weight: 900;
    }

    .poligonium-student-countdown b {
        color: #151515;
    }

    .poligonium-student-course-row > em {
        border-radius: 999px;
        padding: 9px 10px;
        background: rgba(255, 189, 61, .28);
        color: #171717;
        font-size: 13px;
        font-style: normal;
        font-weight: 900;
    }

    .poligonium-student-next {
        min-height: 190px;
        padding: 18px;
        background:
            radial-gradient(circle at 74% 18%, rgba(255, 189, 61, .32), transparent 26%),
            rgba(255,255,255,.08);
    }

    .poligonium-student-next strong,
    .poligonium-student-next span,
    .poligonium-student-next p {
        color: #fff;
    }

    .poligonium-student-next span,
    .poligonium-student-next p {
        color: rgba(255,255,255,.68);
    }

    .poligonium-student-list article,
    .poligonium-student-empty {
        padding: 14px;
    }

    .poligonium-student-list em {
        width: fit-content;
        border-radius: 999px;
        padding: 6px 9px;
        background: rgba(24,24,24,.08);
        color: #171717;
        font-weight: 900;
    }

    .poligonium-student-gallery {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .poligonium-student-gallery article {
        overflow: hidden;
        padding: 10px;
    }

    .poligonium-student-gallery img {
        width: 100%;
        aspect-ratio: 4 / 5;
        object-fit: cover;
        border-radius: 13px;
        margin-bottom: 10px;
    }

    .poligonium-student-gallery .poligonium-student-empty {
        grid-column: 1 / -1;
    }

    @keyframes schoolOrbit {
        0%, 100% { transform: translate3d(0, 0, 0) rotate(0deg); }
        50% { transform: translate3d(18px, -14px, 0) rotate(8deg); }
    }

    @media (max-width: 1100px) {
        .poligonium-student-nav,
        .poligonium-student-hero,
        .poligonium-student-grid {
            grid-template-columns: 1fr;
        }

        .poligonium-student-tabs {
            justify-content: flex-start;
            width: 100%;
            margin: 0;
        }

        .poligonium-student-actions {
            justify-content: flex-start;
        }

        .poligonium-student-gallery {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .poligonium-school-page {
            padding: 46px 18px 70px;
        }

        .poligonium-school-auth {
            grid-template-columns: 1fr;
        }

        .poligonium-school-auth-copy h1 {
            font-size: 44px;
        }

        .poligonium-student-dashboard {
            border-radius: 24px;
            padding: 14px;
        }

        .poligonium-student-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .poligonium-student-welcome h1 {
            font-size: 42px;
        }
    }

    @media (max-width: 620px) {
        .poligonium-student-nav {
            gap: 10px;
        }

        .poligonium-student-tabs {
            padding: 4px;
        }

        .poligonium-student-tabs a {
            padding: 8px 11px;
            font-size: 12px;
        }

        .poligonium-student-welcome,
        .poligonium-student-panel,
        .poligonium-student-stats article {
            padding: 16px;
        }

        .poligonium-student-progress-card,
        .poligonium-student-course-row {
            grid-template-columns: 1fr;
        }

        .poligonium-student-circle {
            width: 124px;
        }

        .poligonium-student-stats,
        .poligonium-student-gallery {
            grid-template-columns: 1fr;
        }
    }
</style>

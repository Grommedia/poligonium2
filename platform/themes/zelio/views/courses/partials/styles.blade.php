<style>
    .poligonium-courses-page {
        position: relative;
        isolation: isolate;
        overflow: visible;
        margin-top: -1px;
        background:
            radial-gradient(circle at 16% 12%, rgba(255, 135, 45, .18), transparent 32%),
            radial-gradient(circle at 88% 18%, rgba(31, 111, 235, .12), transparent 30%),
            linear-gradient(90deg, rgba(17, 24, 39, .055) 1px, transparent 1px),
            linear-gradient(0deg, rgba(17, 24, 39, .055) 1px, transparent 1px),
            #fbfaf8;
        background-size: auto, auto, 36px 36px, 36px 36px, auto;
        color: #111827;
    }

    .poligonium-courses-page::before {
        position: absolute;
        inset: 0;
        z-index: -2;
        background:
            linear-gradient(115deg, rgba(255, 255, 255, .78), transparent 34%),
            linear-gradient(290deg, rgba(255, 126, 32, .12), transparent 42%);
        content: "";
        pointer-events: none;
    }

    .poligonium-courses-orb {
        position: absolute;
        z-index: -1;
        border: 1px solid rgba(17, 24, 39, .12);
        border-radius: 999px;
        pointer-events: none;
    }

    .poligonium-courses-orb::before,
    .poligonium-courses-orb::after {
        position: absolute;
        border: 1px solid rgba(255, 126, 32, .35);
        border-radius: inherit;
        content: "";
    }

    .poligonium-courses-orb::before {
        inset: 18%;
        animation: poligoniumCourseSpin 16s linear infinite;
    }

    .poligonium-courses-orb::after {
        inset: 34%;
        background: rgba(255, 126, 32, .18);
        box-shadow: 0 0 28px rgba(255, 126, 32, .28);
        animation: poligoniumCoursePulse 2.8s ease-in-out infinite;
    }

    .poligonium-courses-orb.is-one {
        top: 118px;
        right: max(24px, 5vw);
        width: 190px;
        height: 190px;
    }

    .poligonium-courses-orb.is-two {
        bottom: 70px;
        left: max(18px, 4vw);
        width: 130px;
        height: 130px;
        opacity: .72;
    }

    .poligonium-courses-wrap {
        width: min(100% - 48px, 1280px);
        margin: 0 auto;
        padding: clamp(104px, 11vw, 146px) 0 90px;
    }

    .poligonium-courses-hero {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(280px, 380px);
        gap: clamp(22px, 4vw, 52px);
        align-items: end;
    }

    .poligonium-courses-hero > div:first-child {
        padding-left: clamp(112px, 10vw, 152px);
    }

    .poligonium-courses-kicker {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 14px;
        color: var(--primary-color);
        font-size: 14px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .poligonium-courses-kicker::before {
        width: 34px;
        height: 2px;
        background: currentColor;
        content: "";
    }

    .poligonium-courses-hero h1,
    .poligonium-course-detail h1,
    .poligonium-lesson-main h1 {
        max-width: 940px;
        margin: 0;
        color: #111827;
        font-size: clamp(48px, 8vw, 104px);
        font-weight: 950;
        line-height: .94;
        letter-spacing: 0;
    }

    .poligonium-courses-catalog {
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(17, 24, 39, .14);
        border-radius: 8px;
        background:
            linear-gradient(90deg, rgba(17, 24, 39, .045) 1px, transparent 1px),
            linear-gradient(0deg, rgba(17, 24, 39, .045) 1px, transparent 1px),
            rgba(255, 255, 255, .62);
        background-size: 18px 18px, 18px 18px, auto;
        box-shadow: 0 22px 60px rgba(17, 24, 39, .11);
        padding: 22px 24px;
        backdrop-filter: blur(14px);
    }

    .poligonium-courses-catalog::after {
        position: absolute;
        top: -60%;
        left: -40%;
        width: 48%;
        height: 220%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .76), transparent);
        content: "";
        transform: rotate(18deg);
        animation: poligoniumCourseSweep 5.5s ease-in-out infinite;
    }

    .poligonium-courses-catalog span {
        display: inline-flex;
        margin-bottom: 14px;
        border: 1px solid rgba(255, 126, 32, .35);
        border-radius: 999px;
        background: rgba(255, 126, 32, .1);
        padding: 6px 11px;
        color: #b45309;
        font-size: 12px;
        font-weight: 900;
    }

    .poligonium-courses-catalog strong {
        display: block;
        color: #111827;
        font-size: 25px;
        font-weight: 950;
        line-height: 1.05;
    }

    .poligonium-courses-catalog p,
    .poligonium-courses-hero p,
    .poligonium-course-detail p,
    .poligonium-lesson-main p {
        max-width: 760px;
        margin-bottom: 0;
        color: #4b5563;
        font-size: 17px;
        line-height: 1.65;
    }

    .poligonium-courses-catalog p {
        margin-top: 10px;
        font-size: 15px;
        line-height: 1.55;
    }

    .poligonium-course-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 22px;
        margin-top: 46px;
        overflow: visible;
    }

    .poligonium-course-card {
        position: relative;
        display: flex;
        min-height: 560px;
        overflow: visible;
        flex-direction: column;
        border: 1px solid rgba(17, 24, 39, .14);
        border-radius: 8px;
        background:
            linear-gradient(90deg, rgba(17, 24, 39, .04) 1px, transparent 1px),
            linear-gradient(0deg, rgba(17, 24, 39, .04) 1px, transparent 1px),
            rgba(255, 255, 255, .74);
        background-size: 22px 22px, 22px 22px, auto;
        box-shadow: 0 22px 58px rgba(17, 24, 39, .1);
        color: #111827;
        text-decoration: none;
        transform: translateY(0);
        transition: border-color .26s ease, box-shadow .26s ease, transform .26s ease;
        backdrop-filter: blur(14px);
    }

    .poligonium-course-card::before,
    .poligonium-course-card::after {
        position: absolute;
        z-index: 2;
        width: 58px;
        height: 58px;
        border-color: rgba(17, 24, 39, .42);
        border-style: solid;
        content: "";
        pointer-events: none;
        transition: border-color .26s ease, transform .26s ease;
    }

    .poligonium-course-card::before {
        top: 16px;
        left: 16px;
        border-width: 1px 0 0 1px;
    }

    .poligonium-course-card::after {
        right: 16px;
        bottom: 16px;
        border-width: 0 1px 1px 0;
    }

    .poligonium-course-card:hover,
    .poligonium-course-card:focus-visible {
        border-color: rgba(255, 126, 32, .48);
        box-shadow: 0 30px 78px rgba(17, 24, 39, .14), 0 0 0 1px rgba(255, 126, 32, .14);
        color: #111827;
        outline: 0;
        text-decoration: none;
        transform: translateY(-8px);
    }

    .poligonium-course-card.is-release-locked {
        cursor: pointer;
    }

    .poligonium-course-card.is-release-locked .poligonium-course-media img {
        filter: saturate(.86) contrast(.95);
    }

    .poligonium-course-card:hover::before,
    .poligonium-course-card:hover::after,
    .poligonium-course-card:focus-visible::before,
    .poligonium-course-card:focus-visible::after {
        border-color: rgba(255, 126, 32, .72);
        transform: scale(1.08);
    }

    .poligonium-course-card-glow {
        position: absolute;
        inset: -40% auto auto -55%;
        width: 52%;
        height: 180%;
        z-index: 1;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .76), transparent);
        opacity: 0;
        transform: rotate(18deg) translateX(-60%);
        transition: opacity .24s ease, transform .6s ease;
        pointer-events: none;
    }

    .poligonium-course-card:hover .poligonium-course-card-glow,
    .poligonium-course-card:focus-visible .poligonium-course-card-glow {
        opacity: 1;
        transform: rotate(18deg) translateX(320%);
    }

    .poligonium-course-media {
        position: relative;
        display: grid;
        min-height: 245px;
        place-items: center;
        overflow: hidden;
        border-radius: 8px 8px 0 0;
        border-bottom: 1px solid rgba(17, 24, 39, .08);
        background:
            radial-gradient(circle at 52% 40%, rgba(255, 126, 32, .2), transparent 28%),
            radial-gradient(circle at 35% 62%, rgba(31, 111, 235, .14), transparent 30%),
            linear-gradient(135deg, rgba(255, 255, 255, .68), rgba(255, 245, 232, .74));
        color: #2563eb;
        font-weight: 900;
    }

    .poligonium-course-media::before {
        display: none;
        content: none;
    }

    .poligonium-course-media img {
        width: 100%;
        height: 100%;
        min-height: 245px;
        object-fit: cover;
        transition: transform .35s ease;
    }

    .poligonium-course-card:hover .poligonium-course-media img,
    .poligonium-course-card:focus-visible .poligonium-course-media img {
        transform: scale(1.04);
    }

    .poligonium-course-media-label {
        position: absolute;
        right: 22px;
        bottom: 20px;
        z-index: 2;
        border: 1px solid rgba(17, 24, 39, .12);
        border-radius: 999px;
        background: rgba(255, 255, 255, .78);
        padding: 8px 12px;
        color: #111827;
        font-size: 12px;
        font-weight: 900;
        box-shadow: 0 12px 30px rgba(17, 24, 39, .08);
        backdrop-filter: blur(8px);
    }

    .poligonium-course-release-badge {
        position: absolute;
        top: 112px;
        right: -58px;
        z-index: 9;
        display: grid;
        place-items: center;
        width: 220px;
        aspect-ratio: 1;
        border: 2px solid rgba(17, 24, 39, .16);
        border-radius: 50%;
        background:
            radial-gradient(circle at 36% 24%, rgba(255,255,255,.92), transparent 26%),
            linear-gradient(135deg, #fff7ed, #ffbe59 46%, #ff7a1a);
        color: #111827;
        box-shadow: 0 26px 70px rgba(255, 126, 32, .38), 0 18px 42px rgba(17, 24, 39, .18);
        text-align: center;
        transform: rotate(4deg);
        transition: transform .24s ease, box-shadow .24s ease;
    }

    .poligonium-course-release-badge::before {
        position: absolute;
        inset: 16px;
        border: 2px dashed rgba(17, 24, 39, .28);
        border-radius: inherit;
        content: "";
        animation: poligoniumCourseBadgeSpin 18s linear infinite;
    }

    .poligonium-course-card:hover .poligonium-course-release-badge,
    .poligonium-course-card:focus-visible .poligonium-course-release-badge {
        box-shadow: 0 32px 82px rgba(255, 126, 32, .46), 0 22px 48px rgba(17, 24, 39, .2);
        transform: rotate(0deg) scale(1.03);
    }

    .poligonium-course-release-badge small,
    .poligonium-course-release-badge strong,
    .poligonium-course-release-badge__days,
    .poligonium-course-release-badge em {
        position: relative;
        z-index: 1;
        display: block;
    }

    .poligonium-course-release-badge small {
        max-width: 148px;
        color: rgba(17, 24, 39, .72);
        font-size: 16px;
        font-weight: 950;
        line-height: 1.05;
        text-transform: uppercase;
    }

    .poligonium-course-release-badge strong {
        margin-top: 7px;
        color: #111827;
        font-size: 72px;
        font-weight: 950;
        line-height: .9;
    }

    .poligonium-course-release-badge__days {
        margin-top: 4px;
        color: rgba(17, 24, 39, .76);
        font-size: 17px;
        font-weight: 950;
        line-height: 1;
        text-transform: lowercase;
    }

    .poligonium-course-release-badge em {
        margin-top: 12px;
        max-width: 154px;
        color: rgba(17, 24, 39, .72);
        font-size: 14px;
        font-style: normal;
        font-weight: 950;
        letter-spacing: 0;
        line-height: 1.18;
    }

    .poligonium-course-lab-preview {
        position: relative;
        display: none;
        width: 170px;
        height: 170px;
    }

    .poligonium-course-spider-body {
        position: absolute;
        inset: 34px;
        border: 1px solid rgba(255, 255, 255, .75);
        border-radius: 48% 52% 46% 54%;
        background:
            radial-gradient(circle at 35% 28%, rgba(255, 255, 255, .9), transparent 18%),
            linear-gradient(135deg, rgba(180, 226, 255, .56), rgba(255, 126, 32, .32));
        box-shadow: inset 0 0 28px rgba(255, 255, 255, .55), 0 18px 42px rgba(17, 24, 39, .18);
    }

    .poligonium-course-spider-leg {
        position: absolute;
        width: 72px;
        height: 9px;
        border-radius: 999px;
        background: linear-gradient(90deg, #1f2937, #6b7280, #111827);
        transform-origin: center right;
        box-shadow: 0 10px 20px rgba(17, 24, 39, .16);
    }

    .poligonium-course-spider-leg.is-1 { top: 56px; left: 4px; transform: rotate(18deg); }
    .poligonium-course-spider-leg.is-2 { top: 82px; left: 0; transform: rotate(2deg); }
    .poligonium-course-spider-leg.is-3 { top: 108px; left: 9px; transform: rotate(-22deg); }
    .poligonium-course-spider-leg.is-4 { top: 56px; right: 4px; transform: rotate(162deg); }
    .poligonium-course-spider-leg.is-5 { top: 82px; right: 0; transform: rotate(178deg); }
    .poligonium-course-spider-leg.is-6 { top: 108px; right: 9px; transform: rotate(202deg); }

    .poligonium-course-spider-spark {
        display: none;
    }

    .poligonium-course-spider-spark.is-a { top: 18px; left: 78px; }
    .poligonium-course-spider-spark.is-b { top: 72px; right: 14px; animation-delay: .35s; }
    .poligonium-course-spider-spark.is-c { bottom: 20px; left: 30px; animation-delay: .72s; }

    .poligonium-course-body {
        position: relative;
        z-index: 3;
        display: flex;
        flex: 1;
        flex-direction: column;
        padding: 22px;
    }

    .poligonium-course-meta,
    .poligonium-course-bottom,
    .poligonium-course-stats,
    .poligonium-course-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .poligonium-course-meta span,
    .poligonium-course-stats span,
    .poligonium-lesson-row strong,
    .poligonium-course-bottom strong {
        border-radius: 999px;
        background: rgba(17, 24, 39, .08);
        padding: 7px 11px;
        color: #111827;
        font-size: 13px;
        font-weight: 900;
    }

    .poligonium-course-tags {
        margin-top: 14px;
    }

    .poligonium-course-tags span {
        border: 1px solid rgba(255, 126, 32, .18);
        border-radius: 999px;
        background:
            linear-gradient(90deg, rgba(255, 126, 32, .06) 1px, transparent 1px),
            linear-gradient(0deg, rgba(255, 126, 32, .06) 1px, transparent 1px),
            rgba(255, 255, 255, .66);
        background-size: 14px 14px, 14px 14px, auto;
        padding: 6px 10px;
        color: #1f2937;
        font-size: 12px;
        font-weight: 850;
    }

    .poligonium-course-tags.is-detail {
        margin-top: 20px;
    }

    .poligonium-course-title {
        display: block;
        margin: 18px 0 10px;
        color: #111827;
        font-size: 30px;
        font-weight: 950;
        line-height: 1.08;
    }

    .poligonium-course-description {
        display: block;
        color: #4b5563;
        font-size: 15px;
        line-height: 1.62;
    }

    .poligonium-course-bottom {
        margin-top: auto;
        padding-top: 22px;
        justify-content: space-between;
    }

    .poligonium-course-bottom > span {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #111827;
        font-weight: 950;
    }

    .poligonium-course-bottom strong small {
        display: block;
        margin-top: 3px;
        color: #6b7280;
        font-size: 11px;
        font-weight: 800;
    }

    .poligonium-course-bottom > span::after {
        width: 30px;
        height: 30px;
        display: inline-grid;
        place-items: center;
        border-radius: 50%;
        background: #ff7e20;
        color: #111827;
        content: "→";
        transition: transform .22s ease;
    }

    .poligonium-course-card:hover .poligonium-course-bottom > span::after,
    .poligonium-course-card:focus-visible .poligonium-course-bottom > span::after {
        transform: translateX(4px);
    }

    .poligonium-course-spark {
        position: absolute;
        z-index: 8;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #ff7e20;
        box-shadow: 0 0 18px rgba(255, 126, 32, .95), 0 0 2px #fff;
        pointer-events: none;
        animation: poligoniumCoursePointerSpark .76s ease-out forwards;
    }

    .poligonium-course-release-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: grid;
        place-items: center;
        padding: 22px;
        opacity: 0;
        pointer-events: none;
        transition: opacity .22s ease;
    }

    .poligonium-course-release-modal[aria-hidden="false"] {
        opacity: 1;
        pointer-events: auto;
    }

    .poligonium-course-release-modal__backdrop {
        position: absolute;
        inset: 0;
        background: rgba(17, 24, 39, .58);
        backdrop-filter: blur(12px);
    }

    .poligonium-course-release-modal__dialog {
        position: relative;
        width: min(100%, 560px);
        overflow: hidden;
        border: 1px solid rgba(17, 24, 39, .16);
        border-radius: 18px;
        background:
            radial-gradient(circle at 88% 16%, rgba(255, 126, 32, .24), transparent 34%),
            linear-gradient(90deg, rgba(17, 24, 39, .04) 1px, transparent 1px),
            linear-gradient(0deg, rgba(17, 24, 39, .04) 1px, transparent 1px),
            #fffaf3;
        background-size: auto, 22px 22px, 22px 22px, auto;
        padding: 30px;
        box-shadow: 0 30px 90px rgba(17, 24, 39, .28);
        transform: translateY(16px) scale(.98);
        transition: transform .22s ease;
    }

    .poligonium-course-release-modal[aria-hidden="false"] .poligonium-course-release-modal__dialog {
        transform: translateY(0) scale(1);
    }

    .poligonium-course-release-modal__close {
        position: absolute;
        top: 14px;
        right: 14px;
        display: grid;
        width: 38px;
        aspect-ratio: 1;
        place-items: center;
        border: 1px solid rgba(17, 24, 39, .12);
        border-radius: 50%;
        background: rgba(255,255,255,.72);
        color: #111827;
        font-size: 25px;
        line-height: 1;
        cursor: pointer;
    }

    .poligonium-course-release-modal__kicker {
        display: inline-flex;
        border-radius: 999px;
        background: rgba(255, 126, 32, .12);
        padding: 7px 11px;
        color: #b45309;
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
    }

    .poligonium-course-release-modal h2 {
        margin: 18px 0 10px;
        color: #111827;
        font-size: clamp(34px, 5vw, 54px);
        font-weight: 950;
        line-height: .98;
        letter-spacing: 0;
    }

    .poligonium-course-release-modal p {
        margin: 0;
        color: #4b5563;
        font-size: 16px;
        line-height: 1.65;
    }

    .poligonium-course-release-modal__timer {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px;
        margin-top: 22px;
    }

    .poligonium-course-release-modal__timer span {
        display: grid;
        min-height: 82px;
        place-items: center;
        border: 1px solid rgba(17, 24, 39, .12);
        border-radius: 14px;
        background: rgba(255,255,255,.68);
        color: rgba(17, 24, 39, .66);
        font-size: 11px;
        font-weight: 950;
        text-transform: uppercase;
    }

    .poligonium-course-release-modal__timer strong {
        display: block;
        color: #111827;
        font-size: 30px;
        line-height: 1;
    }

    .poligonium-course-release-modal__button {
        width: 100%;
        border: 0;
        border-radius: 14px;
        margin-top: 20px;
        padding: 14px 18px;
        background: #111827;
        color: #fff;
        font-weight: 950;
        cursor: pointer;
        transition: background .22s ease, transform .22s ease;
    }

    .poligonium-course-release-modal__button:hover {
        background: #ff7a1a;
        transform: translateY(-2px);
    }

    html.has-course-release-modal,
    html.has-course-release-modal body {
        overflow: hidden;
    }

    .poligonium-course-body a,
    .poligonium-course-bottom a,
    .poligonium-back-link {
        color: #111827;
        font-weight: 850;
        text-decoration: none;
    }

    .poligonium-course-detail,
    .poligonium-lesson-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(320px, .42fr);
        gap: 24px;
        align-items: start;
    }

    .poligonium-course-card,
    .poligonium-course-side,
    .poligonium-chapter,
    .poligonium-lesson-main,
    .poligonium-lesson-sidebar,
    .poligonium-course-empty {
        border: 1px solid rgba(17, 24, 39, .1);
        border-radius: 8px;
        background:
            linear-gradient(90deg, rgba(17, 24, 39, .035) 1px, transparent 1px),
            linear-gradient(0deg, rgba(17, 24, 39, .035) 1px, transparent 1px),
            rgba(255, 255, 255, .74);
        background-size: 24px 24px, 24px 24px, auto;
        box-shadow: 0 20px 54px rgba(17, 24, 39, .08);
        backdrop-filter: blur(14px);
    }

    .poligonium-course-side,
    .poligonium-course-empty {
        padding: 26px;
    }

    .poligonium-course-notice {
        width: fit-content;
        max-width: 100%;
        margin: 18px 0 0;
        border: 1px solid rgba(28, 143, 85, .26);
        border-radius: 12px;
        padding: 11px 14px;
        background: rgba(28, 143, 85, .10);
        color: #11683d;
        font-size: 14px;
        font-weight: 800;
    }

    .poligonium-course-notice.is-error {
        border-color: rgba(255, 67, 43, .28);
        background: rgba(255, 67, 43, .10);
        color: #a21c0d;
    }

    .poligonium-course-buy-button,
    .poligonium-course-buy-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        width: 100%;
        border: 0;
        border-radius: 14px;
        margin-top: 18px;
        padding: 15px 18px;
        background: #171717;
        color: #fff;
        font-weight: 900;
        text-decoration: none;
        cursor: pointer;
        transition: transform .22s ease, background .22s ease, box-shadow .22s ease;
    }

    .poligonium-course-buy-button span {
        border-radius: 999px;
        padding: 5px 9px;
        background: rgba(255,255,255,.14);
        font-size: 12px;
    }

    .poligonium-course-buy-button:hover,
    .poligonium-course-buy-link:hover {
        background: #ff7a1a;
        color: #fff;
        box-shadow: 0 16px 34px rgba(255, 122, 26, .26);
        transform: translateY(-2px);
    }

    .poligonium-course-buy-note {
        display: block;
        margin-top: 11px;
        color: rgba(20,20,20,.58);
        font-size: 12px;
        line-height: 1.45;
    }

    .poligonium-course-buy-link.is-muted {
        border: 1px solid rgba(17, 24, 39, .12);
        background: rgba(255, 255, 255, .72);
        color: #111827;
    }

    .poligonium-course-detail-countdown {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 8px;
        margin-top: 18px;
    }

    .poligonium-course-detail-countdown span {
        display: grid;
        min-height: 74px;
        place-items: center;
        border: 1px solid rgba(255, 126, 32, .22);
        border-radius: 14px;
        background:
            linear-gradient(90deg, rgba(17, 24, 39, .035) 1px, transparent 1px),
            linear-gradient(0deg, rgba(17, 24, 39, .035) 1px, transparent 1px),
            rgba(255, 255, 255, .72);
        background-size: 18px 18px, 18px 18px, auto;
        color: #6b7280;
        font-size: 11px;
        font-weight: 950;
        text-transform: uppercase;
    }

    .poligonium-course-detail-countdown strong {
        display: block;
        color: #111827;
        font-size: 28px;
        line-height: 1;
    }

    .poligonium-course-content,
    .poligonium-curriculum {
        margin-top: 34px;
    }

    .poligonium-chapter,
    .poligonium-lesson-main,
    .poligonium-lesson-sidebar {
        padding: 24px;
    }

    .poligonium-chapter {
        margin-top: 16px;
    }

    .poligonium-lesson-row,
    .poligonium-file-row,
    .poligonium-lesson-sidebar a {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-top: 10px;
        border: 1px solid rgba(17, 24, 39, .08);
        border-radius: 12px;
        background: rgba(255, 255, 255, .68);
        padding: 13px 14px;
        color: #111827;
        text-decoration: none;
    }

    .poligonium-lesson-row.is-disabled {
        cursor: default;
        opacity: .68;
    }

    .poligonium-lesson-row.is-disabled strong {
        background: rgba(255, 126, 32, .16);
        color: #9a4a08;
    }

    .poligonium-player {
        display: grid;
        min-height: 420px;
        margin-top: 24px;
        place-items: center;
        overflow: hidden;
        border-radius: 18px;
        background: #111827;
        color: #fff;
    }

    .poligonium-player iframe,
    .poligonium-player video {
        width: 100%;
        height: 100%;
        min-height: 420px;
        border: 0;
    }

    .poligonium-player-locked,
    .poligonium-player-empty {
        max-width: 480px;
        padding: 32px;
        text-align: center;
    }

    .poligonium-lesson-sidebar {
        position: sticky;
        top: 112px;
    }

    .poligonium-lesson-sidebar h3 {
        margin: 18px 0 8px;
        font-size: 18px;
    }

    .poligonium-lesson-sidebar a.is-active {
        border-color: rgba(255, 126, 32, .38);
        background: rgba(255, 126, 32, .08);
    }

    @keyframes poligoniumCourseSpin {
        to {
            transform: rotate(360deg);
        }
    }

    @keyframes poligoniumCoursePulse {
        50% {
            opacity: .55;
            transform: scale(1.18);
        }
    }

    @keyframes poligoniumCourseSweep {
        0%, 38% {
            transform: rotate(18deg) translateX(-80%);
        }
        72%, 100% {
            transform: rotate(18deg) translateX(360%);
        }
    }

    @keyframes poligoniumCourseFloat {
        50% {
            transform: translateY(-9px) rotate(1deg);
        }
    }

    @keyframes poligoniumCourseSparkle {
        50% {
            opacity: .55;
            transform: translateY(-8px) scale(1.5);
        }
    }

    @keyframes poligoniumCoursePointerSpark {
        to {
            opacity: 0;
            transform: translate(var(--spark-x), var(--spark-y)) scale(.2);
        }
    }

    @keyframes poligoniumCourseBadgeSpin {
        to {
            transform: rotate(360deg);
        }
    }

    html[data-bs-theme="dark"] .poligonium-courses-page {
        background:
            radial-gradient(circle at 16% 12%, rgba(255, 135, 45, .12), transparent 32%),
            radial-gradient(circle at 88% 18%, rgba(31, 111, 235, .1), transparent 30%),
            linear-gradient(90deg, rgba(255, 255, 255, .045) 1px, transparent 1px),
            linear-gradient(0deg, rgba(255, 255, 255, .045) 1px, transparent 1px),
            #111827;
        color: #f9fafb;
    }

    html[data-bs-theme="dark"] .poligonium-courses-hero h1,
    html[data-bs-theme="dark"] .poligonium-course-detail h1,
    html[data-bs-theme="dark"] .poligonium-lesson-main h1,
    html[data-bs-theme="dark"] .poligonium-course-title,
    html[data-bs-theme="dark"] .poligonium-courses-catalog strong,
    html[data-bs-theme="dark"] .poligonium-course-bottom > span,
    html[data-bs-theme="dark"] .poligonium-course-card {
        color: #f9fafb;
    }

    html[data-bs-theme="dark"] .poligonium-courses-catalog,
    html[data-bs-theme="dark"] .poligonium-course-card,
    html[data-bs-theme="dark"] .poligonium-course-side,
    html[data-bs-theme="dark"] .poligonium-chapter,
    html[data-bs-theme="dark"] .poligonium-lesson-main,
    html[data-bs-theme="dark"] .poligonium-lesson-sidebar,
    html[data-bs-theme="dark"] .poligonium-course-empty {
        border-color: rgba(255, 255, 255, .12);
        background:
            linear-gradient(90deg, rgba(255, 255, 255, .04) 1px, transparent 1px),
            linear-gradient(0deg, rgba(255, 255, 255, .04) 1px, transparent 1px),
            rgba(17, 24, 39, .76);
    }

    html[data-bs-theme="dark"] .poligonium-courses-catalog p,
    html[data-bs-theme="dark"] .poligonium-courses-hero p,
    html[data-bs-theme="dark"] .poligonium-course-detail p,
    html[data-bs-theme="dark"] .poligonium-lesson-main p,
    html[data-bs-theme="dark"] .poligonium-course-description {
        color: #d1d5db;
    }

    @media (max-width: 1199.98px) {
        .poligonium-course-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 991.98px) {
        .poligonium-courses-hero,
        .poligonium-course-detail,
        .poligonium-lesson-layout {
            grid-template-columns: 1fr;
        }

        .poligonium-courses-hero > div:first-child {
            padding-left: 0;
        }

        .poligonium-courses-catalog {
            max-width: 460px;
        }

        .poligonium-lesson-sidebar {
            position: static;
        }
    }

    @media (max-width: 767.98px) {
        .poligonium-course-grid {
            grid-template-columns: 1fr;
        }

        .poligonium-course-card {
            min-height: 0;
        }

        .poligonium-course-release-badge {
            top: 128px;
            right: -32px;
            width: 154px;
        }

        .poligonium-course-release-badge small {
            max-width: 108px;
            font-size: 12px;
        }

        .poligonium-course-release-badge strong {
            font-size: 50px;
        }

        .poligonium-course-release-badge__days {
            font-size: 13px;
        }

        .poligonium-course-release-badge em {
            max-width: 112px;
            font-size: 11px;
        }
    }

    @media (max-width: 575.98px) {
        .poligonium-courses-wrap {
            width: min(100% - 24px, 1280px);
            padding-top: 72px;
            padding-bottom: 46px;
        }

        .poligonium-courses-hero {
            gap: 14px;
        }

        .poligonium-courses-catalog {
            max-width: none;
            padding: 16px;
            border-radius: 12px;
        }

        .poligonium-course-grid {
            gap: 18px;
            margin-top: 26px;
        }

        .poligonium-courses-hero h1,
        .poligonium-course-detail h1,
        .poligonium-lesson-main h1 {
            font-size: clamp(36px, 11vw, 46px);
            line-height: 1;
        }

        .poligonium-courses-hero p,
        .poligonium-course-detail p {
            font-size: 14px;
            line-height: 1.5;
        }

        .poligonium-course-card {
            border-radius: 8px;
        }

        .poligonium-course-media {
            min-height: 190px;
        }

        .poligonium-course-media img {
            min-height: 190px;
        }

        .poligonium-course-body {
            padding: 16px;
        }

        .poligonium-course-title {
            margin: 12px 0 8px;
            font-size: 25px;
        }

        .poligonium-course-description {
            font-size: 14px;
            line-height: 1.45;
        }

        .poligonium-course-meta,
        .poligonium-course-bottom,
        .poligonium-course-stats,
        .poligonium-course-tags {
            gap: 7px;
        }

        .poligonium-course-meta span,
        .poligonium-course-stats span,
        .poligonium-course-bottom strong {
            padding: 6px 9px;
            font-size: 12px;
        }

        .poligonium-course-bottom {
            padding-top: 14px;
        }

        .poligonium-course-release-badge {
            top: 108px;
            right: -8px;
            width: 132px;
        }

        .poligonium-course-release-badge::before {
            inset: 10px;
        }

        .poligonium-course-release-badge small {
            max-width: 92px;
            font-size: 10px;
        }

        .poligonium-course-release-badge strong {
            font-size: 40px;
        }

        .poligonium-course-release-badge__days,
        .poligonium-course-release-badge em {
            font-size: 10px;
        }

        .poligonium-course-card-glow {
            display: none;
        }

        .poligonium-course-release-modal__dialog {
            padding: 24px 18px;
        }

        .poligonium-course-release-modal__timer {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .poligonium-courses-orb::before,
        .poligonium-courses-orb::after,
        .poligonium-courses-catalog::after,
        .poligonium-course-media::before,
        .poligonium-course-lab-preview,
        .poligonium-course-spider-spark,
        .poligonium-course-release-badge::before,
        .poligonium-course-spark {
            animation: none;
        }

        .poligonium-course-card,
        .poligonium-course-card-glow,
        .poligonium-course-media img,
        .poligonium-course-bottom > span::after {
            transition: none;
        }
    }
</style>

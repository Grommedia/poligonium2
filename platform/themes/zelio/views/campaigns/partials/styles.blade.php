<style>
    .poligonium-campaigns-page {
        position: relative;
        overflow: hidden;
        min-height: 100vh;
        padding: 150px 0 90px;
        background: #f7f9fc;
        color: #111827;
    }

    .poligonium-campaigns-grid-bg {
        position: absolute;
        inset: 0;
        opacity: .48;
        background-image:
            linear-gradient(rgba(17, 24, 39, .07) 1px, transparent 1px),
            linear-gradient(90deg, rgba(17, 24, 39, .07) 1px, transparent 1px);
        background-size: 38px 38px;
        pointer-events: none;
    }

    .poligonium-campaigns-wrap {
        position: relative;
        z-index: 1;
        width: min(1180px, calc(100% - 32px));
        margin: 0 auto;
    }

    .poligonium-campaign-detail {
        padding-top: 92px;
    }

    .poligonium-campaign-detail .poligonium-campaigns-wrap {
        width: min(1560px, calc(100% - 48px));
    }

    .poligonium-campaigns-hero,
    .poligonium-campaign-detail-hero {
        display: grid;
        grid-template-columns: minmax(0, 1.12fr) minmax(280px, .88fr);
        gap: 32px;
        align-items: center;
        margin-bottom: 42px;
    }

    .poligonium-campaigns-kicker {
        display: inline-flex;
        align-items: center;
        min-height: 28px;
        margin: 0 0 12px;
        padding: 0 12px;
        border: 1px solid rgba(15, 23, 42, .14);
        border-radius: 999px;
        background: rgba(255, 255, 255, .7);
        color: #0f766e;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .poligonium-campaigns-hero h1,
    .poligonium-campaign-detail-hero h1 {
        max-width: 820px;
        margin: 0;
        color: #0f172a;
        font-size: 64px;
        line-height: 1;
        letter-spacing: 0;
    }

    .poligonium-campaign-detail-hero h1 {
        font-size: clamp(42px, 4vw, 56px);
        line-height: 1.02;
    }

    .poligonium-campaigns-hero p,
    .poligonium-campaign-detail-copy > p {
        max-width: 760px;
        margin: 18px 0 0;
        color: #475569;
        font-size: 18px;
        line-height: 1.7;
    }

    .poligonium-campaign-detail-copy > p {
        margin-top: 12px;
        font-size: 16px;
        line-height: 1.55;
    }

    .poligonium-campaigns-hero-object {
        position: relative;
        min-height: 310px;
        border: 1px solid rgba(15, 23, 42, .14);
        border-radius: 8px;
        background:
            linear-gradient(rgba(255, 255, 255, .84), rgba(255, 255, 255, .84)),
            repeating-linear-gradient(0deg, transparent 0 34px, rgba(15, 23, 42, .08) 34px 35px),
            repeating-linear-gradient(90deg, transparent 0 34px, rgba(15, 23, 42, .08) 34px 35px);
        box-shadow: 0 24px 70px rgba(15, 23, 42, .12);
    }

    .poligonium-campaigns-hero-object span {
        position: absolute;
        display: block;
        border: 2px solid currentColor;
        color: #2563eb;
        transform-style: preserve-3d;
        animation: campaignFloat 5.8s ease-in-out infinite;
    }

    .poligonium-campaigns-hero-object span:nth-child(1) {
        width: 132px;
        height: 132px;
        left: 22%;
        top: 26%;
        color: #ef4444;
        transform: rotate(18deg);
    }

    .poligonium-campaigns-hero-object span:nth-child(2) {
        width: 92px;
        height: 92px;
        right: 18%;
        top: 18%;
        color: #0f766e;
        animation-delay: -1.2s;
    }

    .poligonium-campaigns-hero-object span:nth-child(3) {
        width: 74px;
        height: 74px;
        right: 34%;
        bottom: 20%;
        color: #f59e0b;
        animation-delay: -2.3s;
    }

    @keyframes campaignFloat {
        0%, 100% { translate: 0 0; rotate: 0deg; }
        50% { translate: 0 -14px; rotate: 8deg; }
    }

    .poligonium-campaigns-list {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
    }

    .poligonium-campaign-card,
    .poligonium-campaign-empty,
    .poligonium-campaign-rewards article,
    .poligonium-campaign-support-form,
    .poligonium-campaign-budget-row,
    .poligonium-campaign-supporters article,
    .poligonium-campaign-soft-note,
    .poligonium-campaign-team article,
    .poligonium-campaign-updates article,
    .poligonium-campaign-faqs details {
        border: 1px solid rgba(15, 23, 42, .12);
        border-radius: 8px;
        background: rgba(255, 255, 255, .82);
        box-shadow: 0 14px 40px rgba(15, 23, 42, .08);
    }

    .poligonium-campaign-card {
        overflow: hidden;
        transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
    }

    .poligonium-campaign-card:hover {
        transform: translateY(-5px);
        border-color: rgba(37, 99, 235, .28);
        box-shadow: 0 22px 60px rgba(15, 23, 42, .14);
    }

    .poligonium-campaign-card-media,
    .poligonium-campaign-detail-media {
        position: relative;
        display: block;
        overflow: hidden;
        background: #111827;
    }

    .poligonium-campaign-card-media {
        aspect-ratio: 16 / 10;
    }

    .poligonium-campaign-card-media img,
    .poligonium-campaign-detail-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .45s ease;
    }

    .poligonium-campaign-card:hover img {
        transform: scale(1.04);
    }

    .poligonium-campaign-card-media span {
        position: absolute;
        inset: 20px;
        display: grid;
        place-items: center;
        border: 1px dashed rgba(255, 255, 255, .34);
        color: #f8fafc;
        font-weight: 800;
    }

    .poligonium-campaign-card-body {
        padding: 22px;
    }

    .poligonium-campaign-card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 14px;
    }

    .poligonium-campaign-card-meta span {
        padding: 6px 9px;
        border: 1px solid rgba(15, 23, 42, .1);
        border-radius: 999px;
        background: #f8fafc;
        color: #334155;
        font-size: 12px;
        font-weight: 800;
    }

    .poligonium-campaign-card h2,
    .poligonium-campaign-section h2,
    .poligonium-campaign-rewards h2 {
        margin: 0;
        color: #0f172a;
        font-size: 24px;
        line-height: 1.2;
        letter-spacing: 0;
    }

    .poligonium-campaign-card h2 a {
        color: inherit;
    }

    .poligonium-campaign-card p,
    .poligonium-campaign-rewards p,
    .poligonium-campaign-team p,
    .poligonium-campaign-updates p,
    .poligonium-campaign-faqs p {
        margin: 12px 0 0;
        color: #475569;
        line-height: 1.65;
    }

    .poligonium-campaign-card-subtitle {
        color: #0f766e !important;
        font-weight: 800;
    }

    .poligonium-campaign-progress {
        position: relative;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 12px;
        align-items: end;
        margin-top: 20px;
        padding-bottom: 15px;
    }

    .poligonium-campaign-progress strong {
        display: block;
        color: #0f172a;
        font-size: 23px;
        line-height: 1.1;
    }

    .poligonium-campaign-progress span {
        display: block;
        margin-top: 5px;
        color: #64748b;
        font-size: 13px;
    }

    .poligonium-campaign-progress b {
        color: #2563eb;
        font-size: 18px;
    }

    .poligonium-campaign-progress i {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 7px;
        overflow: hidden;
        border-radius: 999px;
        background: #e2e8f0;
    }

    .poligonium-campaign-progress i::before {
        content: "";
        display: block;
        width: var(--progress);
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #2563eb, #0f766e, #f59e0b);
        animation: campaignProgressPulse 2.6s ease-in-out infinite;
    }

    @keyframes campaignProgressPulse {
        0%, 100% { filter: saturate(1); }
        50% { filter: saturate(1.45); }
    }

    .poligonium-campaign-link,
    .poligonium-campaign-actions a,
    .poligonium-campaign-rewards a,
    .poligonium-campaign-rewards button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        width: fit-content;
        margin-top: 20px;
        padding: 0 16px;
        border: 1px solid #111827;
        border-radius: 8px;
        background: #111827;
        color: #fff;
        font: inherit;
        font-weight: 800;
        cursor: pointer;
        transition: transform .2s ease, background .2s ease;
    }

    .poligonium-campaign-link:hover,
    .poligonium-campaign-actions a:hover,
    .poligonium-campaign-rewards a:hover,
    .poligonium-campaign-rewards button:hover {
        transform: translateY(-2px);
        background: #2563eb;
        color: #fff;
    }

    .poligonium-campaign-empty {
        grid-column: 1 / -1;
        padding: 38px;
    }

    .poligonium-campaign-back {
        display: inline-flex;
        margin-bottom: 16px;
        color: #2563eb;
        font-weight: 800;
    }

    .poligonium-campaign-detail-hero {
        grid-template-columns: minmax(320px, .92fr) minmax(0, 1.08fr);
        gap: 28px;
        margin-bottom: 28px;
    }

    .poligonium-campaign-detail-media {
        height: clamp(360px, 29vw, 460px);
        min-height: 0;
        border-radius: 8px;
        box-shadow: 0 24px 70px rgba(15, 23, 42, .16);
    }

    .poligonium-campaign-fallback-art {
        position: absolute;
        inset: 0;
        display: grid;
        place-items: center;
        background:
            repeating-linear-gradient(0deg, transparent 0 36px, rgba(255, 255, 255, .08) 36px 37px),
            repeating-linear-gradient(90deg, transparent 0 36px, rgba(255, 255, 255, .08) 36px 37px),
            #111827;
    }

    .poligonium-campaign-fallback-art span {
        position: absolute;
        width: 130px;
        height: 130px;
        border: 2px solid #f8fafc;
        animation: campaignFloat 6s ease-in-out infinite;
    }

    .poligonium-campaign-fallback-art span:nth-child(2) {
        width: 86px;
        height: 86px;
        translate: 90px -80px;
        border-color: #f59e0b;
        animation-delay: -1.1s;
    }

    .poligonium-campaign-fallback-art span:nth-child(3) {
        width: 72px;
        height: 72px;
        translate: -100px 90px;
        border-color: #0f766e;
        animation-delay: -2s;
    }

    .poligonium-campaign-lead {
        color: #0f766e !important;
        font-weight: 800;
    }

    .poligonium-campaign-detail .poligonium-campaign-progress {
        margin-top: 14px;
    }

    .poligonium-campaign-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .poligonium-campaign-hero-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 18px;
    }

    .poligonium-campaign-detail .poligonium-campaign-hero-stats {
        margin-top: 12px;
    }

    .poligonium-campaign-hero-stats span {
        border: 1px solid rgba(15, 23, 42, .12);
        border-radius: 999px;
        padding: 8px 12px;
        background: rgba(255, 255, 255, .72);
        color: #334155;
        font-size: 13px;
        font-weight: 900;
    }

    .poligonium-campaign-actions a + a {
        background: #fff;
        color: #111827;
    }

    .poligonium-campaign-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 360px;
        gap: 24px;
        align-items: start;
    }

    .poligonium-campaign-detail .poligonium-campaign-layout {
        grid-template-columns: minmax(0, .92fr) minmax(640px, 1.08fr);
        gap: 28px;
    }

    .poligonium-campaign-section {
        padding: 34px 0;
        border-top: 1px solid rgba(15, 23, 42, .12);
    }

    .poligonium-campaign-content {
        margin-top: 18px;
        color: #334155;
        font-size: 17px;
        line-height: 1.75;
    }

    .poligonium-campaign-budget {
        display: grid;
        gap: 12px;
        margin-top: 18px;
    }

    .poligonium-campaign-budget-row {
        position: relative;
        overflow: hidden;
        padding: 18px;
    }

    .poligonium-campaign-budget-row div {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        gap: 12px;
        color: #0f172a;
        font-weight: 900;
    }

    .poligonium-campaign-budget-row span {
        color: #2563eb;
    }

    .poligonium-campaign-budget-row i {
        position: relative;
        z-index: 1;
        display: block;
        height: 8px;
        margin-top: 14px;
        overflow: hidden;
        border-radius: 999px;
        background: #e2e8f0;
    }

    .poligonium-campaign-budget-row i::before {
        content: "";
        display: block;
        width: var(--budget);
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #0f766e, #2563eb);
        animation: campaignProgressPulse 2.8s ease-in-out infinite;
    }

    .poligonium-campaign-roadmap {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-top: 18px;
    }

    .poligonium-campaign-roadmap div {
        position: relative;
        min-height: 110px;
        padding: 18px;
        border: 1px solid rgba(15, 23, 42, .12);
        border-radius: 8px;
        background:
            linear-gradient(rgba(255, 255, 255, .86), rgba(255, 255, 255, .86)),
            repeating-linear-gradient(0deg, transparent 0 24px, rgba(15, 23, 42, .07) 24px 25px),
            repeating-linear-gradient(90deg, transparent 0 24px, rgba(15, 23, 42, .07) 24px 25px);
        box-shadow: 0 14px 40px rgba(15, 23, 42, .08);
    }

    .poligonium-campaign-roadmap span {
        display: grid;
        place-items: center;
        width: 34px;
        height: 34px;
        margin-bottom: 18px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #334155;
        font-weight: 900;
    }

    .poligonium-campaign-roadmap strong {
        color: #0f172a;
        line-height: 1.25;
    }

    .poligonium-campaign-roadmap .is-done,
    .poligonium-campaign-roadmap .is-current {
        border-color: rgba(37, 99, 235, .32);
        box-shadow: 0 18px 52px rgba(37, 99, 235, .13);
    }

    .poligonium-campaign-roadmap .is-done span,
    .poligonium-campaign-roadmap .is-current span {
        background: #2563eb;
        color: #fff;
    }

    .poligonium-campaign-roadmap .is-current::after {
        content: "";
        position: absolute;
        right: 14px;
        top: 14px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #f59e0b;
        box-shadow: 0 0 0 8px rgba(245, 158, 11, .18);
        animation: campaignPulseDot 1.6s ease-in-out infinite;
    }

    @keyframes campaignPulseDot {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(.72); opacity: .65; }
    }

    .poligonium-campaign-supporters {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        margin-top: 18px;
    }

    .poligonium-campaign-supporters article,
    .poligonium-campaign-soft-note {
        padding: 18px;
    }

    .poligonium-campaign-supporters strong,
    .poligonium-campaign-supporters span {
        display: block;
    }

    .poligonium-campaign-supporters span {
        margin-top: 5px;
        color: #0f766e;
        font-weight: 900;
    }

    .poligonium-campaign-soft-note {
        margin-top: 18px;
        color: #475569;
        line-height: 1.65;
    }

    .poligonium-campaign-team {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-top: 18px;
    }

    .poligonium-campaign-team article {
        padding: 18px;
    }

    .poligonium-campaign-team img {
        width: 72px;
        height: 72px;
        margin-bottom: 12px;
        border-radius: 50%;
        object-fit: cover;
    }

    .poligonium-campaign-team strong,
    .poligonium-campaign-team span {
        display: block;
    }

    .poligonium-campaign-team span {
        margin-top: 4px;
        color: #0f766e;
        font-weight: 800;
    }

    .poligonium-campaign-updates,
    .poligonium-campaign-faqs {
        display: grid;
        gap: 14px;
        margin-top: 18px;
    }

    .poligonium-campaign-updates article,
    .poligonium-campaign-faqs details {
        padding: 20px;
    }

    .poligonium-campaign-updates time {
        color: #64748b;
        font-size: 13px;
        font-weight: 800;
    }

    .poligonium-campaign-updates h3 {
        margin: 8px 0 0;
        font-size: 20px;
    }

    .poligonium-campaign-faqs summary {
        cursor: pointer;
        color: #0f172a;
        font-weight: 900;
    }

    .poligonium-campaign-rewards {
        position: sticky;
        top: 110px;
        display: grid;
        gap: 14px;
    }

    .poligonium-campaign-detail .poligonium-campaign-rewards {
        top: 96px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        align-content: start;
    }

    .poligonium-campaign-detail .poligonium-campaign-rewards h2,
    .poligonium-campaign-detail .poligonium-campaign-support-form {
        grid-column: 1 / -1;
    }

    .poligonium-campaign-rewards article {
        padding: 20px;
    }

    .poligonium-campaign-detail .poligonium-campaign-rewards article {
        min-width: 0;
        padding: 16px;
    }

    .poligonium-campaign-rewards article.is-featured {
        border-color: rgba(37, 99, 235, .34);
        box-shadow: 0 18px 52px rgba(37, 99, 235, .14);
    }

    .poligonium-reward-topline {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        align-items: baseline;
    }

    .poligonium-campaign-rewards strong {
        color: #2563eb;
        font-size: 24px;
    }

    .poligonium-campaign-rewards span {
        color: #64748b;
        font-size: 13px;
        font-weight: 800;
    }

    .poligonium-reward-delivery {
        width: fit-content;
        margin-top: 12px;
        border: 1px solid rgba(15, 118, 110, .18);
        border-radius: 999px;
        padding: 6px 10px;
        background: rgba(15, 118, 110, .08);
        color: #0f766e;
        font-size: 12px;
        font-weight: 900;
    }

    .poligonium-campaign-rewards h3 {
        margin: 12px 0 0;
        color: #0f172a;
        font-size: 20px;
    }

    .poligonium-campaign-detail .poligonium-campaign-rewards h3 {
        font-size: 18px;
        line-height: 1.25;
    }

    .poligonium-campaign-rewards ul {
        display: grid;
        gap: 8px;
        margin: 14px 0 0;
        padding: 0;
        list-style: none;
    }

    .poligonium-campaign-rewards li {
        position: relative;
        padding-left: 18px;
        color: #334155;
    }

    .poligonium-campaign-detail .poligonium-campaign-rewards p,
    .poligonium-campaign-detail .poligonium-campaign-rewards li {
        font-size: 14px;
        line-height: 1.48;
    }

    .poligonium-campaign-detail .poligonium-campaign-rewards ul {
        gap: 6px;
        margin-top: 12px;
    }

    .poligonium-campaign-rewards li::before {
        content: "";
        position: absolute;
        left: 0;
        top: .64em;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #0f766e;
    }

    .poligonium-campaign-support-form {
        display: grid;
        gap: 13px;
        padding: 20px;
    }

    .poligonium-campaign-support-form h2 {
        margin: 0 0 4px;
        color: #0f172a;
        font-size: 22px;
        letter-spacing: 0;
    }

    .poligonium-selected-reward {
        border: 1px solid rgba(37, 99, 235, .22);
        border-radius: 8px;
        padding: 13px;
        background:
            linear-gradient(rgba(255, 255, 255, .82), rgba(255, 255, 255, .82)),
            repeating-linear-gradient(0deg, transparent 0 18px, rgba(37, 99, 235, .07) 18px 19px),
            repeating-linear-gradient(90deg, transparent 0 18px, rgba(37, 99, 235, .07) 18px 19px);
    }

    .poligonium-selected-reward strong,
    .poligonium-selected-reward span {
        display: block;
    }

    .poligonium-selected-reward strong {
        color: #0f172a;
        font-size: 16px;
    }

    .poligonium-selected-reward span {
        margin-top: 4px;
        color: #2563eb;
        font-weight: 900;
    }

    .poligonium-campaign-support-form label {
        display: grid;
        gap: 6px;
        margin: 0;
        color: #334155;
        font-weight: 800;
    }

    .poligonium-campaign-support-form input,
    .poligonium-campaign-support-form select,
    .poligonium-campaign-support-form textarea {
        width: 100%;
        border: 1px solid rgba(15, 23, 42, .16);
        border-radius: 8px;
        background: rgba(255, 255, 255, .9);
        color: #0f172a;
        font: inherit;
    }

    .poligonium-campaign-support-form input,
    .poligonium-campaign-support-form select {
        min-height: 42px;
        padding: 0 12px;
    }

    .poligonium-campaign-support-form textarea {
        padding: 10px 12px;
        resize: vertical;
    }

    .poligonium-campaign-support-form button {
        min-height: 44px;
        border: 0;
        border-radius: 8px;
        background: #111827;
        color: #fff;
        font-weight: 900;
        cursor: pointer;
        transition: transform .2s ease, background .2s ease;
    }

    .poligonium-campaign-support-form button:hover {
        transform: translateY(-2px);
        background: #2563eb;
    }

    .poligonium-campaign-form-success,
    .poligonium-campaign-form-error {
        padding: 12px;
        border-radius: 8px;
        font-weight: 800;
        line-height: 1.45;
    }

    .poligonium-campaign-form-success {
        border: 1px solid rgba(15, 118, 110, .22);
        background: rgba(15, 118, 110, .1);
        color: #0f766e;
    }

    .poligonium-campaign-form-error {
        border: 1px solid rgba(220, 38, 38, .22);
        background: rgba(220, 38, 38, .08);
        color: #b91c1c;
    }

    @media (max-width: 991px) {
        .poligonium-campaigns-page {
            padding-top: 116px;
        }

        .poligonium-campaigns-hero,
        .poligonium-campaign-detail-hero,
        .poligonium-campaign-layout {
            grid-template-columns: 1fr;
        }

        .poligonium-campaigns-hero h1,
        .poligonium-campaign-detail-hero h1 {
            font-size: 44px;
        }

        .poligonium-campaigns-list,
        .poligonium-campaign-roadmap,
        .poligonium-campaign-supporters,
        .poligonium-campaign-team {
            grid-template-columns: 1fr;
        }

        .poligonium-campaign-detail-media {
            min-height: 360px;
        }

        .poligonium-campaign-rewards {
            position: static;
        }
    }

    @media (max-width: 575px) {
        .poligonium-campaigns-wrap {
            width: min(100% - 20px, 1180px);
        }

        .poligonium-campaigns-hero h1,
        .poligonium-campaign-detail-hero h1 {
            font-size: 34px;
        }

        .poligonium-campaign-card-body,
        .poligonium-campaign-rewards article,
        .poligonium-campaign-updates article,
        .poligonium-campaign-faqs details {
            padding: 16px;
        }
    }
</style>

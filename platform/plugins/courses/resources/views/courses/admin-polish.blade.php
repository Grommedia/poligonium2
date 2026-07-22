@php
    use Botble\Courses\Support\CourseOptions;

    $softwareOptions = CourseOptions::software();
    $skillOptions = CourseOptions::skills();
    $course ??= null;
    $courseExists = $course instanceof \Botble\Courses\Models\Course && $course->getKey();
    $previewUrls = $courseExists ? [
        'visitor' => route('courses.courses.preview', [$course, 'visitor']),
        'buyer' => route('courses.courses.preview', [$course, 'buyer']),
        'student' => route('courses.courses.preview', [$course, 'student']),
    ] : [];
    $autosaveUrl = $courseExists ? route('courses.courses.autosave', $course) : null;
    $translationUrls = $courseExists ? [
        'load' => route('courses.courses.translations', $course),
        'save' => route('courses.courses.translations.save', $course),
    ] : [];
@endphp

<style>
    .poligonium-course-admin-hide {
        display: none !important;
    }

    .poligonium-course-workspace {
        position: relative;
        z-index: 2;
        margin: 0 0 18px;
        border: 1px solid rgba(24, 36, 51, .12);
        border-radius: 14px;
        background:
            linear-gradient(90deg, rgba(24, 36, 51, .045) 1px, transparent 1px),
            linear-gradient(0deg, rgba(24, 36, 51, .045) 1px, transparent 1px),
            #f8fafc;
        background-size: 24px 24px;
        box-shadow: 0 18px 44px rgba(24, 36, 51, .08);
        overflow: clip;
    }

    .poligonium-course-workspace__topbar {
        position: sticky;
        top: 0;
        z-index: 20;
        display: grid;
        grid-template-columns: minmax(180px, 1fr) minmax(280px, 1.45fr) minmax(280px, auto);
        gap: 14px;
        align-items: center;
        padding: 14px 18px;
        border-bottom: 1px solid rgba(24, 36, 51, .1);
        background: rgba(255, 255, 255, .92);
        backdrop-filter: blur(18px);
    }

    .poligonium-course-workspace__back {
        color: #344050;
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
    }

    .poligonium-course-workspace__title {
        min-width: 0;
    }

    .poligonium-course-workspace__title strong {
        display: block;
        overflow: hidden;
        color: #111827;
        font-size: 17px;
        font-weight: 900;
        line-height: 1.15;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .poligonium-course-workspace__title span,
    .poligonium-course-workspace__save-state {
        color: #6b7280;
        font-size: 12px;
        font-weight: 700;
    }

    .poligonium-course-workspace__actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 8px;
    }

    .poligonium-course-workspace__actions .btn {
        border-radius: 999px;
        font-weight: 800;
    }

    .poligonium-course-workspace__nav {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 10px;
        padding: 14px 18px;
        background: rgba(255, 255, 255, .52);
    }

    .poligonium-course-workspace__nav button {
        min-height: 72px;
        border: 1px solid rgba(24, 36, 51, .1);
        border-radius: 12px;
        background: rgba(255, 255, 255, .78);
        color: #344050;
        text-align: left;
        transition: border-color .18s ease, background .18s ease, transform .18s ease, box-shadow .18s ease;
    }

    .poligonium-course-workspace__nav button:hover,
    .poligonium-course-workspace__nav button.is-active {
        border-color: rgba(255, 126, 32, .42);
        background: #fff;
        box-shadow: 0 12px 28px rgba(24, 36, 51, .09);
        transform: translateY(-1px);
    }

    .poligonium-course-workspace__nav strong,
    .poligonium-course-choice__title,
    .poligonium-course-card-preview__name,
    .poligonium-course-publication__title {
        display: block;
        color: #111827;
        font-weight: 900;
    }

    .poligonium-course-workspace__nav span {
        display: block;
        margin-top: 4px;
        color: #6b7280;
        font-size: 12px;
        font-weight: 650;
        line-height: 1.25;
    }

    .poligonium-course-workspace__body {
        padding: 18px;
    }

    .poligonium-course-panel {
        display: none;
    }

    .poligonium-course-panel.is-active {
        display: block;
    }

    .poligonium-course-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(300px, 360px);
        gap: 18px;
        align-items: start;
    }

    .poligonium-course-layout > aside {
        display: grid;
        gap: 16px;
    }

    .poligonium-course-stack {
        display: grid;
        gap: 16px;
    }

    .poligonium-course-box {
        border: 1px solid rgba(24, 36, 51, .1);
        border-radius: 12px;
        background: rgba(255, 255, 255, .9);
        padding: 18px;
    }

    .poligonium-course-box__title {
        margin: 0 0 12px;
        color: #111827;
        font-size: 16px;
        font-weight: 900;
    }

    .poligonium-course-box .mb-3:last-child,
    .poligonium-course-box .form-group:last-child {
        margin-bottom: 0 !important;
    }

    .poligonium-course-sidebar-group {
        overflow: hidden;
        border-color: rgba(24, 36, 51, .12) !important;
        box-shadow: 0 10px 26px rgba(24, 36, 51, .07);
    }

    .poligonium-course-sidebar-group > .card-header,
    .poligonium-course-sidebar-group > .widget-title {
        background:
            linear-gradient(90deg, rgba(24, 36, 51, .045) 1px, transparent 1px),
            linear-gradient(0deg, rgba(24, 36, 51, .045) 1px, transparent 1px),
            #f8fafc;
        background-size: 18px 18px;
    }

    .poligonium-course-sidebar-group__field-card {
        margin: 0 0 12px !important;
        border: 1px solid rgba(24, 36, 51, .08) !important;
        border-radius: 10px !important;
        background: rgba(255, 255, 255, .9) !important;
        box-shadow: none !important;
    }

    .poligonium-course-sidebar-group__field-card:last-child {
        margin-bottom: 0 !important;
    }

    .poligonium-course-sidebar-group__field-card > .card-header,
    .poligonium-course-sidebar-group__field-card > .widget-title {
        min-height: auto;
        border-bottom-color: rgba(24, 36, 51, .06) !important;
        background: transparent !important;
        padding: 10px 12px 0 !important;
    }

    .poligonium-course-sidebar-group__field-card > .card-body,
    .poligonium-course-sidebar-group__field-card > .widget-body {
        padding: 10px 12px 12px !important;
    }

    .poligonium-course-slug-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        margin-top: -6px;
        margin-bottom: 12px;
        color: #6b7280;
        font-size: 12px;
        font-weight: 750;
    }

    .poligonium-course-slug-row code {
        border: 1px solid rgba(24, 36, 51, .08);
        border-radius: 999px;
        background: #f3f4f6;
        padding: 5px 9px;
        color: #374151;
        font-size: 12px;
    }

    .poligonium-course-counter {
        margin-top: 4px;
        color: #6b7280;
        font-size: 12px;
        font-weight: 700;
        text-align: right;
    }

    .poligonium-course-cover-zone {
        border: 1px dashed rgba(24, 36, 51, .28);
        border-radius: 12px;
        background:
            linear-gradient(90deg, rgba(24, 36, 51, .035) 1px, transparent 1px),
            linear-gradient(0deg, rgba(24, 36, 51, .035) 1px, transparent 1px),
            rgba(255, 255, 255, .72);
        background-size: 18px 18px;
        padding: 16px;
    }

    .poligonium-course-cover-zone .preview-image-wrapper {
        width: 100% !important;
        max-width: 100% !important;
    }

    .poligonium-course-cover-zone .preview-image-wrapper img {
        width: 100%;
        aspect-ratio: 16 / 9;
        border-radius: 10px;
        object-fit: cover;
    }

    .poligonium-course-cover-zone::before {
        content: "Перетащите изображение сюда или выберите из медиатеки";
        display: block;
        margin-bottom: 10px;
        color: #374151;
        font-size: 14px;
        font-weight: 850;
        text-align: center;
    }

    .poligonium-course-choice {
        display: grid;
        gap: 12px;
    }

    .poligonium-course-choice__tags,
    .poligonium-course-choice__grid,
    .poligonium-course-levels {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .poligonium-course-choice__tag,
    .poligonium-course-choice__option,
    .poligonium-course-levels label {
        border: 1px solid rgba(24, 36, 51, .1);
        border-radius: 999px;
        background: #fff;
        padding: 8px 11px;
        color: #344050;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        user-select: none;
    }

    .poligonium-course-choice__tag {
        border-color: rgba(255, 126, 32, .28);
        background: rgba(255, 126, 32, .1);
    }

    .poligonium-course-choice__option input,
    .poligonium-course-levels input {
        margin-right: 6px;
    }

    .poligonium-course-choice__option:has(input:checked),
    .poligonium-course-levels label:has(input:checked) {
        border-color: rgba(47, 128, 237, .45);
        background: rgba(47, 128, 237, .08);
        color: #1f4f9a;
    }

    .poligonium-course-card-preview {
        position: sticky;
        top: 92px;
        border: 1px solid rgba(24, 36, 51, .16);
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 20px 42px rgba(24, 36, 51, .12);
        overflow: hidden;
    }

    .poligonium-course-card-preview__image {
        display: grid;
        aspect-ratio: 16 / 9;
        place-items: center;
        background:
            linear-gradient(90deg, rgba(255, 126, 32, .1), rgba(47, 128, 237, .08)),
            #eef2f7;
        color: #6b7280;
        font-weight: 900;
        overflow: hidden;
    }

    .poligonium-course-card-preview__image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .poligonium-course-card-preview__body {
        display: grid;
        gap: 10px;
        padding: 16px;
    }

    .poligonium-course-card-preview__name {
        font-size: 20px;
        line-height: 1.08;
    }

    .poligonium-course-card-preview__description {
        color: #4b5563;
        font-size: 13px;
        line-height: 1.45;
    }

    .poligonium-course-card-preview__meta,
    .poligonium-course-card-preview__tools {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
    }

    .poligonium-course-card-preview__meta span,
    .poligonium-course-card-preview__tools span {
        border-radius: 999px;
        background: #f3f4f6;
        padding: 6px 9px;
        color: #374151;
        font-size: 12px;
        font-weight: 800;
    }

    .poligonium-course-card-preview__footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        border-top: 1px solid rgba(24, 36, 51, .08);
        padding-top: 12px;
    }

    .poligonium-course-card-preview__price {
        color: #111827;
        font-size: 22px;
        font-weight: 950;
    }

    .poligonium-course-card-preview__button {
        border-radius: 999px;
        background: #ff7e20;
        padding: 9px 13px;
        color: #111827;
        font-size: 12px;
        font-weight: 950;
    }

    .poligonium-course-access-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .poligonium-course-panel[data-course-section="access"] .poligonium-course-layout {
        grid-template-columns: minmax(0, 1.55fr) minmax(320px, 370px);
    }

    .poligonium-course-access-hero {
        display: flex;
        gap: 14px;
        align-items: center;
        border: 1px solid rgba(16, 185, 129, .24);
        border-radius: 14px;
        background:
            linear-gradient(90deg, rgba(16, 185, 129, .12), rgba(47, 128, 237, .08)),
            rgba(255, 255, 255, .88);
        padding: 14px 16px;
        color: #1f2937;
        box-shadow: 0 14px 30px rgba(24, 36, 51, .07);
    }

    .poligonium-course-access-hero__icon {
        display: grid;
        flex: 0 0 34px;
        width: 34px;
        height: 34px;
        place-items: center;
        border-radius: 999px;
        background: #10b981;
        color: #fff;
        font-weight: 950;
    }

    .poligonium-course-access-hero strong {
        display: block;
        margin-bottom: 3px;
        font-size: 14px;
        font-weight: 900;
    }

    .poligonium-course-access-hero span {
        color: #4b5563;
        font-size: 12px;
        font-weight: 700;
    }

    .poligonium-course-access-dashboard {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .poligonium-course-access-dashboard .poligonium-course-box:nth-child(3) {
        grid-column: 1 / -1;
    }

    .poligonium-course-access-dashboard .card.meta-boxes,
    .poligonium-course-access-dashboard .widget.meta-boxes {
        margin: 0 0 12px !important;
        border: 1px solid rgba(24, 36, 51, .08) !important;
        border-radius: 10px !important;
        background: rgba(248, 250, 252, .78) !important;
        box-shadow: none !important;
    }

    .poligonium-course-access-dashboard .card.meta-boxes:last-child,
    .poligonium-course-access-dashboard .widget.meta-boxes:last-child {
        margin-bottom: 0 !important;
    }

    .poligonium-course-access-dashboard .card.meta-boxes > .card-header,
    .poligonium-course-access-dashboard .widget.meta-boxes > .widget-title {
        min-height: auto;
        border-bottom-color: rgba(24, 36, 51, .06) !important;
        background: transparent !important;
        padding: 10px 12px 0 !important;
    }

    .poligonium-course-access-dashboard .card.meta-boxes > .card-body,
    .poligonium-course-access-dashboard .widget.meta-boxes > .widget-body {
        padding: 10px 12px 12px !important;
    }

    .poligonium-course-side-card {
        border: 1px solid rgba(24, 36, 51, .1);
        border-radius: 14px;
        background: rgba(255, 255, 255, .92);
        padding: 14px;
        box-shadow: 0 14px 30px rgba(24, 36, 51, .07);
    }

    .poligonium-course-side-card__title {
        display: block;
        margin-bottom: 10px;
        color: #111827;
        font-size: 14px;
        font-weight: 900;
    }

    .poligonium-course-side-list {
        display: grid;
        gap: 9px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .poligonium-course-side-list li,
    .poligonium-course-side-action {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        border-radius: 10px;
        background: #f8fafc;
        padding: 9px 10px;
        color: #4b5563;
        font-size: 12px;
        font-weight: 750;
    }

    .poligonium-course-side-list strong {
        color: #111827;
        font-weight: 900;
        text-align: right;
    }

    .poligonium-course-side-action {
        width: 100%;
        border: 1px solid rgba(24, 36, 51, .08);
        text-align: left;
    }

    .poligonium-course-publication {
        display: grid;
        gap: 10px;
    }

    .poligonium-course-publication__bar {
        height: 10px;
        overflow: hidden;
        border-radius: 999px;
        background: #e5e7eb;
    }

    .poligonium-course-publication__bar span {
        display: block;
        width: var(--course-ready, 0%);
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #ff7e20, #ffd166);
    }

    .poligonium-course-checklist {
        display: grid;
        gap: 8px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .poligonium-course-checklist li {
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid rgba(24, 36, 51, .08);
        border-radius: 10px;
        background: #fff;
        padding: 10px 12px;
        color: #374151;
        font-weight: 750;
    }

    .poligonium-course-checklist li.is-ok {
        border-color: rgba(35, 165, 98, .24);
        color: #16704a;
    }

    .poligonium-course-checklist li.is-warn {
        border-color: rgba(245, 158, 11, .32);
        color: #92400e;
    }

    .poligonium-course-checklist li.is-error {
        border-color: rgba(220, 38, 38, .32);
        color: #991b1b;
    }

    @media (max-width: 1199.98px) {
        .poligonium-course-workspace__topbar,
        .poligonium-course-layout {
            grid-template-columns: 1fr;
        }

        .poligonium-course-workspace__nav {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .poligonium-course-card-preview {
            position: relative;
            top: 0;
        }
    }

    @media (max-width: 575.98px) {
        .poligonium-course-workspace__nav,
        .poligonium-course-access-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script type="application/json" data-poligonium-course-editor-config>
{!! json_encode([
    'softwareLabels' => $softwareOptions,
    'skillLabels' => $skillOptions,
    'previewUrls' => $previewUrls,
    'autosaveUrl' => $autosaveUrl,
    'translationUrls' => $translationUrls,
    'homeUrl' => route('courses.courses.index'),
    'currentLocale' => app()->getLocale(),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

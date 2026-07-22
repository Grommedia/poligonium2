@php
    /** @var \Botble\Courses\Models\Course $course */
    $course->loadMissing(['chapters.lessons.files']);
    $chapters = $course->chapters;
    $lessonsCount = $course->lessons()->count();
    $duration = (int) $course->lessons()->sum('duration_minutes');
    $lessonPayload = $chapters
        ->flatMap(fn ($chapter) => $chapter->lessons)
        ->mapWithKeys(function ($lesson) use ($course) {
            $status = $lesson->status;

            return [
                $lesson->getKey() => [
                    'id' => $lesson->getKey(),
                    'chapter_id' => $lesson->chapter_id,
                    'name' => $lesson->name,
                    'description' => $lesson->description,
                    'content' => $lesson->content,
                    'material_type' => $lesson->material_type ?: 'video',
                    'video_path' => $lesson->video_path,
                    'video_url' => $lesson->video_path ? RvMedia::url($lesson->video_path) : null,
                    'video_embed' => $lesson->video_embed,
                    'duration_minutes' => (int) $lesson->duration_minutes,
                    'access_mode' => $lesson->access_mode ?: ($lesson->is_free_preview ? 'free' : 'paid'),
                    'opens_at' => $lesson->opens_at?->format('Y-m-d\TH:i'),
                    'drip_days' => $lesson->drip_days,
                    'status' => is_object($status) && method_exists($status, 'getValue') ? $status->getValue() : (string) $status,
                    'update_url' => route('courses.courses.curriculum.lessons.update', [$course, $lesson]),
                    'quick_action_url' => route('courses.courses.curriculum.lessons.quick-action', [$course, $lesson]),
                    'files' => $lesson->files
                        ->map(fn ($file) => [
                            'id' => $file->getKey(),
                            'name' => $file->name,
                            'file_path' => $file->file_path,
                            'url' => $file->file_path ? RvMedia::url($file->file_path) : null,
                            'destroy_url' => route('courses.courses.curriculum.lessons.files.destroy', [$course, $lesson, $file]),
                        ])
                        ->values(),
                ],
            ];
        });
@endphp

<style>
    .poligonium-curriculum {
        display: grid;
        gap: 16px;
    }

    .poligonium-curriculum__top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        border: 1px solid rgba(24, 36, 51, .1);
        border-radius: 14px;
        background: #fff;
        padding: 16px;
    }

    .poligonium-curriculum__stats {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        color: #374151;
        font-weight: 850;
    }

    .poligonium-curriculum__stats span {
        border-radius: 999px;
        background: #f3f4f6;
        padding: 7px 10px;
    }

    .poligonium-curriculum__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .poligonium-curriculum__inline {
        display: none;
        border: 1px solid rgba(47, 128, 237, .18);
        border-radius: 14px;
        background: rgba(47, 128, 237, .05);
        padding: 16px;
    }

    .poligonium-curriculum__inline.is-open {
        display: block;
    }

    .poligonium-curriculum__structure {
        display: grid;
        gap: 14px;
    }

    .poligonium-curriculum__section {
        border: 1px solid rgba(24, 36, 51, .12);
        border-radius: 14px;
        background: rgba(255, 255, 255, .94);
        padding: 14px;
    }

    .poligonium-curriculum__section-head,
    .poligonium-curriculum__lesson {
        display: grid;
        grid-template-columns: auto minmax(0, 1fr) auto;
        gap: 12px;
        align-items: start;
    }

    .poligonium-curriculum__drag {
        display: grid;
        width: 28px;
        height: 28px;
        place-items: center;
        border-radius: 8px;
        background: #eef2f7;
        color: #4b5563;
        cursor: grab;
        font-weight: 950;
        line-height: 1;
    }

    .poligonium-curriculum__title {
        color: #111827;
        font-weight: 950;
    }

    .poligonium-curriculum__muted {
        color: #6b7280;
        font-size: 13px;
        font-weight: 650;
    }

    .poligonium-curriculum__lessons {
        display: grid;
        gap: 8px;
        min-height: 36px;
        margin-top: 12px;
        padding-left: 40px;
    }

    .poligonium-curriculum__lesson {
        border: 1px solid rgba(24, 36, 51, .08);
        border-radius: 12px;
        background: #fff;
        padding: 10px;
    }

    .poligonium-curriculum__lesson.is-dragging,
    .poligonium-curriculum__section.is-dragging {
        opacity: .48;
    }

    .poligonium-curriculum__meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 5px;
    }

    .poligonium-curriculum__meta span {
        color: #6b7280;
        font-size: 12px;
        font-weight: 750;
    }

    .poligonium-curriculum__meta .is-warn {
        color: #b45309;
    }

    .poligonium-curriculum__meta .is-ok {
        color: #16704a;
    }

    .poligonium-curriculum__menu {
        position: relative;
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 6px;
    }

    .poligonium-curriculum__quick {
        position: absolute;
        top: calc(100% + 6px);
        right: 0;
        z-index: 4;
        display: none;
        min-width: 190px;
        border: 1px solid rgba(24, 36, 51, .12);
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, .14);
        padding: 6px;
    }

    .poligonium-curriculum__quick.is-open {
        display: grid;
    }

    .poligonium-curriculum__quick button,
    .poligonium-curriculum__quick a {
        border: 0;
        border-radius: 9px;
        background: transparent;
        color: #374151;
        padding: 8px 10px;
        text-align: left;
        font-size: 13px;
        font-weight: 750;
        text-decoration: none;
    }

    .poligonium-curriculum__quick button:hover,
    .poligonium-curriculum__quick a:hover {
        background: #f3f4f6;
    }

    .poligonium-curriculum__quick .is-danger {
        color: #b42318;
    }

    .poligonium-curriculum__insert {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 10px 0;
        color: #6b7280;
    }

    .poligonium-curriculum__insert::before,
    .poligonium-curriculum__insert::after {
        content: '';
        flex: 1;
        height: 1px;
        background: rgba(24, 36, 51, .1);
    }

    .poligonium-curriculum__notice {
        display: none;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        border: 1px solid rgba(22, 112, 74, .16);
        border-radius: 12px;
        background: rgba(22, 112, 74, .06);
        color: #14532d;
        padding: 10px 12px;
        font-weight: 750;
    }

    .poligonium-curriculum__notice.is-open {
        display: flex;
    }

    .poligonium-lesson-drawer {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: none;
        background: rgba(17, 24, 39, .34);
    }

    .poligonium-lesson-drawer.is-open {
        display: block;
    }

    .poligonium-lesson-drawer__panel {
        position: absolute;
        top: 0;
        right: 0;
        width: min(480px, 100%);
        height: 100%;
        overflow: auto;
        background: #fff;
        box-shadow: -20px 0 48px rgba(17, 24, 39, .2);
    }

    .poligonium-lesson-drawer__head {
        position: sticky;
        top: 0;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        border-bottom: 1px solid rgba(24, 36, 51, .1);
        background: rgba(255, 255, 255, .94);
        padding: 16px;
        backdrop-filter: blur(14px);
    }

    .poligonium-lesson-drawer__body {
        display: grid;
        gap: 14px;
        padding: 16px;
    }

    .poligonium-lesson-drawer__conditional {
        display: none;
    }

    .poligonium-lesson-drawer__conditional.is-open {
        display: block;
    }

    .poligonium-lesson-drawer__video-state {
        display: none;
        border: 1px solid rgba(24, 36, 51, .1);
        border-radius: 10px;
        background: #f8fafc;
        padding: 10px;
        color: #374151;
        font-size: 13px;
        font-weight: 700;
    }

    .poligonium-lesson-drawer__video-state.is-open {
        display: block;
    }

    .poligonium-lesson-drawer__video-preview {
        display: none;
        width: 100%;
        max-height: 220px;
        border-radius: 12px;
        background: #111827;
    }

    .poligonium-lesson-drawer__video-preview.is-open {
        display: block;
    }

    .poligonium-lesson-drawer__files {
        display: grid;
        gap: 8px;
    }

    .poligonium-lesson-drawer__file {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        border: 1px solid rgba(24, 36, 51, .1);
        border-radius: 10px;
        background: #fff;
        padding: 9px 10px;
        color: #374151;
        font-size: 13px;
        font-weight: 750;
    }

    @media (max-width: 767.98px) {
        .poligonium-curriculum__top,
        .poligonium-curriculum__section-head,
        .poligonium-curriculum__lesson {
            grid-template-columns: 1fr;
            display: grid;
        }

        .poligonium-curriculum__lessons {
            padding-left: 0;
        }
    }
</style>

<div
    id="course-curriculum-builder"
    class="poligonium-curriculum"
    data-store-chapter-url="{{ route('courses.courses.curriculum.chapters.store', $course) }}"
    data-store-lesson-url="{{ route('courses.courses.curriculum.lessons.store', $course) }}"
    data-bulk-lesson-url="{{ route('courses.courses.curriculum.lessons.bulk', $course) }}"
    data-reorder-url="{{ route('courses.courses.curriculum.reorder', $course) }}"
    data-csrf-token="{{ csrf_token() }}"
>
    <div class="poligonium-curriculum__top">
        <div>
            <h3 class="mb-2">Программа курса</h3>
            <div class="poligonium-curriculum__stats">
                <span>{{ $chapters->count() }} розділи</span>
                <span>{{ $lessonsCount }} уроків</span>
                <span>{{ intdiv($duration, 60) }} год {{ str_pad((string) ($duration % 60), 2, '0', STR_PAD_LEFT) }} хв</span>
            </div>
        </div>
        <div class="poligonium-curriculum__actions">
            <button class="btn btn-primary" type="button" data-curriculum-action="toggle-chapter-form">+ Додати розділ</button>
            <button class="btn btn-outline-secondary" type="button" data-curriculum-action="bulk-upload">Завантажити кілька матеріалів</button>
        </div>
    </div>

    <div class="poligonium-curriculum__inline" data-curriculum-chapter-form>
        <h4 class="mb-3" data-curriculum-chapter-title>Новий розділ</h4>
        <input type="hidden" data-curriculum-field="chapter-after">
        <input type="hidden" data-curriculum-field="chapter-update-url">
        <div class="mb-3">
            <label class="form-label" for="curriculum-chapter-name">Назва</label>
            <input class="form-control" id="curriculum-chapter-name" type="text" data-curriculum-field="chapter-name" placeholder="Введіть назву розділу">
        </div>
        <div class="mb-3">
            <label class="form-label" for="curriculum-chapter-description">Опис, необов’язково</label>
            <textarea class="form-control" id="curriculum-chapter-description" rows="2" data-curriculum-field="chapter-description" placeholder="Коротко опишіть зміст розділу"></textarea>
        </div>
        <div class="d-flex flex-wrap gap-2 justify-content-end">
            <button class="btn btn-outline-secondary" type="button" data-curriculum-action="cancel-chapter">Скасувати</button>
            <button class="btn btn-primary" type="button" data-curriculum-action="save-chapter" data-curriculum-chapter-submit>Додати розділ</button>
        </div>
    </div>

    <div class="poligonium-curriculum__inline" data-curriculum-bulk-form>
        <h4 class="mb-3">Завантажити кілька матеріалів</h4>
        <div class="mb-3">
            <label class="form-label" for="curriculum-bulk-chapter">Раздел</label>
            <select class="form-select" id="curriculum-bulk-chapter" data-curriculum-field="bulk-chapter">
                <option value="">Оберіть розділ</option>
                @foreach ($chapters as $chapter)
                    <option value="{{ $chapter->getKey() }}">{{ $chapter->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Додати відео з медіатеки</label>
            <div class="border rounded p-3 bg-light">
                {!! Form::mediaFile('curriculum_bulk_video_path', null) !!}
                <button class="btn btn-sm btn-outline-primary mt-2" type="button" data-curriculum-action="append-bulk-video">Додати до списку</button>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="curriculum-bulk-videos">Відео для створення уроків</label>
            <textarea class="form-control" id="curriculum-bulk-videos" rows="5" data-curriculum-field="bulk-videos" placeholder="Одне відео в рядку"></textarea>
            <div class="form-hint mt-2">З кожного відео буде створено чернетку уроку. Назва береться з імені файлу.</div>
        </div>
        <div class="d-flex flex-wrap gap-2 justify-content-end">
            <button class="btn btn-outline-secondary" type="button" data-curriculum-action="cancel-bulk">Скасувати</button>
            <button class="btn btn-primary" type="button" data-curriculum-action="save-bulk">Створити чернетки уроків</button>
        </div>
    </div>

    <div class="poligonium-curriculum__notice" data-curriculum-undo>
        <span>Порядок програми збережено.</span>
        <button class="btn btn-sm btn-outline-success" type="button" data-curriculum-action="undo-order">Скасувати</button>
    </div>

    <div class="poligonium-curriculum__notice" data-curriculum-lesson-undo>
        <span>Урок видалено.</span>
        <button class="btn btn-sm btn-outline-success" type="button" data-curriculum-action="restore-lesson">Відновити</button>
    </div>

    <div class="poligonium-curriculum__structure">
        @if ($chapters->isEmpty())
            <div class="empty">
                <p class="empty-title">Розділів ще немає</p>
                <p class="empty-subtitle text-muted">Створіть перший розділ, після цього можна додавати уроки прямо всередині курсу.</p>
            </div>
        @else
            <div data-sortable-list="chapters">
                @foreach ($chapters as $chapter)
                    @php
                        $chapterDuration = (int) $chapter->lessons->sum('duration_minutes');
                    @endphp
                    <section class="poligonium-curriculum__section" draggable="true" data-sortable-item="chapter" data-chapter-id="{{ $chapter->getKey() }}">
                        <div class="poligonium-curriculum__section-head">
                            <span class="poligonium-curriculum__drag" aria-hidden="true">≡</span>
                            <div>
                                <div class="poligonium-curriculum__title">Розділ {{ $loop->iteration }}. {{ $chapter->name }}</div>
                                <div class="poligonium-curriculum__muted">
                                    {{ $chapter->lessons->count() }} уроків • {{ intdiv($chapterDuration, 60) }} год {{ str_pad((string) ($chapterDuration % 60), 2, '0', STR_PAD_LEFT) }} хв
                                </div>
                                @if ($chapter->description)
                                    <div class="poligonium-curriculum__muted mt-1">{{ $chapter->description }}</div>
                                @endif
                            </div>
                            <div class="poligonium-curriculum__menu">
                                <button
                                    class="btn btn-sm btn-outline-secondary"
                                    type="button"
                                    data-curriculum-action="edit-chapter"
                                    data-chapter-name="{{ e($chapter->name) }}"
                                    data-chapter-description="{{ e($chapter->description) }}"
                                    data-chapter-update-url="{{ route('courses.courses.curriculum.chapters.update', [$course, $chapter]) }}"
                                >Редагувати</button>
                            </div>
                        </div>

                        <div class="poligonium-curriculum__lessons" data-sortable-list="lessons" data-chapter-id="{{ $chapter->getKey() }}">
                            @foreach ($chapter->lessons as $lesson)
                                <article class="poligonium-curriculum__lesson" draggable="true" data-sortable-item="lesson" data-lesson-id="{{ $lesson->getKey() }}">
                                    <span class="poligonium-curriculum__drag" aria-hidden="true">≡</span>
                                    <div>
                                        <div class="poligonium-curriculum__title">{{ $loop->iteration }}. {{ $lesson->name }}</div>
                                        @if ($lesson->description)
                                            <div class="poligonium-curriculum__muted">{{ $lesson->description }}</div>
                                        @endif
                                        <div class="poligonium-curriculum__meta">
                                            @if (($lesson->video_status ?: null) === 'uploaded' || $lesson->video_path || $lesson->video_embed)
                                                <span class="is-ok">Відео завантажено</span>
                                            @elseif (($lesson->video_status ?: null) === 'processing')
                                                <span>Відео обробляється</span>
                                            @elseif (($lesson->video_status ?: null) === 'failed')
                                                <span class="is-warn">Помилка відео</span>
                                            @else
                                                <span class="is-warn" data-course-video-missing>Відео відсутнє</span>
                                            @endif
                                            @if (($lesson->access_mode ?: null) === 'free' || $lesson->is_free_preview)
                                                <span>Безкоштовний перегляд</span>
                                            @elseif (($lesson->access_mode ?: null) === 'scheduled')
                                                <span>Відкриється за датою</span>
                                            @elseif (($lesson->access_mode ?: null) === 'drip')
                                                <span>Відкриється після покупки</span>
                                            @elseif ($lesson->requires_access)
                                                <span>Після покупки</span>
                                            @else
                                                <span>Відкрито</span>
                                            @endif
                                            <span>{{ (int) $lesson->duration_minutes }} хв</span>
                                            <span>{{ $lesson->status->label() }}</span>
                                        </div>
                                    </div>
                                    <div class="poligonium-curriculum__menu">
                                        <button
                                            class="btn btn-sm btn-outline-primary"
                                            type="button"
                                            data-curriculum-action="edit-lesson"
                                            data-lesson-id="{{ $lesson->getKey() }}"
                                        >Редагувати</button>
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-curriculum-action="toggle-lesson-menu">⋮</button>
                                        <div class="poligonium-curriculum__quick" data-curriculum-lesson-menu>
                                            <button type="button" data-curriculum-action="edit-lesson" data-lesson-id="{{ $lesson->getKey() }}">Редагувати</button>
                                            <a href="{{ route('courses.lessons.edit', $lesson->getKey()) }}" target="_blank" rel="noopener">Передперегляд</a>
                                            <button type="button" data-curriculum-action="quick-lesson-action" data-lesson-id="{{ $lesson->getKey() }}" data-quick-action="duplicate">Дублювати</button>
                                            <button type="button" data-curriculum-action="quick-lesson-action" data-lesson-id="{{ $lesson->getKey() }}" data-quick-action="make_free">Зробити безкоштовним</button>
                                            <button type="button" data-curriculum-action="quick-lesson-action" data-lesson-id="{{ $lesson->getKey() }}" data-quick-action="hide">Приховати</button>
                                            <button class="is-danger" type="button" data-curriculum-action="quick-lesson-action" data-lesson-id="{{ $lesson->getKey() }}" data-quick-action="delete">Видалити</button>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <button class="btn btn-sm btn-outline-primary mt-3" type="button" data-curriculum-action="open-lesson-drawer" data-chapter-id="{{ $chapter->getKey() }}">
                            + Додати урок
                        </button>
                    </section>
                    <div class="poligonium-curriculum__insert">
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-curriculum-action="insert-chapter-after" data-after-chapter-id="{{ $chapter->getKey() }}">
                            + Розділ після цього
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="poligonium-lesson-drawer" data-lesson-drawer>
        <div class="poligonium-lesson-drawer__panel">
            <div class="poligonium-lesson-drawer__head">
                <strong data-curriculum-lesson-title>Новий урок</strong>
                <button class="btn btn-icon btn-sm btn-outline-secondary" type="button" data-curriculum-action="close-lesson-drawer">×</button>
            </div>
            <div class="poligonium-lesson-drawer__body">
                <input type="hidden" data-curriculum-field="lesson-chapter">
                <input type="hidden" data-curriculum-field="lesson-update-url">

                <div>
                    <label class="form-label" for="curriculum-lesson-name">Назва уроку</label>
                    <input class="form-control" id="curriculum-lesson-name" type="text" data-curriculum-field="lesson-name" placeholder="Металеві механічні лапки">
                </div>

                <div>
                    <label class="form-label" for="curriculum-lesson-description">Короткий опис</label>
                    <textarea class="form-control" id="curriculum-lesson-description" rows="3" data-curriculum-field="lesson-description" placeholder="Створюємо лапки з шарнірами..."></textarea>
                </div>

                <div>
                    <label class="form-label">Тип матеріалу</label>
                    <div class="d-grid gap-2">
                        <label><input type="radio" name="curriculum_material_type" value="video" checked> Відео</label>
                        <label><input type="radio" name="curriculum_material_type" value="text"> Текст</label>
                        <label><input type="radio" name="curriculum_material_type" value="video_text"> Відео і текст</label>
                        <label><input type="radio" name="curriculum_material_type" value="assignment"> Практичне завдання</label>
                        <label><input type="radio" name="curriculum_material_type" value="quiz"> Тест</label>
                    </div>
                </div>

                <div>
                    <label class="form-label" for="curriculum-lesson-content">Текст уроку</label>
                    <textarea class="form-control" id="curriculum-lesson-content" rows="5" data-curriculum-field="lesson-content" placeholder="Текст, посилання, нотатки і завдання для учня"></textarea>
                </div>

                <div>
                    <label class="form-label">Відео уроку</label>
                    <div class="border rounded p-3 bg-light">
                        {!! Form::mediaFile('curriculum_lesson_video_path', null) !!}
                        <div class="form-hint mt-2">Оберіть відео з медіатеки. Тривалість поки можна вказати вручну нижче.</div>
                    </div>
                    <video class="poligonium-lesson-drawer__video-preview mt-2" data-curriculum-video-preview controls preload="metadata"></video>
                    <div class="poligonium-lesson-drawer__video-state mt-2" data-curriculum-video-state></div>
                    <button class="btn btn-sm btn-outline-danger mt-2" type="button" data-curriculum-action="clear-lesson-video">Видалити відео</button>
                </div>

                <div>
                    <label class="form-label" for="curriculum-lesson-embed">Embed-відео, якщо потрібно</label>
                    <textarea class="form-control" id="curriculum-lesson-embed" rows="2" data-curriculum-field="lesson-embed" placeholder="YouTube/Vimeo iframe или ссылка для встраивания"></textarea>
                </div>

                <div>
                    <label class="form-label">Прикріплені файли</label>
                    <div class="poligonium-lesson-drawer__files" data-curriculum-lesson-files>
                        <div class="poligonium-curriculum__muted">Файлів поки немає</div>
                    </div>
                </div>

                <div class="border rounded p-3 bg-light">
                    <div class="mb-2">
                        <label class="form-label" for="curriculum-lesson-attachment-name">Додати файл до уроку</label>
                        <input class="form-control" id="curriculum-lesson-attachment-name" type="text" data-curriculum-field="lesson-attachment-name" placeholder="Файли проєкту, сцена, архів матеріалів">
                    </div>
                    {!! Form::mediaFile('curriculum_lesson_attachment_path', null) !!}
                    <div class="form-hint mt-2">Під час збереження уроку файл додасться до списку матеріалів.</div>
                </div>

                <div>
                    <label class="form-label" for="curriculum-lesson-duration">Расчётная продолжительность</label>
                    <div class="input-group">
                        <input class="form-control" id="curriculum-lesson-duration" type="number" min="0" data-curriculum-field="lesson-duration" placeholder="15">
                        <span class="input-group-text">минут</span>
                    </div>
                </div>

                <div>
                    <label class="form-label" for="curriculum-lesson-access">Доступ до уроку</label>
                    <select class="form-select" id="curriculum-lesson-access" data-curriculum-field="lesson-access">
                        <option value="free">Безкоштовний перегляд</option>
                        <option value="paid" selected>Після покупки курсу</option>
                        <option value="scheduled">Відкрити у визначену дату</option>
                        <option value="drip">Відкрити через кілька днів після покупки</option>
                    </select>
                </div>

                <div class="poligonium-lesson-drawer__conditional" data-curriculum-access-panel="scheduled">
                    <label class="form-label" for="curriculum-lesson-opens-at">Дата открытия</label>
                    <input class="form-control" id="curriculum-lesson-opens-at" type="datetime-local" data-curriculum-field="lesson-opens-at">
                    <div class="form-hint mt-1">Часовой пояс: Europe/Kyiv.</div>
                </div>

                <div class="poligonium-lesson-drawer__conditional" data-curriculum-access-panel="drip">
                    <label class="form-label" for="curriculum-lesson-drip-days">Відкрити через</label>
                    <div class="input-group">
                        <input class="form-control" id="curriculum-lesson-drip-days" type="number" min="0" data-curriculum-field="lesson-drip-days" placeholder="7">
                        <span class="input-group-text">днів після покупки</span>
                    </div>
                </div>

                <div>
                    <label class="form-label" for="curriculum-lesson-status">Публікація уроку</label>
                    <select class="form-select" id="curriculum-lesson-status" data-curriculum-field="lesson-status">
                        <option value="draft">Чернетка</option>
                        <option value="published" selected>Опубліковано</option>
                        <option value="pending">На проверке</option>
                    </select>
                </div>

                <div class="d-flex flex-wrap gap-2 justify-content-end">
                    <button class="btn btn-outline-secondary" type="button" data-curriculum-action="close-lesson-drawer">Скасувати</button>
                    <button class="btn btn-primary" type="button" data-curriculum-action="save-lesson" data-curriculum-lesson-submit>Створити урок</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const builder = document.getElementById('course-curriculum-builder')

        if (!builder) {
            return
        }

        const field = (name) => builder.querySelector(`[data-curriculum-field="${name}"]`)
        const input = (name) => builder.querySelector(`[name="${name}"]`)
        const token = builder.dataset.csrfToken
        const drawer = builder.querySelector('[data-lesson-drawer]')
        const lessonTitle = builder.querySelector('[data-curriculum-lesson-title]')
        const lessonSubmit = builder.querySelector('[data-curriculum-lesson-submit]')
        const lessonVideoState = builder.querySelector('[data-curriculum-video-state]')
        const lessonVideoPreview = builder.querySelector('[data-curriculum-video-preview]')
        const lessonFiles = builder.querySelector('[data-curriculum-lesson-files]')
        const chapterForm = builder.querySelector('[data-curriculum-chapter-form]')
        const bulkForm = builder.querySelector('[data-curriculum-bulk-form]')
        const chapterFormTitle = builder.querySelector('[data-curriculum-chapter-title]')
        const chapterFormSubmit = builder.querySelector('[data-curriculum-chapter-submit]')
        const undoNotice = builder.querySelector('[data-curriculum-undo]')
        const lessonUndoNotice = builder.querySelector('[data-curriculum-lesson-undo]')
        const lessons = @json($lessonPayload)
        let lessonUndo = null
        let lessonUndoTimer = null

        const showSuccess = (message) => window.Botble ? Botble.showSuccess(message) : alert(message)
        const showError = (message) => window.Botble ? Botble.showError(message) : alert(message)
        const escapeHtml = (value = '') => String(value).replace(/[&<>"']/g, (character) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;',
        }[character]))

        const post = (url, payload, button, options = {}) => {
            const settings = { reload: true, onSuccess: null, ...options }
            const formData = new FormData()

            Object.entries(payload).forEach(([key, value]) => {
                formData.append(key, value ?? '')
            })

            if (button) {
                button.disabled = true
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            })
                .then(async (response) => {
                    const data = await response.json().catch(() => ({}))

                    if (!response.ok || data.error) {
                        throw data
                    }

                    showSuccess(data.message || 'Сохранено')
                    settings.onSuccess?.(data)

                    if (settings.reload) {
                        window.setTimeout(() => window.location.reload(), 450)
                    }
                })
                .catch((error) => {
                    const firstError = error?.errors ? Object.values(error.errors).flat()[0] : null
                    showError(firstError || error?.message || 'Не удалось сохранить')
                })
                .finally(() => {
                    if (button) {
                        button.disabled = false
                    }
            })
        }

        const postJson = (url, payload, onSuccess = null) => {
            fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(payload),
            })
                .then(async (response) => {
                    const data = await response.json().catch(() => ({}))

                    if (!response.ok || data.error) {
                        throw data
                    }

                    showSuccess(data.message || 'Порядок программы сохранён')
                    onSuccess?.(data)
                })
                .catch((error) => {
                    const firstError = error?.errors ? Object.values(error.errors).flat()[0] : null
                    showError(firstError || error?.message || 'Не удалось сохранить порядок')
                })
        }

        const openChapterForm = ({ title, submitLabel, name = '', description = '', afterChapterId = '', updateUrl = '' }) => {
            chapterFormTitle.textContent = title
            chapterFormSubmit.textContent = submitLabel
            field('chapter-name').value = name
            field('chapter-description').value = description
            field('chapter-after').value = afterChapterId
            field('chapter-update-url').value = updateUrl
            chapterForm.classList.add('is-open')
            chapterForm.scrollIntoView({ behavior: 'smooth', block: 'center' })
            window.setTimeout(() => field('chapter-name')?.focus(), 180)
        }

        const closeChapterForm = () => {
            chapterForm.classList.remove('is-open')
            field('chapter-name').value = ''
            field('chapter-description').value = ''
            field('chapter-after').value = ''
            field('chapter-update-url').value = ''
            chapterFormTitle.textContent = 'Новий розділ'
            chapterFormSubmit.textContent = 'Додати розділ'
        }

        const showUndo = () => {
            undoNotice?.classList.add('is-open')
        }

        const hideUndo = () => {
            undoNotice?.classList.remove('is-open')
        }

        const showLessonUndo = (data) => {
            if (!data?.data?.undo_token || !data?.data?.restore_url) {
                window.setTimeout(() => window.location.reload(), 450)
                return
            }

            lessonUndo = data.data
            lessonUndoNotice?.classList.add('is-open')

            window.clearTimeout(lessonUndoTimer)
            lessonUndoTimer = window.setTimeout(() => {
                lessonUndo = null
                lessonUndoNotice?.classList.remove('is-open')
                window.location.reload()
            }, 20000)
        }

        const selectedMaterialType = () => builder.querySelector('[name="curriculum_material_type"]:checked')?.value || 'video'

        const setMaterialType = (value = 'video') => {
            const option = builder.querySelector(`[name="curriculum_material_type"][value="${value}"]`)

            if (option) {
                option.checked = true
            }
        }

        const updateAccessPanels = () => {
            const access = field('lesson-access')?.value || 'paid'

            builder.querySelectorAll('[data-curriculum-access-panel]').forEach((panel) => {
                panel.classList.toggle('is-open', panel.dataset.curriculumAccessPanel === access)
            })
        }

        const mediaUrlFromPath = (path = '') => {
            if (!path) {
                return ''
            }

            if (path.startsWith('http') || path.startsWith('/')) {
                return path
            }

            return `/storage/${path.replace(/^\/+/, '')}`
        }

        const setLessonVideo = (path = '', url = '') => {
            const mediaInput = input('curriculum_lesson_video_path')

            if (mediaInput) {
                mediaInput.value = path || ''
                mediaInput.dispatchEvent(new Event('change', { bubbles: true }))
            }

            if (!lessonVideoState) {
                return
            }

            if (path) {
                lessonVideoState.textContent = `Відео обрано: ${path.split('/').pop()}`
                lessonVideoState.classList.add('is-open')
                if (lessonVideoPreview) {
                    lessonVideoPreview.src = url || mediaUrlFromPath(path)
                    lessonVideoPreview.classList.add('is-open')
                }
            } else {
                lessonVideoState.textContent = 'Відео відсутнє'
                lessonVideoState.classList.add('is-open')
                if (lessonVideoPreview) {
                    lessonVideoPreview.removeAttribute('src')
                    lessonVideoPreview.classList.remove('is-open')
                }
            }
        }

        const renderLessonFiles = (files = []) => {
            if (!lessonFiles) {
                return
            }

            if (!files.length) {
                lessonFiles.innerHTML = '<div class="poligonium-curriculum__muted">Файлів поки немає</div>'
                return
            }

            lessonFiles.innerHTML = files.map((file) => {
                const label = file.name || file.file_path?.split('/').pop() || 'Файл уроку'
                const href = file.url || mediaUrlFromPath(file.file_path || '')

                return `<div class="poligonium-lesson-drawer__file"><span>${escapeHtml(label)}</span><span class="d-flex gap-2 align-items-center">${href ? `<a href="${escapeHtml(href)}" target="_blank" rel="noopener">Відкрити</a>` : ''}${file.destroy_url ? `<button class="btn btn-sm btn-outline-danger" type="button" data-curriculum-action="remove-lesson-file" data-file-destroy-url="${escapeHtml(file.destroy_url)}">Видалити</button>` : ''}</span></div>`
            }).join('')
        }

        const fillLessonDrawer = (lesson = null, chapterId = '') => {
            lessonTitle.textContent = lesson ? 'Редагування уроку' : 'Новий урок'
            lessonSubmit.textContent = lesson ? 'Зберегти урок' : 'Створити урок'

            field('lesson-chapter').value = lesson?.chapter_id || chapterId || ''
            field('lesson-update-url').value = lesson?.update_url || ''
            field('lesson-name').value = lesson?.name || ''
            field('lesson-description').value = lesson?.description || ''
            field('lesson-content').value = lesson?.content || ''
            field('lesson-embed').value = lesson?.video_embed || ''
            field('lesson-duration').value = lesson?.duration_minutes || ''
            field('lesson-access').value = lesson?.access_mode || 'paid'
            field('lesson-opens-at').value = lesson?.opens_at || ''
            field('lesson-drip-days').value = lesson?.drip_days || ''
            field('lesson-status').value = lesson?.status || 'published'
            field('lesson-attachment-name').value = ''
            const attachmentInput = input('curriculum_lesson_attachment_path')

            if (attachmentInput) {
                attachmentInput.value = ''
                attachmentInput.dispatchEvent(new Event('change', { bubbles: true }))
            }

            setMaterialType(lesson?.material_type || 'video')
            setLessonVideo(lesson?.video_path || '', lesson?.video_url || '')
            renderLessonFiles(lesson?.files || [])
            updateAccessPanels()
        }

        const openLessonDrawer = (lesson = null, chapterId = '') => {
            fillLessonDrawer(lesson, chapterId)
            drawer.classList.add('is-open')
            field('lesson-name')?.focus()
        }

        const closeLessonDrawer = () => {
            drawer.classList.remove('is-open')
            window.setTimeout(() => fillLessonDrawer(null, ''), 160)
        }

        builder.addEventListener('click', (event) => {
            const action = event.target.closest('[data-curriculum-action]')?.dataset.curriculumAction

            if (!action) {
                return
            }

            if (action === 'toggle-chapter-form') {
                openChapterForm({
                    title: 'Новий розділ',
                    submitLabel: 'Додати розділ',
                })
            }

            if (action === 'insert-chapter-after') {
                openChapterForm({
                    title: 'Новий розділ після вибраного',
                    submitLabel: 'Додати розділ сюди',
                    afterChapterId: event.target.closest('[data-after-chapter-id]')?.dataset.afterChapterId || '',
                })
            }

            if (action === 'edit-chapter') {
                const button = event.target.closest('[data-curriculum-action]')

                openChapterForm({
                    title: 'Редагування розділу',
                    submitLabel: 'Зберегти розділ',
                    name: button.dataset.chapterName || '',
                    description: button.dataset.chapterDescription || '',
                    updateUrl: button.dataset.chapterUpdateUrl || '',
                })
            }

            if (action === 'cancel-chapter') {
                closeChapterForm()
            }

            if (action === 'save-chapter') {
                const updateUrl = field('chapter-update-url')?.value
                const payload = {
                    name: field('chapter-name')?.value,
                    description: field('chapter-description')?.value,
                }

                if (updateUrl) {
                    payload._method = 'PUT'
                } else {
                    payload.after_chapter_id = field('chapter-after')?.value
                }

                post(updateUrl || builder.dataset.storeChapterUrl, payload, event.target.closest('button'))
            }

            if (action === 'open-lesson-drawer') {
                openLessonDrawer(null, event.target.closest('[data-chapter-id]')?.dataset.chapterId || '')
            }

            if (action === 'edit-lesson') {
                const lessonId = event.target.closest('[data-lesson-id]')?.dataset.lessonId

                openLessonDrawer(lessons[lessonId] || null)
            }

            if (action === 'toggle-lesson-menu') {
                const menu = event.target.closest('.poligonium-curriculum__menu')?.querySelector('[data-curriculum-lesson-menu]')

                builder.querySelectorAll('[data-curriculum-lesson-menu]').forEach((item) => {
                    item.classList.toggle('is-open', item === menu && !item.classList.contains('is-open'))
                })
            }

            if (action === 'quick-lesson-action') {
                const button = event.target.closest('[data-quick-action]')
                const lesson = lessons[button?.dataset.lessonId]
                const quickAction = button?.dataset.quickAction

                if (!lesson?.quick_action_url || !quickAction) {
                    return
                }

                if (quickAction === 'delete' && !window.confirm('Видалити цей урок? Його можна відновити протягом 20 секунд.')) {
                    return
                }

                const lessonRow = button.closest('[data-sortable-item="lesson"]')

                post(lesson.quick_action_url, { action: quickAction }, button, {
                    reload: quickAction !== 'delete',
                    onSuccess: quickAction === 'delete' ? (data) => {
                        lessonRow?.classList.add('d-none')
                        showLessonUndo(data)
                    } : null,
                })
            }

            if (action === 'close-lesson-drawer') {
                closeLessonDrawer()
            }

            if (action === 'save-lesson') {
                const updateUrl = field('lesson-update-url')?.value
                const payload = {
                    chapter_id: field('lesson-chapter')?.value,
                    name: field('lesson-name')?.value,
                    description: field('lesson-description')?.value,
                    content: field('lesson-content')?.value,
                    material_type: selectedMaterialType(),
                    video_path: input('curriculum_lesson_video_path')?.value,
                    video_embed: field('lesson-embed')?.value,
                    duration_minutes: field('lesson-duration')?.value,
                    access_mode: field('lesson-access')?.value,
                    opens_at: field('lesson-opens-at')?.value,
                    drip_days: field('lesson-drip-days')?.value,
                    attachment_name: field('lesson-attachment-name')?.value,
                    attachment_path: input('curriculum_lesson_attachment_path')?.value,
                    status: field('lesson-status')?.value,
                }

                if (updateUrl) {
                    payload._method = 'PUT'
                }

                post(updateUrl || builder.dataset.storeLessonUrl, payload, event.target.closest('button'))
            }

            if (action === 'clear-lesson-video') {
                setLessonVideo('')
                field('lesson-embed').value = ''
            }

            if (action === 'remove-lesson-file') {
                const destroyUrl = event.target.closest('[data-file-destroy-url]')?.dataset.fileDestroyUrl

                if (!destroyUrl || !window.confirm('Видалити цей файл з уроку?')) {
                    return
                }

                post(destroyUrl, { _method: 'DELETE' }, event.target.closest('button'))
            }

            if (action === 'bulk-upload') {
                bulkForm?.classList.add('is-open')
                bulkForm?.scrollIntoView({ behavior: 'smooth', block: 'center' })
            }

            if (action === 'cancel-bulk') {
                bulkForm?.classList.remove('is-open')
            }

            if (action === 'append-bulk-video') {
                const videoInput = input('curriculum_bulk_video_path')
                const videoPath = videoInput?.value?.trim()
                const list = field('bulk-videos')

                if (!videoPath || !list) {
                    showError('Спочатку оберіть відео з медіатеки.')
                    return
                }

                const existing = list.value.trim()
                list.value = existing ? `${existing}\n${videoPath}` : videoPath
                videoInput.value = ''
                videoInput.dispatchEvent(new Event('change', { bubbles: true }))
            }

            if (action === 'save-bulk') {
                const videos = (field('bulk-videos')?.value || '')
                    .split('\n')
                    .map((item) => item.trim())
                    .filter(Boolean)

                post(builder.dataset.bulkLessonUrl, {
                    chapter_id: field('bulk-chapter')?.value,
                    ...videos.reduce((payload, video, index) => {
                        payload[`videos[${index}]`] = video

                        return payload
                    }, {}),
                }, event.target.closest('button'))
            }

            if (action === 'undo-order' && previousOrder) {
                hideUndo()
                postJson(builder.dataset.reorderUrl, previousOrder, () => {
                    window.setTimeout(() => window.location.reload(), 450)
                })
            }

            if (action === 'restore-lesson' && lessonUndo) {
                window.clearTimeout(lessonUndoTimer)
                post(lessonUndo.restore_url, { undo_token: lessonUndo.undo_token }, event.target.closest('button'))
            }
        })

        document.addEventListener('click', (event) => {
            if (!event.target.closest('.poligonium-curriculum__menu')) {
                builder.querySelectorAll('[data-curriculum-lesson-menu]').forEach((item) => item.classList.remove('is-open'))
            }
        })

        field('lesson-access')?.addEventListener('change', updateAccessPanels)
        input('curriculum_lesson_video_path')?.addEventListener('change', (event) => {
            setLessonVideo(event.target.value)
        })

        lessonVideoPreview?.addEventListener('loadedmetadata', () => {
            const durationField = field('lesson-duration')

            if (!durationField || Number(durationField.value || 0) > 0 || !Number.isFinite(lessonVideoPreview.duration)) {
                return
            }

            durationField.value = Math.max(1, Math.round(lessonVideoPreview.duration / 60))
        })

        const collectOrder = () => ({
            chapters: Array.from(builder.querySelectorAll('[data-sortable-list="chapters"] > [data-sortable-item="chapter"]'))
                .map((item) => item.dataset.chapterId),
            lesson_groups: Array.from(builder.querySelectorAll('[data-sortable-list="lessons"]'))
                .map((list) => ({
                    chapter_id: list.dataset.chapterId,
                    lessons: Array.from(list.querySelectorAll('[data-sortable-item="lesson"]'))
                        .map((item) => item.dataset.lessonId),
                })),
        })

        const getDragAfterElement = (container, y, type) => {
            const items = [...container.querySelectorAll(`[data-sortable-item="${type}"]:not(.is-dragging)`)]

            return items.reduce((closest, child) => {
                const box = child.getBoundingClientRect()
                const offset = y - box.top - box.height / 2

                if (offset < 0 && offset > closest.offset) {
                    return { offset, element: child }
                }

                return closest
            }, { offset: Number.NEGATIVE_INFINITY, element: null }).element
        }

        let draggedItem = null
        let previousOrder = null

        builder.addEventListener('dragstart', (event) => {
            const item = event.target.closest('[data-sortable-item]')

            if (!item) {
                return
            }

            draggedItem = item
            previousOrder = collectOrder()
            hideUndo()
            item.classList.add('is-dragging')
            event.dataTransfer.effectAllowed = 'move'
        })

        builder.addEventListener('dragend', () => {
            draggedItem?.classList.remove('is-dragging')
            draggedItem = null
        })

        builder.addEventListener('dragover', (event) => {
            const list = event.target.closest('[data-sortable-list]')

            if (!list || !draggedItem) {
                return
            }

            const type = draggedItem.dataset.sortableItem
            const expectedList = type === 'chapter' ? 'chapters' : 'lessons'

            if (list.dataset.sortableList !== expectedList) {
                return
            }

            event.preventDefault()
            const after = getDragAfterElement(list, event.clientY, type)

            if (after) {
                list.insertBefore(draggedItem, after)
            } else {
                list.appendChild(draggedItem)
            }
        })

        builder.addEventListener('drop', (event) => {
            if (!draggedItem) {
                return
            }

            event.preventDefault()
            const nextOrder = collectOrder()

            if (JSON.stringify(previousOrder) === JSON.stringify(nextOrder)) {
                return
            }

            postJson(builder.dataset.reorderUrl, nextOrder, showUndo)
        })
    })
</script>

<?php

namespace Botble\Courses\Http\Controllers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\Courses\Forms\CourseForm;
use Botble\Courses\Http\Requests\CourseRequest;
use Botble\Courses\Models\Course;
use Botble\Courses\Support\CoursePublicationReadiness;
use Botble\Courses\Support\CourseTranslationService;
use Botble\Courses\Tables\CourseTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class CourseController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/courses::courses.name'))
            ->add(trans('plugins/courses::courses.courses'), route('courses.courses.index'));
    }

    public function index(CourseTable $table)
    {
        $this->pageTitle(trans('plugins/courses::courses.courses'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('core/base::forms.create'));

        return CourseForm::create()->renderForm();
    }

    public function store(CourseRequest $request, CoursePublicationReadiness $readiness)
    {
        $form = CourseForm::create()->setRequest($request);
        $form->save();
        $this->syncCourseCover($form->getModel(), $request);

        if ($response = $this->handlePublicationIntent($form->getModel()->refresh(), $request, $readiness)) {
            return $response;
        }

        return $this->httpResponse()
            ->setPreviousRoute('courses.courses.index')
            ->setNextRoute('courses.courses.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(Course $course)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $course->name]));

        return CourseForm::createFromModel($course)->renderForm();
    }

    public function update(Course $course, CourseRequest $request, CoursePublicationReadiness $readiness)
    {
        $wasPublished = ($course->publication_state ?: 'draft') === 'published';

        CourseForm::createFromModel($course)->setRequest($request)->save();
        $this->syncCourseCover($course, $request);
        $course->refresh();

        if ($response = $this->handlePublicationIntent($course, $request, $readiness)) {
            return $response;
        }

        if ($wasPublished && ! $course->has_unpublished_changes) {
            $course->forceFill(['has_unpublished_changes' => true])->save();
        }

        return $this->httpResponse()
            ->setPreviousRoute('courses.courses.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Course $course)
    {
        return DeleteResourceAction::make($course);
    }

    public function preview(Course $course, string $role = 'visitor'): RedirectResponse
    {
        $role = in_array($role, ['visitor', 'buyer', 'student'], true) ? $role : 'visitor';

        return redirect()->to(URL::temporarySignedRoute(
            'courses.public.preview',
            now()->addMinutes(30),
            [
                'course' => $course->slug,
                'preview_role' => $role,
            ]
        ));
    }

    public function publish(Course $course, CoursePublicationReadiness $readiness)
    {
        return $this->publishCourse($course, $readiness);
    }

    public function hide(Course $course)
    {
        $course->forceFill([
            'publication_state' => 'hidden',
            'publish_scheduled_at' => null,
            'has_unpublished_changes' => false,
        ])->save();

        return $this->httpResponse()
            ->setPreviousRoute('courses.courses.index')
            ->withUpdatedSuccessMessage();
    }

    public function autosave(Course $course, Request $request)
    {
        if ($request->has('software')) {
            $request->merge(['software' => $this->normalizeMultiValue($request->input('software'))]);
        }

        if ($request->has('skills')) {
            $request->merge(['skills' => $this->normalizeMultiValue($request->input('skills'))]);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:191'],
            'slug' => ['sometimes', 'required', 'string', 'max:191'],
            'description' => ['sometimes', 'nullable', 'string'],
            'content' => ['sometimes', 'nullable', 'string'],
            'image' => ['sometimes', 'nullable', 'string'],
            'intro_video' => ['sometimes', 'nullable', 'string'],
            'category_id' => ['sometimes', 'nullable', 'exists:plg_course_categories,id'],
            'difficulty' => ['sometimes', 'nullable', 'string', 'max:80'],
            'software' => ['sometimes', 'array'],
            'software.*' => ['string'],
            'skills' => ['sometimes', 'array'],
            'skills.*' => ['string'],
            'visibility_mode' => ['sometimes', 'nullable', 'string', 'max:60'],
            'price_type' => ['sometimes', 'nullable', 'string', 'max:60'],
            'price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'currency' => ['sometimes', 'nullable', 'string', 'max:10'],
            'sale_status' => ['sometimes', 'nullable', 'string', 'max:60'],
            'sales_mode' => ['sometimes', 'nullable', 'string', 'max:60'],
            'sales_starts_at' => ['sometimes', 'nullable', 'date'],
            'early_access_price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'early_access_starts_at' => ['sometimes', 'nullable', 'date'],
            'early_access_ends_at' => ['sometimes', 'nullable', 'date'],
            'early_access_slots' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'course_access_mode' => ['sometimes', 'nullable', 'string', 'max:60'],
            'released_at' => ['sometimes', 'nullable', 'date'],
            'timezone' => ['sometimes', 'nullable', 'string', 'max:80'],
            'show_release_date_on_card' => ['sometimes', 'boolean'],
            'gradual_access_enabled' => ['sometimes', 'boolean'],
        ]);

        if ($validated === []) {
            return $this->httpResponse()->setData(['saved_at' => now()->toIso8601String()]);
        }

        $course->forceFill($validated);

        if (($course->publication_state ?: 'draft') === 'published') {
            $course->has_unpublished_changes = true;
        }

        $course->save();

        return $this
            ->httpResponse()
            ->setData([
                'saved_at' => now()->toIso8601String(),
                'has_unpublished_changes' => (bool) $course->has_unpublished_changes,
            ])
            ->setMessage('Черновик автоматически сохранён.');
    }

    public function translations(Course $course, CourseTranslationService $translations)
    {
        return $this
            ->httpResponse()
            ->setData([
                'locales' => $translations->locales(),
                'structure' => $translations->structure($course),
                'completion' => $translations->completion($course),
                'translations' => collect($translations->locales())
                    ->mapWithKeys(fn (string $label, string $locale) => [$locale => $translations->load($course, $locale)])
                    ->all(),
            ]);
    }

    public function saveTranslation(Course $course, Request $request, CourseTranslationService $translations)
    {
        $validated = $request->validate([
            'locale' => ['required', 'string', 'in:uk,en'],
            'course' => ['nullable', 'array'],
            'course.name' => ['nullable', 'string', 'max:191'],
            'course.description' => ['nullable', 'string'],
            'course.content' => ['nullable', 'string'],
            'chapters' => ['nullable', 'array'],
            'chapters.*.name' => ['nullable', 'string', 'max:191'],
            'chapters.*.description' => ['nullable', 'string'],
            'lessons' => ['nullable', 'array'],
            'lessons.*.name' => ['nullable', 'string', 'max:191'],
            'lessons.*.description' => ['nullable', 'string'],
            'lessons.*.content' => ['nullable', 'string'],
        ]);

        $translations->save($course, $validated['locale'], $validated);

        if (($course->publication_state ?: 'draft') === 'published') {
            $course->forceFill(['has_unpublished_changes' => true])->save();
        }

        return $this
            ->httpResponse()
            ->setData([
                'saved_at' => now()->toIso8601String(),
                'completion' => $translations->completion($course),
            ])
            ->setMessage('Перевод сохранён.');
    }

    protected function normalizeMultiValue(mixed $value): array
    {
        if (is_array($value)) {
            return collect($value)
                ->flatten()
                ->flatMap(fn (mixed $item) => $this->normalizeMultiValue($item))
                ->values()
                ->all();
        }

        if ($value === null || $value === '') {
            return [];
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return collect($decoded)
                    ->flatten()
                    ->filter(fn (mixed $item) => $item !== null && $item !== '')
                    ->map(fn (mixed $item) => (string) $item)
                    ->values()
                    ->all();
            }

            if (str_contains($value, ',')) {
                return array_values(array_filter(array_map('trim', explode(',', $value))));
            }
        }

        return [(string) $value];
    }

    public function schedule(Course $course, Request $request, CoursePublicationReadiness $readiness)
    {
        $request->validate([
            'publish_scheduled_at' => ['required', 'date', 'after:now'],
        ]);

        $result = $readiness->evaluate($course);

        if (! $result['can_publish']) {
            return $this->httpResponse()
                ->setError()
                ->setMessage('Нельзя запланировать публикацию: заполните критические поля.');
        }

        $course->forceFill([
            'status' => BaseStatusEnum::PUBLISHED,
            'publication_state' => 'scheduled',
            'publish_scheduled_at' => $request->input('publish_scheduled_at'),
            'published_snapshot' => $readiness->publishSnapshot($course),
            'has_unpublished_changes' => false,
        ])->save();

        return $this->httpResponse()
            ->setPreviousRoute('courses.courses.index')
            ->withUpdatedSuccessMessage();
    }

    protected function syncCourseCover(Course $course, CourseRequest $request): void
    {
        if (! $request->has('image')) {
            return;
        }

        $image = $request->input('image');

        if ($course->image !== $image) {
            $course->forceFill(['image' => $image])->save();
        }
    }

    protected function handlePublicationIntent(Course $course, CourseRequest $request, CoursePublicationReadiness $readiness)
    {
        $intent = $request->input('_publication_intent');

        if ($intent === 'publish') {
            return $this->publishCourse($course, $readiness);
        }

        if ($intent === 'schedule') {
            if (! $request->input('publish_scheduled_at')) {
                return $this->httpResponse()
                    ->setError()
                    ->setMessage('Укажите дату запланированной публикации.');
            }

            return $this->schedule($course, $request, $readiness);
        }

        if ($intent === 'hide') {
            return $this->hide($course);
        }

        return null;
    }

    protected function publishCourse(Course $course, CoursePublicationReadiness $readiness)
    {
        $result = $readiness->evaluate($course);

        if (! $result['can_publish']) {
            return $this->httpResponse()
                ->setError()
                ->setMessage('Нельзя опубликовать курс: заполните критические поля.');
        }

        $course->forceFill([
            'status' => BaseStatusEnum::PUBLISHED,
            'publication_state' => 'published',
            'publish_scheduled_at' => null,
            'published_at' => now(),
            'published_snapshot' => $readiness->publishSnapshot($course),
            'has_unpublished_changes' => false,
        ])->save();

        return $this->httpResponse()
            ->setPreviousRoute('courses.courses.index')
            ->setNextRoute('courses.courses.edit', $course->getKey())
            ->withUpdatedSuccessMessage();
    }
}

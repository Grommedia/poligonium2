<?php

namespace Botble\Courses\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Courses\Support\CourseOptions;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CourseRequest extends Request
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'software' => $this->normalizeMultiChecklistValue($this->input('software')),
            'skills' => $this->normalizeMultiChecklistValue($this->input('skills')),
        ]);
    }

    public function validationData(): array
    {
        $data = parent::validationData();

        $data['software'] = $this->normalizeMultiChecklistValue($data['software'] ?? null);
        $data['skills'] = $this->normalizeMultiChecklistValue($data['skills'] ?? null);

        return $data;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'exists:plg_course_categories,id'],
            'name' => ['required', 'string', 'max:191'],
            'slug' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'intro_video' => ['nullable', 'string'],
            'difficulty' => ['nullable', Rule::in(array_keys(CourseOptions::levels()))],
            'software' => ['nullable', 'array'],
            'software.*' => ['string', Rule::in(array_keys(CourseOptions::software()))],
            'skills' => ['nullable', 'array'],
            'skills.*' => ['string', Rule::in(array_keys(CourseOptions::skills()))],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'lesson_count' => ['nullable', 'integer', 'min:0'],
            'visibility_mode' => ['nullable', Rule::in(array_keys(CourseOptions::visibilityModes()))],
            'price_type' => ['nullable', Rule::in(array_keys(CourseOptions::priceTypes()))],
            'price' => ['nullable', 'numeric', 'min:0'],
            'sale_status' => ['nullable', Rule::in(array_keys(CourseOptions::saleStatuses()))],
            'sales_mode' => ['nullable', Rule::in(array_keys(CourseOptions::salesModes()))],
            'sales_starts_at' => ['nullable', 'date'],
            'early_access_price' => ['nullable', 'numeric', 'min:0'],
            'early_access_starts_at' => ['nullable', 'date'],
            'early_access_ends_at' => ['nullable', 'date'],
            'released_at' => ['nullable', 'date'],
            'course_access_mode' => ['nullable', Rule::in(array_keys(CourseOptions::accessModes()))],
            'timezone' => ['nullable', 'string', 'max:80'],
            'show_release_date_on_card' => ['nullable', 'boolean'],
            'gradual_access_enabled' => ['nullable', 'boolean'],
            'publication_state' => ['nullable', Rule::in(array_keys(CourseOptions::publicationStates()))],
            'publish_scheduled_at' => ['nullable', 'date'],
            'published_at' => ['nullable', 'date'],
            'published_snapshot' => ['nullable'],
            'has_unpublished_changes' => ['nullable', 'boolean'],
            'early_access_slots' => ['nullable', 'integer', 'min:0'],
            'early_access_sold' => ['nullable', 'integer', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'is_featured' => ['nullable', 'boolean'],
            'is_free_preview' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer'],
            'status' => ['required', Rule::in(BaseStatusEnum::values())],
        ];
    }

    protected function normalizeMultiChecklistValue(mixed $value): array
    {
        if (is_array($value)) {
            return collect($value)
                ->flatten()
                ->flatMap(fn (mixed $item) => $this->normalizeMultiChecklistValue($item))
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
}

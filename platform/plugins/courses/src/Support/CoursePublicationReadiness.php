<?php

namespace Botble\Courses\Support;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Courses\Models\Course;

class CoursePublicationReadiness
{
    public function evaluate(Course $course): array
    {
        $course->loadMissing(['chapters.lessons']);

        $checks = [
            $this->check('Добавлено название', (bool) trim((string) $course->name), true),
            $this->check('Добавлено краткое описание', (bool) trim((string) $course->description), true),
            $this->check('Загружено титульное изображение', (bool) trim((string) $course->image), true),
            $this->check('Выбрано направление', (bool) $course->category_id, true),
            $this->check('Указано программное обеспечение', count($course->software ?: []) > 0, true),
            $this->check(
                'Установлена цена платного курса',
                ($course->price_type ?: 'paid') === 'free' || (float) $course->price > 0,
                true
            ),
            $this->check(
                'Указана дата открытия курса',
                ($course->course_access_mode ?: 'immediate') !== 'scheduled' || (bool) $course->released_at,
                true
            ),
            $this->check('Создан хотя бы один раздел', $course->chapters->isNotEmpty(), false),
            $this->check(
                'Создан хотя бы один урок',
                $course->chapters->flatMap(fn ($chapter) => $chapter->lessons)->isNotEmpty(),
                false
            ),
            $this->check(
                sprintf('В уроках без видео: %d', $this->missingVideoCount($course)),
                $this->missingVideoCount($course) === 0,
                false
            ),
            $this->check('English-версия требует проверки', false, false),
        ];

        $passed = collect($checks)->where('passed', true)->count();
        $criticalErrors = collect($checks)->where('passed', false)->where('critical', true)->values()->all();
        $warnings = collect($checks)->where('passed', false)->where('critical', false)->values()->all();

        return [
            'percent' => (int) round(($passed / max(count($checks), 1)) * 100),
            'checks' => $checks,
            'critical_errors' => $criticalErrors,
            'warnings' => $warnings,
            'can_publish' => count($criticalErrors) === 0,
        ];
    }

    public function publishSnapshot(Course $course): array
    {
        $course->loadMissing(['category', 'chapters.lessons.files']);

        return [
            'course' => $course->only([
                'category_id',
                'name',
                'slug',
                'description',
                'content',
                'image',
                'intro_video',
                'difficulty',
                'software',
                'skills',
                'duration_minutes',
                'lesson_count',
                'visibility_mode',
                'price_type',
                'price',
                'currency',
                'sales_mode',
                'sales_starts_at',
                'early_access_price',
                'early_access_starts_at',
                'early_access_ends_at',
                'released_at',
                'course_access_mode',
                'timezone',
                'show_release_date_on_card',
                'gradual_access_enabled',
            ]),
            'chapters' => $course->chapters
                ->filter(fn ($chapter) => $this->statusValue($chapter->status) === BaseStatusEnum::PUBLISHED)
                ->map(fn ($chapter) => [
                    'id' => $chapter->getKey(),
                    'name' => $chapter->name,
                    'description' => $chapter->description,
                    'order' => $chapter->order,
                    'status' => $this->statusValue($chapter->status),
                    'lessons' => $chapter->lessons
                        ->filter(fn ($lesson) => $this->statusValue($lesson->status) === BaseStatusEnum::PUBLISHED)
                        ->map(fn ($lesson) => [
                            'id' => $lesson->getKey(),
                            'name' => $lesson->name,
                            'description' => $lesson->description,
                            'content' => $lesson->content,
                            'material_type' => $lesson->material_type,
                            'video_path' => $lesson->video_path,
                            'video_embed' => $lesson->video_embed,
                            'duration_minutes' => $lesson->duration_minutes,
                            'access_mode' => $lesson->access_mode,
                            'opens_at' => $lesson->opens_at,
                            'drip_days' => $lesson->drip_days,
                            'order' => $lesson->order,
                            'is_free_preview' => $lesson->is_free_preview,
                            'requires_access' => $lesson->requires_access,
                            'status' => $this->statusValue($lesson->status),
                        ])
                        ->values()
                        ->all(),
                ])
                ->values()
                ->all(),
        ];
    }

    protected function check(string $label, bool $passed, bool $critical): array
    {
        return [
            'label' => $label,
            'passed' => $passed,
            'critical' => $critical,
            'type' => $passed ? 'ok' : ($critical ? 'critical' : 'warning'),
        ];
    }

    protected function missingVideoCount(Course $course): int
    {
        return $course->chapters
            ->flatMap(fn ($chapter) => $chapter->lessons)
            ->filter(fn ($lesson) => ($lesson->material_type ?: 'video') !== 'text')
            ->filter(fn ($lesson) => ! $lesson->video_path && ! $lesson->video_embed)
            ->count();
    }

    protected function statusValue(mixed $status): string
    {
        return is_object($status) && method_exists($status, 'getValue') ? $status->getValue() : (string) $status;
    }
}

<?php

namespace Botble\Courses\Support;

use Botble\Courses\Models\Course;
use Illuminate\Support\Facades\DB;

class CourseTranslationService
{
    public function locales(): array
    {
        return [
            'uk' => 'Українська',
            'en' => 'English',
        ];
    }

    public function emptyPayload(Course $course): array
    {
        $course->loadMissing(['chapters.lessons']);

        return [
            'course' => [
                'name' => '',
                'description' => '',
                'content' => '',
            ],
            'chapters' => $course->chapters
                ->mapWithKeys(fn ($chapter) => [
                    $chapter->getKey() => [
                        'name' => '',
                        'description' => '',
                    ],
                ])
                ->all(),
            'lessons' => $course->chapters
                ->flatMap(fn ($chapter) => $chapter->lessons)
                ->mapWithKeys(fn ($lesson) => [
                    $lesson->getKey() => [
                        'name' => '',
                        'description' => '',
                        'content' => '',
                    ],
                ])
                ->all(),
        ];
    }

    public function load(Course $course, string $locale): array
    {
        $payload = $this->emptyPayload($course);

        $courseTranslation = DB::table('plg_courses_translations')
            ->where('plg_courses_id', $course->getKey())
            ->where('lang_code', $locale)
            ->first();

        if ($courseTranslation) {
            $payload['course'] = [
                'name' => (string) $courseTranslation->name,
                'description' => (string) $courseTranslation->description,
                'content' => (string) $courseTranslation->content,
            ];
        }

        DB::table('plg_course_chapters_translations')
            ->whereIn('plg_course_chapters_id', array_keys($payload['chapters']))
            ->where('lang_code', $locale)
            ->get()
            ->each(function ($translation) use (&$payload): void {
                $payload['chapters'][$translation->plg_course_chapters_id] = [
                    'name' => (string) $translation->name,
                    'description' => (string) $translation->description,
                ];
            });

        DB::table('plg_course_lessons_translations')
            ->whereIn('plg_course_lessons_id', array_keys($payload['lessons']))
            ->where('lang_code', $locale)
            ->get()
            ->each(function ($translation) use (&$payload): void {
                $payload['lessons'][$translation->plg_course_lessons_id] = [
                    'name' => (string) $translation->name,
                    'description' => (string) $translation->description,
                    'content' => (string) $translation->content,
                ];
            });

        return $payload;
    }

    public function structure(Course $course): array
    {
        $course->loadMissing(['chapters.lessons']);

        return [
            'course' => [
                'name' => $course->name,
                'description' => $course->description,
                'content' => $course->content,
            ],
            'chapters' => $course->chapters
                ->map(fn ($chapter) => [
                    'id' => $chapter->getKey(),
                    'name' => $chapter->name,
                    'description' => $chapter->description,
                ])
                ->values()
                ->all(),
            'lessons' => $course->chapters
                ->flatMap(fn ($chapter) => $chapter->lessons)
                ->map(fn ($lesson) => [
                    'id' => $lesson->getKey(),
                    'chapter_id' => $lesson->chapter_id,
                    'name' => $lesson->name,
                    'description' => $lesson->description,
                    'content' => $lesson->content,
                ])
                ->values()
                ->all(),
        ];
    }

    public function save(Course $course, string $locale, array $payload): void
    {
        abort_unless(array_key_exists($locale, $this->locales()), 422);

        $courseFields = $payload['course'] ?? [];

        DB::table('plg_courses_translations')->updateOrInsert(
            [
                'lang_code' => $locale,
                'plg_courses_id' => $course->getKey(),
            ],
            [
                'name' => $courseFields['name'] ?? null,
                'description' => $courseFields['description'] ?? null,
                'content' => $courseFields['content'] ?? null,
            ]
        );

        foreach ($payload['chapters'] ?? [] as $chapterId => $fields) {
            if (! $course->chapters()->whereKey($chapterId)->exists()) {
                continue;
            }

            DB::table('plg_course_chapters_translations')->updateOrInsert(
                [
                    'lang_code' => $locale,
                    'plg_course_chapters_id' => $chapterId,
                ],
                [
                    'name' => $fields['name'] ?? null,
                    'description' => $fields['description'] ?? null,
                ]
            );
        }

        foreach ($payload['lessons'] ?? [] as $lessonId => $fields) {
            if (! $course->lessons()->whereKey($lessonId)->exists()) {
                continue;
            }

            DB::table('plg_course_lessons_translations')->updateOrInsert(
                [
                    'lang_code' => $locale,
                    'plg_course_lessons_id' => $lessonId,
                ],
                [
                    'name' => $fields['name'] ?? null,
                    'description' => $fields['description'] ?? null,
                    'content' => $fields['content'] ?? null,
                ]
            );
        }
    }

    public function completion(Course $course): array
    {
        return collect($this->locales())
            ->mapWithKeys(fn (string $label, string $locale) => [$locale => $this->completionForLocale($course, $locale)])
            ->all();
    }

    public function completionForLocale(Course $course, string $locale): int
    {
        $payload = $this->load($course, $locale);
        $values = [
            $payload['course']['name'] ?? '',
            $payload['course']['description'] ?? '',
            $payload['course']['content'] ?? '',
        ];

        foreach ($payload['chapters'] as $chapter) {
            $values[] = $chapter['name'] ?? '';
            $values[] = $chapter['description'] ?? '';
        }

        foreach ($payload['lessons'] as $lesson) {
            $values[] = $lesson['name'] ?? '';
            $values[] = $lesson['description'] ?? '';
            $values[] = $lesson['content'] ?? '';
        }

        $total = count($values);
        $filled = collect($values)->filter(fn ($value) => trim((string) $value) !== '')->count();

        return (int) round(($filled / max($total, 1)) * 100);
    }

    public function applyToCourse(Course $course, string $locale): Course
    {
        if (! array_key_exists($locale, $this->locales())) {
            return $course;
        }

        $payload = $this->load($course, $locale);

        foreach ($payload['course'] as $key => $value) {
            if (trim((string) $value) !== '') {
                $course->setAttribute($key, $value);
            }
        }

        if ($course->relationLoaded('chapters')) {
            $course->chapters->each(function ($chapter) use ($payload): void {
                foreach ($payload['chapters'][$chapter->getKey()] ?? [] as $key => $value) {
                    if (trim((string) $value) !== '') {
                        $chapter->setAttribute($key, $value);
                    }
                }

                if ($chapter->relationLoaded('lessons')) {
                    $chapter->lessons->each(function ($lesson) use ($payload): void {
                        foreach ($payload['lessons'][$lesson->getKey()] ?? [] as $key => $value) {
                            if (trim((string) $value) !== '') {
                                $lesson->setAttribute($key, $value);
                            }
                        }
                    });
                }
            });
        }

        return $course;
    }
}

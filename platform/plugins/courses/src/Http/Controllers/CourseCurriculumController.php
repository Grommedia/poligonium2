<?php

namespace Botble\Courses\Http\Controllers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Courses\Models\Course;
use Botble\Courses\Models\CourseChapter;
use Botble\Courses\Models\CourseLesson;
use Botble\Courses\Models\CourseLessonFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CourseCurriculumController extends BaseController
{
    public function storeChapter(Course $course, Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'after_chapter_id' => [
                'nullable',
                'integer',
                Rule::exists('plg_course_chapters', 'id')->where('course_id', $course->getKey()),
            ],
        ]);

        $order = $this->nextChapterOrder($course);

        if (! empty($validated['after_chapter_id'])) {
            $afterOrder = (int) CourseChapter::query()
                ->where('course_id', $course->getKey())
                ->whereKey($validated['after_chapter_id'])
                ->value('order');

            CourseChapter::query()
                ->where('course_id', $course->getKey())
                ->where('order', '>', $afterOrder)
                ->increment('order');

            $order = $afterOrder + 1;
        }

        $chapter = CourseChapter::query()->create([
            'course_id' => $course->getKey(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'order' => $order,
            'is_free_preview' => false,
            'status' => BaseStatusEnum::PUBLISHED,
        ]);

        return $this
            ->httpResponse()
            ->setData(['id' => $chapter->getKey()])
            ->setMessage(trans('plugins/courses::courses.chapter_created'));
    }

    public function updateChapter(Course $course, CourseChapter $chapter, Request $request)
    {
        abort_unless((int) $chapter->course_id === (int) $course->getKey(), 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
        ]);

        $chapter->forceFill([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ])->save();

        return $this
            ->httpResponse()
            ->setData(['id' => $chapter->getKey()])
            ->setMessage(trans('plugins/courses::courses.chapter_updated'));
    }

    public function storeLesson(Course $course, Request $request)
    {
        $validated = $request->validate([
            'chapter_id' => [
                'required',
                'integer',
                Rule::exists('plg_course_chapters', 'id')->where('course_id', $course->getKey()),
            ],
            'name' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'material_type' => ['nullable', Rule::in(['video', 'text', 'video_text', 'assignment', 'quiz'])],
            'video_path' => ['nullable', 'string', 'max:191'],
            'video_embed' => ['nullable', 'string'],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'access_mode' => ['nullable', Rule::in(['free', 'paid', 'scheduled', 'drip', 'closed'])],
            'opens_at' => ['nullable', 'date'],
            'drip_days' => ['nullable', 'integer', 'min:0'],
            'attachment_name' => ['nullable', 'string', 'max:191'],
            'attachment_path' => ['nullable', 'string', 'max:191'],
            'status' => ['nullable', Rule::in(BaseStatusEnum::values())],
        ]);

        $accessMode = $validated['access_mode'] ?? 'paid';
        $accessState = $this->lessonAccessState($accessMode);
        $videoStatus = $this->lessonVideoStatus($validated['video_path'] ?? null, $validated['video_embed'] ?? null);

        $lesson = CourseLesson::query()->create([
            'course_id' => $course->getKey(),
            'chapter_id' => $validated['chapter_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'content' => $validated['content'] ?? null,
            'material_type' => $validated['material_type'] ?? 'video',
            'video_path' => $validated['video_path'] ?? null,
            'video_embed' => $validated['video_embed'] ?? null,
            'video_status' => $videoStatus,
            'duration_minutes' => $validated['duration_minutes'] ?? 0,
            'access_mode' => $accessState['mode'],
            'opens_at' => $accessState['mode'] === 'scheduled' ? ($validated['opens_at'] ?? null) : null,
            'drip_days' => $accessState['mode'] === 'drip' ? ($validated['drip_days'] ?? null) : null,
            'order' => $this->nextLessonOrder($course, (int) $validated['chapter_id']),
            'is_free_preview' => $accessState['is_free_preview'],
            'requires_access' => $accessState['requires_access'],
            'status' => $accessMode === 'closed' ? BaseStatusEnum::DRAFT : ($validated['status'] ?? BaseStatusEnum::PUBLISHED),
        ]);

        $this->createLessonAttachment($course, $lesson, $validated);

        $this->syncCourseStats($course);

        return $this
            ->httpResponse()
            ->setData(['id' => $lesson->getKey()])
            ->setMessage(trans('plugins/courses::courses.lesson_created'));
    }

    public function updateLesson(Course $course, CourseLesson $lesson, Request $request)
    {
        abort_unless((int) $lesson->course_id === (int) $course->getKey(), 404);

        $validated = $request->validate([
            'chapter_id' => [
                'required',
                'integer',
                Rule::exists('plg_course_chapters', 'id')->where('course_id', $course->getKey()),
            ],
            'name' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'material_type' => ['nullable', Rule::in(['video', 'text', 'video_text', 'assignment', 'quiz'])],
            'video_path' => ['nullable', 'string', 'max:191'],
            'video_embed' => ['nullable', 'string'],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'access_mode' => ['nullable', Rule::in(['free', 'paid', 'scheduled', 'drip', 'closed'])],
            'opens_at' => ['nullable', 'date'],
            'drip_days' => ['nullable', 'integer', 'min:0'],
            'attachment_name' => ['nullable', 'string', 'max:191'],
            'attachment_path' => ['nullable', 'string', 'max:191'],
            'status' => ['nullable', Rule::in(BaseStatusEnum::values())],
        ]);

        $accessMode = $validated['access_mode'] ?? 'paid';
        $accessState = $this->lessonAccessState($accessMode);
        $videoStatus = $this->lessonVideoStatus($validated['video_path'] ?? null, $validated['video_embed'] ?? null);

        $lesson->forceFill([
            'chapter_id' => $validated['chapter_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'content' => $validated['content'] ?? null,
            'material_type' => $validated['material_type'] ?? 'video',
            'video_path' => $validated['video_path'] ?? null,
            'video_embed' => $validated['video_embed'] ?? null,
            'video_status' => $videoStatus,
            'duration_minutes' => $validated['duration_minutes'] ?? 0,
            'access_mode' => $accessState['mode'],
            'opens_at' => $accessState['mode'] === 'scheduled' ? ($validated['opens_at'] ?? null) : null,
            'drip_days' => $accessState['mode'] === 'drip' ? ($validated['drip_days'] ?? null) : null,
            'is_free_preview' => $accessState['is_free_preview'],
            'requires_access' => $accessState['requires_access'],
            'status' => $accessMode === 'closed' ? BaseStatusEnum::DRAFT : ($validated['status'] ?? BaseStatusEnum::PUBLISHED),
        ])->save();

        $this->createLessonAttachment($course, $lesson, $validated);

        $this->syncCourseStats($course);

        return $this
            ->httpResponse()
            ->setData(['id' => $lesson->getKey()])
            ->setMessage(trans('plugins/courses::courses.lesson_updated'));
    }

    public function destroyLessonFile(Course $course, CourseLesson $lesson, CourseLessonFile $file)
    {
        abort_unless((int) $lesson->course_id === (int) $course->getKey(), 404);
        abort_unless((int) $file->lesson_id === (int) $lesson->getKey(), 404);

        $file->delete();

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/courses::courses.lesson_file_deleted'));
    }

    public function quickLessonAction(Course $course, CourseLesson $lesson, Request $request)
    {
        abort_unless((int) $lesson->course_id === (int) $course->getKey(), 404);

        $validated = $request->validate([
            'action' => ['required', Rule::in(['make_free', 'hide', 'duplicate', 'delete'])],
        ]);

        if ($validated['action'] === 'make_free') {
            $lesson->forceFill([
                'access_mode' => 'free',
                'is_free_preview' => true,
                'requires_access' => false,
            ])->save();

            return $this->httpResponse()->setMessage(trans('plugins/courses::courses.lesson_marked_free'));
        }

        if ($validated['action'] === 'hide') {
            $lesson->forceFill(['status' => BaseStatusEnum::DRAFT])->save();

            return $this->httpResponse()->setMessage(trans('plugins/courses::courses.lesson_hidden'));
        }

        if ($validated['action'] === 'duplicate') {
            $copy = $lesson->replicate(['created_at', 'updated_at']);
            $copy->name = $lesson->name . ' — копия';
            $copy->order = $this->nextLessonOrder($course, (int) $lesson->chapter_id);
            $copy->status = BaseStatusEnum::DRAFT;
            $copy->save();

            foreach ($lesson->files as $file) {
                $newFile = $file->replicate(['created_at', 'updated_at']);
                $newFile->lesson_id = $copy->getKey();
                $newFile->course_id = $course->getKey();
                $newFile->save();
            }

            $this->syncCourseStats($course);

            return $this->httpResponse()->setMessage(trans('plugins/courses::courses.lesson_duplicated'));
        }

        $undoToken = Str::uuid()->toString();
        $snapshot = [
            'lesson' => $lesson->getAttributes(),
            'files' => $lesson->files->map(fn (CourseLessonFile $file) => $file->getAttributes())->all(),
        ];

        Cache::put($this->lessonUndoCacheKey($undoToken), $snapshot, now()->addSeconds(20));
        $lesson->delete();
        $this->syncCourseStats($course);

        return $this
            ->httpResponse()
            ->setData([
                'undo_token' => $undoToken,
                'restore_url' => route('courses.courses.curriculum.lessons.restore', $course),
            ])
            ->setMessage(trans('plugins/courses::courses.lesson_deleted'));
    }

    public function restoreLesson(Course $course, Request $request)
    {
        $validated = $request->validate([
            'undo_token' => ['required', 'string'],
        ]);

        $cacheKey = $this->lessonUndoCacheKey($validated['undo_token']);
        $snapshot = Cache::pull($cacheKey);

        if (! $snapshot || empty($snapshot['lesson'])) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/courses::courses.lesson_restore_expired'));
        }

        $lessonData = $snapshot['lesson'];
        unset($lessonData['id']);
        $lessonData['course_id'] = $course->getKey();

        $lesson = CourseLesson::query()->create($lessonData);

        foreach ($snapshot['files'] ?? [] as $fileData) {
            unset($fileData['id']);
            $fileData['course_id'] = $course->getKey();
            $fileData['lesson_id'] = $lesson->getKey();
            CourseLessonFile::query()->create($fileData);
        }

        $this->syncCourseStats($course);

        return $this->httpResponse()->setMessage(trans('plugins/courses::courses.lesson_restored'));
    }

    public function bulkStoreLessons(Course $course, Request $request)
    {
        $validated = $request->validate([
            'chapter_id' => [
                'required',
                'integer',
                Rule::exists('plg_course_chapters', 'id')->where('course_id', $course->getKey()),
            ],
            'videos' => ['required', 'array', 'min:1'],
            'videos.*' => ['required', 'string', 'max:191'],
        ]);

        foreach ($validated['videos'] as $videoPath) {
            $name = pathinfo(str_replace('\\', '/', $videoPath), PATHINFO_FILENAME) ?: trans('plugins/courses::courses.new_lesson');

            CourseLesson::query()->create([
                'course_id' => $course->getKey(),
                'chapter_id' => $validated['chapter_id'],
                'name' => Str::of($name)->replace(['-', '_'], ' ')->headline()->toString(),
                'description' => null,
                'content' => null,
                'material_type' => 'video',
                'video_path' => $videoPath,
                'video_status' => 'uploaded',
                'duration_minutes' => 0,
                'access_mode' => 'paid',
                'order' => $this->nextLessonOrder($course, (int) $validated['chapter_id']),
                'is_free_preview' => false,
                'requires_access' => true,
                'status' => BaseStatusEnum::DRAFT,
            ]);
        }

        $this->syncCourseStats($course);

        return $this->httpResponse()->setMessage(trans('plugins/courses::courses.bulk_lessons_created'));
    }

    public function reorder(Course $course, Request $request)
    {
        $validated = $request->validate([
            'chapters' => ['nullable', 'array'],
            'chapters.*' => [
                'integer',
                Rule::exists('plg_course_chapters', 'id')->where('course_id', $course->getKey()),
            ],
            'lesson_groups' => ['nullable', 'array'],
            'lesson_groups.*.chapter_id' => [
                'required_with:lesson_groups',
                'integer',
                Rule::exists('plg_course_chapters', 'id')->where('course_id', $course->getKey()),
            ],
            'lesson_groups.*.lessons' => ['nullable', 'array'],
            'lesson_groups.*.lessons.*' => [
                'integer',
                Rule::exists('plg_course_lessons', 'id')->where('course_id', $course->getKey()),
            ],
        ]);

        foreach ($validated['chapters'] ?? [] as $index => $chapterId) {
            CourseChapter::query()
                ->where('course_id', $course->getKey())
                ->whereKey($chapterId)
                ->update(['order' => $index + 1]);
        }

        foreach ($validated['lesson_groups'] ?? [] as $group) {
            foreach ($group['lessons'] ?? [] as $index => $lessonId) {
                CourseLesson::query()
                    ->where('course_id', $course->getKey())
                    ->whereKey($lessonId)
                    ->update([
                        'chapter_id' => $group['chapter_id'],
                        'order' => $index + 1,
                    ]);
            }
        }

        $this->syncCourseStats($course);

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/courses::courses.order_saved'));
    }

    protected function nextChapterOrder(Course $course): int
    {
        return ((int) $course->chapters()->max('order')) + 1;
    }

    protected function nextLessonOrder(Course $course, int $chapterId): int
    {
        return ((int) CourseLesson::query()
            ->where('course_id', $course->getKey())
            ->where('chapter_id', $chapterId)
            ->max('order')) + 1;
    }

    protected function syncCourseStats(Course $course): void
    {
        $lessons = $course->lessons();

        $course->forceFill([
            'lesson_count' => (clone $lessons)->count(),
            'duration_minutes' => (int) (clone $lessons)->sum('duration_minutes'),
        ])->save();
    }

    protected function lessonAccessState(string $accessMode): array
    {
        $mode = $accessMode === 'closed' ? 'paid' : $accessMode;

        return [
            'mode' => $mode,
            'is_free_preview' => $accessMode === 'free',
            'requires_access' => $accessMode !== 'free',
        ];
    }

    protected function lessonVideoStatus(?string $videoPath, ?string $videoEmbed): string
    {
        return $videoPath || $videoEmbed ? 'uploaded' : 'missing';
    }

    protected function createLessonAttachment(Course $course, CourseLesson $lesson, array $validated): void
    {
        if (empty($validated['attachment_path'])) {
            return;
        }

        CourseLessonFile::query()->create([
            'course_id' => $course->getKey(),
            'lesson_id' => $lesson->getKey(),
            'name' => $validated['attachment_name'] ?: basename(str_replace('\\', '/', $validated['attachment_path'])),
            'file_path' => $validated['attachment_path'],
            'file_size' => 0,
            'is_downloadable' => true,
            'requires_access' => true,
            'order' => ((int) $lesson->files()->max('order')) + 1,
            'status' => BaseStatusEnum::PUBLISHED,
        ]);
    }

    protected function lessonUndoCacheKey(string $token): string
    {
        return 'courses.lesson.undo.' . $token;
    }
}

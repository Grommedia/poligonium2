<?php

namespace Botble\Courses\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CourseLessonRequest extends Request
{
    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:plg_courses,id'],
            'chapter_id' => ['nullable', 'exists:plg_course_chapters,id'],
            'name' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'video_path' => ['nullable', 'string', 'max:191'],
            'video_embed' => ['nullable', 'string'],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'order' => ['nullable', 'integer'],
            'is_free_preview' => ['nullable', 'boolean'],
            'requires_access' => ['nullable', 'boolean'],
            'status' => ['required', Rule::in(BaseStatusEnum::values())],
        ];
    }
}

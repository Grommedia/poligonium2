<?php

namespace Botble\Courses\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CourseLessonFileRequest extends Request
{
    public function rules(): array
    {
        return [
            'course_id' => ['nullable', 'exists:plg_courses,id'],
            'lesson_id' => ['nullable', 'exists:plg_course_lessons,id'],
            'name' => ['required', 'string', 'max:191'],
            'file_path' => ['nullable', 'string', 'max:191'],
            'file_size' => ['nullable', 'integer', 'min:0'],
            'is_downloadable' => ['nullable', 'boolean'],
            'requires_access' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer'],
            'status' => ['required', Rule::in(BaseStatusEnum::values())],
        ];
    }
}

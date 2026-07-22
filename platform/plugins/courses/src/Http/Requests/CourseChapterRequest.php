<?php

namespace Botble\Courses\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CourseChapterRequest extends Request
{
    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:plg_courses,id'],
            'name' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'order' => ['nullable', 'integer'],
            'is_free_preview' => ['nullable', 'boolean'],
            'status' => ['required', Rule::in(BaseStatusEnum::values())],
        ];
    }
}

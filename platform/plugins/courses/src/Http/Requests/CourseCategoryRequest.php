<?php

namespace Botble\Courses\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CourseCategoryRequest extends Request
{
    public function rules(): array
    {
        return [
            'parent_id' => ['nullable', 'exists:plg_course_categories,id'],
            'name' => ['required', 'string', 'max:191'],
            'slug' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'order' => ['nullable', 'integer'],
            'status' => ['required', Rule::in(BaseStatusEnum::values())],
        ];
    }
}

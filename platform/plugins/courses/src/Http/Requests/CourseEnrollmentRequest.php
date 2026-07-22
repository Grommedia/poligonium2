<?php

namespace Botble\Courses\Http\Requests;

use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CourseEnrollmentRequest extends Request
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'course_id' => ['required', 'exists:plg_courses,id'],
            'source' => ['required', Rule::in(['manual', 'purchase', 'subscription'])],
            'status' => ['required', Rule::in(['active', 'paused', 'expired', 'cancelled'])],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'notes' => ['nullable', 'string'],
        ];
    }
}

<?php

namespace Botble\VfxShowreel\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class VfxShowreelItemRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191'],
            'type' => ['nullable', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'poster' => ['nullable', 'string', 'max:191'],
            'preview_video' => ['nullable', 'string', 'max:191'],
            'tools' => ['nullable', 'string', 'max:191'],
            'year' => ['nullable', 'string', 'max:20'],
            'url' => ['nullable', 'string', 'max:191'],
            'is_featured' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer'],
            'status' => ['required', Rule::in(BaseStatusEnum::values())],
        ];
    }
}

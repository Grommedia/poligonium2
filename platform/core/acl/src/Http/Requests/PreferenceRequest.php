<?php

namespace Botble\ACL\Http\Requests;

use Botble\Support\Http\Requests\Request;

class PreferenceRequest extends Request
{
    public function rules(): array
    {
        return [
            'locale' => ['sometimes', 'nullable', 'string'],
            'locale_direction' => ['required', 'string', 'in:ltr,rtl'],
            'theme_mode' => ['required', 'string', 'in:light,dark'],
        ];
    }
}

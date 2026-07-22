<?php

namespace Botble\MicrosoftClarity\Http\Requests;

use Botble\Support\Http\Requests\Request;

class MicrosoftClaritySettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'microsoft_clarity_enabled' => ['nullable', 'in:0,1'],
            'microsoft_clarity_project_id' => ['nullable', 'string', 'max:100', 'regex:/^[A-Za-z0-9_-]*$/'],
            'microsoft_clarity_tracking_mode' => ['nullable', 'in:project_id,custom_code'],
            'microsoft_clarity_tracking_code' => ['nullable', 'string', 'max:20000'],
            'microsoft_clarity_exclude_admin' => ['nullable', 'in:0,1'],
        ];
    }
}

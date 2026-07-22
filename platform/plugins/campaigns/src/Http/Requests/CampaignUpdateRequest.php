<?php

namespace Botble\Campaigns\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CampaignUpdateRequest extends Request
{
    public function rules(): array
    {
        return [
            'campaign_id' => ['required', 'exists:plg_campaigns,id'],
            'title' => ['required', 'string', 'max:191'],
            'content' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:191'],
            'published_at' => ['nullable', 'date'],
            'order' => ['nullable', 'integer'],
            'status' => ['required', Rule::in(BaseStatusEnum::values())],
        ];
    }
}

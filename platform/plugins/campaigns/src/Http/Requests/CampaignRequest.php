<?php

namespace Botble\Campaigns\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Campaigns\Support\CampaignOptions;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CampaignRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191'],
            'slug' => ['required', 'string', 'max:191'],
            'subtitle' => ['nullable', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:191'],
            'teaser_url' => ['nullable', 'string', 'max:191'],
            'target_amount' => ['nullable', 'numeric', 'min:0'],
            'manual_amount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'production_stage' => ['required', Rule::in(array_keys(CampaignOptions::stages()))],
            'campaign_state' => ['required', Rule::in(array_keys(CampaignOptions::states()))],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
            'is_featured' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer'],
            'status' => ['required', Rule::in(BaseStatusEnum::values())],
        ];
    }
}

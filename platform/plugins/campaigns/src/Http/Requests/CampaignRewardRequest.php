<?php

namespace Botble\Campaigns\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CampaignRewardRequest extends Request
{
    public function rules(): array
    {
        return [
            'campaign_id' => ['required', 'exists:plg_campaigns,id'],
            'title' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'quantity_limit' => ['nullable', 'integer', 'min:0'],
            'manual_backers' => ['nullable', 'integer', 'min:0'],
            'estimated_delivery' => ['nullable', 'string', 'max:191'],
            'includes' => ['nullable', 'string'],
            'is_featured' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer'],
            'status' => ['required', Rule::in(BaseStatusEnum::values())],
        ];
    }
}

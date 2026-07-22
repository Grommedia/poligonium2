<?php

namespace Botble\Campaigns\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CampaignFaqRequest extends Request
{
    public function rules(): array
    {
        return [
            'campaign_id' => ['required', 'exists:plg_campaigns,id'],
            'question' => ['required', 'string', 'max:191'],
            'answer' => ['nullable', 'string'],
            'order' => ['nullable', 'integer'],
            'status' => ['required', Rule::in(BaseStatusEnum::values())],
        ];
    }
}

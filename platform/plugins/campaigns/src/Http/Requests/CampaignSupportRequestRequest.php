<?php

namespace Botble\Campaigns\Http\Requests;

use Botble\Campaigns\Support\CampaignOptions;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CampaignSupportRequestRequest extends Request
{
    public function rules(): array
    {
        return [
            'campaign_id' => ['required', 'exists:plg_campaigns,id'],
            'reward_id' => ['nullable', 'exists:plg_campaign_rewards,id'],
            'name' => ['required', 'string', 'max:191'],
            'email' => ['nullable', 'email', 'max:191'],
            'phone' => ['nullable', 'string', 'max:80'],
            'amount' => ['required', 'numeric', 'min:1'],
            'currency' => ['required', 'string', 'max:10'],
            'message' => ['nullable', 'string'],
            'status' => ['required', Rule::in(array_keys(CampaignOptions::supportRequestStatuses()))],
        ];
    }
}

<?php

namespace Botble\Campaigns\Http\Requests;

use Botble\Campaigns\Support\CampaignOptions;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CampaignContributionRequest extends Request
{
    public function rules(): array
    {
        return [
            'campaign_id' => ['required', 'exists:plg_campaigns,id'],
            'reward_id' => ['nullable', 'exists:plg_campaign_rewards,id'],
            'donor_name' => ['nullable', 'string', 'max:191'],
            'donor_email' => ['nullable', 'email', 'max:191'],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'payment_method' => ['nullable', 'string', 'max:191'],
            'payment_reference' => ['nullable', 'string', 'max:191'],
            'contribution_status' => ['required', Rule::in(array_keys(CampaignOptions::contributionStatuses()))],
            'is_public' => ['nullable', 'boolean'],
            'message' => ['nullable', 'string'],
            'contributed_at' => ['nullable', 'date'],
        ];
    }
}

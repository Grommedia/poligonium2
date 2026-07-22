<?php

namespace Botble\Campaigns\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CampaignTeamMemberRequest extends Request
{
    public function rules(): array
    {
        return [
            'campaign_id' => ['required', 'exists:plg_campaigns,id'],
            'name' => ['required', 'string', 'max:191'],
            'role' => ['nullable', 'string', 'max:191'],
            'bio' => ['nullable', 'string'],
            'avatar' => ['nullable', 'string', 'max:191'],
            'url' => ['nullable', 'string', 'max:191'],
            'order' => ['nullable', 'integer'],
            'status' => ['required', Rule::in(BaseStatusEnum::values())],
        ];
    }
}

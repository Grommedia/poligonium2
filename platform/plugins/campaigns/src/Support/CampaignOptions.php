<?php

namespace Botble\Campaigns\Support;

class CampaignOptions
{
    public static function states(): array
    {
        return [
            'active' => trans('plugins/campaigns::campaigns.state_active'),
            'planned' => trans('plugins/campaigns::campaigns.state_planned'),
            'completed' => trans('plugins/campaigns::campaigns.state_completed'),
            'paused' => trans('plugins/campaigns::campaigns.state_paused'),
        ];
    }

    public static function stages(): array
    {
        return [
            'concept' => trans('plugins/campaigns::campaigns.stage_concept'),
            'pre_production' => trans('plugins/campaigns::campaigns.stage_pre_production'),
            'production' => trans('plugins/campaigns::campaigns.stage_production'),
            'animation' => trans('plugins/campaigns::campaigns.stage_animation'),
            'rendering' => trans('plugins/campaigns::campaigns.stage_rendering'),
            'post_production' => trans('plugins/campaigns::campaigns.stage_post_production'),
            'released' => trans('plugins/campaigns::campaigns.stage_released'),
        ];
    }

    public static function contributionStatuses(): array
    {
        return [
            'pending' => trans('plugins/campaigns::campaigns.contribution_pending'),
            'confirmed' => trans('plugins/campaigns::campaigns.contribution_confirmed'),
            'cancelled' => trans('plugins/campaigns::campaigns.contribution_cancelled'),
            'refunded' => trans('plugins/campaigns::campaigns.contribution_refunded'),
        ];
    }

    public static function supportRequestStatuses(): array
    {
        return [
            'new' => trans('plugins/campaigns::campaigns.support_request_new'),
            'contacted' => trans('plugins/campaigns::campaigns.support_request_contacted'),
            'converted' => trans('plugins/campaigns::campaigns.support_request_converted'),
            'cancelled' => trans('plugins/campaigns::campaigns.support_request_cancelled'),
        ];
    }
}

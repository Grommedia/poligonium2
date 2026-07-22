<?php

namespace Botble\Campaigns\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignSupportRequest extends BaseModel
{
    protected $table = 'plg_campaign_support_requests';

    protected $fillable = [
        'campaign_id',
        'reward_id',
        'name',
        'email',
        'phone',
        'amount',
        'currency',
        'message',
        'status',
    ];

    protected $casts = [
        'name' => SafeContent::class,
        'message' => SafeContent::class,
        'amount' => 'float',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id')->withDefault();
    }

    public function reward(): BelongsTo
    {
        return $this->belongsTo(CampaignReward::class, 'reward_id')->withDefault();
    }
}

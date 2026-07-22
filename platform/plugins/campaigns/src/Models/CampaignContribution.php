<?php

namespace Botble\Campaigns\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignContribution extends BaseModel
{
    protected $table = 'plg_campaign_contributions';

    protected $fillable = [
        'campaign_id',
        'reward_id',
        'donor_name',
        'donor_email',
        'amount',
        'currency',
        'payment_method',
        'payment_reference',
        'contribution_status',
        'is_public',
        'message',
        'contributed_at',
    ];

    protected $casts = [
        'donor_name' => SafeContent::class,
        'message' => SafeContent::class,
        'amount' => 'float',
        'is_public' => 'boolean',
        'contributed_at' => 'datetime',
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

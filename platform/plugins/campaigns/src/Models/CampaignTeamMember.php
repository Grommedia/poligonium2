<?php

namespace Botble\Campaigns\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignTeamMember extends BaseModel
{
    protected $table = 'plg_campaign_team_members';

    protected $fillable = [
        'campaign_id',
        'name',
        'role',
        'bio',
        'avatar',
        'url',
        'order',
        'status',
    ];

    protected $casts = [
        'name' => SafeContent::class,
        'role' => SafeContent::class,
        'bio' => SafeContent::class,
        'status' => BaseStatusEnum::class,
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id')->withDefault();
    }
}

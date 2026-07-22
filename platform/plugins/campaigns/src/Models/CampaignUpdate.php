<?php

namespace Botble\Campaigns\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignUpdate extends BaseModel
{
    protected $table = 'plg_campaign_updates';

    protected $fillable = [
        'campaign_id',
        'title',
        'content',
        'image',
        'published_at',
        'order',
        'status',
    ];

    protected $casts = [
        'title' => SafeContent::class,
        'content' => SafeContent::class,
        'published_at' => 'datetime',
        'status' => BaseStatusEnum::class,
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id')->withDefault();
    }
}

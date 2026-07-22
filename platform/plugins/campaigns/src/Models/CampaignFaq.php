<?php

namespace Botble\Campaigns\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignFaq extends BaseModel
{
    protected $table = 'plg_campaign_faqs';

    protected $fillable = [
        'campaign_id',
        'question',
        'answer',
        'order',
        'status',
    ];

    protected $casts = [
        'question' => SafeContent::class,
        'answer' => SafeContent::class,
        'status' => BaseStatusEnum::class,
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id')->withDefault();
    }
}

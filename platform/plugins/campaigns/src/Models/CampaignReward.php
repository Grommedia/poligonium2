<?php

namespace Botble\Campaigns\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampaignReward extends BaseModel
{
    protected $table = 'plg_campaign_rewards';

    protected $fillable = [
        'campaign_id',
        'title',
        'description',
        'amount',
        'currency',
        'quantity_limit',
        'manual_backers',
        'estimated_delivery',
        'includes',
        'is_featured',
        'order',
        'status',
    ];

    protected $casts = [
        'title' => SafeContent::class,
        'description' => SafeContent::class,
        'amount' => 'float',
        'manual_backers' => 'integer',
        'is_featured' => 'boolean',
        'status' => BaseStatusEnum::class,
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id')->withDefault();
    }

    public function contributions(): HasMany
    {
        return $this->hasMany(CampaignContribution::class, 'reward_id');
    }

    public function supportRequests(): HasMany
    {
        return $this->hasMany(CampaignSupportRequest::class, 'reward_id');
    }

    public function getBackersCountAttribute(): int
    {
        $confirmedContributions = $this->relationLoaded('contributions')
            ? $this->contributions->where('contribution_status', 'confirmed')->count()
            : $this->contributions()->where('contribution_status', 'confirmed')->count();

        $requests = $this->relationLoaded('supportRequests')
            ? $this->supportRequests->whereIn('status', ['new', 'contacted', 'confirmed'])->count()
            : $this->supportRequests()->whereIn('status', ['new', 'contacted', 'confirmed'])->count();

        return (int) $this->manual_backers + $confirmedContributions + $requests;
    }

    public function getIncludesListAttribute(): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $this->includes))
            ->map(fn (string $item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}

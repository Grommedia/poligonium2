<?php

namespace Botble\Campaigns\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Campaigns\Support\CampaignOptions;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends BaseModel
{
    protected $table = 'plg_campaigns';

    protected $fillable = [
        'name',
        'slug',
        'subtitle',
        'description',
        'content',
        'image',
        'teaser_url',
        'target_amount',
        'manual_amount',
        'currency',
        'production_stage',
        'campaign_state',
        'starts_at',
        'ends_at',
        'is_featured',
        'order',
        'status',
    ];

    protected $casts = [
        'name' => SafeContent::class,
        'subtitle' => SafeContent::class,
        'description' => SafeContent::class,
        'content' => SafeContent::class,
        'target_amount' => 'float',
        'manual_amount' => 'float',
        'starts_at' => 'date',
        'ends_at' => 'date',
        'is_featured' => 'boolean',
        'status' => BaseStatusEnum::class,
    ];

    public function rewards(): HasMany
    {
        return $this->hasMany(CampaignReward::class, 'campaign_id')->orderBy('order');
    }

    public function contributions(): HasMany
    {
        return $this->hasMany(CampaignContribution::class, 'campaign_id');
    }

    public function supportRequests(): HasMany
    {
        return $this->hasMany(CampaignSupportRequest::class, 'campaign_id');
    }

    public function confirmedContributions(): HasMany
    {
        return $this->contributions()->where('contribution_status', 'confirmed');
    }

    public function updates(): HasMany
    {
        return $this->hasMany(CampaignUpdate::class, 'campaign_id')->orderByDesc('published_at')->orderByDesc('created_at');
    }

    public function teamMembers(): HasMany
    {
        return $this->hasMany(CampaignTeamMember::class, 'campaign_id')->orderBy('order');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(CampaignFaq::class, 'campaign_id')->orderBy('order');
    }

    public function getCollectedAmountAttribute(): float
    {
        $confirmed = $this->relationLoaded('confirmedContributions')
            ? $this->confirmedContributions->sum('amount')
            : $this->confirmedContributions()->sum('amount');

        return (float) $this->manual_amount + (float) $confirmed;
    }

    public function getProgressPercentAttribute(): int
    {
        if ((float) $this->target_amount <= 0) {
            return 0;
        }

        return min(100, (int) round($this->collected_amount / (float) $this->target_amount * 100));
    }

    public function getStateLabelAttribute(): string
    {
        return CampaignOptions::states()[$this->campaign_state] ?? (string) $this->campaign_state;
    }

    public function getStageLabelAttribute(): string
    {
        return CampaignOptions::stages()[$this->production_stage] ?? (string) $this->production_stage;
    }
}

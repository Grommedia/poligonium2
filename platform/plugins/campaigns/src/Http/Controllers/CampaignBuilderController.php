<?php

namespace Botble\Campaigns\Http\Controllers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Campaigns\Models\Campaign;
use Botble\Campaigns\Models\CampaignFaq;
use Botble\Campaigns\Models\CampaignReward;
use Botble\Campaigns\Models\CampaignTeamMember;
use Botble\Campaigns\Models\CampaignUpdate;
use Illuminate\Http\Request;

class CampaignBuilderController extends BaseController
{
    public function storeReward(Campaign $campaign, Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'includes' => ['nullable', 'string'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $reward = CampaignReward::query()->create([
            'campaign_id' => $campaign->getKey(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'amount' => $validated['amount'] ?? 0,
            'currency' => $campaign->currency ?: 'UAH',
            'includes' => $validated['includes'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'order' => $this->nextOrder($campaign, 'rewards'),
            'status' => BaseStatusEnum::PUBLISHED,
        ]);

        return $this
            ->httpResponse()
            ->setData(['id' => $reward->getKey()])
            ->setMessage(trans('plugins/campaigns::campaigns.reward_created'));
    }

    public function storeUpdate(Campaign $campaign, Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:191'],
            'content' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
        ]);

        $update = CampaignUpdate::query()->create([
            'campaign_id' => $campaign->getKey(),
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'published_at' => $validated['published_at'] ?? now(),
            'status' => BaseStatusEnum::PUBLISHED,
        ]);

        return $this
            ->httpResponse()
            ->setData(['id' => $update->getKey()])
            ->setMessage(trans('plugins/campaigns::campaigns.update_created'));
    }

    public function storeTeamMember(Campaign $campaign, Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'role' => ['nullable', 'string', 'max:191'],
            'bio' => ['nullable', 'string'],
        ]);

        $member = CampaignTeamMember::query()->create([
            'campaign_id' => $campaign->getKey(),
            'name' => $validated['name'],
            'role' => $validated['role'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'order' => $this->nextOrder($campaign, 'teamMembers'),
            'status' => BaseStatusEnum::PUBLISHED,
        ]);

        return $this
            ->httpResponse()
            ->setData(['id' => $member->getKey()])
            ->setMessage(trans('plugins/campaigns::campaigns.team_member_created'));
    }

    public function storeFaq(Campaign $campaign, Request $request)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:191'],
            'answer' => ['required', 'string'],
        ]);

        $faq = CampaignFaq::query()->create([
            'campaign_id' => $campaign->getKey(),
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'order' => $this->nextOrder($campaign, 'faqs'),
            'status' => BaseStatusEnum::PUBLISHED,
        ]);

        return $this
            ->httpResponse()
            ->setData(['id' => $faq->getKey()])
            ->setMessage(trans('plugins/campaigns::campaigns.faq_created'));
    }

    protected function nextOrder(Campaign $campaign, string $relation): int
    {
        return ((int) $campaign->{$relation}()->max('order')) + 1;
    }
}

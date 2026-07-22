<?php

namespace Botble\Campaigns\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Campaigns\Models\Campaign;
use Botble\Campaigns\Models\CampaignReward;
use Botble\Campaigns\Models\CampaignSupportRequest;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\AdminBar;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PublicController extends BaseController
{
    public function index(): Response
    {
        SeoHelper::setTitle('Підтримати українську анімацію');

        $campaigns = Campaign::query()
            ->with('confirmedContributions')
            ->wherePublished()
            ->orderByDesc('is_featured')
            ->orderBy('order')
            ->latest()
            ->paginate(9);

        return Theme::scope('campaigns.index', compact('campaigns'))->render();
    }

    public function show(Campaign $campaign): Response
    {
        abort_unless($campaign->status->getValue() === 'published' || Auth::guard()->check(), 404);

        $campaign->load([
            'confirmedContributions',
            'rewards' => fn ($query) => $query->wherePublished()->orderBy('order'),
            'updates' => fn ($query) => $query->wherePublished()->orderByDesc('published_at')->orderByDesc('created_at'),
            'teamMembers' => fn ($query) => $query->wherePublished()->orderBy('order'),
            'faqs' => fn ($query) => $query->wherePublished()->orderBy('order'),
        ]);

        SeoHelper::setTitle($campaign->name)->setDescription($campaign->description);

        if (function_exists('admin_bar')) {
            AdminBar::registerLink(
                trans('plugins/campaigns::campaigns.edit_this_campaign'),
                route('campaigns.campaigns.edit', $campaign->id),
                null,
                'campaigns.campaigns.edit'
            );
        }

        return Theme::scope('campaigns.show', compact('campaign'))->render();
    }

    public function support(Campaign $campaign, Request $request): RedirectResponse
    {
        abort_unless($campaign->status->getValue() === 'published', 404);

        $validated = $request->validate([
            'reward_id' => [
                'nullable',
                'integer',
                Rule::exists('plg_campaign_rewards', 'id')->where('campaign_id', $campaign->getKey()),
            ],
            'name' => ['required', 'string', 'max:191'],
            'email' => ['nullable', 'email', 'max:191'],
            'phone' => ['nullable', 'string', 'max:80'],
            'amount' => ['nullable', 'numeric', 'min:1'],
            'message' => ['nullable', 'string'],
        ]);

        $reward = ! empty($validated['reward_id'])
            ? CampaignReward::query()
                ->where('campaign_id', $campaign->getKey())
                ->whereKey($validated['reward_id'])
                ->first()
            : null;

        $amount = $reward ? (float) $reward->amount : (float) ($validated['amount'] ?? 0);

        if ($amount <= 0) {
            return redirect()
                ->to(route('campaigns.public.show', $campaign) . '#support-form')
                ->withErrors(['amount' => 'Оберіть пакет підтримки або вкажіть суму.'])
                ->withInput();
        }

        CampaignSupportRequest::query()->create([
            'campaign_id' => $campaign->getKey(),
            'reward_id' => $reward?->getKey(),
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'amount' => $amount,
            'currency' => $reward?->currency ?: ($campaign->currency ?: 'UAH'),
            'message' => $validated['message'] ?? null,
            'status' => 'new',
        ]);

        return redirect()
            ->to(route('campaigns.public.show', $campaign) . '#support-form')
            ->with('campaign_support_success', 'Дякую! Заявку отримано. Я звʼяжусь з вами та узгоджу зручний спосіб підтримки.');
    }
}

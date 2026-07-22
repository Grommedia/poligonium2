<?php

namespace Botble\Campaigns\Forms;

use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Campaigns\Http\Requests\CampaignContributionRequest;
use Botble\Campaigns\Models\Campaign;
use Botble\Campaigns\Models\CampaignContribution;
use Botble\Campaigns\Models\CampaignReward;
use Botble\Campaigns\Support\CampaignOptions;

class CampaignContributionForm extends FormAbstract
{
    public function setup(): void
    {
        $campaigns = Campaign::query()->orderBy('name')->pluck('name', 'id')->all();
        $rewards = CampaignReward::query()
            ->with('campaign')
            ->orderBy('campaign_id')
            ->orderBy('order')
            ->get()
            ->mapWithKeys(fn (CampaignReward $reward) => [$reward->id => $reward->campaign->name . ' - ' . $reward->title])
            ->all();

        $this
            ->model(CampaignContribution::class)
            ->setValidatorClass(CampaignContributionRequest::class)
            ->add('campaign_id', SelectField::class, [
                'label' => trans('plugins/campaigns::campaigns.campaign'),
                'choices' => $campaigns,
                'required' => true,
            ])
            ->add('reward_id', SelectField::class, [
                'label' => trans('plugins/campaigns::campaigns.reward'),
                'choices' => ['' => trans('plugins/campaigns::campaigns.no_reward')] + $rewards,
            ])
            ->add('donor_name', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.donor_name'),
            ])
            ->add('donor_email', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.donor_email'),
            ])
            ->add('amount', NumberField::class, [
                'label' => trans('plugins/campaigns::campaigns.amount'),
                'default_value' => 0,
            ])
            ->add('currency', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.currency'),
                'default_value' => 'UAH',
            ])
            ->add('payment_method', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.payment_method'),
                'attr' => ['placeholder' => 'Mono / LiqPay / Manual'],
            ])
            ->add('payment_reference', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.payment_reference'),
            ])
            ->add('contribution_status', SelectField::class, [
                'label' => trans('plugins/campaigns::campaigns.contribution_status'),
                'choices' => CampaignOptions::contributionStatuses(),
            ])
            ->add('is_public', OnOffField::class, [
                'label' => trans('plugins/campaigns::campaigns.public_supporter'),
                'default_value' => true,
            ])
            ->add('message', TextareaField::class, [
                'label' => trans('plugins/campaigns::campaigns.message'),
                'attr' => ['rows' => 4],
            ])
            ->add('contributed_at', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.contributed_at'),
                'attr' => ['placeholder' => '2026-06-13 12:00:00'],
            ])
            ->setBreakFieldPoint('contribution_status');
    }
}

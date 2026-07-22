<?php

namespace Botble\Campaigns\Forms;

use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Campaigns\Http\Requests\CampaignSupportRequestRequest;
use Botble\Campaigns\Models\Campaign;
use Botble\Campaigns\Models\CampaignReward;
use Botble\Campaigns\Models\CampaignSupportRequest;
use Botble\Campaigns\Support\CampaignOptions;

class CampaignSupportRequestForm extends FormAbstract
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
            ->model(CampaignSupportRequest::class)
            ->setValidatorClass(CampaignSupportRequestRequest::class)
            ->add('campaign_id', SelectField::class, [
                'label' => trans('plugins/campaigns::campaigns.campaign'),
                'choices' => $campaigns,
                'required' => true,
            ])
            ->add('reward_id', SelectField::class, [
                'label' => trans('plugins/campaigns::campaigns.reward'),
                'choices' => ['' => trans('plugins/campaigns::campaigns.no_reward')] + $rewards,
            ])
            ->add('name', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.supporter_name'),
                'required' => true,
            ])
            ->add('email', TextField::class, [
                'label' => 'Email',
            ])
            ->add('phone', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.phone'),
            ])
            ->add('amount', NumberField::class, [
                'label' => trans('plugins/campaigns::campaigns.amount'),
                'default_value' => 1000,
            ])
            ->add('currency', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.currency'),
                'default_value' => 'UAH',
            ])
            ->add('message', TextareaField::class, [
                'label' => trans('plugins/campaigns::campaigns.message'),
                'attr' => ['rows' => 4],
            ])
            ->add('status', SelectField::class, [
                'label' => trans('plugins/campaigns::campaigns.support_request_status'),
                'choices' => CampaignOptions::supportRequestStatuses(),
            ])
            ->setBreakFieldPoint('status');
    }
}

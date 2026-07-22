<?php

namespace Botble\Campaigns\Forms;

use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Campaigns\Http\Requests\CampaignRewardRequest;
use Botble\Campaigns\Models\Campaign;
use Botble\Campaigns\Models\CampaignReward;

class CampaignRewardForm extends FormAbstract
{
    public function setup(): void
    {
        $campaigns = Campaign::query()->orderBy('name')->pluck('name', 'id')->all();

        $this
            ->model(CampaignReward::class)
            ->setValidatorClass(CampaignRewardRequest::class)
            ->add('campaign_id', SelectField::class, [
                'label' => trans('plugins/campaigns::campaigns.campaign'),
                'choices' => $campaigns,
                'required' => true,
            ])
            ->add('title', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.reward_title'),
                'required' => true,
            ])
            ->add('amount', NumberField::class, [
                'label' => trans('plugins/campaigns::campaigns.amount'),
                'default_value' => 0,
            ])
            ->add('currency', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.currency'),
                'default_value' => 'UAH',
            ])
            ->add('description', TextareaField::class, [
                'label' => trans('plugins/campaigns::campaigns.description'),
                'attr' => ['rows' => 4],
            ])
            ->add('includes', TextareaField::class, [
                'label' => trans('plugins/campaigns::campaigns.includes'),
                'attr' => ['rows' => 5, 'placeholder' => trans('plugins/campaigns::campaigns.includes_placeholder')],
            ])
            ->add('quantity_limit', NumberField::class, [
                'label' => trans('plugins/campaigns::campaigns.quantity_limit'),
            ])
            ->add('manual_backers', NumberField::class, [
                'label' => 'Бэкеров вручную',
                'default_value' => 0,
                'help_block' => [
                    'text' => 'Стартовое количество участников для отображения на сайте. Реальные заявки будут добавляться сверху.',
                    'tag' => 'small',
                    'attr' => ['class' => 'form-hint'],
                ],
            ])
            ->add('estimated_delivery', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.estimated_delivery'),
                'attr' => ['placeholder' => '2027'],
            ])
            ->add('is_featured', OnOffField::class, [
                'label' => trans('plugins/campaigns::campaigns.featured'),
            ])
            ->add('order', NumberField::class, SortOrderFieldOption::make())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}

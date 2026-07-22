<?php

namespace Botble\Campaigns\Forms;

use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Campaigns\Http\Requests\CampaignFaqRequest;
use Botble\Campaigns\Models\Campaign;
use Botble\Campaigns\Models\CampaignFaq;

class CampaignFaqForm extends FormAbstract
{
    public function setup(): void
    {
        $campaigns = Campaign::query()->orderBy('name')->pluck('name', 'id')->all();

        $this
            ->model(CampaignFaq::class)
            ->setValidatorClass(CampaignFaqRequest::class)
            ->add('campaign_id', SelectField::class, [
                'label' => trans('plugins/campaigns::campaigns.campaign'),
                'choices' => $campaigns,
                'required' => true,
            ])
            ->add('question', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.question'),
                'required' => true,
            ])
            ->add('answer', TextareaField::class, [
                'label' => trans('plugins/campaigns::campaigns.answer'),
                'attr' => ['rows' => 5],
            ])
            ->add('order', NumberField::class, SortOrderFieldOption::make())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}

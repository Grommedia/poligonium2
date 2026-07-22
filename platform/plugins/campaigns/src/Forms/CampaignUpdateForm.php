<?php

namespace Botble\Campaigns\Forms;

use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Campaigns\Http\Requests\CampaignUpdateRequest;
use Botble\Campaigns\Models\Campaign;
use Botble\Campaigns\Models\CampaignUpdate;

class CampaignUpdateForm extends FormAbstract
{
    public function setup(): void
    {
        $campaigns = Campaign::query()->orderBy('name')->pluck('name', 'id')->all();

        $this
            ->model(CampaignUpdate::class)
            ->setValidatorClass(CampaignUpdateRequest::class)
            ->add('campaign_id', SelectField::class, [
                'label' => trans('plugins/campaigns::campaigns.campaign'),
                'choices' => $campaigns,
                'required' => true,
            ])
            ->add('title', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.title'),
                'required' => true,
            ])
            ->add('content', EditorField::class, [
                'label' => trans('plugins/campaigns::campaigns.content'),
            ])
            ->add('image', 'mediaImage', [
                'label' => trans('plugins/campaigns::campaigns.image'),
            ])
            ->add('published_at', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.published_at'),
                'attr' => ['placeholder' => '2026-06-13 12:00:00'],
            ])
            ->add('order', NumberField::class, SortOrderFieldOption::make())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}

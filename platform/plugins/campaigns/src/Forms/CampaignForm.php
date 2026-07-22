<?php

namespace Botble\Campaigns\Forms;

use Botble\Base\Forms\FieldOptions\ContentFieldOption;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Campaigns\Http\Requests\CampaignRequest;
use Botble\Campaigns\Models\Campaign;
use Botble\Campaigns\Support\CampaignOptions;

class CampaignForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Campaign::class)
            ->setValidatorClass(CampaignRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add('slug', TextField::class, [
                'label' => 'Slug',
                'attr' => ['placeholder' => 'animated-short-film-name'],
            ])
            ->add('subtitle', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.subtitle'),
            ])
            ->add('description', TextareaField::class, DescriptionFieldOption::make())
            ->add('content', EditorField::class, ContentFieldOption::make()->allowedShortcodes())
            ->add('image', 'mediaImage', [
                'label' => trans('plugins/campaigns::campaigns.cover_image'),
            ])
            ->add('teaser_url', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.teaser_url'),
                'attr' => ['placeholder' => 'https://youtube.com/...'],
            ])
            ->add('target_amount', NumberField::class, [
                'label' => trans('plugins/campaigns::campaigns.target_amount'),
                'default_value' => 0,
            ])
            ->add('manual_amount', NumberField::class, [
                'label' => trans('plugins/campaigns::campaigns.manual_amount'),
                'default_value' => 0,
            ])
            ->add('currency', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.currency'),
                'default_value' => 'UAH',
            ])
            ->add('production_stage', SelectField::class, [
                'label' => trans('plugins/campaigns::campaigns.production_stage'),
                'choices' => CampaignOptions::stages(),
            ])
            ->add('campaign_state', SelectField::class, [
                'label' => trans('plugins/campaigns::campaigns.campaign_state'),
                'choices' => CampaignOptions::states(),
            ])
            ->add('starts_at', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.starts_at'),
                'attr' => ['placeholder' => '2026-06-13'],
            ])
            ->add('ends_at', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.ends_at'),
                'attr' => ['placeholder' => '2026-12-31'],
            ])
            ->add('is_featured', OnOffField::class, [
                'label' => trans('plugins/campaigns::campaigns.featured'),
            ])
            ->add('order', NumberField::class, SortOrderFieldOption::make())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');

        $campaign = $this->getModel();

        if ($campaign instanceof Campaign && $campaign->getKey()) {
            $campaign->loadMissing(['rewards', 'updates', 'teamMembers', 'faqs']);

            $this->addMetaBoxes([
                'campaign_project_builder' => [
                    'attributes' => [
                        'id' => 'campaign-project-builder-box',
                    ],
                    'id' => 'campaign_project_builder',
                    'title' => trans('plugins/campaigns::campaigns.project_builder'),
                    'subtitle' => trans('plugins/campaigns::campaigns.project_builder_subtitle'),
                    'content' => view('plugins/campaigns::campaigns.builder', [
                        'campaign' => $campaign,
                    ])->render(),
                    'priority' => 10,
                ],
            ]);
        }
    }
}

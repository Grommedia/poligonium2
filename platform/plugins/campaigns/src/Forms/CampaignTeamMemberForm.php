<?php

namespace Botble\Campaigns\Forms;

use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Campaigns\Http\Requests\CampaignTeamMemberRequest;
use Botble\Campaigns\Models\Campaign;
use Botble\Campaigns\Models\CampaignTeamMember;

class CampaignTeamMemberForm extends FormAbstract
{
    public function setup(): void
    {
        $campaigns = Campaign::query()->orderBy('name')->pluck('name', 'id')->all();

        $this
            ->model(CampaignTeamMember::class)
            ->setValidatorClass(CampaignTeamMemberRequest::class)
            ->add('campaign_id', SelectField::class, [
                'label' => trans('plugins/campaigns::campaigns.campaign'),
                'choices' => $campaigns,
                'required' => true,
            ])
            ->add('name', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.member_name'),
                'required' => true,
            ])
            ->add('role', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.role'),
            ])
            ->add('bio', TextareaField::class, [
                'label' => trans('plugins/campaigns::campaigns.bio'),
                'attr' => ['rows' => 4],
            ])
            ->add('avatar', 'mediaImage', [
                'label' => trans('plugins/campaigns::campaigns.avatar'),
            ])
            ->add('url', TextField::class, [
                'label' => trans('plugins/campaigns::campaigns.url'),
            ])
            ->add('order', NumberField::class, SortOrderFieldOption::make())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}

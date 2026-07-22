<?php

namespace Botble\Campaigns\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Campaigns\Forms\CampaignRewardForm;
use Botble\Campaigns\Http\Requests\CampaignRewardRequest;
use Botble\Campaigns\Models\CampaignReward;
use Botble\Campaigns\Tables\CampaignRewardTable;

class CampaignRewardController extends BaseController
{
    public function index(CampaignRewardTable $table)
    {
        $this->pageTitle(trans('plugins/campaigns::campaigns.rewards'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('core/base::forms.create'));

        return CampaignRewardForm::create()->renderForm();
    }

    public function store(CampaignRewardRequest $request)
    {
        $form = CampaignRewardForm::create()->setRequest($request);
        $form->save();

        return $this->httpResponse()
            ->setPreviousRoute('campaigns.rewards.index')
            ->setNextRoute('campaigns.rewards.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(CampaignReward $reward)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $reward->title]));

        return CampaignRewardForm::createFromModel($reward)->renderForm();
    }

    public function update(CampaignReward $reward, CampaignRewardRequest $request)
    {
        CampaignRewardForm::createFromModel($reward)->setRequest($request)->save();

        return $this->httpResponse()
            ->setPreviousRoute('campaigns.rewards.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CampaignReward $reward)
    {
        return DeleteResourceAction::make($reward);
    }
}

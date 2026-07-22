<?php

namespace Botble\Campaigns\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Campaigns\Forms\CampaignContributionForm;
use Botble\Campaigns\Http\Requests\CampaignContributionRequest;
use Botble\Campaigns\Models\CampaignContribution;
use Botble\Campaigns\Tables\CampaignContributionTable;

class CampaignContributionController extends BaseController
{
    public function index(CampaignContributionTable $table)
    {
        $this->pageTitle(trans('plugins/campaigns::campaigns.contributions'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('core/base::forms.create'));

        return CampaignContributionForm::create()->renderForm();
    }

    public function store(CampaignContributionRequest $request)
    {
        $form = CampaignContributionForm::create()->setRequest($request);
        $form->save();

        return $this->httpResponse()
            ->setPreviousRoute('campaigns.contributions.index')
            ->setNextRoute('campaigns.contributions.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(CampaignContribution $contribution)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $contribution->donor_name ?: $contribution->id]));

        return CampaignContributionForm::createFromModel($contribution)->renderForm();
    }

    public function update(CampaignContribution $contribution, CampaignContributionRequest $request)
    {
        CampaignContributionForm::createFromModel($contribution)->setRequest($request)->save();

        return $this->httpResponse()
            ->setPreviousRoute('campaigns.contributions.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CampaignContribution $contribution)
    {
        return DeleteResourceAction::make($contribution);
    }
}

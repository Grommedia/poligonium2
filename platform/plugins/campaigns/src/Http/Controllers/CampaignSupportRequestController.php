<?php

namespace Botble\Campaigns\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Campaigns\Forms\CampaignSupportRequestForm;
use Botble\Campaigns\Http\Requests\CampaignSupportRequestRequest;
use Botble\Campaigns\Models\CampaignSupportRequest;
use Botble\Campaigns\Tables\CampaignSupportRequestTable;

class CampaignSupportRequestController extends BaseController
{
    public function index(CampaignSupportRequestTable $table)
    {
        $this->pageTitle(trans('plugins/campaigns::campaigns.support_requests'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('core/base::forms.create'));

        return CampaignSupportRequestForm::create()->renderForm();
    }

    public function store(CampaignSupportRequestRequest $request)
    {
        $form = CampaignSupportRequestForm::create()->setRequest($request);
        $form->save();

        return $this->httpResponse()
            ->setPreviousRoute('campaigns.support-requests.index')
            ->setNextRoute('campaigns.support-requests.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(CampaignSupportRequest $supportRequest)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $supportRequest->name]));

        return CampaignSupportRequestForm::createFromModel($supportRequest)->renderForm();
    }

    public function update(CampaignSupportRequest $supportRequest, CampaignSupportRequestRequest $request)
    {
        CampaignSupportRequestForm::createFromModel($supportRequest)->setRequest($request)->save();

        return $this->httpResponse()
            ->setPreviousRoute('campaigns.support-requests.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CampaignSupportRequest $supportRequest)
    {
        return DeleteResourceAction::make($supportRequest);
    }
}

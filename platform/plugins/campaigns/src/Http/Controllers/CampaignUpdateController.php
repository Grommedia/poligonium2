<?php

namespace Botble\Campaigns\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Campaigns\Forms\CampaignUpdateForm;
use Botble\Campaigns\Http\Requests\CampaignUpdateRequest;
use Botble\Campaigns\Models\CampaignUpdate;
use Botble\Campaigns\Tables\CampaignUpdateTable;

class CampaignUpdateController extends BaseController
{
    public function index(CampaignUpdateTable $table)
    {
        $this->pageTitle(trans('plugins/campaigns::campaigns.updates'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('core/base::forms.create'));

        return CampaignUpdateForm::create()->renderForm();
    }

    public function store(CampaignUpdateRequest $request)
    {
        $form = CampaignUpdateForm::create()->setRequest($request);
        $form->save();

        return $this->httpResponse()
            ->setPreviousRoute('campaigns.updates.index')
            ->setNextRoute('campaigns.updates.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(CampaignUpdate $update)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $update->title]));

        return CampaignUpdateForm::createFromModel($update)->renderForm();
    }

    public function update(CampaignUpdate $update, CampaignUpdateRequest $request)
    {
        CampaignUpdateForm::createFromModel($update)->setRequest($request)->save();

        return $this->httpResponse()
            ->setPreviousRoute('campaigns.updates.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CampaignUpdate $update)
    {
        return DeleteResourceAction::make($update);
    }
}

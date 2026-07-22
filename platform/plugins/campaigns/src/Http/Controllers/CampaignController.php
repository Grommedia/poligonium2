<?php

namespace Botble\Campaigns\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\Campaigns\Forms\CampaignForm;
use Botble\Campaigns\Http\Requests\CampaignRequest;
use Botble\Campaigns\Models\Campaign;
use Botble\Campaigns\Tables\CampaignTable;

class CampaignController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/campaigns::campaigns.name'))
            ->add(trans('plugins/campaigns::campaigns.campaigns'), route('campaigns.campaigns.index'));
    }

    public function index(CampaignTable $table)
    {
        $this->pageTitle(trans('plugins/campaigns::campaigns.campaigns'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('core/base::forms.create'));

        return CampaignForm::create()->renderForm();
    }

    public function store(CampaignRequest $request)
    {
        $form = CampaignForm::create()->setRequest($request);
        $form->save();

        return $this->httpResponse()
            ->setPreviousRoute('campaigns.campaigns.index')
            ->setNextRoute('campaigns.campaigns.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(Campaign $campaign)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $campaign->name]));

        return CampaignForm::createFromModel($campaign)->renderForm();
    }

    public function update(Campaign $campaign, CampaignRequest $request)
    {
        CampaignForm::createFromModel($campaign)->setRequest($request)->save();

        return $this->httpResponse()
            ->setPreviousRoute('campaigns.campaigns.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Campaign $campaign)
    {
        return DeleteResourceAction::make($campaign);
    }
}

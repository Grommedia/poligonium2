<?php

namespace Botble\Campaigns\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Campaigns\Forms\CampaignFaqForm;
use Botble\Campaigns\Http\Requests\CampaignFaqRequest;
use Botble\Campaigns\Models\CampaignFaq;
use Botble\Campaigns\Tables\CampaignFaqTable;

class CampaignFaqController extends BaseController
{
    public function index(CampaignFaqTable $table)
    {
        $this->pageTitle(trans('plugins/campaigns::campaigns.faqs'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('core/base::forms.create'));

        return CampaignFaqForm::create()->renderForm();
    }

    public function store(CampaignFaqRequest $request)
    {
        $form = CampaignFaqForm::create()->setRequest($request);
        $form->save();

        return $this->httpResponse()
            ->setPreviousRoute('campaigns.faqs.index')
            ->setNextRoute('campaigns.faqs.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(CampaignFaq $faq)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $faq->question]));

        return CampaignFaqForm::createFromModel($faq)->renderForm();
    }

    public function update(CampaignFaq $faq, CampaignFaqRequest $request)
    {
        CampaignFaqForm::createFromModel($faq)->setRequest($request)->save();

        return $this->httpResponse()
            ->setPreviousRoute('campaigns.faqs.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CampaignFaq $faq)
    {
        return DeleteResourceAction::make($faq);
    }
}

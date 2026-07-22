<?php

namespace Botble\Campaigns\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Campaigns\Forms\CampaignTeamMemberForm;
use Botble\Campaigns\Http\Requests\CampaignTeamMemberRequest;
use Botble\Campaigns\Models\CampaignTeamMember;
use Botble\Campaigns\Tables\CampaignTeamMemberTable;

class CampaignTeamMemberController extends BaseController
{
    public function index(CampaignTeamMemberTable $table)
    {
        $this->pageTitle(trans('plugins/campaigns::campaigns.team'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('core/base::forms.create'));

        return CampaignTeamMemberForm::create()->renderForm();
    }

    public function store(CampaignTeamMemberRequest $request)
    {
        $form = CampaignTeamMemberForm::create()->setRequest($request);
        $form->save();

        return $this->httpResponse()
            ->setPreviousRoute('campaigns.team.index')
            ->setNextRoute('campaigns.team.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(CampaignTeamMember $team)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $team->name]));

        return CampaignTeamMemberForm::createFromModel($team)->renderForm();
    }

    public function update(CampaignTeamMember $team, CampaignTeamMemberRequest $request)
    {
        CampaignTeamMemberForm::createFromModel($team)->setRequest($request)->save();

        return $this->httpResponse()
            ->setPreviousRoute('campaigns.team.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CampaignTeamMember $team)
    {
        return DeleteResourceAction::make($team);
    }
}

<?php

namespace Botble\Campaigns\Tables;

use Botble\Campaigns\Models\CampaignTeamMember;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\LinkableColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class CampaignTeamMemberTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CampaignTeamMember::class)
            ->addColumns([
                IdColumn::make(),
                LinkableColumn::make('name')->route('campaigns.team.edit')->alignStart(),
                FormattedColumn::make('campaign_id')
                    ->title(trans('plugins/campaigns::campaigns.campaign'))
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->campaign->name),
                FormattedColumn::make('role')->title(trans('plugins/campaigns::campaigns.role'))->withEmptyState(),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('campaigns.team.create'))
            ->addActions([
                EditAction::make()->route('campaigns.team.edit'),
                DeleteAction::make()->route('campaigns.team.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('campaigns.team.destroy'))
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(fn (Builder $query) => $query->with('campaign')->select(['id', 'campaign_id', 'name', 'role', 'created_at', 'status']));
    }
}

<?php

namespace Botble\Campaigns\Tables;

use Botble\Campaigns\Models\CampaignContribution;
use Botble\Campaigns\Support\CampaignOptions;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\LinkableColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class CampaignContributionTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CampaignContribution::class)
            ->addColumns([
                IdColumn::make(),
                LinkableColumn::make('donor_name')->route('campaigns.contributions.edit')->alignStart()->title(trans('plugins/campaigns::campaigns.donor_name')),
                FormattedColumn::make('campaign_id')
                    ->title(trans('plugins/campaigns::campaigns.campaign'))
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->campaign->name),
                FormattedColumn::make('amount')
                    ->title(trans('plugins/campaigns::campaigns.amount'))
                    ->getValueUsing(fn (FormattedColumn $column) => number_format((float) $column->getItem()->amount, 0, '.', ' ') . ' ' . $column->getItem()->currency),
                FormattedColumn::make('contribution_status')
                    ->title(trans('plugins/campaigns::campaigns.contribution_status'))
                    ->getValueUsing(fn (FormattedColumn $column) => CampaignOptions::contributionStatuses()[$column->getItem()->contribution_status] ?? $column->getItem()->contribution_status),
                CreatedAtColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('campaigns.contributions.create'))
            ->addActions([
                EditAction::make()->route('campaigns.contributions.edit'),
                DeleteAction::make()->route('campaigns.contributions.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('campaigns.contributions.destroy'))
            ->queryUsing(fn (Builder $query) => $query->with('campaign')->select(['id', 'campaign_id', 'donor_name', 'amount', 'currency', 'contribution_status', 'created_at']));
    }
}

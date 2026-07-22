<?php

namespace Botble\Campaigns\Tables;

use Botble\Campaigns\Models\CampaignReward;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\LinkableColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class CampaignRewardTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CampaignReward::class)
            ->addColumns([
                IdColumn::make(),
                LinkableColumn::make('title')->route('campaigns.rewards.edit')->alignStart(),
                FormattedColumn::make('campaign_id')
                    ->title(trans('plugins/campaigns::campaigns.campaign'))
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->campaign->name),
                FormattedColumn::make('amount')
                    ->title(trans('plugins/campaigns::campaigns.amount'))
                    ->getValueUsing(fn (FormattedColumn $column) => number_format((float) $column->getItem()->amount, 0, '.', ' ') . ' ' . $column->getItem()->currency),
                FormattedColumn::make('manual_backers')
                    ->title('Бэкеров')
                    ->getValueUsing(fn (FormattedColumn $column) => (string) $column->getItem()->backers_count),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('campaigns.rewards.create'))
            ->addActions([
                EditAction::make()->route('campaigns.rewards.edit'),
                DeleteAction::make()->route('campaigns.rewards.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('campaigns.rewards.destroy'))
            ->addBulkChanges([
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(fn (Builder $query) => $query
                ->with(['campaign', 'contributions', 'supportRequests'])
                ->select(['id', 'campaign_id', 'title', 'amount', 'currency', 'manual_backers', 'created_at', 'status']));
    }
}

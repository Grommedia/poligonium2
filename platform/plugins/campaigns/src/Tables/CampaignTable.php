<?php

namespace Botble\Campaigns\Tables;

use Botble\Campaigns\Models\Campaign;
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

class CampaignTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Campaign::class)
            ->addColumns([
                IdColumn::make(),
                LinkableColumn::make('name')->route('campaigns.campaigns.edit')->alignStart(),
                FormattedColumn::make('campaign_state')
                    ->title(trans('plugins/campaigns::campaigns.campaign_state'))
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->state_label),
                FormattedColumn::make('target_amount')
                    ->title(trans('plugins/campaigns::campaigns.target_amount'))
                    ->getValueUsing(fn (FormattedColumn $column) => $this->money($column->getItem()->target_amount, $column->getItem()->currency)),
                FormattedColumn::make('collected')
                    ->title(trans('plugins/campaigns::campaigns.collected_amount'))
                    ->getValueUsing(fn (FormattedColumn $column) => $this->money($column->getItem()->collected_amount, $column->getItem()->currency) . ' (' . $column->getItem()->progress_percent . '%)'),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('campaigns.campaigns.create'))
            ->addActions([
                EditAction::make()->route('campaigns.campaigns.edit'),
                DeleteAction::make()->route('campaigns.campaigns.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('campaigns.campaigns.destroy'))
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(fn (Builder $query) => $query->select(['id', 'name', 'campaign_state', 'target_amount', 'manual_amount', 'currency', 'created_at', 'status']));
    }

    protected function money(float|int|null $amount, string $currency): string
    {
        return number_format((float) $amount, 0, '.', ' ') . ' ' . $currency;
    }
}

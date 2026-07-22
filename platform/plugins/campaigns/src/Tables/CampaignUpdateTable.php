<?php

namespace Botble\Campaigns\Tables;

use Botble\Campaigns\Models\CampaignUpdate;
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

class CampaignUpdateTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CampaignUpdate::class)
            ->addColumns([
                IdColumn::make(),
                LinkableColumn::make('title')->route('campaigns.updates.edit')->alignStart(),
                FormattedColumn::make('campaign_id')
                    ->title(trans('plugins/campaigns::campaigns.campaign'))
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->campaign->name),
                FormattedColumn::make('published_at')
                    ->title(trans('plugins/campaigns::campaigns.published_at'))
                    ->withEmptyState(),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('campaigns.updates.create'))
            ->addActions([
                EditAction::make()->route('campaigns.updates.edit'),
                DeleteAction::make()->route('campaigns.updates.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('campaigns.updates.destroy'))
            ->addBulkChanges([
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(fn (Builder $query) => $query->with('campaign')->select(['id', 'campaign_id', 'title', 'published_at', 'created_at', 'status']));
    }
}

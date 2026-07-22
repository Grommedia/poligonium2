<?php

namespace Botble\Campaigns\Tables;

use Botble\Campaigns\Models\CampaignFaq;
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

class CampaignFaqTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CampaignFaq::class)
            ->addColumns([
                IdColumn::make(),
                LinkableColumn::make('question')->route('campaigns.faqs.edit')->alignStart(),
                FormattedColumn::make('campaign_id')
                    ->title(trans('plugins/campaigns::campaigns.campaign'))
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->campaign->name),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('campaigns.faqs.create'))
            ->addActions([
                EditAction::make()->route('campaigns.faqs.edit'),
                DeleteAction::make()->route('campaigns.faqs.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('campaigns.faqs.destroy'))
            ->addBulkChanges([
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(fn (Builder $query) => $query->with('campaign')->select(['id', 'campaign_id', 'question', 'created_at', 'status']));
    }
}

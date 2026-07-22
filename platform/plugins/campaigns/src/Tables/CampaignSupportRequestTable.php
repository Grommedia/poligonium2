<?php

namespace Botble\Campaigns\Tables;

use Botble\Campaigns\Models\CampaignSupportRequest;
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

class CampaignSupportRequestTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CampaignSupportRequest::class)
            ->addColumns([
                IdColumn::make(),
                LinkableColumn::make('name')->route('campaigns.support-requests.edit')->alignStart()->title(trans('plugins/campaigns::campaigns.supporter_name')),
                FormattedColumn::make('campaign_id')
                    ->title(trans('plugins/campaigns::campaigns.campaign'))
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->campaign->name),
                FormattedColumn::make('amount')
                    ->title(trans('plugins/campaigns::campaigns.amount'))
                    ->getValueUsing(fn (FormattedColumn $column) => number_format((float) $column->getItem()->amount, 0, '.', ' ') . ' ' . $column->getItem()->currency),
                FormattedColumn::make('status')
                    ->title(trans('plugins/campaigns::campaigns.support_request_status'))
                    ->getValueUsing(fn (FormattedColumn $column) => CampaignOptions::supportRequestStatuses()[$column->getItem()->status] ?? $column->getItem()->status),
                CreatedAtColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('campaigns.support-requests.create'))
            ->addActions([
                EditAction::make()->route('campaigns.support-requests.edit'),
                DeleteAction::make()->route('campaigns.support-requests.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('campaigns.support-requests.destroy'))
            ->queryUsing(fn (Builder $query) => $query->with('campaign')->select(['id', 'campaign_id', 'name', 'amount', 'currency', 'status', 'created_at']));
    }
}

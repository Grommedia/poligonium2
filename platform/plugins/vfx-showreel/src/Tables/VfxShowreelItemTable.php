<?php

namespace Botble\VfxShowreel\Tables;

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
use Botble\VfxShowreel\Models\VfxShowreelItem;
use Illuminate\Database\Eloquent\Builder;

class VfxShowreelItemTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(VfxShowreelItem::class)
            ->addColumns([
                IdColumn::make(),
                LinkableColumn::make('name')->route('vfx-showreel.items.edit')->alignStart(),
                FormattedColumn::make('type')->title(trans('plugins/vfx-showreel::vfx-showreel.type'))->withEmptyState(),
                FormattedColumn::make('year')->title(trans('plugins/vfx-showreel::vfx-showreel.year'))->withEmptyState(),
                FormattedColumn::make('order')->title(trans('core/base::tables.order')),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('vfx-showreel.items.create'))
            ->addActions([
                EditAction::make()->route('vfx-showreel.items.edit'),
                DeleteAction::make()->route('vfx-showreel.items.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('vfx-showreel.items.destroy'))
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(fn (Builder $query) => $query->select(['id', 'name', 'type', 'year', 'order', 'created_at', 'status']));
    }
}

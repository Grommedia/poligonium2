<?php

namespace Botble\Academy\Tables;

use Botble\Academy\Models\AcademyCategory;
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

class AcademyCategoryTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(AcademyCategory::class)
            ->addColumns([
                IdColumn::make(),
                LinkableColumn::make('name')->route('academy.categories.edit')->alignStart(),
                FormattedColumn::make('slug')->title(trans('plugins/academy::academy.slug'))->withEmptyState(),
                FormattedColumn::make('order')->title(trans('core/base::tables.order')),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('academy.categories.create'))
            ->addActions([
                EditAction::make()->route('academy.categories.edit'),
                DeleteAction::make()->route('academy.categories.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('academy.categories.destroy'))
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(fn (Builder $query) => $query->select(['id', 'name', 'slug', 'order', 'created_at', 'status']));
    }
}

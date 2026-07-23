<?php

namespace Botble\Academy\Tables;

use Botble\Academy\Models\AcademyArticle;
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

class AcademyArticleTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(AcademyArticle::class)
            ->addColumns([
                IdColumn::make(),
                LinkableColumn::make('name')->route('academy.articles.edit')->alignStart(),
                FormattedColumn::make('category_name')
                    ->title(trans('plugins/academy::academy.category'))
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->category?->name ?: '-'),
                FormattedColumn::make('reading_time')->title(trans('plugins/academy::academy.reading_time'))->withEmptyState(),
                FormattedColumn::make('order')->title(trans('core/base::tables.order')),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('academy.articles.create'))
            ->addActions([
                EditAction::make()->route('academy.articles.edit'),
                DeleteAction::make()->route('academy.articles.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('academy.articles.destroy'))
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(fn (Builder $query) => $query
                ->with('category')
                ->select(['id', 'category_id', 'name', 'reading_time', 'order', 'created_at', 'status'])
            );
    }
}

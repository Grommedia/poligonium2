<?php

namespace Botble\Courses\Tables;

use Botble\Courses\Models\CourseCategory;
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
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class CourseCategoryTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CourseCategory::class)
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route('courses.categories.edit'),
                FormattedColumn::make('parent_id')
                    ->title(trans('plugins/courses::courses.parent_category'))
                    ->withEmptyState()
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->parent->name),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('courses.categories.create'))
            ->addActions([
                EditAction::make()->route('courses.categories.edit'),
                DeleteAction::make()->route('courses.categories.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('courses.categories.destroy'))
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(fn (Builder $query) => $query->select(['id', 'parent_id', 'name', 'created_at', 'status']));
    }
}

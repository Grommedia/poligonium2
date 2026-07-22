<?php

namespace Botble\Courses\Tables;

use Botble\Courses\Models\CourseChapter;
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

class CourseChapterTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CourseChapter::class)
            ->addColumns([
                IdColumn::make(),
                LinkableColumn::make('name')->route('courses.chapters.edit')->alignStart(),
                FormattedColumn::make('course_id')
                    ->title(trans('plugins/courses::courses.course'))
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->course->name),
                FormattedColumn::make('order')->title('Order'),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('courses.chapters.create'))
            ->addActions([
                EditAction::make()->route('courses.chapters.edit'),
                DeleteAction::make()->route('courses.chapters.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('courses.chapters.destroy'))
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(fn (Builder $query) => $query->select(['id', 'course_id', 'name', 'order', 'created_at', 'status']));
    }
}

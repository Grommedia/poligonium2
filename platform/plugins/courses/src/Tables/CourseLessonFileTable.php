<?php

namespace Botble\Courses\Tables;

use Botble\Courses\Models\CourseLessonFile;
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
use Botble\Table\Columns\YesNoColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class CourseLessonFileTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CourseLessonFile::class)
            ->addColumns([
                IdColumn::make(),
                LinkableColumn::make('name')->route('courses.files.edit')->alignStart(),
                FormattedColumn::make('course_id')
                    ->title(trans('plugins/courses::courses.course'))
                    ->withEmptyState()
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->course->name),
                FormattedColumn::make('lesson_id')
                    ->title(trans('plugins/courses::courses.lesson'))
                    ->withEmptyState()
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->lesson->name),
                YesNoColumn::make('requires_access')->title(trans('plugins/courses::courses.requires_access')),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('courses.files.create'))
            ->addActions([
                EditAction::make()->route('courses.files.edit'),
                DeleteAction::make()->route('courses.files.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('courses.files.destroy'))
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(fn (Builder $query) => $query->select(['id', 'course_id', 'lesson_id', 'name', 'requires_access', 'created_at', 'status']));
    }
}

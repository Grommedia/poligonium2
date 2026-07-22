<?php

namespace Botble\Courses\Tables;

use Botble\Courses\Models\CourseLesson;
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

class CourseLessonTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CourseLesson::class)
            ->addColumns([
                IdColumn::make(),
                LinkableColumn::make('name')->route('courses.lessons.edit')->alignStart(),
                FormattedColumn::make('course_id')
                    ->title(trans('plugins/courses::courses.course'))
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->course->name),
                FormattedColumn::make('chapter_id')
                    ->title(trans('plugins/courses::courses.chapter'))
                    ->withEmptyState()
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->chapter->name),
                YesNoColumn::make('is_free_preview')->title(trans('plugins/courses::courses.free_preview')),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('courses.lessons.create'))
            ->addActions([
                EditAction::make()->route('courses.lessons.edit'),
                DeleteAction::make()->route('courses.lessons.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('courses.lessons.destroy'))
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(fn (Builder $query) => $query->select(['id', 'course_id', 'chapter_id', 'name', 'is_free_preview', 'created_at', 'status']));
    }
}

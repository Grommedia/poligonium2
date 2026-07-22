<?php

namespace Botble\Courses\Tables;

use Botble\Courses\Models\CourseEnrollment;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class CourseEnrollmentTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CourseEnrollment::class)
            ->addColumns([
                IdColumn::make(),
                FormattedColumn::make('user_id')
                    ->title(trans('plugins/courses::courses.student'))
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->user->email),
                FormattedColumn::make('course_id')
                    ->title(trans('plugins/courses::courses.course'))
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->course->name),
                FormattedColumn::make('source')->title(trans('plugins/courses::courses.source')),
                FormattedColumn::make('status')->title('Status'),
                CreatedAtColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('courses.enrollments.create'))
            ->addActions([
                EditAction::make()->route('courses.enrollments.edit'),
                DeleteAction::make()->route('courses.enrollments.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('courses.enrollments.destroy'))
            ->addBulkChange(CreatedAtBulkChange::make())
            ->queryUsing(fn (Builder $query) => $query->select(['id', 'user_id', 'course_id', 'source', 'status', 'created_at']));
    }
}

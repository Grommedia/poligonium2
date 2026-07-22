<?php

namespace Botble\Courses\Tables;

use Botble\Courses\Models\Course;
use Botble\Courses\Support\CourseOptions;
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

class CourseTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Course::class)
            ->addColumns([
                IdColumn::make(),
                LinkableColumn::make('name')->route('courses.courses.edit')->alignStart(),
                FormattedColumn::make('category_id')
                    ->title(trans('plugins/courses::courses.category'))
                    ->withEmptyState()
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->category->name),
                FormattedColumn::make('difficulty')
                    ->title(trans('plugins/courses::courses.difficulty'))
                    ->withEmptyState()
                    ->getValueUsing(fn (FormattedColumn $column) => CourseOptions::levels()[$column->getItem()->difficulty] ?? $column->getItem()->difficulty),
                FormattedColumn::make('software')
                    ->title(trans('plugins/courses::courses.software'))
                    ->withEmptyState()
                    ->getValueUsing(function (FormattedColumn $column): string {
                        $software = $column->getItem()->software ?: [];
                        $options = CourseOptions::software();

                        return collect($software)
                            ->map(fn (string $value) => $options[$value] ?? $value)
                            ->take(4)
                            ->implode(', ');
                    }),
                FormattedColumn::make('price')
                    ->title(trans('plugins/courses::courses.price'))
                    ->getValueUsing(fn (FormattedColumn $column) => number_format((float) $column->getItem()->price, 0, '.', ' ') . ' ' . $column->getItem()->currency),
                FormattedColumn::make('sale_status')
                    ->title(trans('plugins/courses::courses.sale_status'))
                    ->withEmptyState()
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->sale_status_label),
                FormattedColumn::make('publication_state')
                    ->title('Публикация')
                    ->withEmptyState()
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->publicStatusLabel()),
                FormattedColumn::make('current_price')
                    ->title(trans('plugins/courses::courses.current_price'))
                    ->getValueUsing(fn (FormattedColumn $column) => number_format((float) $column->getItem()->current_price, 0, '.', ' ') . ' ' . $column->getItem()->currency),
                FormattedColumn::make('lesson_count')->title(trans('plugins/courses::courses.lesson_count')),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('courses.courses.create'))
            ->addActions([
                EditAction::make()->route('courses.courses.edit'),
                DeleteAction::make()->route('courses.courses.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('courses.courses.destroy'))
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(fn (Builder $query) => $query->select([
                'id',
                'category_id',
                'name',
                'difficulty',
                'software',
                'price',
                'sale_status',
                'early_access_price',
                'early_access_starts_at',
                'early_access_ends_at',
                'released_at',
                'publication_state',
                'publish_scheduled_at',
                'published_at',
                'published_snapshot',
                'has_unpublished_changes',
                'early_access_slots',
                'early_access_sold',
                'currency',
                'lesson_count',
                'created_at',
                'status',
            ]));
    }
}

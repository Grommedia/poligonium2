<?php

namespace Botble\Courses\Tables;

use Botble\Courses\Models\CoursePurchase;
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

class CoursePurchaseTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CoursePurchase::class)
            ->addColumns([
                IdColumn::make(),
                FormattedColumn::make('user_id')
                    ->title('Ученик')
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->user->email),
                FormattedColumn::make('course_id')
                    ->title('Курс')
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->course->name),
                FormattedColumn::make('purchase_type')
                    ->title('Тип')
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->purchase_type_label),
                FormattedColumn::make('amount')
                    ->title('Сумма')
                    ->getValueUsing(fn (FormattedColumn $column) => number_format((float) $column->getItem()->amount, 0, '.', ' ') . ' ' . $column->getItem()->currency),
                FormattedColumn::make('status')
                    ->title('Статус')
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->status_label),
                FormattedColumn::make('provider_status')->title('Monopay'),
                FormattedColumn::make('confirm')
                    ->title('Доступ')
                    ->getValueUsing(fn (FormattedColumn $column) => $this->renderConfirmButton($column->getItem())),
                CreatedAtColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('courses.purchases.create'))
            ->addActions([
                EditAction::make()->route('courses.purchases.edit'),
                DeleteAction::make()->route('courses.purchases.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('courses.purchases.destroy'))
            ->addBulkChange(CreatedAtBulkChange::make())
            ->queryUsing(fn (Builder $query) => $query->select([
                'id',
                'user_id',
                'course_id',
                'purchase_type',
                'amount',
                'currency',
                'status',
                'provider_status',
                'created_at',
            ]));
    }

    protected function renderConfirmButton(CoursePurchase $purchase): string
    {
        if ($purchase->status === 'paid') {
            return '<span class="badge bg-success">Открыт</span>';
        }

        if ($purchase->status !== 'pending') {
            return '<span class="badge bg-secondary">Нет</span>';
        }

        return sprintf(
            '<form method="POST" action="%s" style="display:inline">%s<button type="submit" class="btn btn-sm btn-primary">Подтвердить</button></form>',
            route('courses.purchases.confirm', $purchase->id),
            csrf_field()
        );
    }
}

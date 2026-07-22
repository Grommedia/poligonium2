<?php

namespace Botble\Courses\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\Courses\Forms\CoursePurchaseForm;
use Botble\Courses\Http\Requests\CoursePurchaseRequest;
use Botble\Courses\Models\CoursePurchase;
use Botble\Courses\Support\CourseAccessService;
use Botble\Courses\Tables\CoursePurchaseTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class CoursePurchaseController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/courses::courses.name'))
            ->add('Покупки курсів', route('courses.purchases.index'));
    }

    public function index(CoursePurchaseTable $table)
    {
        $this->pageTitle('Покупки курсів');

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('core/base::forms.create'));

        return CoursePurchaseForm::create()->renderForm();
    }

    public function store(CoursePurchaseRequest $request)
    {
        $form = CoursePurchaseForm::create()->setRequest($request);
        $form->save();

        $purchase = $form->getModel();

        if ($purchase->status === 'paid') {
            app(CourseAccessService::class)->grantFromPurchase($purchase, true);
        }

        return $this->httpResponse()
            ->setPreviousRoute('courses.purchases.index')
            ->setNextRoute('courses.purchases.edit', $purchase->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(CoursePurchase $purchase)
    {
        $this->pageTitle('Покупка курсу: ' . ($purchase->course->name ?: '#' . $purchase->id));

        return CoursePurchaseForm::createFromModel($purchase)->renderForm();
    }

    public function update(CoursePurchase $purchase, CoursePurchaseRequest $request)
    {
        $wasPaid = $purchase->status === 'paid';

        CoursePurchaseForm::createFromModel($purchase)->setRequest($request)->save();
        $purchase->refresh();

        if ($purchase->status === 'paid') {
            app(CourseAccessService::class)->grantFromPurchase($purchase, ! $wasPaid);
        }

        return $this->httpResponse()
            ->setPreviousRoute('courses.purchases.index')
            ->withUpdatedSuccessMessage();
    }

    public function confirm(CoursePurchase $purchase): RedirectResponse
    {
        $wasPaid = $purchase->status === 'paid';

        DB::transaction(function () use ($purchase, $wasPaid): void {
            if (! $wasPaid) {
                $purchase->forceFill(['status' => 'paid'])->save();
            }

            app(CourseAccessService::class)->grantFromPurchase($purchase, ! $wasPaid);
        });

        return redirect()
            ->route('courses.purchases.index')
            ->with('success_msg', 'Оплата подтверждена, доступ к курсу открыт.');
    }

    public function destroy(CoursePurchase $purchase)
    {
        return DeleteResourceAction::make($purchase);
    }

}

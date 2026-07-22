<?php

namespace Botble\Courses\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\Courses\Forms\CourseEnrollmentForm;
use Botble\Courses\Http\Requests\CourseEnrollmentRequest;
use Botble\Courses\Models\CourseEnrollment;
use Botble\Courses\Tables\CourseEnrollmentTable;

class CourseEnrollmentController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/courses::courses.name'))
            ->add(trans('plugins/courses::courses.enrollments'), route('courses.enrollments.index'));
    }

    public function index(CourseEnrollmentTable $table)
    {
        $this->pageTitle(trans('plugins/courses::courses.enrollments'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('core/base::forms.create'));

        return CourseEnrollmentForm::create()->renderForm();
    }

    public function store(CourseEnrollmentRequest $request)
    {
        $form = CourseEnrollmentForm::create()->setRequest($request);
        $form->save();

        return $this->httpResponse()
            ->setPreviousRoute('courses.enrollments.index')
            ->setNextRoute('courses.enrollments.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(CourseEnrollment $enrollment)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $enrollment->course->name]));

        return CourseEnrollmentForm::createFromModel($enrollment)->renderForm();
    }

    public function update(CourseEnrollment $enrollment, CourseEnrollmentRequest $request)
    {
        CourseEnrollmentForm::createFromModel($enrollment)->setRequest($request)->save();

        return $this->httpResponse()
            ->setPreviousRoute('courses.enrollments.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CourseEnrollment $enrollment)
    {
        return DeleteResourceAction::make($enrollment);
    }
}

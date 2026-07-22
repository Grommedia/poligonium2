<?php

namespace Botble\Courses\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\Courses\Forms\CourseLessonForm;
use Botble\Courses\Http\Requests\CourseLessonRequest;
use Botble\Courses\Models\CourseLesson;
use Botble\Courses\Tables\CourseLessonTable;

class CourseLessonController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/courses::courses.name'))
            ->add(trans('plugins/courses::courses.lessons'), route('courses.lessons.index'));
    }

    public function index(CourseLessonTable $table)
    {
        $this->pageTitle(trans('plugins/courses::courses.lessons'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('core/base::forms.create'));

        return CourseLessonForm::create()->renderForm();
    }

    public function store(CourseLessonRequest $request)
    {
        $form = CourseLessonForm::create()->setRequest($request);
        $form->save();
        $lesson = $form->getModel();

        return $this->httpResponse()
            ->setPreviousRoute('courses.courses.edit', $lesson->course_id)
            ->setNextRoute('courses.lessons.edit', $lesson->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(CourseLesson $lesson)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $lesson->name]));

        return CourseLessonForm::createFromModel($lesson)->renderForm();
    }

    public function update(CourseLesson $lesson, CourseLessonRequest $request)
    {
        CourseLessonForm::createFromModel($lesson)->setRequest($request)->save();

        return $this->httpResponse()
            ->setPreviousRoute('courses.courses.edit', $lesson->course_id)
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CourseLesson $lesson)
    {
        return DeleteResourceAction::make($lesson);
    }
}

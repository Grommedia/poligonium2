<?php

namespace Botble\Courses\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\Courses\Forms\CourseChapterForm;
use Botble\Courses\Http\Requests\CourseChapterRequest;
use Botble\Courses\Models\CourseChapter;
use Botble\Courses\Tables\CourseChapterTable;

class CourseChapterController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/courses::courses.name'))
            ->add(trans('plugins/courses::courses.chapters'), route('courses.chapters.index'));
    }

    public function index(CourseChapterTable $table)
    {
        $this->pageTitle(trans('plugins/courses::courses.chapters'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('core/base::forms.create'));

        return CourseChapterForm::create()->renderForm();
    }

    public function store(CourseChapterRequest $request)
    {
        $form = CourseChapterForm::create()->setRequest($request);
        $form->save();
        $chapter = $form->getModel();

        return $this->httpResponse()
            ->setPreviousRoute('courses.courses.edit', $chapter->course_id)
            ->setNextRoute('courses.chapters.edit', $chapter->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(CourseChapter $chapter)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $chapter->name]));

        return CourseChapterForm::createFromModel($chapter)->renderForm();
    }

    public function update(CourseChapter $chapter, CourseChapterRequest $request)
    {
        CourseChapterForm::createFromModel($chapter)->setRequest($request)->save();

        return $this->httpResponse()
            ->setPreviousRoute('courses.courses.edit', $chapter->course_id)
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CourseChapter $chapter)
    {
        return DeleteResourceAction::make($chapter);
    }
}

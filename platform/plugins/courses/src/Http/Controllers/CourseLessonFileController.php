<?php

namespace Botble\Courses\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\Courses\Forms\CourseLessonFileForm;
use Botble\Courses\Http\Requests\CourseLessonFileRequest;
use Botble\Courses\Models\CourseLessonFile;
use Botble\Courses\Tables\CourseLessonFileTable;

class CourseLessonFileController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/courses::courses.name'))
            ->add(trans('plugins/courses::courses.files'), route('courses.files.index'));
    }

    public function index(CourseLessonFileTable $table)
    {
        $this->pageTitle(trans('plugins/courses::courses.files'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('core/base::forms.create'));

        return CourseLessonFileForm::create()->renderForm();
    }

    public function store(CourseLessonFileRequest $request)
    {
        $form = CourseLessonFileForm::create()->setRequest($request);
        $form->save();

        return $this->httpResponse()
            ->setPreviousRoute('courses.files.index')
            ->setNextRoute('courses.files.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(CourseLessonFile $file)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $file->name]));

        return CourseLessonFileForm::createFromModel($file)->renderForm();
    }

    public function update(CourseLessonFile $file, CourseLessonFileRequest $request)
    {
        CourseLessonFileForm::createFromModel($file)->setRequest($request)->save();

        return $this->httpResponse()
            ->setPreviousRoute('courses.files.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CourseLessonFile $file)
    {
        return DeleteResourceAction::make($file);
    }
}

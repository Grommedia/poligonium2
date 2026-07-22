<?php

namespace Botble\Courses\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\Courses\Forms\CourseCategoryForm;
use Botble\Courses\Http\Requests\CourseCategoryRequest;
use Botble\Courses\Models\CourseCategory;
use Botble\Courses\Tables\CourseCategoryTable;

class CourseCategoryController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/courses::courses.name'))
            ->add(trans('plugins/courses::courses.categories'), route('courses.categories.index'));
    }

    public function index(CourseCategoryTable $table)
    {
        $this->pageTitle(trans('plugins/courses::courses.categories'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('core/base::forms.create'));

        return CourseCategoryForm::create()->renderForm();
    }

    public function store(CourseCategoryRequest $request)
    {
        $form = CourseCategoryForm::create()->setRequest($request);
        $form->save();

        return $this->httpResponse()
            ->setPreviousRoute('courses.categories.index')
            ->setNextRoute('courses.categories.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(CourseCategory $category)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $category->name]));

        return CourseCategoryForm::createFromModel($category)->renderForm();
    }

    public function update(CourseCategory $category, CourseCategoryRequest $request)
    {
        CourseCategoryForm::createFromModel($category)->setRequest($request)->save();

        return $this->httpResponse()
            ->setPreviousRoute('courses.categories.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CourseCategory $category)
    {
        return DeleteResourceAction::make($category);
    }
}

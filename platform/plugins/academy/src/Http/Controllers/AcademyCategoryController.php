<?php

namespace Botble\Academy\Http\Controllers;

use Botble\Academy\Forms\AcademyCategoryForm;
use Botble\Academy\Http\Requests\AcademyCategoryRequest;
use Botble\Academy\Models\AcademyCategory;
use Botble\Academy\Tables\AcademyCategoryTable;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Illuminate\Support\Str;

class AcademyCategoryController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/academy::academy.name'))
            ->add(trans('plugins/academy::academy.categories'), route('academy.categories.index'));
    }

    public function index(AcademyCategoryTable $table)
    {
        $this->pageTitle(trans('plugins/academy::academy.categories'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('core/base::forms.create'));

        return AcademyCategoryForm::create()->renderForm();
    }

    public function store(AcademyCategoryRequest $request)
    {
        $request->merge(['slug' => $this->makeSlug($request->input('name'), $request->input('slug'))]);

        $form = AcademyCategoryForm::create()->setRequest($request);
        $form->save();

        return $this->httpResponse()
            ->setPreviousRoute('academy.categories.index')
            ->setNextRoute('academy.categories.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(AcademyCategory $category)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $category->name]));

        return AcademyCategoryForm::createFromModel($category)->renderForm();
    }

    public function update(AcademyCategory $category, AcademyCategoryRequest $request)
    {
        $request->merge(['slug' => $this->makeSlug($request->input('name'), $request->input('slug'), $category->getKey())]);

        AcademyCategoryForm::createFromModel($category)->setRequest($request)->save();

        return $this->httpResponse()
            ->setPreviousRoute('academy.categories.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(AcademyCategory $category)
    {
        return DeleteResourceAction::make($category);
    }

    protected function makeSlug(string $name, ?string $slug = null, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $name) ?: 'academy-category';
        $candidate = $base;
        $counter = 2;

        while (AcademyCategory::query()
            ->where('slug', $candidate)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()
        ) {
            $candidate = $base . '-' . $counter++;
        }

        return $candidate;
    }
}

<?php

namespace Botble\Academy\Http\Controllers;

use Botble\Academy\Forms\AcademyArticleForm;
use Botble\Academy\Http\Requests\AcademyArticleRequest;
use Botble\Academy\Models\AcademyArticle;
use Botble\Academy\Tables\AcademyArticleTable;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Illuminate\Support\Str;

class AcademyArticleController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/academy::academy.name'))
            ->add(trans('plugins/academy::academy.articles'), route('academy.articles.index'));
    }

    public function index(AcademyArticleTable $table)
    {
        $this->pageTitle(trans('plugins/academy::academy.articles'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('core/base::forms.create'));

        return AcademyArticleForm::create()->renderForm();
    }

    public function store(AcademyArticleRequest $request)
    {
        $request->merge(['slug' => $this->makeSlug($request->input('name'), $request->input('slug'))]);

        $form = AcademyArticleForm::create()->setRequest($request);
        $form->save();

        return $this->httpResponse()
            ->setPreviousRoute('academy.articles.index')
            ->setNextRoute('academy.articles.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(AcademyArticle $article)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $article->name]));

        return AcademyArticleForm::createFromModel($article)->renderForm();
    }

    public function update(AcademyArticle $article, AcademyArticleRequest $request)
    {
        $request->merge(['slug' => $this->makeSlug($request->input('name'), $request->input('slug'), $article->getKey())]);

        AcademyArticleForm::createFromModel($article)->setRequest($request)->save();

        return $this->httpResponse()
            ->setPreviousRoute('academy.articles.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(AcademyArticle $article)
    {
        return DeleteResourceAction::make($article);
    }

    protected function makeSlug(string $name, ?string $slug = null, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $name) ?: 'academy-article';
        $candidate = $base;
        $counter = 2;

        while (AcademyArticle::query()
            ->where('slug', $candidate)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()
        ) {
            $candidate = $base . '-' . $counter++;
        }

        return $candidate;
    }
}

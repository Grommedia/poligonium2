<?php

namespace Botble\Academy\Http\Controllers;

use Botble\Academy\Models\AcademyArticle;
use Botble\Academy\Models\AcademyCategory;
use Botble\Base\Http\Controllers\BaseController;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\AdminBar;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PublicController extends BaseController
{
    public function index(): Response
    {
        SeoHelper::setTitle('Академія 3D-моделювання Polygonium')
            ->setDescription('Практичні статті про 3D-моделювання, персонажів, Blender, Houdini, VFX, навчання та виробничий пайплайн Polygonium.');

        $categories = AcademyCategory::query()
            ->where('status', 'published')
            ->withCount(['articles' => fn ($query) => $this->publishedArticles($query)])
            ->orderBy('order')
            ->get();

        $featuredArticles = $this->articleQuery()
            ->where('is_featured', true)
            ->limit(3)
            ->get();

        $latestArticles = $this->articleQuery()
            ->where('is_featured', false)
            ->limit(6)
            ->get();

        return Theme::scope('academy.index', compact('categories', 'featuredArticles', 'latestArticles'))->render();
    }

    public function articles(): Response
    {
        SeoHelper::setTitle('Статті Академії 3D-моделювання')
            ->setDescription('Бібліотека корисних матеріалів Polygonium про 3D-моделювання, персонажів, анімацію, VFX і навчання.');

        $articles = $this->articleQuery()->paginate(9);
        $categories = AcademyCategory::query()
            ->where('status', 'published')
            ->orderBy('order')
            ->get();

        return Theme::scope('academy.articles', compact('articles', 'categories'))->render();
    }

    public function category(AcademyCategory $category): Response
    {
        abort_unless($category->status->getValue() === 'published' || Auth::guard()->check(), 404);

        SeoHelper::setTitle($category->name)
            ->setDescription($category->description ?: 'Матеріали Академії 3D-моделювання Polygonium.');

        $articles = $this->articleQuery()
            ->where('category_id', $category->getKey())
            ->paginate(9);

        $categories = AcademyCategory::query()
            ->where('status', 'published')
            ->orderBy('order')
            ->get();

        return Theme::scope('academy.articles', compact('articles', 'categories', 'category'))->render();
    }

    public function show(AcademyArticle $article): Response
    {
        abort_unless($article->status->getValue() === 'published' || Auth::guard()->check(), 404);

        $article->load('category');

        SeoHelper::setTitle($article->seo_title ?: $article->name)
            ->setDescription($article->seo_description ?: $article->description);

        if (function_exists('admin_bar')) {
            AdminBar::registerLink(
                trans('plugins/academy::academy.edit_this_article'),
                route('academy.articles.edit', $article->id),
                null,
                'academy.articles.edit'
            );
        }

        $relatedArticles = $this->articleQuery()
            ->whereKeyNot($article->getKey())
            ->when($article->category_id, fn ($query) => $query->where('category_id', $article->category_id))
            ->limit(3)
            ->get();

        return Theme::scope('academy.show', compact('article', 'relatedArticles'))->render();
    }

    protected function articleQuery()
    {
        return AcademyArticle::query()
            ->with('category')
            ->where('status', 'published')
            ->where(function ($query): void {
                $query->whereNull('published_at')->orWhere('published_at', '<=', now());
            })
            ->orderByDesc('is_featured')
            ->orderBy('order')
            ->latest();
    }

    protected function publishedArticles($query)
    {
        return $query
            ->where('status', 'published')
            ->where(function ($query): void {
                $query->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }
}

<?php

namespace Botble\Academy\Forms;

use Botble\Academy\Http\Requests\AcademyArticleRequest;
use Botble\Academy\Models\AcademyArticle;
use Botble\Academy\Models\AcademyCategory;
use Botble\Base\Forms\FieldOptions\ContentFieldOption;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\IsFeaturedFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;

class AcademyArticleForm extends FormAbstract
{
    public function setup(): void
    {
        $categories = AcademyCategory::query()
            ->where('status', 'published')
            ->orderBy('order')
            ->pluck('name', 'id')
            ->all();

        $this
            ->model(AcademyArticle::class)
            ->setValidatorClass(AcademyArticleRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add('slug', TextField::class, [
                'label' => trans('plugins/academy::academy.slug'),
                'helperText' => trans('plugins/academy::academy.slug_helper'),
            ])
            ->add('category_id', SelectField::class, [
                'label' => trans('plugins/academy::academy.category'),
                'choices' => ['' => trans('plugins/academy::academy.no_category')] + $categories,
            ])
            ->add('description', TextareaField::class, DescriptionFieldOption::make()
                ->label(trans('plugins/academy::academy.short_description'))
                ->rows(3)
            )
            ->add('cover_image', 'mediaImage', MediaImageFieldOption::make()
                ->label(trans('plugins/academy::academy.cover_image'))
                ->value($this->getModel()->cover_image)
                ->helperText(trans('plugins/academy::academy.cover_image_helper'))
                ->toArray()
            )
            ->add('content', EditorField::class, ContentFieldOption::make()
                ->label(trans('plugins/academy::academy.content'))
                ->allowedShortcodes()
            )
            ->add('difficulty', SelectField::class, [
                'label' => trans('plugins/academy::academy.difficulty'),
                'choices' => [
                    '' => trans('plugins/academy::academy.not_selected'),
                    'Початковий' => 'Початковий',
                    'Середній' => 'Середній',
                    'Просунутий' => 'Просунутий',
                    'Професійний' => 'Професійний',
                ],
            ])
            ->add('software', TextField::class, [
                'label' => trans('plugins/academy::academy.software'),
                'helperText' => trans('plugins/academy::academy.comma_helper'),
                'attr' => ['placeholder' => 'Blender, Houdini, ZBrush'],
            ])
            ->add('skills', TextField::class, [
                'label' => trans('plugins/academy::academy.skills'),
                'helperText' => trans('plugins/academy::academy.comma_helper'),
                'attr' => ['placeholder' => '3D-моделювання, ригінг, анімація'],
            ])
            ->add('reading_time', NumberField::class, [
                'label' => trans('plugins/academy::academy.reading_time'),
                'attr' => ['placeholder' => '5'],
            ])
            ->add('cta_label', TextField::class, [
                'label' => trans('plugins/academy::academy.cta_label'),
                'attr' => ['placeholder' => 'Перейти до курсів'],
            ])
            ->add('cta_url', TextField::class, [
                'label' => trans('plugins/academy::academy.cta_url'),
                'attr' => ['placeholder' => '/courses'],
            ])
            ->add('seo_title', TextField::class, [
                'label' => trans('plugins/academy::academy.seo_title'),
            ])
            ->add('seo_description', TextareaField::class, [
                'label' => trans('plugins/academy::academy.seo_description'),
                'attr' => ['rows' => 3],
            ])
            ->add('published_at', TextField::class, [
                'label' => trans('plugins/academy::academy.published_at'),
                'attr' => ['placeholder' => '2026-07-23 10:00:00'],
            ])
            ->add('is_featured', OnOffField::class, IsFeaturedFieldOption::make())
            ->add('order', NumberField::class, SortOrderFieldOption::make())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}

<?php

namespace Botble\Academy\Forms;

use Botble\Academy\Http\Requests\AcademyCategoryRequest;
use Botble\Academy\Models\AcademyCategory;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;

class AcademyCategoryForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(AcademyCategory::class)
            ->setValidatorClass(AcademyCategoryRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add('slug', TextField::class, [
                'label' => trans('plugins/academy::academy.slug'),
                'helperText' => trans('plugins/academy::academy.slug_helper'),
            ])
            ->add('description', TextareaField::class, DescriptionFieldOption::make()->rows(3))
            ->add('icon', TextField::class, [
                'label' => trans('plugins/academy::academy.icon'),
                'attr' => ['placeholder' => 'ti ti-cube'],
            ])
            ->add('color', TextField::class, [
                'label' => trans('plugins/academy::academy.color'),
                'attr' => ['placeholder' => '#2563eb'],
            ])
            ->add('order', NumberField::class, SortOrderFieldOption::make())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}

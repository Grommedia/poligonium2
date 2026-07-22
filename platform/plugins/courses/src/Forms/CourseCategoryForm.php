<?php

namespace Botble\Courses\Forms;

use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Courses\Http\Requests\CourseCategoryRequest;
use Botble\Courses\Models\CourseCategory;

class CourseCategoryForm extends FormAbstract
{
    public function setup(): void
    {
        $categories = CourseCategory::query()
            ->orderBy('order')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();

        $this
            ->model(CourseCategory::class)
            ->setValidatorClass(CourseCategoryRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add('slug', TextField::class, [
                'label' => 'Slug',
                'attr' => ['placeholder' => 'houdini-beginner'],
            ])
            ->add('parent_id', SelectField::class, [
                'label' => trans('plugins/courses::courses.parent_category'),
                'choices' => ['' => trans('plugins/courses::courses.select_category')] + $categories,
            ])
            ->add('description', TextareaField::class, DescriptionFieldOption::make())
            ->add('order', NumberField::class, SortOrderFieldOption::make())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}

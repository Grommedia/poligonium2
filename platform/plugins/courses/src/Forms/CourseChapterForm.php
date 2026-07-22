<?php

namespace Botble\Courses\Forms;

use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Courses\Http\Requests\CourseChapterRequest;
use Botble\Courses\Models\Course;
use Botble\Courses\Models\CourseChapter;

class CourseChapterForm extends FormAbstract
{
    public function setup(): void
    {
        $courses = Course::query()->pluck('name', 'id')->all();

        $this
            ->model(CourseChapter::class)
            ->setValidatorClass(CourseChapterRequest::class)
            ->add('course_id', SelectField::class, [
                'label' => trans('plugins/courses::courses.course'),
                'choices' => ['' => trans('plugins/courses::courses.select_course')] + $courses,
                'required' => true,
            ])
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add('description', TextareaField::class, DescriptionFieldOption::make())
            ->add('is_free_preview', OnOffField::class, [
                'label' => trans('plugins/courses::courses.free_preview'),
            ])
            ->add('order', NumberField::class, SortOrderFieldOption::make())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}

<?php

namespace Botble\Courses\Forms;

use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Courses\Http\Requests\CourseLessonFileRequest;
use Botble\Courses\Models\Course;
use Botble\Courses\Models\CourseLesson;
use Botble\Courses\Models\CourseLessonFile;

class CourseLessonFileForm extends FormAbstract
{
    public function setup(): void
    {
        $courses = Course::query()->pluck('name', 'id')->all();
        $lessons = CourseLesson::query()
            ->with('course')
            ->orderBy('course_id')
            ->orderBy('order')
            ->get()
            ->mapWithKeys(fn (CourseLesson $lesson) => [$lesson->id => $lesson->course->name . ' / ' . $lesson->name])
            ->all();

        $this
            ->model(CourseLessonFile::class)
            ->setValidatorClass(CourseLessonFileRequest::class)
            ->add('course_id', SelectField::class, [
                'label' => trans('plugins/courses::courses.course'),
                'choices' => ['' => trans('plugins/courses::courses.select_course')] + $courses,
            ])
            ->add('lesson_id', SelectField::class, [
                'label' => trans('plugins/courses::courses.lesson'),
                'choices' => ['' => trans('plugins/courses::courses.select_lesson')] + $lessons,
            ])
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add('file_path', TextField::class, [
                'label' => trans('plugins/courses::courses.file_path'),
                'attr' => ['placeholder' => 'private/courses/files/project.zip'],
            ])
            ->add('file_size', NumberField::class, [
                'label' => trans('plugins/courses::courses.file_size'),
                'default_value' => 0,
            ])
            ->add('is_downloadable', OnOffField::class, [
                'label' => trans('plugins/courses::courses.downloadable'),
                'default_value' => true,
            ])
            ->add('requires_access', OnOffField::class, [
                'label' => trans('plugins/courses::courses.requires_access'),
                'default_value' => true,
            ])
            ->add('order', NumberField::class, SortOrderFieldOption::make())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}

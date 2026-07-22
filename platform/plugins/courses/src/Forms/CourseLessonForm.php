<?php

namespace Botble\Courses\Forms;

use Botble\Base\Forms\FieldOptions\ContentFieldOption;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\MediaFileFieldOption;
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
use Botble\Courses\Http\Requests\CourseLessonRequest;
use Botble\Courses\Models\Course;
use Botble\Courses\Models\CourseChapter;
use Botble\Courses\Models\CourseLesson;

class CourseLessonForm extends FormAbstract
{
    public function setup(): void
    {
        $courses = Course::query()->pluck('name', 'id')->all();
        $chapters = CourseChapter::query()
            ->with('course')
            ->orderBy('course_id')
            ->orderBy('order')
            ->get()
            ->mapWithKeys(fn (CourseChapter $chapter) => [$chapter->id => $chapter->course->name . ' / ' . $chapter->name])
            ->all();

        $this
            ->model(CourseLesson::class)
            ->setValidatorClass(CourseLessonRequest::class)
            ->add('course_id', SelectField::class, [
                'label' => trans('plugins/courses::courses.course'),
                'choices' => ['' => trans('plugins/courses::courses.select_course')] + $courses,
                'required' => true,
            ])
            ->add('chapter_id', SelectField::class, [
                'label' => trans('plugins/courses::courses.chapter'),
                'choices' => ['' => trans('plugins/courses::courses.select_chapter')] + $chapters,
            ])
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add('description', TextareaField::class, DescriptionFieldOption::make())
            ->add('content', EditorField::class, ContentFieldOption::make()->allowedShortcodes())
            ->add('video_path', 'mediaFile', MediaFileFieldOption::make()
                ->label(trans('plugins/courses::courses.video_path'))
                ->helperText(trans('plugins/courses::courses.video_path_hint'))
                ->value($this->getModel()->video_path)
                ->toArray()
            )
            ->add('video_embed', TextareaField::class, [
                'label' => trans('plugins/courses::courses.video_embed'),
                'attr' => ['rows' => 4],
            ])
            ->add('duration_minutes', NumberField::class, [
                'label' => trans('plugins/courses::courses.duration_minutes'),
                'default_value' => 0,
            ])
            ->add('is_free_preview', OnOffField::class, [
                'label' => trans('plugins/courses::courses.free_preview'),
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

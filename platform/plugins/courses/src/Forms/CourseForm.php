<?php

namespace Botble\Courses\Forms;

use Botble\Base\Forms\FieldOptions\ContentFieldOption;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\MediaFileFieldOption;
use Botble\Base\Forms\FieldOptions\MultiChecklistFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\DatetimeField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\RadioField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Courses\Http\Requests\CourseRequest;
use Botble\Courses\Models\Course;
use Botble\Courses\Models\CourseCategory;
use Botble\Courses\Support\CourseOptions;

class CourseForm extends FormAbstract
{
    public function setup(): void
    {
        $categories = CourseCategory::query()
            ->orderBy('order')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();

        $this
            ->model(Course::class)
            ->setValidatorClass(CourseRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required()->maxLength(90))
            ->add('slug', TextField::class, [
                'label' => 'Slug',
                'attr' => [
                    'placeholder' => 'magic-spider-modeling-animation',
                    'data-course-slug-field' => true,
                ],
                'help_block' => [
                    'text' => trans('plugins/courses::courses.slug_hint'),
                    'tag' => 'small',
                    'attr' => ['class' => 'form-hint'],
                ],
            ])
            ->add('category_id', SelectField::class, [
                'label' => trans('plugins/courses::courses.category'),
                'choices' => ['' => trans('plugins/courses::courses.select_category')] + $categories,
                'attr' => [
                    'data-course-category-field' => true,
                ],
            ])
            ->add('difficulty', RadioField::class, [
                'label' => trans('plugins/courses::courses.difficulty'),
                'choices' => CourseOptions::levels(),
                'selected' => $this->getModel()->difficulty ?: 'beginner',
                'attr' => [
                    'data-course-level-field' => true,
                ],
                'wrapper' => [
                    'class' => 'poligonium-course-level-row',
                ],
            ])
            ->add('course_choice_compact_style', HtmlField::class, HtmlFieldOption::make()->content(<<<'HTML'
                <style>
                    .poligonium-course-level-row .form-check-group {
                        display: flex;
                        flex-wrap: wrap;
                        gap: 10px;
                        align-items: center;
                    }

                    .poligonium-course-level-row .form-check {
                        margin: 0;
                        min-height: auto;
                    }

                    .poligonium-course-level-row .form-check-input {
                        margin-top: .2rem;
                    }

                    .poligonium-course-level-row .form-check-label {
                        color: #344050;
                        font-size: 13px;
                        line-height: 1.25;
                    }

                    .poligonium-course-checklist .fieldset-for-multi-check-list {
                        margin: 0;
                        border: 1px solid rgba(24, 36, 51, .08);
                        border-radius: 8px;
                        background: #f8fafc;
                        padding: 10px 12px 2px;
                    }

                    .poligonium-course-checklist .multi-check-list-wrapper {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
                        gap: 6px 14px;
                        max-height: 150px;
                        overflow: auto;
                        padding-right: 4px;
                    }

                    .poligonium-course-checklist .form-check {
                        margin: 0 0 8px;
                        min-height: auto;
                    }

                    .poligonium-course-checklist .form-check-label {
                        color: #344050;
                        font-size: 13px;
                        line-height: 1.25;
                    }
                </style>
            HTML))
            ->add('software[]', MultiCheckListField::class, MultiChecklistFieldOption::make()
                ->label(trans('plugins/courses::courses.software'))
                ->choices(CourseOptions::software())
                ->selected($this->getModel()->software ?: [])
                ->attributes([
                    'class' => 'poligonium-course-checklist-input',
                    'label_attr' => ['class' => 'form-check poligonium-course-check-item'],
                ])
                ->wrapperAttributes(['class' => 'poligonium-course-checklist'])
            )
            ->add('skills[]', MultiCheckListField::class, MultiChecklistFieldOption::make()
                ->label(trans('plugins/courses::courses.skills'))
                ->choices(CourseOptions::skills())
                ->selected($this->getModel()->skills ?: [])
                ->attributes([
                    'class' => 'poligonium-course-checklist-input',
                    'label_attr' => ['class' => 'form-check poligonium-course-check-item'],
                ])
                ->wrapperAttributes(['class' => 'poligonium-course-checklist'])
            )
            ->add('description', TextareaField::class, DescriptionFieldOption::make()
                ->label(trans('plugins/courses::courses.short_description'))
                ->helperText(trans('plugins/courses::courses.short_description_hint'))
                ->attributes([
                    'maxlength' => 180,
                    'rows' => 4,
                    'data-course-short-description' => true,
                ])
            )
            ->add('content', EditorField::class, ContentFieldOption::make()->allowedShortcodes()->label(trans('plugins/courses::courses.full_description')))
            ->add('duration_minutes', NumberField::class, [
                'label' => trans('plugins/courses::courses.duration_minutes'),
                'default_value' => 0,
                'attr' => [
                    'readonly' => true,
                    'data-course-calculated-field' => 'duration',
                ],
                'help_block' => [
                    'text' => trans('plugins/courses::courses.duration_hint'),
                    'tag' => 'small',
                    'attr' => ['class' => 'form-hint'],
                ],
            ])
            ->add('lesson_count', NumberField::class, [
                'label' => trans('plugins/courses::courses.lesson_count'),
                'default_value' => 0,
                'attr' => [
                    'readonly' => true,
                    'data-course-calculated-field' => 'lessons',
                ],
                'help_block' => [
                    'text' => trans('plugins/courses::courses.lesson_count_hint'),
                    'tag' => 'small',
                    'attr' => ['class' => 'form-hint'],
                ],
            ])
            ->add('image', 'mediaImage', MediaImageFieldOption::make()
                ->label(trans('plugins/courses::courses.cover_image'))
                ->value($this->getModel()->image)
                ->toArray()
            )
            ->add('intro_video', 'mediaFile', MediaFileFieldOption::make()
                ->label(trans('plugins/courses::courses.intro_video'))
                ->value($this->getModel()->intro_video)
                ->toArray()
            )
            ->add('visibility_mode', SelectField::class, [
                'label' => trans('plugins/courses::courses.visibility_mode'),
                'choices' => CourseOptions::visibilityModes(),
                'selected' => $this->getModel()->visibility_mode ?: 'catalog',
            ])
            ->add('price_type', SelectField::class, [
                'label' => trans('plugins/courses::courses.price_type'),
                'choices' => CourseOptions::priceTypes(),
                'selected' => $this->getModel()->price_type ?: 'paid',
            ])
            ->add('price', NumberField::class, [
                'label' => trans('plugins/courses::courses.price'),
                'default_value' => 0,
                'attr' => [
                    'min' => 0,
                    'step' => 1,
                    'data-course-price-field' => true,
                ],
                'help_block' => [
                    'text' => trans('plugins/courses::courses.price_hint'),
                    'tag' => 'small',
                    'attr' => ['class' => 'form-hint'],
                ],
            ])
            ->add('sale_status', SelectField::class, [
                'label' => trans('plugins/courses::courses.sale_status'),
                'choices' => CourseOptions::saleStatuses(),
                'attr' => [
                    'data-course-sale-status' => true,
                ],
                'help_block' => [
                    'text' => trans('plugins/courses::courses.sale_status_hint'),
                    'tag' => 'small',
                    'attr' => ['class' => 'form-hint'],
                ],
            ])
            ->add('sales_mode', SelectField::class, [
                'label' => trans('plugins/courses::courses.sales_mode'),
                'choices' => CourseOptions::salesModes(),
                'selected' => $this->getModel()->sales_mode ?: 'immediate',
            ])
            ->add('sales_starts_at', DatetimeField::class, [
                'label' => trans('plugins/courses::courses.sales_starts_at'),
                'value' => $this->getModel()->sales_starts_at?->format('Y-m-d\TH:i'),
                'help_block' => [
                    'text' => trans('plugins/courses::courses.datetime_hint'),
                    'tag' => 'small',
                    'attr' => ['class' => 'form-hint'],
                ],
            ])
            ->add('early_access_price', NumberField::class, [
                'label' => trans('plugins/courses::courses.early_access_price'),
                'attr' => [
                    'data-course-early-access-field' => true,
                ],
                'help_block' => [
                    'text' => trans('plugins/courses::courses.early_access_price_hint'),
                    'tag' => 'small',
                    'attr' => ['class' => 'form-hint'],
                ],
            ])
            ->add('early_access_starts_at', DatetimeField::class, [
                'label' => trans('plugins/courses::courses.early_access_starts_at'),
                'value' => $this->getModel()->early_access_starts_at?->format('Y-m-d\TH:i'),
                'attr' => [
                    'data-course-early-access-field' => true,
                ],
                'help_block' => [
                    'text' => trans('plugins/courses::courses.datetime_hint'),
                    'tag' => 'small',
                    'attr' => ['class' => 'form-hint'],
                ],
            ])
            ->add('early_access_ends_at', DatetimeField::class, [
                'label' => trans('plugins/courses::courses.early_access_ends_at'),
                'value' => $this->getModel()->early_access_ends_at?->format('Y-m-d\TH:i'),
                'attr' => [
                    'data-course-early-access-field' => true,
                ],
                'help_block' => [
                    'text' => trans('plugins/courses::courses.early_access_ends_at_hint'),
                    'tag' => 'small',
                    'attr' => ['class' => 'form-hint'],
                ],
            ])
            ->add('released_at', DatetimeField::class, [
                'label' => trans('plugins/courses::courses.released_at'),
                'value' => $this->getModel()->released_at?->format('Y-m-d\TH:i'),
                'help_block' => [
                    'text' => trans('plugins/courses::courses.released_at_hint'),
                    'tag' => 'small',
                    'attr' => ['class' => 'form-hint'],
                ],
            ])
            ->add('course_access_mode', SelectField::class, [
                'label' => trans('plugins/courses::courses.course_access_mode'),
                'choices' => CourseOptions::accessModes(),
                'selected' => $this->getModel()->course_access_mode ?: 'immediate',
            ])
            ->add('timezone', SelectField::class, [
                'label' => trans('plugins/courses::courses.timezone'),
                'choices' => [
                    'Europe/Kyiv' => 'Europe/Kyiv',
                    'UTC' => 'UTC',
                    'Europe/Warsaw' => 'Europe/Warsaw',
                    'Europe/London' => 'Europe/London',
                    'America/New_York' => 'America/New_York',
                ],
                'selected' => $this->getModel()->timezone ?: 'Europe/Kyiv',
            ])
            ->add('show_release_date_on_card', OnOffField::class, [
                'label' => trans('plugins/courses::courses.show_release_date_on_card'),
                'default_value' => true,
            ])
            ->add('gradual_access_enabled', OnOffField::class, [
                'label' => trans('plugins/courses::courses.gradual_access_enabled'),
                'default_value' => false,
            ])
            ->add('publication_state', SelectField::class, [
                'label' => 'Стан публікації',
                'choices' => CourseOptions::publicationStates(),
                'selected' => $this->getModel()->publication_state ?: 'draft',
                'help_block' => [
                    'text' => 'Окремо від продажів і дати відкриття матеріалів.',
                    'tag' => 'small',
                    'attr' => ['class' => 'form-hint'],
                ],
            ])
            ->add('publish_scheduled_at', DatetimeField::class, [
                'label' => 'Опублікувати за розкладом',
                'value' => $this->getModel()->publish_scheduled_at?->format('Y-m-d\TH:i'),
                'help_block' => [
                    'text' => trans('plugins/courses::courses.datetime_hint'),
                    'tag' => 'small',
                    'attr' => ['class' => 'form-hint'],
                ],
            ])
            ->add('has_unpublished_changes', OnOffField::class, [
                'label' => 'Є неопубліковані зміни',
                'default_value' => false,
                'attr' => [
                    'readonly' => true,
                ],
            ])
            ->add('early_access_slots', NumberField::class, [
                'label' => trans('plugins/courses::courses.early_access_slots'),
                'attr' => [
                    'data-course-early-access-field' => true,
                ],
                'help_block' => [
                    'text' => trans('plugins/courses::courses.early_access_slots_hint'),
                    'tag' => 'small',
                    'attr' => ['class' => 'form-hint'],
                ],
            ])
            ->add('early_access_sold', NumberField::class, [
                'label' => trans('plugins/courses::courses.early_access_sold'),
                'default_value' => 0,
                'attr' => [
                    'readonly' => true,
                    'data-course-early-access-field' => true,
                ],
                'help_block' => [
                    'text' => trans('plugins/courses::courses.early_access_sold_hint'),
                    'tag' => 'small',
                    'attr' => ['class' => 'form-hint'],
                ],
            ])
            ->add('currency', SelectField::class, [
                'label' => trans('plugins/courses::courses.currency'),
                'choices' => [
                    'UAH' => 'UAH — українська гривня',
                    'EUR' => 'EUR — євро',
                    'USD' => 'USD — долар США',
                ],
                'selected' => $this->getModel()->currency ?: 'UAH',
                'help_block' => [
                    'text' => trans('plugins/courses::courses.currency_hint'),
                    'tag' => 'small',
                    'attr' => ['class' => 'form-hint'],
                ],
            ])
            ->add('is_featured', OnOffField::class, [
                'label' => trans('plugins/courses::courses.featured'),
                'help_block' => [
                    'text' => trans('plugins/courses::courses.featured_hint'),
                    'tag' => 'small',
                    'attr' => ['class' => 'form-hint'],
                ],
            ])
            ->add('is_free_preview', OnOffField::class, [
                'label' => trans('plugins/courses::courses.free_preview'),
                'help_block' => [
                    'text' => trans('plugins/courses::courses.free_preview_course_hint'),
                    'tag' => 'small',
                    'attr' => ['class' => 'form-hint'],
                ],
            ])
            ->add('order', NumberField::class, SortOrderFieldOption::make())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('image');

        $course = $this->getModel();

        if ($course instanceof Course && $course->getKey()) {
            $course->loadMissing(['chapters.lessons']);

            $this->addMetaBoxes([
                'course_curriculum_builder' => [
                    'attributes' => [
                        'id' => 'course-curriculum-builder-box',
                    ],
                    'id' => 'course_curriculum_builder',
                    'title' => trans('plugins/courses::courses.curriculum'),
                    'subtitle' => trans('plugins/courses::courses.curriculum_subtitle'),
                    'content' => view('plugins/courses::courses.curriculum-builder', [
                        'course' => $course,
                    ])->render(),
                    'priority' => 10,
                ],
            ]);
        }
    }
}

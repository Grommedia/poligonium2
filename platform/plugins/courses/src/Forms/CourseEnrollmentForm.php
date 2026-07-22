<?php

namespace Botble\Courses\Forms;

use Botble\ACL\Models\User;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Courses\Http\Requests\CourseEnrollmentRequest;
use Botble\Courses\Models\Course;
use Botble\Courses\Models\CourseEnrollment;

class CourseEnrollmentForm extends FormAbstract
{
    public function setup(): void
    {
        $users = User::query()
            ->get()
            ->mapWithKeys(fn (User $user) => [$user->id => trim($user->first_name . ' ' . $user->last_name) . ' <' . $user->email . '>'])
            ->all();

        $courses = Course::query()->pluck('name', 'id')->all();

        $this
            ->model(CourseEnrollment::class)
            ->setValidatorClass(CourseEnrollmentRequest::class)
            ->add('user_id', SelectField::class, [
                'label' => trans('plugins/courses::courses.student'),
                'choices' => $users,
                'required' => true,
            ])
            ->add('course_id', SelectField::class, [
                'label' => trans('plugins/courses::courses.course'),
                'choices' => $courses,
                'required' => true,
            ])
            ->add('source', SelectField::class, [
                'label' => trans('plugins/courses::courses.source'),
                'choices' => [
                    'manual' => 'Manual',
                    'purchase' => 'Purchase',
                    'subscription' => 'Subscription',
                ],
            ])
            ->add('status', SelectField::class, [
                'label' => 'Status',
                'choices' => [
                    'active' => 'Active',
                    'paused' => 'Paused',
                    'expired' => 'Expired',
                    'cancelled' => 'Cancelled',
                ],
            ])
            ->add('starts_at', TextField::class, [
                'label' => trans('plugins/courses::courses.starts_at'),
                'attr' => ['placeholder' => '2026-06-13 12:00:00'],
            ])
            ->add('ends_at', TextField::class, [
                'label' => trans('plugins/courses::courses.ends_at'),
                'attr' => ['placeholder' => 'optional'],
            ])
            ->add('notes', TextareaField::class, [
                'label' => trans('plugins/courses::courses.notes'),
                'attr' => ['rows' => 4],
            ])
            ->setBreakFieldPoint('status');
    }
}

<?php

namespace Botble\Courses\Forms;

use Botble\ACL\Models\User;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Courses\Http\Requests\CoursePurchaseRequest;
use Botble\Courses\Models\Course;
use Botble\Courses\Models\CoursePurchase;

class CoursePurchaseForm extends FormAbstract
{
    public function setup(): void
    {
        $users = User::query()
            ->get()
            ->mapWithKeys(fn (User $user) => [$user->id => trim($user->first_name . ' ' . $user->last_name) . ' <' . $user->email . '>'])
            ->all();

        $courses = Course::query()->pluck('name', 'id')->all();

        $this
            ->model(CoursePurchase::class)
            ->setValidatorClass(CoursePurchaseRequest::class)
            ->add('user_id', SelectField::class, [
                'label' => 'Ученик',
                'choices' => $users,
                'required' => true,
            ])
            ->add('course_id', SelectField::class, [
                'label' => 'Курс',
                'choices' => $courses,
                'required' => true,
            ])
            ->add('purchase_type', SelectField::class, [
                'label' => 'Тип покупки',
                'choices' => [
                    'early_access' => 'Ранний доступ',
                    'full' => 'Полная покупка',
                    'manual' => 'Ручная покупка',
                    'subscription' => 'Подписка',
                ],
                'required' => true,
            ])
            ->add('status', SelectField::class, [
                'label' => 'Статус',
                'choices' => [
                    'pending' => 'Ожидает оплаты',
                    'paid' => 'Оплачено',
                    'cancelled' => 'Отменено',
                    'refunded' => 'Возврат',
                ],
                'required' => true,
            ])
            ->add('amount', TextField::class, [
                'label' => 'Сумма',
                'required' => true,
            ])
            ->add('full_price', TextField::class, [
                'label' => 'Полная цена курса',
            ])
            ->add('discount_amount', TextField::class, [
                'label' => 'Скидка',
            ])
            ->add('currency', TextField::class, [
                'label' => 'Валюта',
                'required' => true,
                'attr' => ['placeholder' => 'UAH'],
            ])
            ->add('payment_provider', TextField::class, [
                'label' => 'Платежная система',
                'attr' => ['placeholder' => 'manual / liqpay / wayforpay'],
            ])
            ->add('payment_reference', TextField::class, [
                'label' => 'Номер / комментарий платежа',
            ])
            ->add('provider_invoice_id', TextField::class, [
                'label' => 'Monopay invoiceId',
                'attr' => ['readonly' => true],
            ])
            ->add('provider_page_url', TextField::class, [
                'label' => 'Ссылка на оплату Monopay',
                'attr' => ['readonly' => true],
            ])
            ->add('provider_status', TextField::class, [
                'label' => 'Статус Monopay',
                'attr' => ['readonly' => true],
            ])
            ->setBreakFieldPoint('amount');
    }
}

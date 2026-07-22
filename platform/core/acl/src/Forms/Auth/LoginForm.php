<?php

namespace Botble\ACL\Forms\Auth;

use Botble\ACL\Http\Requests\LoginRequest;
use Botble\ACL\Models\User;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\CheckboxField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\PasswordField;
use Botble\Base\Forms\Fields\TextField;

class LoginForm extends AuthForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setValidatorClass(LoginRequest::class)
            ->setUrl(route('access.login'))
            ->heading(trans('core/acl::auth.sign_in_below', [], 'uk'))
            ->add(
                'username',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('core/acl::auth.login.username', [], 'uk'))
                    ->value(old(
                        'email',
                        BaseHelper::hasDemoModeEnabled() ? config('core.base.general.demo.account.username') : null,
                    ))
                    ->required()
                    ->attributes(['tabindex' => 1, 'placeholder' => trans('core/acl::auth.login.placeholder.username', [], 'uk')])
            )
            ->add(
                'password',
                PasswordField::class,
                TextFieldOption::make()
                    ->label(trans('core/acl::auth.login.password', [], 'uk'))
                    ->labelAttributes([
                        'description' => Html::link(route('access.password.request'), trans('core/acl::auth.lost_your_password', [], 'uk'), ['tabindex' => 5, 'title' => trans('core/acl::auth.forgot_password.title', [], 'uk')])->toHtml(),
                    ])
                    ->attributes(['tabindex' => 2])
                    ->when(BaseHelper::hasDemoModeEnabled(), function (TextFieldOption $option): void {
                        $option->value(config('core.base.general.demo.account.password'));
                    })
                    ->required()
                    ->placeholder(trans('core/acl::auth.login.placeholder.password', [], 'uk'))
            )
            ->add(
                'remember',
                CheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('core/acl::auth.login.remember', [], 'uk'))
                    ->value(true)
                    ->attributes(['tabindex' => 3])
            )
            ->submitButton(trans('core/acl::auth.login.login', [], 'uk'), 'ti ti-login-2')
            ->add(
                'filters',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content(apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, User::class))
            );
    }
}

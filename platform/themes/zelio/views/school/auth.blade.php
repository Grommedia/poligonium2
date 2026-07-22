@php
    $isRegister = ($mode ?? 'login') === 'register';
    $isEnglish = request()->segment(1) === 'en';
    $copy = $isEnglish
        ? [
            'title_register' => 'Create student account',
            'title_login' => 'Student sign in',
            'intro' => 'Your account gives you early course access, purchased lessons, learning progress and the future student gallery.',
            'early_access' => 'Early access',
            'my_courses' => 'My courses',
            'school_gallery' => 'School gallery',
            'form_register' => 'Registration',
            'form_login' => 'Authorization',
            'new_student' => 'New student',
            'my_access' => 'My access',
            'nickname' => 'Nickname',
            'nickname_hint' => 'Use Latin letters, numbers, dots, dashes or underscores. This is how we will address you in the school.',
            'nickname_placeholder' => 'For example: magic_spider',
            'email' => 'Email',
            'password' => 'Password',
            'password_repeat' => 'Repeat password',
            'remember' => 'Remember me',
            'submit_register' => 'Create account',
            'submit_login' => 'Sign in',
            'has_account' => 'Already have an account?',
            'no_account' => 'No account yet?',
            'login_link' => 'Sign in',
            'register_link' => 'Register',
        ]
        : [
            'title_register' => 'Створити кабінет учня',
            'title_login' => 'Вхід до кабінету учня',
            'intro' => 'Кабінет потрібен для раннього доступу до курсів, перегляду куплених уроків, прогресу навчання та майбутньої галереї робіт учнів.',
            'early_access' => 'Ранній доступ',
            'my_courses' => 'Мої курси',
            'school_gallery' => 'Галерея школи',
            'form_register' => 'Реєстрація',
            'form_login' => 'Авторизація',
            'new_student' => 'Новий учень',
            'my_access' => 'Мій доступ',
            'nickname' => 'Нікнейм',
            'nickname_hint' => 'Латинські літери, цифри, крапка, дефіс або нижнє підкреслення. Так ми будемо звертатися до тебе в школі.',
            'nickname_placeholder' => 'Наприклад: magic_spider',
            'email' => 'Email',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',
            'remember' => 'Запамʼятати мене',
            'submit_register' => 'Створити кабінет',
            'submit_login' => 'Увійти',
            'has_account' => 'Вже є кабінет?',
            'no_account' => 'Ще немає кабінету?',
            'login_link' => 'Увійти',
            'register_link' => 'Зареєструватися',
        ];
@endphp

<section class="poligonium-school-page">
    <div class="poligonium-school-orb is-left" aria-hidden="true"></div>
    <div class="poligonium-school-orb is-right" aria-hidden="true"></div>

    <div class="poligonium-school-auth">
        <div class="poligonium-school-auth-copy">
            <p class="poligonium-school-kicker">Polygonium School</p>
            <h1>{{ $isRegister ? $copy['title_register'] : $copy['title_login'] }}</h1>
            <p>{{ $copy['intro'] }}</p>

            <div class="poligonium-school-auth-points">
                <span>{{ $copy['early_access'] }}</span>
                <span>{{ $copy['my_courses'] }}</span>
                <span>{{ $copy['school_gallery'] }}</span>
            </div>
        </div>

        <form class="poligonium-school-form" method="POST" action="{{ $isRegister ? route('courses.student.register.submit') : route('courses.student.login.submit') }}">
            @csrf

            <div class="poligonium-school-form-head">
                <span>{{ $isRegister ? $copy['form_register'] : $copy['form_login'] }}</span>
                <strong>{{ $isRegister ? $copy['new_student'] : $copy['my_access'] }}</strong>
            </div>

            @if (session('status'))
                <div class="poligonium-school-alert is-success">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="poligonium-school-alert">
                    {{ $errors->first() }}
                </div>
            @endif

            @if ($isRegister)
                <label>
                    <span>{{ $copy['nickname'] }}</span>
                    <input type="text" name="name" value="{{ old('name') }}" autocomplete="nickname" placeholder="{{ $copy['nickname_placeholder'] }}" pattern="[A-Za-z0-9_.-]{3,40}" required>
                    <small>{{ $copy['nickname_hint'] }}</small>
                </label>
            @endif

            <label>
                <span>{{ $copy['email'] }}</span>
                <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
            </label>

            <label>
                <span>{{ $copy['password'] }}</span>
                <input type="password" name="password" autocomplete="{{ $isRegister ? 'new-password' : 'current-password' }}" required>
            </label>

            @if ($isRegister)
                <label>
                    <span>{{ $copy['password_repeat'] }}</span>
                    <input type="password" name="password_confirmation" autocomplete="new-password" required>
                </label>
            @else
                <label class="poligonium-school-check">
                    <input type="checkbox" name="remember" value="1">
                    <span>{{ $copy['remember'] }}</span>
                </label>
            @endif

            <button type="submit">{{ $isRegister ? $copy['submit_register'] : $copy['submit_login'] }}</button>

            <p class="poligonium-school-switch">
                @if ($isRegister)
                    {{ $copy['has_account'] }}
                    <a href="{{ route('courses.student.login') }}">{{ $copy['login_link'] }}</a>
                @else
                    {{ $copy['no_account'] }}
                    <a href="{{ route('courses.student.register') }}">{{ $copy['register_link'] }}</a>
                @endif
            </p>
        </form>
    </div>
</section>

@include('theme.zelio::views.school.partials.styles')

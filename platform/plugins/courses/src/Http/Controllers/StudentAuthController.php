<?php

namespace Botble\Courses\Http\Controllers;

use Botble\ACL\Models\User;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Courses\Models\StudentProfile;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class StudentAuthController extends BaseController
{
    public function showLogin(): Response|RedirectResponse
    {
        if (Auth::guard()->check()) {
            return redirect()->route('courses.student.cabinet');
        }

        SeoHelper::setTitle($this->isEnglish() ? 'Student sign in' : 'Вхід до кабінету учня');

        return Theme::scope('school.auth', ['mode' => 'login'])->render();
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard()->attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors([
                    'email' => $this->isEnglish()
                        ? 'Could not sign in. Check your email and password.'
                        : 'Не вдалося увійти. Перевірте email і пароль.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();
        $this->ensureStudentProfile(Auth::guard()->user());

        return redirect()->intended(route('courses.student.cabinet'));
    }

    public function showRegister(): Response|RedirectResponse
    {
        if (Auth::guard()->check()) {
            return redirect()->route('courses.student.cabinet');
        }

        SeoHelper::setTitle($this->isEnglish() ? 'Student registration' : 'Реєстрація учня');

        return Theme::scope('school.auth', ['mode' => 'register'])->render();
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:40', 'regex:/^[A-Za-z0-9_.-]+$/'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'name.required' => $this->isEnglish() ? 'Choose a nickname.' : 'Придумайте нікнейм.',
            'name.min' => $this->isEnglish() ? 'Nickname must be at least 3 characters.' : 'Нікнейм має містити щонайменше 3 символи.',
            'name.max' => $this->isEnglish() ? 'Nickname must be no longer than 40 characters.' : 'Нікнейм має бути не довшим за 40 символів.',
            'name.regex' => $this->isEnglish()
                ? 'Use Latin letters, numbers, dots, dashes or underscores for the nickname.'
                : 'Нікнейм має бути латиницею: літери, цифри, крапка, дефіс або нижнє підкреслення.',
        ]);

        $nickname = $this->normalizeNickname($validated['name']);

        $user = new User();
        $user->forceFill([
            'first_name' => $nickname,
            'last_name' => '',
            'username' => $this->makeUsername($nickname),
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'super_user' => 0,
            'manage_supers' => 0,
            'permissions' => '[]',
        ]);
        $user->save();

        $this->ensureStudentProfile($user, $nickname);

        Auth::guard()->login($user);
        $request->session()->regenerate();

        return redirect()->route('courses.student.cabinet');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('courses.student.login')
            ->with('status', $this->isEnglish() ? 'You have signed out.' : 'Ви вийшли з кабінету.');
    }

    protected function ensureStudentProfile(User $user, ?string $displayName = null): StudentProfile
    {
        return StudentProfile::query()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'display_name' => $displayName ?: $user->name,
                'rank_slug' => 'newcomer',
                'xp' => 0,
                'public_gallery_enabled' => false,
            ]
        );
    }

    protected function normalizeNickname(string $nickname): string
    {
        return Str::lower(trim($nickname));
    }

    protected function makeUsername(string $nickname): string
    {
        $base = Str::slug($nickname) ?: 'student';
        $username = $base;
        $counter = 2;

        while (User::query()->where('username', $username)->exists()) {
            $username = $base . '-' . $counter;
            $counter++;
        }

        return $username;
    }

    protected function isEnglish(): bool
    {
        return request()->segment(1) === 'en';
    }
}

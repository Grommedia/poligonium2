<?php

namespace Botble\Setting\Http\Controllers;

use Botble\Base\Facades\AdminAppearance;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Language;
use Botble\Setting\Forms\AdminAppearanceSettingForm;
use Botble\Setting\Http\Requests\AdminAppearanceRequest;
use Illuminate\Support\Arr;

class AdminAppearanceSettingController extends SettingController
{
    public function index()
    {
        $this->pageTitle(trans('core/setting::setting.admin_appearance.title'));

        return AdminAppearanceSettingForm::create()->renderForm();
    }

    public function update(AdminAppearanceRequest $request): BaseHttpResponse
    {
        $localeDirectionKey = AdminAppearance::getSettingKey('locale_direction');

        $data = Arr::except($request->validated(), [$localeDirectionKey]);

        $isDemoModeEnabled = BaseHelper::hasDemoModeEnabled();

        $adminLocalDirection = $request->input($localeDirectionKey);
        $localeKey = AdminAppearance::getSettingKey('locale');
        $adminLocale = $this->normalizeAdminLocale($request->input($localeKey)) ?: $this->fallbackAdminLocale();

        if ($adminLocalDirection != setting($localeDirectionKey)) {
            session()->put('admin_locale_direction', $adminLocalDirection);
        }

        if ($adminLocale) {
            session()->put('site-locale', $adminLocale);
            app()->setLocale($adminLocale);

            if (auth()->check() && method_exists($user = auth()->user(), 'setMeta')) {
                $user->setMeta('locale', $adminLocale);
                $user->setMeta('locale_direction', $adminLocalDirection);
            }
        }

        if (! $isDemoModeEnabled) {
            $data[$localeDirectionKey] = $adminLocalDirection;
            $data[$localeKey] = $adminLocale;
        }

        $this->forceSaveSettings =  ! $isDemoModeEnabled;

        return $this->performUpdate($data);
    }

    protected function normalizeAdminLocale(mixed $locale): ?string
    {
        if (! $locale) {
            return null;
        }

        $normalizedLocale = str_replace('-', '_', mb_strtolower(trim((string) $locale)));

        foreach ($this->adminLocales() as $availableLocale => $candidates) {
            if (in_array($normalizedLocale, $candidates, true)) {
                return $availableLocale;
            }
        }

        return null;
    }

    protected function fallbackAdminLocale(): string
    {
        foreach ([setting(AdminAppearance::getSettingKey('locale')), setting('admin_appearance_locale'), app()->getLocale(), 'ru', 'en'] as $locale) {
            if ($locale = $this->normalizeAdminLocale($locale)) {
                return $locale;
            }
        }

        return 'en';
    }

    protected function adminLocales(): array
    {
        return array_filter([
            'en' => ['en', 'en_us', 'english'],
            'ru' => is_dir(lang_path('ru')) ? ['ru', 'ru_ru', 'russian'] : null,
        ]);
    }
}

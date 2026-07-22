<?php

namespace Botble\Courses\Http\Controllers;

use Botble\ACL\Models\UserMeta;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Setting\Facades\Setting;
use Illuminate\Http\Request;

class AdminLocaleController extends BaseController
{
    public function __invoke(Request $request)
    {
        $locale = $this->normalizeLocale($request->route('admin_locale') ?: $request->input('admin_locale', $request->input('locale'))) ?: $this->fallbackLocale();
        $user = $request->user();

        if ($user) {
            UserMeta::setMeta('locale', $locale, $user->getAuthIdentifier());
            UserMeta::setMeta('locale_direction', 'ltr', $user->getAuthIdentifier());
        }

        Setting::set('admin_appearance_locale', $locale);
        Setting::set('admin_appearance_locale_direction', 'ltr');
        Setting::save();

        $request->session()->put('site-locale', $locale);
        $request->session()->put('admin_locale_direction', 'ltr');
        app()->setLocale($locale);

        return redirect()->back();
    }

    protected function normalizeLocale(mixed $locale): ?string
    {
        if (! $locale) {
            return null;
        }

        $locale = trim((string) $locale);
        $normalizedLocale = str_replace('-', '_', strtolower($locale));

        $aliases = [
            'ru' => 'ru',
            'ru_ru' => 'ru',
            'russian' => 'ru',
            'en' => 'en',
            'en_us' => 'en',
            'english' => 'en',
            'uk' => 'uk',
            'uk_ua' => 'uk',
            'ua' => 'uk',
            'ukrainian' => 'uk',
        ];

        if (isset($aliases[$normalizedLocale])) {
            $locale = $aliases[$normalizedLocale];
        }

        $normalizedLocale = str_replace('-', '_', strtolower($locale));

        foreach ($this->adminLocales() as $availableLocale => $aliases) {
            if (in_array($normalizedLocale, $aliases, true)) {
                return $availableLocale;
            }
        }

        return null;
    }

    protected function fallbackLocale(): string
    {
        foreach ([setting('admin_appearance_locale'), app()->getLocale(), 'uk', 'en'] as $locale) {
            if ($locale = $this->normalizeLocale($locale)) {
                return $locale;
            }
        }

        return 'en';
    }

    protected function adminLocales(): array
    {
        return array_filter([
            'en' => ['en', 'en_us', 'english'],
            'uk' => is_dir(lang_path('uk')) ? ['uk', 'uk_ua', 'ua', 'ukrainian'] : null,
            'ru' => is_dir(lang_path('ru')) ? ['ru', 'ru_ru', 'russian'] : null,
        ]);
    }
}

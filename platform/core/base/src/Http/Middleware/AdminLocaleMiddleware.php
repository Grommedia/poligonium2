<?php

namespace Botble\Base\Http\Middleware;

use Botble\Base\Facades\AdminHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $adminPrefix = trim((string) config('core.base.general.admin_dir'), '/');
        $isAdminRequest = AdminHelper::isInAdmin(true) || ($adminPrefix && $request->is($adminPrefix, $adminPrefix . '/*'));

        if (! $isAdminRequest) {
            return $next($request);
        }

        $sessionLocale = $request->session()->get('site-locale');
        $globalLocale = setting('admin_appearance_locale', config('core.base.general.locale', config('app.locale')));
        $userLocale = null;

        if (Auth::check() && method_exists($user = Auth::user(), 'getMeta')) {
            $userLocale = $user->getMeta('locale');
        }

        $locale = null;

        foreach ([$userLocale, $sessionLocale, $globalLocale] as $candidateLocale) {
            if ($candidateLocale = $this->normalizeAdminLocale($candidateLocale)) {
                $locale = $candidateLocale;

                break;
            }
        }

        if ($locale) {
            app()->setLocale($locale);
            $request->setLocale($locale);
        }

        return $next($request);
    }

    protected function normalizeAdminLocale(mixed $locale): ?string
    {
        if (! $locale) {
            return null;
        }

        $locale = str_replace('-', '_', strtolower(trim((string) $locale)));

        $aliases = array_filter([
            'en' => ['en', 'en_us', 'english'],
            'ru' => is_dir(lang_path('ru')) ? ['ru', 'ru_ru', 'russian'] : null,
        ]);

        foreach ($aliases as $availableLocale => $candidates) {
            if (in_array($locale, $candidates, true)) {
                return $availableLocale;
            }
        }

        return null;
    }
}

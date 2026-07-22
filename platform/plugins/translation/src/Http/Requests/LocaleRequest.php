<?php

namespace Botble\Translation\Http\Requests;

use Botble\Base\Supports\Language;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class LocaleRequest extends Request
{
    protected function prepareForValidation(): void
    {
        if ($this->has('locale')) {
            $this->merge([
                'locale' => $this->normalizeLocale($this->input('locale')),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'locale' => ['required', Rule::in(Language::getLocaleKeys())],
        ];
    }

    protected function normalizeLocale(?string $locale): ?string
    {
        if (! $locale) {
            return $locale;
        }

        foreach (Language::getListLanguages() as $language) {
            if (in_array($locale, [$language[0] ?? null, $language[1] ?? null], true)) {
                return $language[0];
            }
        }

        return $locale;
    }
}

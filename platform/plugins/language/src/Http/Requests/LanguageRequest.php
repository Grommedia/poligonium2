<?php

namespace Botble\Language\Http\Requests;

use Botble\Base\Supports\Language;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class LanguageRequest extends Request
{
    protected function prepareForValidation(): void
    {
        if ($this->has('lang_locale')) {
            $this->merge([
                'lang_locale' => $this->normalizeLocale($this->input('lang_locale')),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'lang_name' => ['required', 'string', 'max:30', 'min:2'],
            'lang_code' => [
                'required',
                'string',
                Rule::in(Language::getLanguageCodes()),
            ],
            'lang_locale' => [
                'required',
                'string',
                Rule::in(Language::getLocaleKeys()),
            ],
            'lang_flag' => ['required', 'string'],
            'lang_is_rtl' => ['required', 'boolean'],
            'lang_order' => ['required', 'numeric'],
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

<?php

namespace Botble\SeoOptimizer\Http\Requests;

use Botble\Support\Http\Requests\Request;

class SeoOptimizationSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'seo_enable_structured_data' => ['nullable', 'in:0,1'],
            'seo_enable_canonical_hreflang' => ['nullable', 'in:0,1'],
            'seo_google_site_verification' => ['nullable', 'string', 'max:255'],
            'seo_bing_site_verification' => ['nullable', 'string', 'max:255'],
            'seo_organization_name' => ['nullable', 'string', 'max:255'],
            'seo_organization_url' => ['nullable', 'url', 'max:255'],
            'seo_organization_logo' => ['nullable', 'string', 'max:255'],
            'seo_organization_phone' => ['nullable', 'string', 'max:50'],
            'seo_organization_email' => ['nullable', 'email', 'max:255'],
            'seo_organization_same_as' => ['nullable', 'string', 'max:5000'],
            'seo_extra_head_html' => ['nullable', 'string', 'max:20000'],
            'seo_extra_body_html' => ['nullable', 'string', 'max:20000'],
        ];
    }
}

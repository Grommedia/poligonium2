<?php

namespace Botble\Courses\Http\Requests;

use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CoursePurchaseRequest extends Request
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'course_id' => ['required', 'exists:plg_courses,id'],
            'purchase_type' => ['required', Rule::in(['early_access', 'full', 'manual', 'subscription'])],
            'amount' => ['required', 'numeric', 'min:0'],
            'full_price' => ['nullable', 'numeric', 'min:0'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'status' => ['required', Rule::in(['pending', 'paid', 'cancelled', 'refunded'])],
            'payment_provider' => ['nullable', 'string', 'max:120'],
            'payment_reference' => ['nullable', 'string', 'max:191'],
            'provider_invoice_id' => ['nullable', 'string', 'max:191'],
            'provider_page_url' => ['nullable', 'string'],
            'provider_status' => ['nullable', 'string', 'max:60'],
        ];
    }
}

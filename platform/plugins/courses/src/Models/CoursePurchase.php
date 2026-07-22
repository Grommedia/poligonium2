<?php

namespace Botble\Courses\Models;

use Botble\ACL\Models\User;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoursePurchase extends BaseModel
{
    protected $table = 'plg_course_purchases';

    protected $fillable = [
        'user_id',
        'course_id',
        'purchase_type',
        'amount',
        'full_price',
        'discount_amount',
        'currency',
        'status',
        'payment_provider',
        'payment_reference',
        'provider_invoice_id',
        'provider_page_url',
        'provider_status',
        'provider_modified_at',
        'provider_payload',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'full_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'provider_modified_at' => 'datetime',
        'provider_payload' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id')->withDefault();
    }

    public function getStatusLabelAttribute(): string
    {
        return [
            'pending' => 'Очікує оплату',
            'paid' => 'Оплачено',
            'cancelled' => 'Скасовано',
            'refunded' => 'Повернення',
        ][$this->status] ?? (string) $this->status;
    }

    public function getPurchaseTypeLabelAttribute(): string
    {
        return [
            'early_access' => 'Ранній доступ',
            'full' => 'Повна покупка',
            'manual' => 'Ручна покупка',
            'subscription' => 'Підписка',
        ][$this->purchase_type] ?? (string) $this->purchase_type;
    }
}

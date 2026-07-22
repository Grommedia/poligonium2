<?php

namespace Botble\Courses\Support;

use Botble\Courses\Models\CourseEnrollment;
use Botble\Courses\Models\CoursePurchase;

class CourseAccessService
{
    public function grantFromPurchase(CoursePurchase $purchase, bool $incrementEarlyCounter = true): void
    {
        CourseEnrollment::query()->updateOrCreate(
            [
                'user_id' => $purchase->user_id,
                'course_id' => $purchase->course_id,
            ],
            [
                'source' => 'purchase',
                'status' => 'active',
                'starts_at' => $purchase->course->accessOpensAt() ?: now(),
                'ends_at' => null,
                'notes' => trim('Purchase #' . $purchase->id . ' / ' . $purchase->purchase_type),
            ]
        );

        if ($incrementEarlyCounter && $purchase->purchase_type === 'early_access') {
            $purchase->course()->increment('early_access_sold');
        }
    }
}

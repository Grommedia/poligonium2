<?php

namespace Botble\Courses\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Courses\Models\CoursePurchase;
use Botble\Courses\Support\CourseAccessService;
use Botble\Courses\Support\MonopayService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonopayWebhookController extends BaseController
{
    public function __invoke(Request $request, MonopayService $monopay, CourseAccessService $access): JsonResponse
    {
        $rawBody = $request->getContent();

        if (! $monopay->verifyWebhookSignature($rawBody, $request->header('X-Sign'))) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $payload = json_decode($rawBody, true);

        if (! is_array($payload) || empty($payload['invoiceId'])) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $purchase = CoursePurchase::query()
            ->where('provider_invoice_id', $payload['invoiceId'])
            ->first();

        if (! $purchase) {
            return response()->json(['message' => 'Purchase not found'], 404);
        }

        DB::transaction(function () use ($purchase, $payload, $access): void {
            $wasPaid = $purchase->status === 'paid';
            $incomingModifiedAt = ! empty($payload['modifiedDate'])
                ? Carbon::parse($payload['modifiedDate'])
                : now();

            if ($purchase->provider_modified_at && $incomingModifiedAt->lt($purchase->provider_modified_at)) {
                return;
            }

            $status = (string) ($payload['status'] ?? '');

            $purchase->forceFill([
                'provider_status' => $status,
                'provider_modified_at' => $incomingModifiedAt,
                'provider_payload' => $payload,
            ]);

            if ($status === 'success') {
                $expectedAmount = (int) round((float) $purchase->amount * 100);
                $finalAmount = (int) ($payload['finalAmount'] ?? $payload['amount'] ?? 0);

                if ($finalAmount >= $expectedAmount) {
                    $purchase->status = 'paid';
                    $purchase->save();

                    $access->grantFromPurchase($purchase, ! $wasPaid);

                    return;
                }
            }

            if (in_array($status, ['failure', 'expired', 'reversed'], true) && ! $wasPaid) {
                $purchase->status = 'cancelled';
            }

            $purchase->save();
        });

        return response()->json(['message' => 'OK']);
    }
}

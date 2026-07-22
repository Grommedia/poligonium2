<?php

namespace Botble\Courses\Support;

use Botble\Courses\Models\CoursePurchase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class MonopayService
{
    public function createInvoice(CoursePurchase $purchase): array
    {
        $token = $this->token();
        $course = $purchase->course;
        $amount = $this->amountToMinorUnits((float) $purchase->amount);
        $reference = $purchase->payment_reference ?: $this->makeReference($purchase);

        $payload = [
            'amount' => $amount,
            'ccy' => 980,
            'merchantPaymInfo' => [
                'reference' => $reference,
                'destination' => 'Polygonium School: ' . $course->name,
                'comment' => $purchase->purchase_type_label,
                'customerEmails' => [$purchase->user->email],
                'basketOrder' => [
                    [
                        'name' => Str::limit($course->name ?: 'Polygonium course', 120, ''),
                        'qty' => 1,
                        'sum' => $amount,
                        'total' => $amount,
                        'unit' => 'шт.',
                        'code' => 'course-' . $course->id,
                    ],
                ],
            ],
            'redirectUrl' => $this->redirectUrl(),
            'webHookUrl' => $this->webhookUrl(),
            'validity' => (int) config('services.monopay.validity', 3600),
            'paymentType' => 'debit',
        ];

        $response = Http::withHeaders(['X-Token' => $token])
            ->acceptJson()
            ->asJson()
            ->post($this->apiUrl() . '/api/merchant/invoice/create', $payload);

        if (! $response->successful()) {
            throw new RuntimeException('Monopay invoice error: ' . $response->body());
        }

        $data = $response->json();

        if (empty($data['invoiceId']) || empty($data['pageUrl'])) {
            throw new RuntimeException('Monopay returned invalid invoice response.');
        }

        return [
            'reference' => $reference,
            'invoice_id' => $data['invoiceId'],
            'page_url' => $data['pageUrl'],
            'raw' => $data,
        ];
    }

    public function verifyWebhookSignature(string $rawBody, ?string $signature): bool
    {
        if (! $signature) {
            return false;
        }

        $publicKey = openssl_get_publickey(base64_decode($this->publicKey()));

        if (! $publicKey) {
            return false;
        }

        return openssl_verify($rawBody, base64_decode($signature), $publicKey, OPENSSL_ALGO_SHA256) === 1;
    }

    public function publicKey(): string
    {
        return Cache::remember('monopay_public_key', now()->addDay(), function (): string {
            $response = Http::withHeaders(['X-Token' => $this->token()])
                ->acceptJson()
                ->get($this->apiUrl() . '/api/merchant/pubkey');

            if (! $response->successful()) {
                throw new RuntimeException('Monopay public key error: ' . $response->body());
            }

            $key = $response->json('key');

            if (! $key) {
                throw new RuntimeException('Monopay returned empty public key.');
            }

            return $key;
        });
    }

    protected function token(): string
    {
        $token = (string) config('services.monopay.token');

        if ($token === '') {
            throw new RuntimeException('MONOPAY_TOKEN is not configured.');
        }

        return $token;
    }

    protected function apiUrl(): string
    {
        return rtrim((string) config('services.monopay.api_url', 'https://api.monobank.ua'), '/');
    }

    protected function webhookUrl(): string
    {
        return config('services.monopay.webhook_url') ?: route('courses.monopay.webhook');
    }

    protected function redirectUrl(): string
    {
        return config('services.monopay.redirect_url') ?: route('courses.student.cabinet');
    }

    protected function amountToMinorUnits(float $amount): int
    {
        return (int) round($amount * 100);
    }

    protected function makeReference(CoursePurchase $purchase): string
    {
        return 'POL-' . $purchase->id . '-' . Str::upper(Str::random(8));
    }
}

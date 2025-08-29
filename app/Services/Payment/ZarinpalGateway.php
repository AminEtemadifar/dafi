<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;

class ZarinpalGateway implements PaymentGatewayInterface
{
	public function __construct(
		private string $merchantId,
		private bool $sandbox,
		private int $timeout = 15,
	) {}

	private function apiBase(): string
	{
        $base = $this->sandbox ? 'https://sandbox.zarinpal.com/pg/v4' : 'https://api.zarinpal.com/pg/v4';
        return $base;
	}

	private function startPayUrl(string $authority): string
	{
		$base = $this->sandbox ? 'https://sandbox.zarinpal.com/pg/StartPay/' : 'https://www.zarinpal.com/pg/StartPay/';
		return $base . $authority;
	}

	public function requestPayment(int $amount, string $callbackUrl, string $description, ?string $mobile = null, array $meta = []): array
	{
		$payload = [
			'merchant_id' => $this->merchantId,
			'amount' => $amount,
			'description' => $description,
			'callback_url' => $callbackUrl,
			'metadata' => array_filter([
				'mobile' => $mobile,
				'email' => $meta['email'] ?? null,
			]),
		];

		$response = Http::timeout($this->timeout)->asJson()->post($this->apiBase() . '/payment/request.json', $payload);
        $data =$response->json('data');
        $authority = is_array($data) ? ($data['authority'] ?? null) : null;
		if (!$response->ok() || ! $authority) {
			return [
				'redirect_url' => '',
				'authority' => '',
				'raw' => $response->json() ?? [],
			];
		}

		return [
			'redirect_url' => $this->startPayUrl($authority),
			'authority' => $authority,
			'raw' => $response->json() ?? [],
		];
	}

	public function verifyPayment(array $callbackParams, int $amount): array
	{
		$authority = $callbackParams['Authority'] ?? $callbackParams['authority'] ?? null;
		$status = $callbackParams['Status'] ?? $callbackParams['status'] ?? null;
		if (!$authority || strtolower((string)$status) !== 'ok') {
			return ['success' => false, 'ref_id' => null, 'card_pan' => null, 'raw' => ['error' => 'user canceled or invalid params']];
		}

		$payload = [
			'merchant_id' => $this->merchantId,
			'amount' => $amount,
			'authority' => $authority,
		];

		$response = Http::timeout($this->timeout)->asJson()->post($this->apiBase() . '/payment/verify.json', $payload);
		$data = $response->json('data');
		$code = is_array($data) ? ($data['code'] ?? null) : null;
		if ($response->ok() && (int)$code === 100) {
			return [
				'success' => true,
				'ref_id' => (string) ($data['ref_id'] ?? ''),
				'card_pan' => (string) ($data['card_pan'] ?? ''),
				'raw' => $response->json() ?? [],
			];
		}

		return [
			'success' => false,
			'ref_id' => null,
			'card_pan' => null,
			'raw' => $response->json() ?? [],
		];
	}
}

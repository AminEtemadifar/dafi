<?php

namespace App\Services\Payment;

interface PaymentGatewayInterface
{
	/**
	 * Initialize a payment and return data like redirect_url and authority.
	 *
	 * @param int $amount
	 * @param string $callbackUrl
	 * @param string $description
	 * @param string|null $mobile
	 * @param array $meta
	 * @return array{redirect_url:string,authority:string,raw:array}
	 */
	public function requestPayment(int $amount, string $callbackUrl, string $description, ?string $mobile = null, array $meta = []): array;

	/**
	 * Verify a payment using callback params; must return success, ref_id, card_pan, raw response.
	 *
	 * @param array $callbackParams
	 * @param int $amount
	 * @return array{success:bool,ref_id:?string,card_pan:?string,raw:array}
	 */
	public function verifyPayment(array $callbackParams, int $amount): array;
}
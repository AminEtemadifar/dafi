<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Config;

class PaymentManager
{
	public function driver(?string $name = null): PaymentGatewayInterface
	{
		$name = $name ?: Config::get('payment.default', 'zarinpal');
		switch ($name) {
			case 'zarinpal':
			default:
				return new ZarinpalGateway(
					(string) Config::get('payment.drivers.zarinpal.merchant_id', ''),
					(bool) Config::get('payment.drivers.zarinpal.sandbox', false),
					(int) Config::get('payment.drivers.zarinpal.timeout', 15)
				);
		}
	}
}

<?php

return [
	'default' => env('PAYMENT_DRIVER', 'zarinpal'),

	'amount' => (int) env('PAYMENT_AMOUNT', 100000), // in IRR

	'drivers' => [
		'zarinpal' => [
			'merchant_id' => env('ZARINPAL_MERCHANT_ID', ''),
			'sandbox' => (bool) env('ZARINPAL_SANDBOX', true),
			'timeout' => (int) env('ZARINPAL_TIMEOUT', 15),
		],
	],
];
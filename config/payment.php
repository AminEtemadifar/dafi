<?php

return [
	'default' => env('PAYMENT_DRIVER', 'zarinpal'),

	'amount' => (int) env('PAYMENT_AMOUNT', 100000), // in IRR

	'drivers' => [
		'zarinpal' => [
			'merchant_id' => env('ZARINPAL_MERCHANT_ID', 'c5f263c3-1a6c-4f5d-8f0e-cd56f9db5c26'),
			'sandbox' => (bool) env('ZARINPAL_SANDBOX', false),
			'timeout' => (int) env('ZARINPAL_TIMEOUT', 15),
		],
	],
];

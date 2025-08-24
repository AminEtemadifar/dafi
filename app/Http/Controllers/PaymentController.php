<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Submit;
use App\Models\Transaction;
use App\RequestStatusEnum;
use App\Services\Payment\PaymentManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class PaymentController extends Controller
{
	public function start(Request $request, PaymentManager $payments): RedirectResponse
	{
		$mobile = session('user_mobile');
		$name = session('user_name');
		$submit = $mobile ? Submit::where([
			'mobile' => $mobile,
			'name' => $name,
			'request_status' => RequestStatusEnum::PREPARE->value,
		])->latest('id')->first() : null;

		$amount = (int) Config::get('payment.amount', 100000);
		$callbackUrl = URL::to('/payment/callback');
		$description = 'خرید فایل موزیک';

		$driver = $payments->driver();
		$result = $driver->requestPayment($amount, $callbackUrl, $description, $mobile, ['name' => $name]);

		$tx = Transaction::create([
			'submit_id' => $submit?->id,
			'mobile' => $mobile,
			'name' => $name,
			'amount' => $amount,
			'gateway' => Config::get('payment.default', 'zarinpal'),
			'authority' => $result['authority'] ?? '',
			'status' => $result['authority'] ? 'requested' : 'failed',
			'raw_request' => [
				'amount' => $amount,
				'callback' => $callbackUrl,
				'description' => $description,
			],
			'raw_response' => $result['raw'] ?? [],
		]);

		if (!($result['redirect_url'] ?? '')) {
			return redirect()->back()->withErrors(['error' => 'خطا در اتصال به درگاه پرداخت.']);
		}

		return redirect()->away($result['redirect_url']);
	}

	public function callback(Request $request, PaymentManager $payments): RedirectResponse
	{
		$amount = (int) Config::get('payment.amount', 100000);
		$driver = $payments->driver();

		$authority = $request->input('Authority') ?: $request->input('authority');
		$tx = $authority ? Transaction::where('authority', $authority)->latest('id')->first() : null;

		$verify = $driver->verifyPayment($request->all(), $amount);

		if ($tx) {
			$tx->raw_response = $verify['raw'] ?? [];
			$tx->status = $verify['success'] ? 'success' : 'failed';
			$tx->ref_id = $verify['ref_id'] ?? null;
			$tx->card_pan = $verify['card_pan'] ?? null;
			$tx->verified_at = $verify['success'] ? now() : null;
			$tx->save();
		}

		if ($verify['success']) {
			if ($tx && $tx->submit_id) {
				$submit = Submit::find($tx->submit_id);
				if ($submit) {
					$submit->request_status = RequestStatusEnum::REQUESTED->value; // move to requested for admin to process
					$submit->save();
				}
			}
			return redirect('/')->with('status', 'پرداخت با موفقیت انجام شد. کد پیگیری: ' . ($verify['ref_id'] ?? ''));
		}

		return redirect('/')->withErrors(['error' => 'پرداخت ناموفق بود یا توسط کاربر لغو شد.']);
	}
}
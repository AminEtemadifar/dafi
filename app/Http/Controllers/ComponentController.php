<?php

namespace App\Http\Controllers;

use App\Models\Submit;
use App\Notifications\SendOtpNotification;
use App\RequestStatusEnum;
use App\Services\MusicServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ComponentController extends Controller
{
    /**
     * Load a component view
     */
    public function load($component): \Illuminate\View\View
    {
        $validComponents = ['information', 'otp', 'coupon', 'deliver'];

        if (!in_array($component, $validComponents)) {
            abort(404);
        }

        return view('components.' . $component);
    }

    /**
     * Handle information form submission
     */
    public function storeInformation(Request $request): JsonResponse
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'mobile' => 'required|string|max:20'
        ]);

        try {
            $submit = Submit::updateOrCreate(
                [
                    'mobile' => $request->mobile,
                    'name' => $request->fullname,
                    'request_status' => RequestStatusEnum::PREPARE->value,
                ]
            );

            // Save to session for later steps
            session(['user_mobile' => $submit->mobile, 'user_name' => $submit->name]);

            if (empty($submit->otp_code) && (empty($submit->otp_expires_at) || $submit->otp_expires_at < now()->subMinute())){
                // Generate OTP and expiry (e.g., 5 minutes)
                $otp = (string)random_int(1000, 9999);
                $submit->otp_code = $otp;
                $submit->otp_expires_at = now()->addMinutes(5);
                $submit->save();

                // Send SMS via notification channel
                $submit->notify(new SendOtpNotification($submit->mobile, $otp));
            }


            return response()->json([
                'success' => true,
                'message' => 'اطلاعات با موفقیت ثبت شد و کد تایید ارسال شد'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در ثبت اطلاعات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle OTP verification
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'otp' => 'required|string|size:4'
        ]);

        $otp = $request->string('otp');

        $mobile = session('user_mobile');
        $name = session('user_name');

        $submit = Submit::where('mobile', $mobile)
            ->where('otp_code', $otp)
            ->where('request_status', RequestStatusEnum::PREPARE->value)
            ->whereNotNull('otp_code')
            ->where(function ($q) {
                $q->whereNull('otp_expires_at')->orWhere('otp_expires_at', '>=', now());
            })
            ->latest('id')
            ->first();

        if ($submit) {
            $submit->mobile_verified_at = now();
            // Invalidate OTP after successful verification
            $submit->otp_code = null;
            $submit->otp_expires_at = null;
            $submit->save();

            // Keep session values
            session(['user_mobile' => $submit->mobile, 'user_name' => $submit->name]);

            return response()->json([
                'success' => true,
                'message' => 'کد تایید صحیح است'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'کد تایید نامعتبر است یا منقضی شده'
        ]);
    }

    /**
     * Handle coupon verification
     */
    public function verifyCoupon(Request $request, MusicServiceInterface $musicService): JsonResponse
    {
        $request->validate([
            'coupon' => 'required|string|max:50'
        ]);

        $coupon = $request->input('coupon');

        $mobile = session('user_mobile');
        $name = session('user_name');

        $submit = $mobile ? Submit::where([
            'mobile' => $mobile,
            'name' => $name,
            'request_status' => RequestStatusEnum::PREPARE->value,
        ])->latest('id')->first() : null;

        $musicPath = $name ? $musicService->getMusicPathForName($name) : null;

        if ($submit) {
            if ($musicPath) {
                $submit->request_status = RequestStatusEnum::DONE->value;
            } else {
                $submit->request_status = RequestStatusEnum::REQUESTED->value;
            }
            $submit->save();
        }

        return response()->json([
            'success' => true,
            'message' => $musicPath ? 'کد تخفیف معتبر است' : 'کد تخفیف معتبر است، نتیجه به‌زودی آماده می‌شود',
            'music_path' => $musicPath,
        ]);
    }
}

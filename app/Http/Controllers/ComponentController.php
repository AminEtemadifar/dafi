<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;

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
            // Create or update user
            $user = User::updateOrCreate(
                ['mobile' => $request->mobile],
                [
                    'fullname' => $request->fullname,
                    'mobile' => $request->mobile,
                    'name' => $request->fullname // Use fullname as name for now
                ]
            );

            // Store user ID in session for later use
            session(['user_id' => $user->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'اطلاعات با موفقیت ثبت شد'
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

        $otp = $request->input('otp');
        
        // Here you would typically verify OTP against database
        // For demo purposes, accept any 4-digit code
        if (is_numeric($otp)) {
            // Mark user as verified in session
            session(['otp_verified' => true]);
            
            return response()->json([
                'success' => true,
                'message' => 'کد تایید صحیح است'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'کد تایید نامعتبر است'
        ]);
    }

    /**
     * Handle coupon verification
     */
    public function verifyCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'coupon' => 'required|string|max:50'
        ]);

        $coupon = $request->input('coupon');
        
        // Here you would typically verify coupon against database
        // For demo purposes, accept any non-empty coupon
        
        // Mark coupon as verified in session
        session(['coupon_verified' => true]);
        
        return response()->json([
            'success' => true,
            'message' => 'کد تخفیف معتبر است'
        ]);
    }
}

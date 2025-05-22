<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\RedeemedCoupons;
use App\Models\User;

class RadeemedController extends Controller
{
    /**
     * Redeem coupon
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function redeemCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:255',
            'coupon_value' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/', 'max:255']
        ]);

        try {
            if (!Auth::check()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                ]);
            }

            // Check if the coupon code already exists
            $existingCoupon = RedeemedCoupons::where('coupon_code', $request->coupon_code)->first();
            if ($existingCoupon) {
                return response()->json([
                    'status' => false,
                    'message' => 'Coupon code already redeemed',
                ]);
            }

            // Create a new redeemed coupon
            $redeemedCoupon = new RedeemedCoupons();
            $redeemedCoupon->coupon_code = request('coupon_code');
            $redeemedCoupon->coupon_value = request('coupon_value');
            $redeemedCoupon->user_id = Auth::user()->id;
            $redeemedCoupon->save();

            return response()->json([
                'status' => true,
                'message' => 'Coupon redeemed successfully'              
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred during coupon redemption',
                'error' => $e->getMessage(),
            ]);
        }
    }
}

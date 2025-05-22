<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Register user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerUser(Request $request)
    {

        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255|unique:users,phone_number',
        ]);

        // Check if the user already exists
        $existingUser = User::where('phone_number', $request->phone_number)->first();
        if ($existingUser) {
            return response()->json([
                'status' => false,
                'message' => 'User already exists',
            ]);
        }
        try {
            // Create a new user
            $user = new User();
            $user->name = request('full_name');
            $user->phone_number = request('phone_number');
            $user->save();
            if ($user) {
                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->plainTextToken;
                return response()->json([
                    'status' => true,
                    'message' => 'User registered successfully',
                    'data' => [
                        'token' => $token
                    ]
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'User registration failed',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred during registration',
                'error' => $e->getMessage(),
            ]);
        }
    }
    /**
     * Login user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function loginUser(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|max:255',
        ]);

        // Check if the user exists
        $user = User::where('phone_number', $request->phone_number)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ]);
        }

        try {
            // Create a new token for the user
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->plainTextToken;
            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully',
                'data' => [
                    'token' => $token
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred during login',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get user details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserDetails()
    {
        try {
            if (!auth()->user()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                ]);
            }
            $user = User::withSum('redeemedCoupons', 'coupon_value')->where('id', auth()->user()->id)
               ->first();
            return response()->json([
                'status' => true,
                'message' => 'User details retrieved successfully',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving user details',
                'error' => $e->getMessage(),
            ]);
        }
    }
}

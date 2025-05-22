<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RadeemedController;


Route::get('/', function () {
    return 'Api Home';
});

Route::post('/auth/register', [UserController::class, 'registerUser']);
Route::post('/auth/login', [UserController::class, 'loginUser']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/redeem', [RadeemedController::class, 'redeemCoupon']);
    Route::get('/profile_info', [UserController::class, 'getUserDetails']);   
});
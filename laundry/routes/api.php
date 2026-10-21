<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,ServiceController,OrderController,PaymentController};
use App\Http\Middleware\admin;
use App\Http\Middleware\staff;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register',[AuthController::class , 'register']);
        Route::post('login',[AuthController::class , 'login']);
        Route::post('logout',[AuthController::class , 'logout'])->middleware('auth:sanctum');
    });    

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('services',ServiceController::class)->only(['index','show']);
        Route::apiResource('me/payments',PaymentController::class)->only(['index']);
        Route::get('payments/{id}/proof',[PaymentController::class,'show']);
        Route::apiResource('orders',OrderController::class)->only(['store','index']);
        Route::patch('orders/{id}/cancel',[OrderController::class, 'cancel']);
    });
    Route::middleware('auth:sanctum' , admin::class )->group(function () {
        Route::apiResource('admin/services',ServiceController::class)->except(['index','show']);
        Route::apiResource('orders',OrderController::class)->except(['store','index']);
        Route::patch('admin/payments/{id}/status',[PaymentController::class, 'update']);
        Route::patch('admin/orders/{id}/assign',[OrderController::class, 'assign']);
        Route::get('admin/orders',[OrderController::class, 'admin']);
    });
    Route::middleware('auth:sanctum' , staff::class )->group(function () {
        Route::get('staff/orders',[OrderController::class, 'staff']);
        Route::patch('staff/orders/{id}/confirm',[OrderController::class, 'confirm']);
        Route::patch('staff/orders/{id}/payment',[OrderController::class, 'payment']);
        Route::patch('staff/orders/{id}/status',[OrderController::class, 'status']);
    });
});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,ServiceController,OrderController};
use App\Http\Middleware\admin;

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
        Route::apiResource('orders',OrderController::class)->only(['store','index']);

        
    });
    Route::middleware('auth:sanctum' , admin::class )->group(function () {
        Route::apiResource('admin/services',ServiceController::class)->except(['index','show']);
        Route::apiResource('admin/services',OrderController::class)->except(['index','show']);
    });
});
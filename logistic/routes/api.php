<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,ShipmentController,ServiceController};
use App\Http\Middleware\toJson;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

    Route::prefix('v1')->group(function () {   
        Route::prefix('auth')->group(function (){
            Route::post('register', [AuthController::class, 'register']);
            Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);
        });
        Route::middleware(['auth:sanctum'])->group(function(){
            Route::apiResource('shipping-services', ServiceController::class)->only(['index','show']);
            Route::apiResource('shipments', ServiceController::class)->only(['store','show','index']);
            Route::get('me/shipments', [ShipmentController::class, 'me']);
        });
        
        Route::middleware(['auth:sanctum','role:admin'])->group(function(){
            Route::apiResource('admin/shipping-services', ServiceController::class)->except(['index','show']);
            Route::get('me/shipments', [ShipmentController::class, 'me']);
        });

    });
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,ServiceController,AccountController,BillController};
use App\Http\Middleware\checkRole;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function ()  {
    Route::get('me',[AuthController::class,'me'])->middleware('auth:sanctum');
    
    Route::prefix('auth')->group(function ()  {
    Route::post('login',[AuthController::class,'login']);
    Route::post('register',[AuthController::class,'register']);
    Route::post('logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
    });

    Route::middleware('auth:sanctum')->group(function ()  {
        Route::apiResource('services',ServiceController::class)->only('index');
        Route::apiResource('bills',BillController::class)->only(['index','show']);
        Route::apiResource('me/accounts',AccountController::class)->only('index');
        });
        Route::middleware(['auth:sanctum', checkRole::class])->group(function ()  {
            Route::apiResource('me/accounts',AccountController::class)->except('index');
            Route::apiResource('admin/bills',BillController::class)->except(['index','show']);
        Route::apiResource('admin/services',ServiceController::class)->except('index');
    });
});
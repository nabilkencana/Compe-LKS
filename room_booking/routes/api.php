<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,BracnhController,RoomController};
use App\Http\Middleware\Admin;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
     
    Route::prefix('auth')->group(function () {
        Route::post('login' , [AuthController::class , 'login']);
        Route::post('logout' , [AuthController::class , 'logout'])->middleware('auth:sanctum');
        });
        
        Route::middleware('auth:sanctum',Admin::class)->group(function () {
            Route::apiResource('branch', BracnhController::class)->except('index');
            Route::apiResource('room', RoomController::class)->except('index');
        });
            
    Route::apiResource('branch', BracnhController::class)->only('index');
       Route::apiResource('room', RoomController::class)->only('index');
});
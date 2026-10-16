<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\PaymentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/v1')->group(function () {    
    Route::post('/auth/register' , [AuthController::class , 'register']);
    Route::post('/auth/login' , [AuthController::class , 'login']);
    
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/me' , [AuthController::class , 'me']);
        
        Route::get('/services' , [ServiceController::class , 'index']);
        Route::post('/admin/services/{id}' , [ServiceController::class , 'update']);
        
        Route::get('/me/accounts', [AccountController::class, 'index']);
        Route::get('/me/accounts/{id}', [AccountController::class, 'show']);

        Route::get('/bills', [BillController::class, 'index']);
        Route::get('/bills/{id}', [BillController::class, 'show']);
        
        Route::get('/bills/{id}/payment', [PaymentController::class, 'index']); 
        Route::get('/me/payment', [PaymentController::class, 'me']); 
    });

        Route::middleware(['auth:sanctum' , 'role:admin'])->group(function () {
                Route::post('/admin/services' , [ServiceController::class , 'store']);

        });
});

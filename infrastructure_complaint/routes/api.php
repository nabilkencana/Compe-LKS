<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,CategoryController,RoomController,ComplaintController,AdminController};

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
     
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class , 'login']);
        Route::post('register', [AuthController::class , 'register']);
        Route::post('logout', [AuthController::class , 'logout'])->middleware(['auth:sanctum']);
    });
    
    Route::middleware('auth:sanctum')->group(function () {
       Route::post('complaints', [ComplaintController::class, 'store']);
       Route::apiResource('complaint-categories' , CategoryController::class);
        Route::apiResource('rooms' , RoomController::class);

        Route::post('complaints', [ComplaintController::class, 'store']);
        Route::get('me/complaints', [ComplaintController::class, 'mine']);
        Route::get('complaints/{complaint}', [ComplaintController::class, 'show']);
    });
    
      Route::middleware(['auth:sanctum','role:admin'])->prefix('admin')->group(function () {
            Route::get('complaints', [AdminController::class, 'index']);
            Route::patch('complaints/{complaint}/accept', [AdminController::class, 'accept']);
            Route::patch('complaints/{complaint}/reject', [AdminController::class, 'reject']);
        });
});
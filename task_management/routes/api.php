<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,TaskController};
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function (){
    Route::post('login', [AuthController::class , 'login']);
    Route::post('register', [AuthController::class , 'register']);
    Route::post('logout', [AuthController::class , 'logout'])->middleware('auth:sanctum');
});
Route::middleware('auth:sanctum')->group(function () {
    
    Route::apiResource('tasks' ,TaskController::class);
});
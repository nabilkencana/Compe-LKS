<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController , CategoryController , MenuController};
use App\Http\Middleware\Admin;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
   Route::post('register' , [AuthController::class , 'register']); 
   Route::post('login' , [AuthController::class , 'login']); 
   Route::post('logout' , [AuthController::class , 'logout'])->middleware('auth:sanctum'); 
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('categories' , CategoryController::class)->only('index');
    Route::apiResource('menus' , MenuController::class)->only('index');
});

Route::middleware('auth:sanctum' , Admin::class)->group(function () {
    Route::apiResource('categories' , CategoryController::class)->except('index');
    Route::apiResource('menus' , MenuController::class)->except('index');
});
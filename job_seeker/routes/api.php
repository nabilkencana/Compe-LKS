<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobApplicationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/v1')->group(function () {
    
    Route::post('/auth/register',[AuthController::class , 'register']);
    Route::post('/auth/login',[AuthController::class , 'login']);
    Route::get('/jobs',[JobController::class , 'index']);
    Route::post('/jobs/{id}',[JobController::class , 'show']);
    
    Route::middleware(['auth:sanctum'])->group(function ()  {
        Route::get('/me/profile',[ProfileController::class , 'show']);
        Route::patch('/me/profile',[ProfileController::class , 'update']);
        
        // Route::get('/me/resumes',[ProfileController::class , 'show']);
        Route::get('/me/resumes/{id}/download',[ProfileController::class , 'download']);
        Route::delete('/me/resumes/{id}',[ProfileController::class , 'delete']);

        Route::post('/jobs/{id}/applications',[JobApplicationController::class , 'store']);
        Route::get('/me/applications',[JobApplicationController::class , 'index']);
        Route::get('/me/applications/{id}',[JobApplicationController::class , 'show']);
        
        Route::post('/jobs',[JobController::class , 'store']);
        Route::patch('/jobs/{id}',[JobController::class , 'show']);
        Route::delete('/jobs/{id}',[JobController::class , 'destroy']);

        Route::get('/admin/application',[JobController::class , 'index']);
        Route::patch('/admin/application/{id}/status',[JobController::class , 'update']);
    });

});

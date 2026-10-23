<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, CategoryController, RoomController, ComplaintController, AdminController, HistoryController, ReportController, TicketController};

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('complaints', [ComplaintController::class, 'store']);
        Route::apiResource('complaint-categories', CategoryController::class);
        Route::apiResource('rooms', RoomController::class);

        Route::post('complaints', [ComplaintController::class, 'store']);
        Route::get('me/complaints', [ComplaintController::class, 'mine']);
        Route::get('complaints/{complaint}', [ComplaintController::class, 'show']);

        Route::get('tickets/{tickets}', [TicketController::class, 'show']);
        Route::patch('tickets/{tickets}/histories', [HistoryController::class, 'show']);
    });

    Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('complaints', [AdminController::class, 'index']);
        Route::patch('complaints/{complaint}', [AdminController::class, 'update']);
        Route::delete('complaints/{complaint}', [AdminController::class, 'destroy']);
        Route::patch('complaints/{complaint}/accept', [AdminController::class, 'accept']);
        Route::patch('complaints/{complaint}/reject', [AdminController::class, 'reject']);
        Route::patch('complaints/{complaint}/tickets', [TicketController::class, 'assign']);

        Route::get('reports/complaints/summary' , [ReportController::class , 'complaintSummary']);
        Route::get('reports/complaints/by-category' , [ReportController::class , 'complaintByCategory']);
        Route::get('reports/complaints/by-room' , [ReportController::class , 'complaintByRoom']);
        Route::get('reports/complaints/trends' , [ReportController::class , 'complaintTrends']);
        Route::get('reports/tickets/resolution' , [ReportController::class , 'ticketResolution']);
    });

    Route::middleware(['auth:sanctum', 'role:staff'])->group(function () {
        Route::patch('tickets/{tickets}/start', [TicketController::class, 'start']);
        Route::patch('tickets/{tickets}/resolve', [TicketController::class, 'resolve']);
        Route::get('tickets', [TicketController::class, 'all']);
        Route::get('me/tickets', [TicketController::class, 'index']);
    });

    Route::middleware(['auth:sanctum', 'role:staff,admin'])->group(function () {
        Route::post('tickets/{tickets}/histories', [HistoryController::class, 'histories']);
    });
});

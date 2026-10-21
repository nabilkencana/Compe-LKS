<?php

use App\Http\Controllers\AdminShipmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\StaffShipmentController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('shipping-services', [ServiceController::class, 'index']);
        Route::get('shipping-services/{shippingService}', [ServiceController::class, 'show']);
        Route::get('shipments', [ShipmentController::class, 'index']);
        Route::post('shipments', [ShipmentController::class, 'store'])->middleware('role:customer');
        Route::get('me/shipments', [ShipmentController::class, 'me'])->middleware('role:customer');
        Route::get('shipments/{shipment}', [ShipmentController::class, 'show']);
        Route::patch('shipments/{shipment}/cancel', [ShipmentController::class, 'cancel'])->middleware('role:customer');
        Route::get('shipments/{shipment}/tracking', [TrackingController::class, 'byId']);
        Route::get('tracking/{trackingNumber}', [TrackingController::class, 'show']);
        Route::post('shipments/{shipment}/payments', [PaymentController::class, 'store'])->middleware('role:customer');

        Route::middleware('role:admin')->prefix('admin')->group(function () {
            Route::post('shipping-services', [ServiceController::class, 'store']);
            Route::patch('shipping-services/{id}', [ServiceController::class, 'update']);
            Route::delete('shipping-services/{shippingService}', [ServiceController::class, 'destroy']);
            Route::patch('payments/{payment}/verify', [PaymentController::class, 'verify']);
            Route::patch('shipments/{shipment}/assign-courier', [AdminShipmentController::class, 'assignCourier']);
        });

        Route::middleware('role:staff')->prefix('staff')->group(function () {
            Route::patch('shipments/{shipment}/confirm', [StaffShipmentController::class, 'confirm']);
        });

        Route::middleware('role:courier')->prefix('courier')->group(function () {
            Route::get('shipments', [CourierController::class, 'index']);
            Route::patch('shipments/{shipment}/status', [CourierController::class, 'updateStatus']);
            Route::post('shipments/{shipment}/proof', [CourierController::class, 'proof']);
        });
    });
});

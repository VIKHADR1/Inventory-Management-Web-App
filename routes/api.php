<?php

use App\Http\Controllers\API\AdminDashboardController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\HistoryController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ServiceTeamController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);

        Route::get('dashboard/stats', [AdminDashboardController::class, 'stats']);

        Route::apiResource('products', ProductController::class);
        Route::apiResource('orders', OrderController::class);
        Route::apiResource('customers', CustomerController::class);
        Route::apiResource('service-teams', ServiceTeamController::class);
        Route::apiResource('employees', EmployeeController::class);

        Route::get('history', [HistoryController::class, 'index']);
        Route::post('history', [HistoryController::class, 'store']);
        Route::get('history/{historyEntry}', [HistoryController::class, 'show']);
    });
});

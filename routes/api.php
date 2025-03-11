<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\AuthController;
use App\Http\Controllers\Api\V1\Admin\UserController;
use App\Http\Controllers\Api\V1\Admin\DashboardController;
use App\Http\Controllers\Api\V1\Admin\CategorieController;
use App\Http\Controllers\Api\V1\Admin\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Public routes
Route::prefix('v1')->group(function () {
    Route::prefix('admin')->group(function () {

        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        // Protected routes
        Route::middleware('auth:sanctum')->group(function () {

            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);

            // Dashboard Routes
            Route::get('dashboard', [DashboardController::class, 'index']);

            // Product Routes
            Route::apiResource('products', ProductController::class);

            // Category Routes
            Route::apiResource('categories', CategorieController::class);

            // User Routes
            Route::apiResource('users', UserController::class);


            // Other protected routes go here
        });
    });
});

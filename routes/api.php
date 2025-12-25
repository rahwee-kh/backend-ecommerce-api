<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/




Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware(['auth:api', 'admin'])->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::apiResource('products', ProductController::class);
        Route::apiResource('users', UserController::class);
        Route::apiResource('customers', CustomerController::class);
        Route::get('orders', [OrderController::class, 'index']);
        Route::get('orders/statuses', [OrderController::class, 'getStatuses']);
        Route::post('orders/change-status/{order}/{status}', [OrderController::class, 'changeStatus']);
        Route::get('orders/{order}', [OrderController::class, 'show']);
        
    });

});



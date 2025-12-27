<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;

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
        Route::get('/countries', [CustomerController::class, 'countries']);
        Route::get('orders', [OrderController::class, 'index']);
        Route::get('orders/statuses', [OrderController::class, 'getStatuses']);
        Route::post('orders/change-status/{order}/{status}', [OrderController::class, 'changeStatus']);
        Route::get('orders/{order}', [OrderController::class, 'show']);


        // Dashboard Routes
        Route::get('/dashboard/customers-count', [DashboardController::class, 'activeCustomers']);
        Route::get('/dashboard/products-count', [DashboardController::class, 'activeProducts']);
        Route::get('/dashboard/orders-count', [DashboardController::class, 'paidOrders']);
        Route::get('/dashboard/income-amount', [DashboardController::class, 'totalIncome']);
        Route::get('/dashboard/orders-by-country', [DashboardController::class, 'ordersByCountry']);
        Route::get('/dashboard/latest-customers', [DashboardController::class, 'latestCustomers']);
        Route::get('/dashboard/latest-orders', [DashboardController::class, 'latestOrders']);
        
    });

});



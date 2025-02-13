<?php

use Illuminate\Support\Facades\Route;
use Dinesh\Magento\App\Http\Controllers\OrdersController;
use Dinesh\Magento\App\Http\Controllers\CustomersController;
use Dinesh\Magento\App\Http\Controllers\ProductsController;
Route::prefix('api')->group(function () {

    Route::prefix('magento')->group(function () {

        Route::prefix('v1')->group(function () {

            Route::prefix('customer')->group(function () {

                Route::get('/live', [CustomersController::class, 'live']);
                Route::get('/list', [CustomersController::class, 'index']);
                Route::get('/get', [CustomersController::class, 'get']);
                
            });

            Route::prefix('order')->group(function () {

                Route::get('/live', [OrdersController::class, 'live']);
                Route::get('/list', [OrdersController::class, 'index']);
                Route::get('/get', [OrdersController::class, 'get']);
                
            });

            Route::prefix('product')->group(function () {

                Route::get('/live', [ProductsController::class, 'live']);
                Route::get('/list', [ProductsController::class, 'index']);
                Route::get('/get', [ProductsController::class, 'get']);

            });

        });

    });

});

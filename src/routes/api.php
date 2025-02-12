<?php

use Illuminate\Support\Facades\Route;
use Dinesh\Magento\App\Http\Controllers\OrdersController;
use Dinesh\Magento\App\Http\Controllers\StaffsController;
use Dinesh\Magento\App\Http\Controllers\CustomersController;
use Dinesh\Magento\App\Http\Controllers\CompaniesController;
use Dinesh\Magento\App\Http\Controllers\ProductsController;
use Dinesh\Magento\App\Http\Controllers\WebhooksController;
Route::prefix('api')->group(function () {

    Route::prefix('magento')->group(function () {

        Route::prefix('v1')->group(function () {

            /*
             * Only needed when we need to setup new company
             */
            //Route::get('/token', [TokensController::class, 'getToken']);

            Route::prefix('customer')->group(function () {

                Route::get('/live', [CustomersController::class, 'live']);

                Route::get('/list', [CustomersController::class, 'index']);
                Route::get('/search', [CustomersController::class, 'search']);
                Route::get('/listdetail', [CustomersController::class, 'getCustomers']);
                Route::get('/updatetags', [CustomersController::class, 'updateTags']);

                Route::get('/create', [CustomersController::class, 'createCustomerDetail']);
                Route::get('/get', [CustomersController::class, 'getCustomerDetail']);
                Route::get('/update', [CustomersController::class, 'updateCustomerDetail']);

            });

            Route::prefix('order')->group(function () {

                Route::get('/live', [OrdersController::class, 'live']);
                Route::get('/list', [OrdersController::class, 'index']);
                Route::get('/listdetail', [OrdersController::class, 'getOrdersDetail']);
                Route::get('/get', [OrdersController::class, 'getOrderDetail']);
                Route::get('/update', [OrdersController::class, 'updateOrder']);

            });

            Route::prefix('product')->group(function () {

                Route::get('/live', [ProductsController::class, 'live']);
                Route::get('/list', [ProductsController::class, 'index']);
                Route::get('/get', [ProductsController::class, 'getProductDetail']);

            });

        });

    });

});

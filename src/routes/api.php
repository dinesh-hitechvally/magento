<?php

use Illuminate\Support\Facades\Route;
use Dinesh\Magento\App\Http\Controllers\SitesController;
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

            Route::prefix('staff')->group(function () {

                Route::get('/live', [StaffsController::class, 'live']);
                Route::get('/list', [StaffsController::class, 'index']);
                Route::get('/listdetail', [StaffsController::class, 'getStaffs']);

                Route::get('/get', [StaffsController::class, 'getStaffDetail']);

            });

            Route::prefix('order')->group(function () {

                Route::get('/live', [OrdersController::class, 'live']);
                Route::get('/list', [OrdersController::class, 'index']);
                Route::get('/listdetail', [OrdersController::class, 'getOrdersDetail']);
                Route::get('/get', [OrdersController::class, 'getOrderDetail']);
                Route::get('/update', [OrdersController::class, 'updateOrder']);

            });

            Route::prefix('company')->group(function () {

                Route::get('/status/{companyID}', [CompaniesController::class, 'getStatus']);
                Route::get('/detail/{companyID}', [CompaniesController::class, 'getCompany']);
                
            });

            Route::prefix('site')->group(function () {

                Route::get('/live', [SitesController::class, 'live']);
                Route::get('/list', [SitesController::class, 'index']);
                Route::get('/listdetail', [SitesController::class, 'getSitesDetail']);
                Route::get('/get', [SitesController::class, 'getSiteDetail']);

            });

            Route::prefix('product')->group(function () {

                Route::get('/live', [ProductsController::class, 'live']);
                Route::get('/list', [ProductsController::class, 'index']);
                Route::get('/get', [ProductsController::class, 'getProductDetail']);

            });

            Route::prefix('webhook')->group(function () {

                Route::get('/all', [WebhooksController::class, 'Index']);
                Route::get('/create', [WebhooksController::class, 'create']);
                Route::get('/update', [WebhooksController::class, 'update']);

                Route::post('/staff/{companyID}', [WebhooksController::class, 'getStaff']);
                Route::post('/order/{companyID}', [WebhooksController::class, 'getOrder']);
                Route::post('/product/{companyID}', [WebhooksController::class, 'getProduct']);
                Route::post('/customer/{companyID}', [WebhooksController::class, 'getCustomer']);
                Route::post('/delcustomer/{companyID}', [WebhooksController::class, 'delCustomer']);
                
            });

        });

    });

});

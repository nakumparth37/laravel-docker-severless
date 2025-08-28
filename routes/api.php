<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\User;
use App\Http\Controllers\API\V1\ApiAuthController as V1ApiAuthController;
use App\Http\Controllers\API\V1\ProductController as V1ProductController;
use App\Http\Controllers\API\V2\ApiAuthController as V2ApiAuthController;
use App\Http\Controllers\API\V2\ProductController as V2ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Version 1 Routes
Route::prefix('v1')->group(function () {
    Route::post('login', [V1ApiAuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('logout', [V1ApiAuthController::class, 'logout']);
        Route::controller(V1ProductController::class)->group(function() {
            Route::prefix('admin')->group(function () {
                    Route::post('/addNewProduct', 'addNewProduct');
                    Route::post('/updateProduct/{id}', 'updateProduct');
                    Route::post('/deleteFile', 'deleteFolderAndFiles');
                    Route::delete('/deleteProduct/{id}', 'deleteProductData');
                    Route::get('/allProduct', 'getAllProducts');
                });
                Route::prefix('seller')->group(function () {
                    Route::post('/addNewProduct', 'addNewProduct');
                    Route::post('/updateProduct/{id}', 'updateProduct');
                    Route::post('/deleteFile', 'deleteFolderAndFiles');
                    Route::delete('/deleteProduct/{id}', 'deleteProductData');
                });
            })->middleware('CheckApiRole:admin,seller');
    });
});


// Version 2 Routes
Route::prefix('v2')->group(function () {
    Route::post('login', [V2ApiAuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('logout', [V2ApiAuthController::class, 'logout']);
        Route::controller(V2ProductController::class)->group(function() {
            Route::prefix('admin')->group(function () {
                    Route::post('/addNewProduct', 'addNewProduct');
                    Route::post('/updateProduct/{id}', 'updateProduct');
                    Route::post('/deleteFile', 'deleteFolderAndFiles');
                    Route::delete('/deleteProduct/{id}', 'deleteProductData');
                    Route::get('/allProduct', 'getAllProducts');
                });

                Route::prefix('seller')->group(function () {
                    Route::post('/addNewProduct', 'addNewProduct');
                    Route::post('/updateProduct/{id}', 'updateProduct');
                    Route::post('/deleteFile', 'deleteFolderAndFiles');
                    Route::delete('/deleteProduct/{id}', 'deleteProductData');
                    Route::get('/allProduct', 'getAllProducts');
                });

                Route::get('/allProduct', 'getAllProducts');
                Route::post('/updateProduct/{id}', 'updateProduct');
            });
    });
});


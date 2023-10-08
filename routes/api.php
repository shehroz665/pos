<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Category\ProductCategoryController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Size\SizeController;
use App\Http\Controllers\Invoice\InvoiceController;
use App\Http\Controllers\Statistics\StatisticsController;
Route::post('/login',[AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::prefix('productcategory')->middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [ProductCategoryController::class, 'index']);
        Route::get('/archive', [ProductCategoryController::class, 'archive']);
        Route::post('/add', [ProductCategoryController::class, 'store']);
        Route::post('/update/{id}', [ProductCategoryController::class, 'update']);
        Route::post('/changeStatus/{id}', [ProductCategoryController::class, 'changeStatus']);
        Route::post('/restoreOrDelete/{id}', [ProductCategoryController::class, 'restoreOrDelete']);
        Route::delete('/destory/{id}', [ProductCategoryController::class, 'destory']);
        Route::get('/{id}', [ProductCategoryController::class, 'edit']);
    });
    Route::prefix('supplier')->middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [SupplierController::class, 'index']);
        Route::get('/archive', [SupplierController::class, 'archive']);
        Route::post('/add', [SupplierController::class, 'store']);
        Route::post('/update/{id}', [SupplierController::class, 'update']);
        Route::post('/changeStatus/{id}', [SupplierController::class, 'changeStatus']);
        Route::post('/restoreOrDelete/{id}', [SupplierController::class, 'restoreOrDelete']);
        Route::delete('/destory/{id}', [SupplierController::class, 'destory']);
        Route::get('/{id}', [SupplierController::class, 'edit']);
    });
    Route::prefix('product')->middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/availableProducts', [ProductController::class, 'availableProducts']);
        Route::get('/outOfStock', [ProductController::class, 'outOfStock']);
        Route::get('/archive', [ProductController::class, 'archive']);
        Route::get('/dropdown', [ProductController::class, 'dropdown']);
        Route::post('/add', [ProductController::class, 'store']);
        Route::post('/update/{id}', [ProductController::class, 'update']);
        Route::post('/changeStatus/{id}', [ProductController::class, 'changeStatus']);
        Route::post('/restoreOrDelete/{id}', [ProductController::class, 'restoreOrDelete']);
        Route::delete('/destory/{id}', [ProductController::class, 'destory']);
        Route::get('/{id}', [ProductController::class, 'edit']);
    });
    Route::prefix('size')->middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [SizeController::class, 'index']);
    });
    Route::prefix('invoice')->middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [InvoiceController::class, 'index']);
        Route::get('/credit', [InvoiceController::class, 'credit']);
        Route::post('/update/{id}', [InvoiceController::class, 'update']);
        Route::post('/add', [InvoiceController::class, 'store']);
        Route::get('/sales', [InvoiceController::class, 'sales']);
        Route::get('/{id}', [InvoiceController::class, 'edit']);
    });
    Route::prefix('statistics')->middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [StatisticsController::class, 'index']);
        Route::get('/getWeeklyStatistics', [StatisticsController::class, 'getWeeklyStatistics']);
    });
});
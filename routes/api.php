<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Category\ProductCategoryController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\Product\ProductController;
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
        Route::get('/dropdown', [ProductController::class, 'dropdown']);
    });
});
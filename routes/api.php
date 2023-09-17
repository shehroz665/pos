<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Category\ProductCategoryController;
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
});
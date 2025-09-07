<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/user/{id}', [UserController::class, 'show']);
Route::get('/stores/{slug}', [StoreController::class, 'show']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/stores', [StoreController::class, 'store']);


    Route::post('/products', [ProductController::class, 'store']);

    Route::post('/orders', [OrderController::class, 'order']);
});

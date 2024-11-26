<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BiodataController;
use App\Http\Controllers\API\testController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\CommentsController;

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

Route::prefix('v1')->middleware('cors')->group(function () {
    Route::get('/test', [BiodataController::class, 'index']);
    Route::post('/daftar', [BiodataController::class, 'daftar']);
    Route::get('/test123', [testController::class, 'dashboard']);

    Route::prefix('auth')->group(function(){
        Route::post('/register',[AuthController::class, 'register']);
        Route::post('/login',[AuthController::class, 'login']);
        Route::get('/me',[AuthController::class, 'getUser'])->middleware('auth:api');
        Route::post('/logout',[AuthController::class, 'logout'])->middleware('auth:api');
        Route::post('/generate-otp-code',[AuthController::class, 'generateOtp'])->middleware('auth:api');
        Route::post('/verification-account',[AuthController::class, 'verifikasi'])->middleware('auth:api');
    });

    Route::post('/profile', [ProfileController::class, 'updatecreate'])->middleware('auth:api');
    Route::post('/comments/{news_id}',[CommentsController::class, 'updatecreate'])->middleware(['auth:api','isverified']);

    Route::apiResource('category', CategoryController::class);
    Route::apiResource('news', NewsController::class);
});

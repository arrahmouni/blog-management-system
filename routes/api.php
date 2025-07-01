<?php

use Illuminate\Support\Facades\Route;
use app\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;

Route::controller(AuthController::class)->group(function () {
    Route::post('register'              , 'register');
    Route::post('login'                 , 'login');
    Route::post('logout'                , 'logout')->middleware('auth:sanctum');
    Route::post('forgot-password'       , 'sendResetPasswordLink')->middleware(['throttle:3,1']);
    Route::post('reset-password'        , 'resetPassword');
});

Route::controller(CategoryController::class)->middleware(['auth:sanctum', 'active.admin'])->prefix('category')->group(function () {
    Route::get('/'              , 'index');
    Route::get('{category}'     , 'show');
    Route::post('/'             , 'store');
    Route::put('{category}'     , 'update');
    Route::delete('{category}'  , 'destroy');
});

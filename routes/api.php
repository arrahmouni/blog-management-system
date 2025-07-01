<?php

use Illuminate\Support\Facades\Route;
use app\Http\Controllers\Api\AuthController;

Route::controller(AuthController::class)->group(function () {
    Route::post('register'              , 'register');
    Route::post('login'                 , 'login');
    Route::post('logout'                , 'logout')->middleware('auth:sanctum');
    Route::post('forgot-password'       , 'sendResetPasswordLink')->middleware(['throttle:3,1']);
    Route::post('reset-password'        , 'resetPassword');
});

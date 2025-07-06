<?php

use Illuminate\Support\Facades\Route;
use app\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\PostController;

Route::controller(AuthController::class)->group(function () {
    Route::post('register'              , 'register');
    Route::post('admin/login'           , 'loginToAdminPanel');
    Route::post('login'                 , 'login');
    Route::post('logout'                , 'logout')->middleware('auth:sanctum');
    Route::post('forgot-password'       , 'sendResetPasswordLink')->middleware(['throttle:3,1']);
    Route::post('reset-password'        , 'resetPassword');
    Route::get('user'                   , 'showUserInfo')->middleware('auth:sanctum');
});

// Only Admin Can Access
Route::controller(CategoryController::class)->middleware(['auth:sanctum'])->prefix('category')->group(function () {
    Route::get('/'                      , 'index');
    Route::get('{category}'             , 'show');
    Route::post('/'                     , 'store');
    Route::post('{category}'            , 'update');
    Route::delete('{category}'          , 'destroy');
    Route::post('{category}/restore'    , 'restore');
    Route::delete('{category}/force'    , 'forceDelete');
    Route::get('{id}/logs'              , 'logs');
});

Route::controller(PostController::class)->middleware(['auth:sanctum'])->prefix('post')->group(function () {
    Route::get('/'                  , 'index');
    Route::get('{post}'             , 'show');
    Route::post('/'                 , 'store');
    Route::post('{post}'            , 'update');
    Route::delete('{post}'          , 'destroy');
    Route::get('{id}/logs'          , 'logs');
    Route::put('/{post}/approve'    , 'approve');
    Route::post('{post}/restore'    , 'restore');
    Route::delete('{post}/force'    , 'forceDelete');
});

Route::controller(CommentController::class)->middleware(['auth:sanctum'])->group(function () {
    Route::prefix('post/{post}/comment')->group(function () {
        Route::get('/'              , 'index');
        Route::get('{comment}'      , 'show');
        Route::post('/'             , 'store');
    });

    Route::put('comment/{comment}/accept', 'approve');
    Route::delete('comment/{comment}'    , 'destroy');
});

Route::controller(HomeController::class)->group(function () {
    Route::get('posts-list'           , 'posts');
    Route::get('post-details/{slug}'  , 'PostDetails');
    Route::get('post-comments/{post}' , 'postComments');
});

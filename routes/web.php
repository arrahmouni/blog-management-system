<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/admin', 'admin');

Route::get('/admin/{pathMatch}', function () {
    return view('admin');
})->where('pathMatch', '.*');

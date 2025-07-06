<?php

use Illuminate\Support\Facades\Route;

Route::get('/admin/{pathMatch}', function () {
    return view('admin');
})->where('pathMatch', '.*');

Route::view('/{pathMatch}', 'home')->where('pathMatch', '.*');

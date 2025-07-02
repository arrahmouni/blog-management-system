<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

if(!function_exists('isProd')){
    function isProd(){
        return app()->environment('production');
    }
}

if(!function_exists('isDev')){
    function isDev(){
        return app()->environment('local') || app()->environment('development') || app()->environment('staging');
    }
}

if(!function_exists('debugEnabled')){
    function debugEnabled(){
        return config('app.debug');
    }
}

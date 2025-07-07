<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['web', 'auth:sanctum']]);
Broadcast::routes(['middleware' => ['web', 'auth:sanctum']]);
Broadcast::routes(['middleware' => ['web', 'auth']]);

Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

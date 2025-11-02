<?php

use App\Enums\Roles\Permissions;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('/logout', 'logout');
        Route::get('{id}', 'getById');
    });
});

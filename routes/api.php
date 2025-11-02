<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [UserController::class, 'login']);

Route::controller(UserController::class)->middleware('auth:sanctum')->prefix('users')->group(function () {
    Route::get('/logout', 'logout');
    Route::get('{id}', 'getById');
});

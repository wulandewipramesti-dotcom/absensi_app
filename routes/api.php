<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PresenceApiController;
use App\Http\Controllers\PresenceDetailApiController;
use App\Http\Controllers\AuthApiController;

Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthApiController::class, 'profile']);
    Route::post('/logout', [AuthApiController::class, 'logout']);

});

Route::apiResource('presence', PresenceApiController::class);
Route::apiResource('presence-detail', PresenceDetailApiController::class);
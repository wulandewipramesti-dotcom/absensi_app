<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PresenceApiController;
use App\Http\Controllers\PresenceDetailApiController;

Route::apiResource('presence', PresenceApiController::class);
Route::apiResource('presence-detail', PresenceDetailApiController::class);


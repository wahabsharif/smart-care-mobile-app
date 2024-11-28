<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::middleware('api')->group(function () {
    Route::apiResource('users', UserController::class);
});


<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->group(function () {
    // Chat routes
    Route::post('/chats', [ChatController::class, 'create']);
    Route::get('/chats', [ChatController::class, 'getChats']);

    // Message routes
    Route::post('/chats/{chatId}/messages', [MessageController::class, 'store']);
    Route::get('/chats/{chatId}/messages', [MessageController::class, 'getMessages']);
});

Route::post('/login', [UserController::class, 'login']);



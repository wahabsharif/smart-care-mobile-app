<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;

// Public routes
Route::post('/user/login', [UserController::class, 'login'])->name('login');

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    // User CRUD routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');      // Get all users
    Route::post('/users', [UserController::class, 'store'])->name('users.store');     // Create a user
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show'); // Get user details
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update'); // Update user
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy'); // Delete user

    // Chat routes
    Route::post('/chats', [ChatController::class, 'create']);
    Route::get('/chats', [ChatController::class, 'getChats']);

    // Message routes
    Route::post('/chats/{chatId}/messages', [MessageController::class, 'store']);
    Route::get('/chats/{chatId}/messages', [MessageController::class, 'getMessages']);
});

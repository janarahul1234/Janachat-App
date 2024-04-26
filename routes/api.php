<?php

use App\core\Route;
use App\Controllers\UserController;
use App\Controllers\ChatController;

// Publis APIs
Route::post('/api/users/login', [UserController::class, 'login']);

Route::post('/api/users/register', [UserController::class, 'register']);

// Protected APIs
Route::get('/api/users/logout', [UserController::class, 'logout']);

Route::get('/api/users/status', [UserController::class, 'status']);

Route::get('/api/users/uuid/{uuid}', [UserController::class, 'getUserData']);

Route::get('/api/users/friends', [UserController::class, 'getFriends']);

Route::post('/api/users/search', [UserController::class, 'getUserInfo']);

Route::post('/api/chats/send', [ChatController::class, 'sendMessage']);

Route::post('/api/chats/receive', [ChatController::class,'getMessages']);
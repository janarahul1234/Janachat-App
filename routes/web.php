<?php

use App\core\Route;
use App\Controllers\PageController;

// Public Routes
Route::get('/login', [PageController::class, 'login'])->name('login');

Route::get('/register', [PageController::class, 'register'])->name('register');

// Protected Routes
Route::get('/', [PageController::class])->name('chat');
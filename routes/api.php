<?php

use App\Http\Controllers\AuthController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('/check-auth', [AuthController::class, 'checkAuth']);

Route::middleware(['auth:api', 'jwt.cookie'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    // Add your protected routes here
    Route::get('protected', [ProtectedController::class, 'index']);
});

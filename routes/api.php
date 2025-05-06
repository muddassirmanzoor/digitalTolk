<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TranslationController;
use App\Http\Controllers\AuthController;

Route::post('register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/translations', [TranslationController::class, 'index']);
    Route::post('/translations', [TranslationController::class, 'store']);
    Route::get('/translations/{translation}', [TranslationController::class, 'show']);
    Route::put('/translations/{translation}', [TranslationController::class, 'update']);
    Route::get('/export', [TranslationController::class, 'export']);
});



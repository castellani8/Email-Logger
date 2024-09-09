<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SuccessfulEmailController;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/store', [SuccessfulEmailController::class, 'store']);
    Route::put('/update/{id}', [SuccessfulEmailController::class, 'update']);
    Route::delete('/deleteById/{id}', [SuccessfulEmailController::class, 'deleteById']);
    Route::get('/getById/{id}', [SuccessfulEmailController::class, 'getById']);
    Route::get('/getAll', [SuccessfulEmailController::class, 'getAll']);
});

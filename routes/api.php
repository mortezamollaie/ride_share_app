<?php

use App\Http\Controllers\DriverController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserInfo;

Route::post('/login', [LoginController::class, 'submit']);
Route::post('/login/verify', [LoginController::class, 'verify']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', UserInfo::class);
    Route::get('/driver', [DriverController::class, 'show']);
    Route::post('/driver', [DriverController::class, 'update']);
});

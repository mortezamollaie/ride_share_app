<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'submit']);
Route::post('/login/verify', [LoginController::class, 'verify']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', UserInfo::class);
});

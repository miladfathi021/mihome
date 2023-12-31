<?php

use App\Http\Controllers\V1\Auth\SigninController;
use App\Http\Controllers\V1\Auth\SignupController;
use Illuminate\Support\Facades\Route;

Route::post('signup', SignupController::class)
    ->name('signup');

Route::post('signin', SigninController::class)
    ->name('signin');

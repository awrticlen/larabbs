<?php

use App\Http\Controllers\Api\VerificationCodesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::post('verificationCodes', [VerificationCodesController::class, 'store'])
        ->name('verification-codes.store');
    // 用户注册
    Route::post('users', [UsersController::class, 'store'])
        ->name('users.store');
});

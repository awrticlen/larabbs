<?php

use App\Http\Controllers\Api\VerificationCodesController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::post('verificationCodes', [VerificationCodesController::class, 'store'])
        ->name('verification-codes.store');
});

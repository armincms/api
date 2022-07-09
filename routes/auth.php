<?php

use Armincms\Api\Http\Controllers\Auth\AuthenticatedSessionController;
use Armincms\Api\Http\Controllers\Auth\ConfirmablePasswordController;
use Armincms\Api\Http\Controllers\Auth\EmailVerificationNotificationController;
use Armincms\Api\Http\Controllers\Auth\EmailVerificationPromptController;
use Armincms\Api\Http\Controllers\Auth\NewPasswordController;
use Armincms\Api\Http\Controllers\Auth\PasswordResetLinkController;
use Armincms\Api\Http\Controllers\Auth\RegisteredUserController;
use Armincms\Api\Http\Controllers\Auth\VerificationController;
use Armincms\Api\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest:sanctum')
    ->name('api.register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest:sanctum')
    ->name('api.login');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest:sanctum')
    ->name('api.password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest:sanctum')
    ->name('api.password.update');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
    ->middleware('auth')
    ->name('api.password.confirm');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ->name('api.logout');

Route::post('/verification-notification', [VerificationController::class,'create'])
    ->middleware('throttle:6,1')
    ->name('verification.send');

Route::post('/verification', [VerificationController::class,'store'])
    ->middleware('throttle:6,1')
    ->name('verification.verify');

<?php

use App\Http\Controllers\Lead\LeadAuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('lead/register', [RegisteredUserController::class, 'create'])
                ->name('lead.register');

    Route::post('lead/register', [RegisteredUserController::class, 'store']);

    Route::get('lead/login', [LeadAuthenticatedSessionController::class, 'create'])
                ->name('lead.login');

    Route::post('lead/login', [LeadAuthenticatedSessionController::class, 'store']);

    Route::get('lead/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('lead.password.request');

    Route::post('lead/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('lead.password.email');

    Route::get('lead/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('lead.password.reset');

    Route::post('lead/reset-password', [NewPasswordController::class, 'store'])
                ->name('lead.password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('lead/verify-email', EmailVerificationPromptController::class)
                ->name('lead.verification.notice');

    Route::get('lead/verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('lead.verification.verify');

    Route::post('lead/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('lead.verification.send');

    Route::get('lead/confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('lead.password.confirm');

    Route::post('lead/confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('lead/password', [PasswordController::class, 'update'])->name('lead.password.update');

    Route::post('lead/logout', [LeadAuthenticatedSessionController::class, 'destroy'])
                ->name('lead.logout');
});

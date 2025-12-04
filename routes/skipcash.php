<?php

use App\Http\Controllers\SkipCashController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SkipCash Payment Gateway Routes
|--------------------------------------------------------------------------
*/

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Generate payment links
    Route::post('/payment/skipcash/course/{course}', [SkipCashController::class, 'generateCoursePaymentLink'])
        ->name('skipcash.payment.course');

    Route::post('/payment/skipcash/event/{event}', [SkipCashController::class, 'generateEventPaymentLink'])
        ->name('skipcash.payment.event');

    Route::post('/payment/skipcash/subscription', [SkipCashController::class, 'generateSubscriptionPaymentLink'])
        ->name('skipcash.payment.subscription');
});

// Payment return callback (can be accessed without auth)
Route::get('/payment/skipcash/return', [SkipCashController::class, 'handlePaymentReturn'])
    ->name('skipcash.payment.return');

// Webhook endpoint (no auth required)
Route::post('/payment/skipcash/webhook', [SkipCashController::class, 'handleWebhook'])
    ->name('skipcash.payment.webhook');

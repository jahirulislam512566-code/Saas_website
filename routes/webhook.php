<?php
// routes/webhooks.php

use App\Http\Controllers\Webhook\StripeController;
use App\Http\Controllers\Webhook\PayPalController;
use App\Http\Controllers\Webhook\RazorpayController;
use App\Http\Controllers\Webhook\PaddleController;
use App\Http\Controllers\Webhook\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Webhook Routes
|--------------------------------------------------------------------------
|
| Webhook endpoints that receive data from third-party services.
| These routes are public and use signature verification.
|
*/

Route::prefix('webhooks')->name('webhooks.')->group(function () {
    // Stripe Webhook
    Route::post('/stripe', [StripeController::class, 'handle'])
        ->name('stripe')
        ->withoutMiddleware(['auth', 'verified', 'admin']);

    // PayPal Webhook
    Route::post('/paypal', [PayPalController::class, 'handle'])
        ->name('paypal')
        ->withoutMiddleware(['auth', 'verified', 'admin']);

    // Razorpay Webhook
    Route::post('/razorpay', [RazorpayController::class, 'handle'])
        ->name('razorpay')
        ->withoutMiddleware(['auth', 'verified', 'admin']);

    // Paddle Webhook
    Route::post('/paddle', [PaddleController::class, 'handle'])
        ->name('paddle')
        ->withoutMiddleware(['auth', 'verified', 'admin']);

    // Custom Webhook Endpoint
    Route::post('/{webhook}', [WebhookController::class, 'handle'])
        ->name('handle')
        ->withoutMiddleware(['auth', 'verified', 'admin']);
});
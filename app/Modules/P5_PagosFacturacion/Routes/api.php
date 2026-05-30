<?php

use Illuminate\Support\Facades\Route;
use App\Modules\P5_PagosFacturacion\Controllers\StripeWebhookController;

/*
|--------------------------------------------------------------------------
| P5 — PAGOS Y FACTURACIÓN (API ROUTES)
|--------------------------------------------------------------------------
*/

// Webhook de Stripe (sin CSRF ni Auth)
Route::post('pagos/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->name('pagos.stripe.webhook');

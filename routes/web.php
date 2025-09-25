<?php

use App\Http\Controllers\KkiapayWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/kkiapay/rendez_vous/webhook', [KkiapayWebhookController::class, 'handle']);
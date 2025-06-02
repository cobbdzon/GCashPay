<?php

use Illuminate\Support\Facades\Route;
use Paymenter\Extensions\Gateways\GCashPay\GCashPay;

Route::get("/extensions/gcp/pay/{invoiceId}", [
    GCashPay::class,
    "payment",
])->name("extensions.gateways.gcp.pay");

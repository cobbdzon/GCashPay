<?php

use Illuminate\Support\Facades\Route;
use Paymenter\Extensions\Gateways\ManualMultiPay\ManualMultiPay;

Route::get('/extensions/mmp/pay/{invoiceId}', [ManualMultiPay::class, 'payment'])->name('extensions.gateways.mmp.pay');

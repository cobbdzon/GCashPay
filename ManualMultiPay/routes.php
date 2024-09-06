<?php

use Illuminate\Support\Facades\Route;
use App\Helpers\ExtensionHelper;
use App\Models\Invoice;


Route::get('/manualmultipay/payment/{order_id}', function ($order_id) {
    $order_id_prefix = ExtensionHelper::getConfig('ManualMultiPay', 'order_id_prefix');
    $invoiceId = (int) substr($order_id, strlen($order_id_prefix));
    $invoice = Invoice::find($invoiceId);
    $back_invoice = route('clients.invoice.show', $invoiceId);
    $total = isset($invoice->credits) ? $invoice->credits : $invoice->total();
    $payment_confirmation_eta = ExtensionHelper::getConfig('ManualMultiPay', 'payment_confirmation_eta');
    $conversion_rate = (float)ExtensionHelper::getConfig('ManualMultiPay', 'conversion_rate') ?? 1.00;
    $total = $total * $conversion_rate;;
    $merchant_name = ExtensionHelper::getConfig('ManualMultiPay', 'merchant_name');
    $payment_address_type = ExtensionHelper::getConfig('ManualMultiPay', 'payment_address_type');
    $upi_address = ExtensionHelper::getConfig('ManualMultiPay', 'upi_address');
    $bank_account_number = ExtensionHelper::getConfig('ManualMultiPay', 'bank_account_number');
    $bank_ifsc_code = ExtensionHelper::getConfig('ManualMultiPay', 'bank_ifsc_code');
    $aadhaar_number = ExtensionHelper::getConfig('ManualMultiPay', 'aadhaar_number');
    $mobile_number = ExtensionHelper::getConfig('ManualMultiPay', 'mobile_number');
    return view('ManualMultiPay::payment', ['order_id' => $order_id, 'back_invoice' => $back_invoice, 'total' => $total, 'payment_confirmation_eta' => $payment_confirmation_eta,'merchant_name' => $merchant_name, 'payment_address_type' => $payment_address_type, 'upi_address' => $upi_address, 'bank_account_number' => $bank_account_number, 'bank_ifsc_code' => $bank_ifsc_code, 'aadhaar_number' => $aadhaar_number, 'mobile_number' => $mobile_number]);

})->name('ManualMultiPay.payment');
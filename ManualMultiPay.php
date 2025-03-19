<?php

namespace Paymenter\Extensions\Gateways\ManualMultiPay;

use App\Classes\Extension\Gateway;
use App\Helpers\ExtensionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Invoice;
use Illuminate\Support\Facades\View;

class ManualMultiPay extends Gateway
{

    public function boot()
    {
        require __DIR__ . '/routes.php';
        View::addNamespace('gateways.mmp', __DIR__ . '/resources/views');
    }

    /**
     * Get all the configuration for the extension
     * 
     * @param array $values
     * @return array
     */
    public function getConfig($values = [])
    {
        return [
            [
                'name' => 'merchant_name',
                'label' => 'Merchant Name',
                'type' => 'text',
                'description' => 'Merchant Name',
                'required' => true,
            ],
            [
                'name' => 'payment_address_type',
                'label' => 'Payment Address Type',
                'type' => 'select',
                'default' => 'upi',
                'required' => true,
                'options' => [
                    'upi' => 'UPI Address',
                    'bank' => 'Bank Account Number',
                    'aadhaar' => 'Aadhaar Number',
                    'mobile' => 'Mobile Number',
                ],
            ],
            [
                'name' => 'upi_address',
                'label' => 'UPI Address',
                'type' => 'text',
                'required' => true,
                'description' => 'Set to 0 if not applicable',
            ],
            [
                'name' => 'bank_account_number',
                'label' => 'Bank Account Number',
                'type' => 'text',
                'required' => true,
                'description' => 'Set to 0 if not applicable',
            ],
            [
                'name' => 'bank_ifsc_code',
                'label' => 'Bank IFSC Code',
                'type' => 'text',
                'required' => true,
                'description' => 'Set to 0 if not applicable',
            ],
            [
                'name' => 'aadhaar_number',
                'label' => 'Aadhaar Number',
                'type' => 'number',
                'required' => true,
                'description' => 'Set to 0 if not applicable',
            ],
            [
                'name' => 'mobile_number',
                'label' => 'Mobile Number',
                'type' => 'number',
                'required' => true,
                'description' => 'Set to 0 if not applicable',
            ],
            [
                'name' => 'order_prefix',
                'label' => 'Order Prefix',
                'type' => 'text',
                'description' => 'Order Prefix',
                'required' => false,
            ],
            [
                'name' => 'payment_confirmation_eta',
                'label' => 'Payment Confirmation ETA',
                'type' => 'text',
                'required' => false,
            ],
            [
                'name' => 'allow_foreign_currency',
                'label' => 'Allow Foreign Currency',
                'type' => 'checkbox',
            ]
        ];
    }
    
    /**
     * Return a view or a url to redirect to
     * 
     * @param Invoice $invoice
     * @param float $total
     * @return string
     */
    public function pay($invoice, $total)
    {
        return route('extensions.gateways.mmp.pay', ['invoiceId' => $invoice->id]);
    }

    public function payment($invoiceId) {
        $invoice = Invoice::find($invoiceId);
        if (!$invoice) {
            return redirect()->route('invoices')->with('error', 'Invoice not found');
        }
        if ($invoice->status != 'pending') {
            return redirect()->route('invoices.show', ['invoice' => $invoice->id])->with('error', 'Invoice must be pending');
        }
        $allowForeignCurrency = $this->config('allow_foreign_currency');
        if (!$allowForeignCurrency && $invoice->currency_code != 'INR') {
            return redirect()->route('invoices.show', ['invoice' => $invoice->id])->with('error', 'Foreign currency not allowed (Extension Setting)');
        }
        $total = $invoice->remaining;
        $orderId = $this->config('order_prefix') . $invoiceId;
        $paymentLink = '';
        $paymentAddressDisplay = '';
        $merchantName = $this->config('merchant_name');
        $paymentAddressType = $this->config('payment_address_type');
        $upiAddress = $this->config('upi_address');
        $bankAccountNumber = $this->config('bank_account_number');
        $bankIfscCode = $this->config('bank_ifsc_code');
        $aadhaarNumber = $this->config('aadhaar_number');
        $mobileNumber = $this->config('mobile_number');
        $paymentConfirmationEta = $this->config('payment_confirmation_eta');

        switch($paymentAddressType) {
            case "upi":
                $paymentLink = "upi://pay?pa={$upiAddress}&pn={$merchantName}&am={$total}&tn={$orderId}&cu=INR";
                $paymentAddressDisplay = $upiAddress;
                break;
            case "bank":
                $paymentLink = "upi://pay?pa={$bankAccountNumber}@{$bankIfscCode}.ifsc.npci&pn={$merchantName}&am={$total}&tn={$orderId}&cu=INR";
                $paymentAddressDisplay = "{$bankAccountNumber}@{$bankIfscCode}.ifsc.npci";
                break;
            case "aadhaar":
                $paymentLink = "upi://pay?pa={$aadhaarNumber}@aadhaar.npci&pn={$merchantName}&am={$total}&tn={$orderId}&cu=INR";
                $paymentAddressDisplay = "{$aadhaarNumber}@aadhaar.npci";
                break;
            case "mobile":
                $paymentLink = "upi://pay?pa={$mobileNumber}@mobile.npci&pn={$merchantName}&am={$total}&tn={$orderId}&cu=INR";
                $paymentAddressDisplay = "{$mobileNumber}@mobile.npci";
                break;
            default:
                return redirect()->route('invoices.show', ['invoice' => $invoice->id])->with('error', 'Invalid Payment Address Type (Extension Setting)');
        }

        Log::info('MMP Order ID: ' . $orderId . 'Debug: ' . $paymentLink . ' ' . $paymentAddressDisplay . ' ' . $total . ' ' . $merchantName . ' ' . $paymentAddressType . ' ' . $paymentConfirmationEta . ' ' . $upiAddress . ' ' . $bankAccountNumber . ' ' . $bankIfscCode . ' ' . $aadhaarNumber . ' ' . $mobileNumber);
        
        return view('gateways.mmp::pay', [
            'invoice' => $invoice,
            'merchantName' => $merchantName,
            'paymentAddressType' => $paymentAddressType,
            'paymentLink' => $paymentLink,
            'paymentAddressDisplay' => $paymentAddressDisplay,
            'total' => $total,
            'orderId' => $orderId,
            'returnUrl' => route('invoices.show', ['invoice' => $invoice->id]),
            'paymentConfirmationEta' => $paymentConfirmationEta,
            'upiAddress' => $upiAddress,
            'bankAccountNumber' => $bankAccountNumber,
            'bankIfscCode' => $bankIfscCode,
            'aadhaarNumber' => $aadhaarNumber,
            'mobileNumber' => $mobileNumber,
            'allowForeignCurrency' => $allowForeignCurrency,
        ]);
    }
}
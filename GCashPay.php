<?php

namespace Paymenter\Extensions\Gateways\GCashPay;

use App\Classes\Extension\Gateway;
use Illuminate\Support\Facades\Log;
use App\Models\Invoice;
use Illuminate\Support\Facades\View;

class GCashPay extends Gateway
{
    public function boot()
    {
        require __DIR__ . "/routes.php";
        View::addNamespace("gateways.gcp", __DIR__ . "/resources/views");
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
                "name" => "merchant_name",
                "label" => "Merchant Name",
                "type" => "text",
                "description" => "Merchant Name",
                "required" => true,
            ],
            [
                "name" => "mobile_number",
                "label" => "Mobile Number",
                "type" => "number",
                "required" => true,
                "description" => "Mobile number registered with GCash",
            ],
            [
                "name" => "order_prefix",
                "label" => "Order Prefix",
                "type" => "text",
                "description" => "Order Prefix",
                "required" => false,
            ],
            [
                "name" => "payment_confirmation_eta",
                "label" => "Payment Confirmation ETA",
                "type" => "text",
                "required" => false,
            ],
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
        return route("extensions.gateways.gcp.pay", [
            "invoiceId" => $invoice->id,
        ]);
    }

    public function payment($invoiceId)
    {
        $invoice = Invoice::find($invoiceId);
        if (!$invoice) {
            return redirect()
                ->route("invoices")
                ->with("error", "Invoice not found");
        }
        if ($invoice->status != "pending") {
            return redirect()
                ->route("invoices.show", ["invoice" => $invoice->id])
                ->with("error", "Invoice must be pending");
        }
        $total = $invoice->remaining;
        $orderId = $this->config("order_prefix") . $invoiceId;
        $merchantName = $this->config("merchant_name");
        $mobileNumber = $this->config("mobile_number");
        $paymentConfirmationEta = $this->config("payment_confirmation_eta");

        Log::info(
            "gcp Order ID: " .
                $orderId .
                "Debug: " .
                $total .
                " " .
                $merchantName .
                " " .
                $paymentConfirmationEta .
                " " .
                $mobileNumber
        );

        return view("gateways.gcp::pay", [
            "invoice" => $invoice,
            "merchantName" => $merchantName,
            "total" => $total,
            "orderId" => $orderId,
            "returnUrl" => route("invoices.show", ["invoice" => $invoice->id]),
            "paymentConfirmationEta" => $paymentConfirmationEta,
            "mobileNumber" => $mobileNumber,
        ]);
    }
}

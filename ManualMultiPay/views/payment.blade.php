<?php
$paymentLink = "";
$paymentAddressDisplay = "";

switch($payment_address_type) {
    case "upi":
        $paymentLink = "upi://pay?pa={$upi_address}&pn={$merchant_name}&am={$total}&tn={$order_id}&cu=INR";
        $paymentAddressDisplay = $upi_address;
        break;
    case "bank":
        $paymentLink = "upi://pay?pa={$bank_account_number}@{$bank_ifsc_code}.ifsc.npci&pn={$merchant_name}&am={$total}&tn={$order_id}&cu=INR";
        $paymentAddressDisplay = "{$bank_account_number}@{$bank_ifsc_code}.ifsc.npci";
        break;
    case "aadhaar":
        $paymentLink = "upi://pay?pa={$aadhaar_number}@aadhaar.npci&pn={$merchant_name}&am={$total}&tn={$order_id}&cu=INR";
        $paymentAddressDisplay = "{$aadhaar_number}@aadhaar.npci";
        break;
    case "mobile":
        $paymentLink = "upi://pay?pa={$mobile_number}@mobile.npci&pn={$merchant_name}&am={$total}&tn={$order_id}&cu=INR";
        $paymentAddressDisplay = "{$mobile_number}@mobile.npci";
        break;
    default:
        $paymentAddressDisplay = "Invalid Payment Address Type";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MMP Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <h1 class="mb-4 text-center">Scan the QR code to make the payment</h1>
                        <div class="d-flex justify-content-center mb-4">
                            <img id="qr-code" src="https://api.qrserver.com/v1/create-qr-code/?size=225x225&data={{ urlencode($paymentLink) }}" alt="QR Code" class="img-fluid" />
                        </div>
                        <h2 class="text-center mb-3">Order ID: <span class="text-primary">{{ $order_id }}</span></h2>
                        <h2 class="text-center mb-3">Total: <span class="text-primary">₹{{ $total }}</span></h2>
                        <h2 class="text-center mb-3">Merchant: <span class="text-primary">{{ $merchant_name }}</span></h2>
                        <h2 class="text-center mb-3">Transaction Note: <span class="text-primary">{{ $order_id }}</span></h2>
                        <h2 class="text-center mb-3">Payment Address: <span class="text-primary">{{ $paymentAddressDisplay }}</span></h2>
                        @switch($payment_address_type)
                            @case("bank")
                                <h2 class="text-center mb-3">Bank Account Number: <span class="text-primary">{{ $bank_account_number }}</span></h2>
                                <h2 class="text-center mb-3">IFSC Code: <span class="text-primary">{{ $bank_ifsc_code }}</span></h2>
                                @break
                            @case("aadhaar")
                                <h2 class="text-center mb-3">Aadhaar Number: <span class="text-primary">{{ $aadhaar_number }}</span></h2>
                                @break
                            @case("mobile")
                                <h2 class="text-center mb-3">Mobile Number: <span class="text-primary">{{ $mobile_number }}</span></h2>
                                @break
                        @endswitch
                        <div class="d-grid">
                            <a id="payment-link" href="{{ $paymentLink }}" class="btn btn-primary btn-lg">Open Link on Phone (Requires UPI app)</a>
                        </div>
                        <div class="alert alert-info mt-3" role="alert">
                            <p class="mb-3">Please note that payments are manually processed. After making your payment, please wait patiently for it to be confirmed. Thank you for your patience.</p>
                            @if($payment_confirmation_eta)
                                <p class="mb-3">Estimated time for payment confirmation: {{ $payment_confirmation_eta }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
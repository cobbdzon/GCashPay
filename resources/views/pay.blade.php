<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GCash Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <img src="/assets/images/gcash.svg" alt="GCash Logo" class="img-fluid mb-4 mx-auto d-block" style="width: 100px; height: 100px;">
                        <h1 class="mb-4 text-center">Pay with GCash or Maya</h1>
                        <h2 class="text-center mb-3">Order ID: <span class="text-primary">{{ $orderId }}</span></h2>
                        <h2 class="text-center mb-3">Total: <span class="text-primary">{{ $total }}</span></h2>
                        <h2 class="text-center mb-3">Merchant: <span class="text-primary">{{ $merchantName }}</span></h2>
                        <h2 class="text-center mb-3">Mobile Number: <span class="text-primary">{{ $mobileNumber }}</span></h2>
                        <div class="d-grid">
                            <a href="{{ $returnUrl }}" class="btn btn-primary btn-lg mt-2">Back to Invoice</a>
                        </div>
                        <div class="alert alert-info mt-3" role="alert">
                            <p class="mb-3">Please note that payments are manually processed. After making your payment, please wait patiently for it to be confirmed. Thank you for your patience.</p>
                            @if($paymentConfirmationEta)
                                <p class="mb-3">Estimated time for payment confirmation: {{ $paymentConfirmationEta }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php

namespace App\Extensions\Gateways\ManualMultiPay;

use App\Classes\Extensions\Gateway;
use App\Helpers\ExtensionHelper;

class ManualMultiPay extends Gateway
{
    /**
    * Get the extension metadata
    *
    * @return array
    */
    public function getMetadata()
    {
        return [
            'display_name' => 'ManualMultiPay (MMP)',
            'version' => '1.0.0',
            'author' => 'Vaibhav Dhiman',
            'website' => 'https://paymenter.org',
        ];
    }

    /**
     * Get all the configuration for the extension
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            [
                'name' => 'order_id_prefix',
                'friendlyName' => 'Order ID Prefix',
                'type' => 'text',
                'description' => 'Order ID Prefix',
                'required' => false,
            ],
            [
                'name' => 'payment_confirmation_eta',
                'friendlyName' => 'Payment Confirmation ETA',
                'type' => 'text',
                'description' => 'ETA for payment confirmation',
                'required' => false,
            ],
            [
                'name' => 'merchant_name',
                'friendlyName' => 'Merchant Name',
                'type' => 'text',
                'description' => 'Name of the Merchant/Payee',
                'required' => true,
            ],
            [
                'name' => 'payment_address_type',
                'friendlyName' => 'Payment Address Type',
                'type' => 'dropdown',
                'description' => 'Type of address to be used for payment',
                'required' => true,
                'options' => [
                    [
                        'name' => 'UPI Address',
                        'value' => 'upi',
                    ],
                    [
                        'name' => 'Bank Account Number',
                        'value' => 'bank',
                    ],
                    [
                        'name' => 'Aadhaar Number',
                        'value' => 'aadhaar',
                    ],
                    [
                        'name' => 'Mobile Number',
                        'value' => 'mobile',
                    ],
                ],
            ],
            [
                'name' => 'upi_address',
                'friendlyName' => 'UPI Address',
                'type' => 'text',
                'description' => 'UPI Address (Set to 0 if not used)',
                'required' => true,
            ],
            [
                'name' => 'bank_account_number',
                'friendlyName' => 'Bank Account Number',
                'type' => 'text',
                'description' => 'Bank Account Number (Set to 0 if not used)',
                'required' => true,
            ],
            [
                'name' => 'bank_ifsc_code',
                'friendlyName' => 'IFSC Code',
                'type' => 'text',
                'description' => 'IFSC Code (Set to 0 if not used)',
                'required' => true,
            ],
            [
                'name' => 'aadhaar_number',
                'friendlyName' => 'Aadhaar Number',
                'type' => 'text',
                'description' => 'Aadhaar Number (Set to 0 if not used)',
                'required' => true,
            ],
            [
                'name' => 'mobile_number',
                'friendlyName' => 'Mobile Number',
                'type' => 'text',
                'description' => 'Mobile Number (Set to 0 if not used)',
                'required' => true,
            ]
        ];
    }

    /**
     * Get the URL to redirect to
     *
     * @param int $total
     * @param array $products
     * @param int $invoiceId
     * @return string
     */
    public function pay($total, $products, $invoiceId)
    {
        $order_id = ExtensionHelper::getConfig('ManualMultiPay', 'order_id_prefix') . $invoiceId;
        return route('ManualMultiPay.payment', ['order_id' => $order_id]);
    }
}
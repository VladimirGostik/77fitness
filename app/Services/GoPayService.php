<?php

namespace App\Services;

use GoPay\Definition\Payment\PaymentInstrument;
use GoPay\Definition\TokenScope;
use GoPay\GoPay;
use GoPay\Http\Log;

class GoPayService
{
    protected $gopay;

    public function __construct()
    {
        $this->gopay = GoPay::payments([
            'goid' => env('GOPAY_GOID'),
            'clientId' => env('GOPAY_CLIENT_ID'),
            'clientSecret' => env('GOPAY_SECRET_KEY'),
            'gatewayUrl' => env('GOPAY_GATEWAY_URL', 'https://gw.sandbox.gopay.com/'),
            'scope' => TokenScope::PAYMENT_ALL,
            'language' => 'en',
            'timeout' => 30,
        ]);
    }

    public function createPayment($order)
    {
        $response = $this->gopay->payments->createPayment([
            'payer' => [
                'default_payment_instrument' => PaymentInstrument::BANK_ACCOUNT,
                'allowed_payment_instruments' => [PaymentInstrument::BANK_ACCOUNT, PaymentInstrument::CARD],
                'contact' => [
                    'first_name' => $order['first_name'],
                    'last_name' => $order['last_name'],
                    'email' => $order['email'],
                    'phone_number' => $order['phone'],
                ]
            ],
            'amount' => $order['amount'] * 100, // Amount in cents
            'currency' => 'EUR',
            'order_number' => $order['order_number'],
            'order_description' => $order['description'],
            'items' => $order['items'],
            'callback' => [
                'return_url' => url('/payment/success'),
                'notification_url' => url('/payment/notify'),
            ],
            'additional_params' => [
                ['name' => 'invoicenumber', 'value' => '2015001003']
            ],
        ]);

        return $response;
    }
}

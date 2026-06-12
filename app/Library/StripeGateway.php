<?php

namespace App\Library;

use Illuminate\Support\Facades\Session;
use Stripe\Stripe;
use App\Models\Gateway;
use Illuminate\Http\Request;

class StripeGateway
{
    public static function make_payment($array)
    {
        $successUrl = url(Session::get('fund_callback.success_url'));
        $cancelUrl  = url(Session::get('fund_callback.cancel_url'));

        $gateway = Gateway::findOrFail($array['gateway_id']);
        Stripe::setApiKey($gateway->data['stripe_secret']);
        $amount = $array['pay_amount'] * 100;

        $session = \Stripe\Checkout\Session::create([
            'line_items'  => [
                [
                    'price_data' => [
                        'currency'     => 'USD',
                        'product_data' => [
                            "name" => $array['billName'],
                        ],
                        'unit_amount'  => $amount,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url'  => $cancelUrl,
        ]);

        return redirect()->away($session->url);
    }
}

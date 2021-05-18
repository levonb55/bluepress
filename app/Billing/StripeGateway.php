<?php


namespace App\Billing;


use Stripe\Charge;
use Stripe\Stripe;

class StripeGateway
{
    public function __construct()
    {
//        Stripe::setApiKey(config('services.stripe.key'));
        Stripe::setApiKey('sk_test_51DUf97IAvjHUo2eP9E02BWNkXYvqHRMlDganc4ssfkKNA9zRimOHVwUu4jMhHpMFHfyhKjTMZwWiPoiss1MvOS4B00koqLcdJg');

    }

    public function charge($request, $user, $amount)
    {
        return Charge::create([
            'amount' => $amount * 100,
            'currency' => 'USD',
            'source' => $request->input('stripeToken'),
            'receipt_email' => $user->email,
            'description' => 'Charge for ' . $user->email
        ]);
    }
}

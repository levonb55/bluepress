<?php


namespace App\Billing;


use Stripe\Charge;
use Stripe\Stripe;

class StripeGateway
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
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

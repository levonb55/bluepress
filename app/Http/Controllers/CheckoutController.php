<?php

namespace App\Http\Controllers;

use App\Billing\StripeGateway;
use App\Http\Requests\CheckoutRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Session;
use App\Billing\Cart;

class CheckoutController extends Controller
{
    public function getCheckout()
    {
        return view('checkout.index');
    }

    public function postCheckout(CheckoutRequest $request, StripeGateway $stripeGateway)
    {
        $user = auth()->user();

        try {
            $stripeGateway->charge($request, $user, 12);

            Session::forget('cart');

            $cart = new Cart(Session::get('cart'));
            $products = Product::find(array_keys($cart->items));

            foreach ($products as $product) {
                $product->stock -= $cart->items[$product->id]['quantity'];
                $product->update();
            }

            return redirect()->route('home')->with('success', 'You successfully made a purchase!');

        } catch (\Exception $e) {
            return back()->with('payment-error', $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Billing\StripeGateway;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Session;
use App\Billing\Cart;

class CheckoutController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCheckout()
    {
        return view('checkout.index');
    }

    /**
     * @param CheckoutRequest $request
     * @param StripeGateway $stripeGateway
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCheckout(CheckoutRequest $request, StripeGateway $stripeGateway)
    {
        $user = auth()->user();
        $totalPrice = Product::getProducts()['totalPrice'];

        try {
            $charge = $stripeGateway->charge($request, $user, $totalPrice);

            $oldCart = Session::get('cart');

            $cart = new Cart($oldCart);
            $products = Product::find(array_keys($cart->items));

            foreach ($products as $product) {
                if ($product->is_physical) {
                    $product->stock -= $cart->items[$product->id]['quantity'];
                    $product->update();
                }
            }

            $oldCart->totalPrice = $totalPrice;

            Order::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'postal_code' => $request->postal_code,
                'cart' => serialize($oldCart),
                'payment_id' => $charge->id
            ]);

            Session::forget('cart');

            return redirect()->route('home')->with('success', 'You successfully made a purchase!');
        } catch (\Exception $e) {
            return back()->with('payment-error', $e->getMessage());
        }
    }
}

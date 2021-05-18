<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Billing\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;

class CartController extends Controller
{

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (Session::has('cart')) {
            $cart = Session::get('cart');
            $selectedProducts = $cart->items;

            //Send request to the database to have updated prices
            $products = Product::find(array_keys($selectedProducts));

            foreach ($products as $product) {
                $product->quantity = $selectedProducts[$product->id]['quantity'];
            }
        }

        return view('cart.index', [
            'products' => $products ?? null,
            'totalQty' => $cart->totalQty ?? 0
        ]);
    }

    /**
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Product $product)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($product);

        if ($cart->message) {
            return back()->with('taken' . $product->id, $cart->message);
        }

        Session::put('cart', $cart);

        return back();
    }

    /**
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function change(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        if ($validator->fails()) {
            return back()->with('quantity-' . $product->id, $validator->errors()->first());
        }

        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);

        $cart->change($product, $request->quantity);
        Session::put('cart', $cart);

        return back();
    }

    /**
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Product $product)
    {
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $cart->remove($product->id);
        Session::put('cart', $cart);

        return back();
    }
}

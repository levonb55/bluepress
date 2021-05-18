<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Billing\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;

class CartController extends Controller
{

    public static $products = [];

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (Session::has('cart')) {
            $products = Product::getProducts();

            return view('cart.index', [
                'products' => $products['products'],
                'totalQty' => $products['totalQty'],
                'subTotal' => $products['subTotal'],
                'taxAmount' => $products['taxAmount'],
                'totalPrice' => $products['subTotal'] + $products['taxAmount']
            ]);
        } else {
            return view('cart.index', [
                'products' => self::$products,
            ]);
        }
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

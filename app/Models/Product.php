<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

class Product extends Model
{
    protected $fillable = [
        'image_path', 'title', 'description', 'price', 'stock'
    ];

    public static $products = [];
    public static $totalQty = 0;
    public static $subTotal = 0;
    public static $taxAmount = 0;

    public static function getProducts()
    {
        $cart = Session::get('cart');
        $selectedProducts = $cart->items;

        //Send request to the database to have updated prices
        self::$products = static::find(array_keys($selectedProducts));

        foreach (self::$products as $product) {
            $product->quantity = $selectedProducts[$product->id]['quantity'];
            self::$subTotal += $product->quantity * $product->price;
        }

        self::$totalQty = $cart->totalQty;

        if (auth()->user()->is_vat) {
            self::$taxAmount = (self::$subTotal * User::VAT_RATE) / 100;
        }

        return [
            'products' => self::$products,
            'totalQty' => self::$totalQty,
            'subTotal' => self::$subTotal,
            'taxAmount' => self::$taxAmount,
            'totalPrice' => self::$subTotal + self::$taxAmount
        ];
    }
}

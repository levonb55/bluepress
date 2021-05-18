<?php

namespace App\Billing;

class Cart {
    public $items = [];
    public $totalQty = 0;
    public $message;

    public function __construct($oldCart)
    {
        if($oldCart) {
            $this->items = $oldCart->items;
            $this->totalQty = $oldCart->totalQty;
        }
    }

    public function add($item)
    {
        if(array_key_exists($item['id'], $this->items)) {
            $this->message = 'The item is already in the cart.';
        } else {
            $this->items[$item['id']] = ['quantity' => 1, 'item' => $item];
        }

        $this->totalQty ++;
    }

    public function change($item, $quantity)
    {
        $this->totalQty = $this->totalQty - $this->items[$item['id']]['quantity'] + $quantity;
        $this->items[$item['id']]['quantity'] = $quantity;
    }

    public function remove($itemId)
    {
        $this->totalQty -= $this->items[$itemId]['quantity'];
        unset($this->items[$itemId]);
    }
}

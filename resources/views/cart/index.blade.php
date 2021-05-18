@extends('layouts.main')

@section('title', config('app.name') . ' Shopping Cart')

@section('content')
    <div class="container cart">
        <h4>Shopping Cart</h4>
        @if(count($products) > 0)
            <ul class="list-group my-3">
                @foreach($products as $product)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-6 d-flex">
                                <span>{{ $product->title }}</span>
                                <form action="{{ route('cart.change', $product->id) }}" method="POST"
                                      class="offset-md-2">
                                    @csrf
                                    <input type="number" min="1" name="quantity" value="{{ $product->quantity }}"
                                           class="quantity">
                                    <input type="submit" value="Change">
                                    @if(Session::has('quantity-' . $product->id))
                                        <div class="alert alert-danger">
                                            {{ Session::get('quantity-' . $product->id) }}
                                        </div>
                                    @endif
                                </form>
                            </div>
                            <div class="col-md-3">
                                Stock: {{ $product->stock }}
                            </div>
                            <div class="col-md-1 offset-md-2">
                                <span class="badge badge-secondary">${{ $product->price }}</span>
                                <a href="{{ route('cart.remove', $product->id) }}" title="Remove"
                                   class="float-right">X</a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
            Total Quantity: <span class="badge badge-warning">{{ $totalQty }}</span>
            <div class="text-center">
                <a href="{{ route('checkout') }}" class="btn btn-success">Checkout</a>
            </div>
        @else
            <h4>Cart is empty!</h4>
        @endif
    </div>
@endsection

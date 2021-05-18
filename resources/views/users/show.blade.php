@extends('layouts.main')

@section('title', config('app.name') . ' User Profile')

@section('content')
    <div class="container cart">
        <h4>Orders' History</h4>
        @if(count($orders) > 0)
            <ul class="list-group my-3">
                @foreach($orders as $order)
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <ul class="list-group my-4">
                                @foreach($order->cart->items as $item)
                                    <li class="list-group-item">
                                        {{ $item['item']['title'] }} | {{ $item['quantity'] }} Units
                                    </li>
                                @endforeach
                                Total Price: ${{ $order->cart->totalPrice }}
                            </ul>
                        </div>
                    </div>
                @endforeach
            </ul>
        @else
            <h4>No history yet!</h4>
        @endif
    </div>
@endsection

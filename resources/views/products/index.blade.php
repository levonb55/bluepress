@extends('layouts.main')

@section('title', config('app.name') . ' Homepage')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-3 mr-4 mb-4">
                    <div class="card" style="width: 18rem;">
                        <img src="{{ $product->image_path }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->title }}</h5>
                            <p class="card-text">
                                {{ $product->description }}
                            </p>
                            <a href="{{ route('cart.add', $product->id) }}" class="btn btn-primary">Add to Cart</a>
                            <span class="float-right">${{ $product->price }}</span>
                        </div>
                        @if(Session::has('taken' . $product->id))
                            <div class="alert alert-danger">
                                {{ Session::get('taken' . $product->id) }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

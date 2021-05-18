@extends('layouts.main')

@section('title', config('app.name') . ' Homepage')

@section('content')
    <div class="container">
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-3 mr-1">
                    <div class="card" style="width: 18rem;">
                        <img src="{{ $product->image_path }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->title }}</h5>
                            <p class="card-text">
                                {{ $product->description }}
                            </p>
                            <a href="#" class="btn btn-primary">Add to Cart</a>
                            <span class="float-right">${{ $product->price }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

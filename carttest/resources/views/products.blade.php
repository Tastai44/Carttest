@extends('layouts.app-master')

   
@section('content')
    
<div class="row">
    @foreach($products as $product)
        <div class="col-xs-18 col-sm-6 col-md-3">
            <div class="thumbnail">
                <img src="{{ $product->image }}" alt="">
                <div class="caption">
                    <h4>{{ $product->name }}</h4>
                    <p>{{ $product->description }}</p>
                    <p>{{ $product->stock }}</p>
                    <p><strong>Price: </strong> {{ $product->price }}$</p>
                    @auth
                        <p class="btn-holder"><a href="{{ route('add.to.cart', $product->id) }}" class="btn btn-warning btn-block text-center" role="button">Add to cart</a> </p>
                    @endauth
                </div>
            </div>
        </div>
    @endforeach
</div>
    
@endsection
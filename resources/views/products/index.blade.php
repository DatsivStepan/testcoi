@extends('layouts.app')

@section('content')
<style>
    .uper {
        margin-top: 40px;
    }
</style>
<div class="uper">
    @if(session()->get('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}  
    </div><br />
    @endif

    <div class="row" style="margin-bottom: 20px">
        <div class="col-sm-12 text-center">
            @auth
                <a href="/products/create" class="btn btn-primary">Create</a>
            @else
                <a href="/login" class="btn btn-primary">Create</a>
            @endauth
        </div>
    </div>

    <div class="row">
        @if ($products) 
            @foreach($products as $product)
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <img src="{{$product->getImage()}}" style="width:100%;height:300px;object-fit: cover;">
                    <div class="caption">
                        <h3>{{$product->name}}</h3>
                        <p>{{$product->getPriceByCurr('UAH', true)}}</p>
                        <p><a href="/products/{{$product->id}}" class="btn btn-primary btn-block">View</a></p>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="clearfix"></div>
            <div class="col-sm-12">
                {!! $products->render() !!}
            </div>
        @else
            <div class="col-sm-12">Products not found</div>
        @endif
    </div>
</div>
@endsection
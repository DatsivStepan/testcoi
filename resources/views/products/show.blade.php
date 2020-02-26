@extends('layouts.app')

@section('content')
<style>
    .uper {
        margin-top: 40px;
    }
</style>
<div class="card uper">
    <div class="card-header">
        <h1>
            {{ $product->name }}
            <div class="col-sm-1 float-right">
                @auth
                <form action="{{ route('products.destroy', $product->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Delete</button>
                </form>
                @endauth
            </div>
        </h1>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-4">
                <img src="{{$product->getImage()}}" style="width:100%;height:300px;object-fit: cover;">
            </div>
            <div class="col-sm-6">
                @if ($product->userModel)
                    <div class="alert alert-secondary" role="alert">
                        {{$product->userModel->name}}
                    </div>
                @endif
                <ul>
                @foreach($product->pricesModels as $price)
                    <li>{{$price->amount}} {{$price->currency}}</li>
                @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('dashboard')
@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="container">
    <form method="POST" action="{{ route('product.update', $product->id) }}">
    @csrf @method('PUT')

        <div class="form-group">
            <label for="product_name">Product Name</label>
            <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $product->product_name }}" required>
        </div>
    @error('product_name')
        <div class="text-danger">{{ $message }}</div>
    @enderror



    <!-- select category  -->
    <div class="form-group">
            <label for="name">Category</label>
            <select id="category_id" name="category_id" class="form-control">
                <option value="">Choose Category</option>
                @foreach($categories as $c)
                
                <option value="{{ $c->id }}" @if($product->category->id == $c->id) selected="selected" @endif>
                    {{ $c->name }}</option>
                @endforeach

              </select>
    </div>
    @error('category')
        <div class="text-danger">{{ $message }}</div>
    @enderror





    <div class="form-group">
            <label for="usd">USD Value</label>
            <input type="number" step="0.01" class="form-control" id="usd" name="usd" aria-describedby="usd" value="{{ $product->USDcost }}" required>
        </div>
    @error('usd')
        <div class="text-danger">{{ $message }}</div>
    @enderror

    <div class="form-group">
            <label for="usdRate">USD Rate</label>
            <input type="number" step="0.01" class="form-control" id="usdRate" name="usdRate" aria-describedby="usdRate" value="{{ $product->ExchangeUSDRate }}" required>
        </div>
    @error('usdRate')
        <div class="text-danger">{{ $message }}</div>
    @enderror

    <div class="form-group">
            <label for="DFP">Duty Frieght Percentage</label>
            <input type="number" step="0.01" class="form-control" id="DFP" name="DFP" aria-describedby="DFP" value="{{ $product->DFP }}" required>
        </div>
    @error('DFP')
        <div class="text-danger">{{ $message }}</div>
    @enderror

    <div class="form-group">
            <label for="price">Price</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" aria-describedby="price" value="{{ $product->price }}" required>
        </div>
    @error('price')
        <div class="text-danger">{{ $message }}</div>
    @enderror



        <button class="btn btn-block btn-primary" type="submit">Update Product</button>
    </form>
</div>
@endsection
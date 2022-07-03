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
    <form method="POST" action="{{ route('product.store') }}">
    @csrf
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" aria-describedby="name" value="{{ old('name') }}" required>
        </div>
    @error('name')
        <div class="text-danger">{{ $message }}</div>
    @enderror

    <!-- select category  -->
    <div class="form-group">
            <label for="category_id">Category</label>
            <select id="category_id" name="category_id" class="form-control">
                <option value="">Choose Category</option>
                @foreach($categories as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach

              </select>
    </div>
    @error('category')
        <div class="text-danger">{{ $message }}</div>
    @enderror

    <div class="form-group">
            <label for="usd">USD Value</label>
            <input type="number" step="0.01" class="form-control" id="usd" name="usd" aria-describedby="usd" value="{{ old('usd') }}" required>
        </div>
    @error('usd')
        <div class="text-danger">{{ $message }}</div>
    @enderror

    <div class="form-group">
            <label for="usdRate">USD Rate</label>
            <input type="number" step="0.01" class="form-control" id="usdRate" name="usdRate" aria-describedby="usdRate" value="{{ old('usdRate') }}" required>
        </div>
    @error('usdRate')
        <div class="text-danger">{{ $message }}</div>
    @enderror

    <div class="form-group">
            <label for="DFP">Duty Frieght Percentage</label>
            <input type="number" step="0.01" class="form-control" id="DFP" name="DFP" aria-describedby="DFP" value="{{ old('DFP') }}" required>
        </div>
    @error('DFP')
        <div class="text-danger">{{ $message }}</div>
    @enderror

    <div class="form-group">
            <label for="price">Price</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" aria-describedby="price" value="{{ old('price') }}" required>
        </div>
    @error('price')
        <div class="text-danger">{{ $message }}</div>
    @enderror



        <button class="btn btn-block btn-primary" type="submit">Create New Product</button>
    </form>
</div>
@endsection
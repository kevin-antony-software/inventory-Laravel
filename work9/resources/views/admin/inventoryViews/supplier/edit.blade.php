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
    <form method="POST" action="{{ route('supplier.update', $supplier->id) }}">
    @csrf @method('PUT')

        <div class="form-group">
            <label for="company">supplier Name</label>
            <input type="text" class="form-control" id="company" name="company" aria-describedby="company" value="{{ $supplier->company }}" required>
        </div>
    @error('company')
        <div class="text-danger">{{ $message }}</div>
    @enderror

    <div class="form-group">
            <label for="address">address</label>
            <input type="text" class="form-control" id="address" name="address" aria-describedby="address" value="{{ $supplier->address }}">
        </div>
    @error('address')
        <div class="text-danger">{{ $message }}</div>
    @enderror

    <div class="form-group">
            <label for="country">country</label>
            <input type="text" class="form-control" id="country" name="country" aria-describedby="country" value="{{ $supplier->country }}">
        </div>
    @error('country')
        <div class="text-danger">{{ $message }}</div>
    @enderror

    <div class="form-group">
            <label for="email">email</label>
            <input type="email" class="form-control" id="email" name="email" aria-describedby="email" value="{{ $supplier->email }}" >
        </div>
    @error('address')
        <div class="text-danger">{{ $message }}</div>
    @enderror

    <div class="form-group">
            <label for="mobile">Mobile</label>
            <input type="number" class="form-control" id="mobile" name="mobile" aria-describedby="mobile" value="{{ $supplier->mobile }}">
        </div>
    @error('mobile')
        <div class="text-danger">{{ $message }}</div>
    @enderror

    <div class="form-group">
            <label for="phone">Phone</label>
            <input type="number" class="form-control" id="phone" name="phone" aria-describedby="phone" value="{{ $supplier->phone }}">
        </div>
    @error('phone')
        <div class="text-danger">{{ $message }}</div>
    @enderror



        <button class="btn btn-block btn-primary" type="submit">Update supplier</button>
    </form>
</div>
@endsection
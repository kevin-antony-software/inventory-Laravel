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
    <form method="POST" action="{{ route('supplier.store') }}">
    @csrf
 
    <div class="form-group">
    <label for="company">Supplier Name</label>
    <input type="text" class="form-control" id="company" name="company" aria-describedby="company" value="{{ old('company') }}" required>
    @error('company')
    <div class="text-danger">{{ $message }}</div>
  @enderror
  </div>


  <div class="form-group">
    <label for="address">Address</label>
    <input type="text" class="form-control" id="address" name="address" aria-describedby="address" value="{{ old('address') }}">
    @error('address')
    <div class="text-danger">{{ $message }}</div>
  @enderror
  </div>


  <div class="form-group">
    <label for="email">email</label>
    <input type="email" class="form-control" id="email" name="email" aria-describedby="email" value="{{ old('email') }}">
    @error('email')
    <div class="text-danger">{{ $message }}</div>
  @enderror
  </div>
  
  <div class="form-group">
    <label for="phone">phone Number</label>
    <input type="number" class="form-control" id="phone" name="phone" aria-describedby="phone" value="{{ old('phone') }}">
    @error('phone')
    <div class="text-danger">{{ $message }}</div>
  @enderror
  </div>


  <div class="form-group">
    <label for="mobile">mobile Number</label>
    <input type="number" class="form-control" id="mobile" name="mobile" aria-describedby="mobile" value="{{ old('mobile') }}">
    @error('mobile')
    <div class="text-danger">{{ $message }}</div>
  @enderror
  </div>
  
  <div class="form-group">
    <label for="country">country</label>
    <input type="text" class="form-control" id="country" name="country" aria-describedby="country" value="{{ old('country') }}">
    @error('country')
    <div class="text-danger">{{ $message }}</div>
  @enderror
  </div>



        <button class="btn btn-block btn-primary" type="submit">Create New Supplier</button>
    </form>
</div>
@endsection
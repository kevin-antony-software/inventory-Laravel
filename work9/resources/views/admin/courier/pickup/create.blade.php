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
        <form method="POST" action="{{ route('CourierPickup.store') }}">
            @csrf
            <label for="customer" class="form-label">Choose the customer</label>
            <input class="form-control" list="listofCustomers" name="customer" id="customer" autocomplete="off">
            <datalist id="listofCustomers">
                <option value="ALL">
                    @foreach ($CourierCustomers as $customer)
                <option value="{{ $customer->courier_customer_name }}">
                    @endforeach
            </datalist>
            <div class="form-group">
                <label for="model">Model</label>
                <input type="text" class="form-control" id="model" name="model" value="{{ old('model') }}">
                @error('model')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="warranty" id="inlineRadio1" value="withWarranty">
                    <label class="form-check-label" for="inlineRadio1">With Warranty</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="warranty" id="inlineRadio2" value="withoutWarranty">
                    <label class="form-check-label" for="inlineRadio2">Without Warranty</label>
                </div>
            </div>
            <button class="btn btn-block btn-primary" type="submit">Create New Pickup</button>
        </form>
    </div>

@endsection

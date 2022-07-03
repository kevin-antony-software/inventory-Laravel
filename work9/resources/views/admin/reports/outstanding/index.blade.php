@extends('dashboard')
@section('content')

@if (session()->has('error'))
<div class="alert alert-danger"> {{ session()->get('error') }} </div>
@endif



    <div class="container-fluid">
        <form method="POST" action="{{ route('Outstanding.store') }}">
            @csrf
            <label for="customer" class="form-label">Choose the customer or ALL:</label>
            <input class="form-control" list="listofCustomers" name="customer" id="customer">
            <datalist id="listofCustomers">
                <option value="ALL">
                    @foreach ($customers as $customer)
                <option value="{{ $customer->customer_name }}">
                    @endforeach
            </datalist>
            <div class="col-lg-12 pt-2">
                <div class="form-group">
                    <input type="submit" class="btn btn-block btn-primary" value="Submit">
                </div>
            </div>
        </form>
    </div>
@endsection

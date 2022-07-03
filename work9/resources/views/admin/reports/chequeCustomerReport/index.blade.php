@extends('dashboard')
@section('content')
    <div class="container">
        <form method="POST" action="{{ route('chequeCustomerReport.store') }}">
            @csrf
            <div class="row">
                <label class="form-group" for="Customer">Select Cheque Customer</label>
                <select class="form-control" id="Customer" name="Customer">
                    <option selected>Open this select menu</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group pt-2">
                <input type="submit" class="btn btn-block btn-info" value="Search">
            </div>
        </form>
    </div>
@endsection

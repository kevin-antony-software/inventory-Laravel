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
        <a href="{{ route('customer.index') }}" class="btn btn-primary float-right" style="margin-top: 5px;">Back to Index
        </a>
    </div>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <div class="container">
        <div class="table-responsive">
            <table class="table" style="margin-top: 5px;">
                <thead>
                    <tr>
                        <th scope="col">Item</th>
                        <th scope="col">Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Customer Name</th>
                        <td>{{ $customer->customer_name }}</td>
                    </tr>
                    <tr>
                        <td>Customer Type</th>
                        <td>{{ $customer->customer_type }}</td>
                    </tr>
                    <tr>
                        <td>Address</th>
                        <td>{{ $customer->address }}</td>
                    </tr>
                    <tr>
                        <td>Mobile</th>
                        <td>{{ $customer->mobile }}</td>
                    </tr>
                    <tr>
                        <td>city</th>
                        <td>{{ $customer->city }}</td>
                    </tr>
                    <tr>
                        <td>credit limit</th>
                        <td>{{ $customer->creditLimit }}</td>
                    </tr>
                    <tr>
                        <td>Owner</th>
                        <td>{{ $customer->owner_name }}</td>
                    </tr>
                    <tr>
                        <td>Phone</th>
                        <td>{{ $customer->phone }}</td>
                    </tr>

                    <tr>
                        <td>Email</th>
                        <td>{{ $customer->email }}</td>
                    </tr>
                    <tr>
                        <td>VAT Number</th>
                        <td>{{ $customer->VATnumber }}</td>
                    </tr>
                    <tr>
                        <td>BR Number</th>
                        <td>{{ $customer->BRnumber }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

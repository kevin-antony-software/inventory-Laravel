@extends('dashboard')
@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success">{{ session()->get('message') }}</div>
    @elseif (session()->has('error'))
        <div class="alert alert-danger"> {{ session()->get('error') }} </div>
    @endif
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <style>
        .ui-autocomplete {
            max-height: 100px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        * html .ui-autocomplete {
            height: 100px;
        }

    </style>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function() {
            var cusJqery = {!! json_encode($customers->toArray()) !!};
            var prodJqery = {!! json_encode($products->toArray()) !!};
            var cusName = [];
            var prod = [];
            for (var ckj = 0; ckj < cusJqery.length; ckj++) {
                cusName.push(cusJqery[ckj].customer_name);
            }

            for (var ckjp = 0; ckjp < prodJqery.length; ckjp++) {
                prod.push(prodJqery[ckjp].product_name);
            }

            $("#customer_name").autocomplete({
                source: cusName
            });

            $("#product_name").autocomplete({
                source: prod
            });

        });
    </script>
    <div class="container-fluid">
        <form method="POST" action="{{ route('InvoiceProductCustomer.store') }}">
            @csrf
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="customer_name" class="col-md-3">Customer: </label>
                        <input id="customer_name" name="customer_name" class="form-control"
                            value="{{ old('customer_name') }}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="product_name" class="col-md-3">Product: </label>
                        <input id="product_name" name="product_name" class="form-control"
                            value="{{ old('product_name') }}">
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <input type="submit" class="btn btn-block btn-info form-control" value="Search">
                </div>
            </div>
    </div>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js">
    </script>
    <div class="container-fluid pt-2">
        <div class="pt-2">
            <table id="myTable" class="display" width="100%">
                <thead class="thead-dark">
                    <tr>
                        <th width=10%>Invoice ID</th>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Qty </th>
                        <th>Price </th>
                        <th>Discount % </th>
                        <th>Price after Discount </th>
                        <th>Subtotal</th>
                        <th>Date </th>
                        <th width=10%>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @empty($invoicedetails)
                        <tr> No Data Found </tr>
                    @else
                        @foreach ($invoicedetails as $inv)
                            <tr>
                                <td> {{ $inv->invoice_id }}</td>
                                <td> {{ $inv->product_name }} </td>
                                <td> {{ $inv->customer_name }} </td>
                                <td> {{ $inv->qty }} </td>
                                <td> {{ $inv->price }}</td>
                                <td> {{ $inv->discountPercentage }}</td>
                                <td> {{ $inv->priceAfterDiscount }}</td>
                                <td> {{ $inv->subtotal_price }}</td>
                                <td> {{ $inv->created_at }}</td>
                                <td>
                                    <a href="{{ route('invoice.show', $inv->invoice_id) }}" class="fas fa-eye"
                                        target="_blank"></a>
                                </td>
                            </tr>
                        @endforeach
                    @endempty
                </tbody>
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
            });
        });
    </script>
@endsection

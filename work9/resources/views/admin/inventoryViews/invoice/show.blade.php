@extends('dashboard')
@section('content')
    <style>
        #headTable {
            margin-left: 20px;
            margin-top: 20px;
            margin-bottom: 20px;
            border-collapse: collapse;
            border: 1px solid white;
        }

    </style>

    <div class="clearfix">
        <a class="btn btn-success float-right" href="{{ route('invoice.index') }}" role="button">Back to Index</a>
    </div>

    <table id="headTable">
        <tr>
            <th>Invoice ID:</th>
            <td>{{ $invoice->id }}</td>
        </tr>
        <tr>
            <th>Warehouse:</th>
            <td>{{ $invoice->warehouse_name }}</td>
        </tr>
        <tr>
            <th>Supplier:</th>
            <td>{{ $invoice->customer_name }}</td>
        </tr>
        <tr>
            <th>User:</th>
            <td>{{ $invoice->user_name }}</td>
        </tr>
        <tr>
            <th>Date:</th>
            <td>{{ $invoice->created_at->format('d/m/Y') }}</td>
        </tr>

    </table>

    <div class="container-fluid">
        <div class="col-md-12 col-xs-12">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th width=10%>Code</th>
                            <th width=40%>Product Name</th>
                            <th width=10%>Quantity</th>
                            <th width=20%>discount %</th>
                            <th width=20%>Price after discount</th>
                            <th width=20%>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($invoicedetails as $row)
                            <tr>
                                <td>{{ $row->product_id }}</td>
                                <td>{{ $row->product_name }}</td>
                                <td>{{ $row->qty }}</td>
                                <td>{{ $row->discountPercentage }}</td>
                                <td>{{ $row->priceAfterDiscount }}</td>
                                <td>{{ $row->subtotal_price }}</td>

                            </tr>
                        @endforeach

                        <tr>

                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><b>Total Price</b></td>
                            <td>{{ $invoice->total }}</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><b>Payed Amount</b></td>
                            <td>{{ $invoice->payed }}</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><b>Due Amount</b></td>
                            <td>{{ $invoice->dueAmount }}</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

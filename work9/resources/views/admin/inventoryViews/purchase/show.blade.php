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
        <a href="{{ route('purchase.index') }}" class="btn btn-primary float-right" style="margin-top: 5px;">Back to Index
        </a>
    </div>
    <style>
        th {
            width: 20%;
        }

        td {
            width: 50%;
        }
    </style>

    <div class="container pt-5">
        <div class="row">
            <div class="col-3 text-white" style="background: rgb(192, 194, 197);">
                <h4 style="text-align: center;">To : {{ $purchase->warehouse->warehouse_name }} Warehouse</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-3 text-white" style="background: rgb(192, 194, 197);">
                <h4 style="text-align: center;">Date : {{ $purchase->created_at }}</h4>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <table class="table table-hover table-bordered table-sm" style="margin-top: 5px;">
            <thead class="thead-light">
                <tr style="text-align: center;">
                    <th style = "width: 80%;" scope="col">Product Name</th>
                    <th scope="col">Qty</th>
                </tr>
            </thead>
            @if (count($purchase->purchasedetails))
                @foreach ($purchase->purchasedetails as $p)
                    <tbody>
                        <tr style="text-align: center;">
                            <td scope="row">{{ $p->product->product_name }}</td>
                            <td>{{ $p->qty }}</td>

                        </tr>
                @endforeach
            @else
                <tr> No Data Found </tr>
            @endif
            </tbody>
        </table>

    </div>
@endsection

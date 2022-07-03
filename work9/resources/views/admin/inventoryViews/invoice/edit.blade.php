@extends('dashboard')
@section('content')
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    <div class="container">
        <form method="POST" action="{{ route('invoice.update', $invoice->id) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @method('PUT')

            <div class="form-group">
                <div class="row">
                    <div class="col-3 col-md-3">
                        <label for="invoice_id">Invoice No.</label>
                        <input type="text" class="form-control" id="invoice_id" name="invoice_id"
                            aria-describedby="invoice_id" value="{{ $invoice->id }}" disabled>
                    </div>
                    <div class="col-9 col-md-9">
                        <label for="name">Customer Name</label>
                        <input type="text" class="form-control" id="name" name="name" aria-describedby="name"
                            value="{{ $invoice->customer_name }}" disabled>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="col-md-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width=10%>Code</th>
                                    <th width=40%>Product Name</th>
                                    <th width=10%>Quantity</th>
                                    <th width=20%>Return Qty</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($invoiceDetails as $row)
                                    <tr>

                                        <td>{{ $row->product_id }}</td>
                                        <td>{{ $row->product_name }}</td>
                                        <td>{{ $row->qty }}</td>
                                        <td><input type="text" name="quantity{!! $i !!}"
                                                id="quantity{!! $i !!}" class="form-control changesNo"
                                                autocomplete="off" ondrop="return false;" onpaste="return false;"
                                                value="{{ old('quantity' . $i) }}"></td>
                                        <input type="hidden" id="invoicedetailID{!! $i !!}"
                                            name="invoicedetailID{!! $i !!}" value="{{ $row->id }}">
                                        @php
                                            $i++;
                                        @endphp
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary" type="submit">Update Invoice</button>

        </form>
    </div>
@endsection

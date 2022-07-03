@extends('dashboard')
@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
        <div class="container-fluid pt-2">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('returnItems.create') }}" class="btn btn-primary ">Create Return</a>
                </div>
            </div>
            <div class="pt-2">
                <table id="myTable" class="display" width="100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Invoice ID</th>
                            <th>Warehouse</th>
                            <th>Prod ID</th>
                            <th>Prod name</th>
                            <th>Qty</th>
                            <th>price of Each</th>
                            <th>Old Total</th>
                            <th>New Total</th>
                            <th>Date</th>

                        </tr>
                    </thead>
                    <tbody>
                        @if (count($returnItems))
                            @foreach ($returnItems as $returnItem)
                                <tr>
                                    <td> {{ $returnItem->id }}</td>
                                    <td> {{ $returnItem->customer_name }} </td>
                                    <td> {{ $returnItem->invoice_id }} </td>
                                    <td> {{ $returnItem->warehouse_name }} </td>
                                    <td> {{ $returnItem->product_id }} </td>
                                    <td> {{ $returnItem->product_name }} </td>
                                    <td> {{ $returnItem->qty }} </td>
                                    <td> {{ $returnItem->price_of_each }} </td>
                                    <td> {{ $returnItem->old_total }} </td>
                                    <td> {{ $returnItem->new_total }} </td>
                                    <td> {{ $returnItem->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr> No Data Found </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="float-right">
            {{ $returnItems->links() }}
        </div>
        <script>
            $(document).ready(function() {
                $('#myTable').DataTable({
                    "order": [
                        [0, "desc"]
                    ],
                    "bPaginate": false,
                });
            });
        </script>
@endsection

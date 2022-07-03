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
                    <a href="{{ route('writeoff.create') }}" class="btn btn-primary ">Add new Writeoff</a>
                </div>
            </div>
            <div class="pt-2">
                <table id="myTable" class="display" width="100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Type </th>
                            <th>Invoice / Job ID </th>
                            <th>Amount </th>
                            <th>Date </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($writeoffs))
                            @foreach ($writeoffs as $writeoff)
                                <tr>
                                    <td> {{ $writeoff->id }}</td>
                                    <td> {{ $writeoff->customer_name }}</td>
                                    <td> {{ $writeoff->type }}</td>
                                    <td> {{ $writeoff->invoice_id }}</td>
                                    <td> {{ $writeoff->amount }}</td>
                                    <td> {{ $writeoff->created_at->format('d/m/Y') }}</td>
                                   
                                </tr>
                            @endforeach
                        @else
                            <tr> No Data Found </tr>
                        @endif
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
                    ]
                });
            });
        </script>
@endsection

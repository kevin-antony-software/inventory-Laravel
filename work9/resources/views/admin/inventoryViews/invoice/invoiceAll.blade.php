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
            <div class="pt-2">
                <table id="myTable" class="display" width="100%">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width:10%;">ID</th>
                            <th>Customer </th>
                            <th>Total Amount </th>
                            <th>Due Amount </th>
                            <th>Invoice Date </th>
                            <th>User </th>
                            <th>Status </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($invoices))
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <td> {{ $invoice->id }}</td>
                                    <td> {{ $invoice->customer_name }} </td>
                                    <td> {{ $invoice->total }}</td>
                                    <td> {{ $invoice->dueAmount }}</td>
                                    <td> {{ \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y') }} </td>
                                  
                                    <td> {{ $invoice->user_name }}</td>
                                    <td> {{ $invoice->status }}</td>

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
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "paging": false,
                });
            });
        </script>

@endsection

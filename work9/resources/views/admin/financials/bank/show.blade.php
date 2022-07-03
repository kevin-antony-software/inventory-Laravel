@extends('dashboard')
@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <div class="container">

        <a href="{{ route('bank.index') }}" class="btn btn-primary" style="margin-top: 5px;">Back to Index </a>
    
    </div>
    <section class="content">
        <div class="container-fluid pt-2">


            <div class="pt-2">
                <table id="myTable" class="display" width="100%">

                    <thead class="thead-dark">
                        <tr>
                            <th style="width:10%;">ID</th>
                            <th style="width:10%;">Bank ID </th>
                            <th style="width:10%;">Payment ID </th>
                            <th style="width:10%;">Expense ID </th>
                            <th style="width:10%;">Reason </th>
                            <th style="width:10%;">Credit </th>
                            <th style="width:10%;">Debit </th>
                            <th style="width:10%;">balance </th>
                            <th style="width:10%;">Date </th>

                        </tr>
                    </thead>
                    <tbody>
                        @if (count($bankDetails))
                            @foreach ($bankDetails as $item)
                                <tr>
                                    <td> {{ $item->id }}</td>
                                    <td> {{ $item->bank_id }} </td>
                                    <td> {{ $item->payment_id }} </td>
                                    <td> {{ $item->expense_id }} </td>
                                    <td> {{ $item->reason }} </td>
                                    <td> {{ $item->credit }} </td>
                                    <td> {{ $item->debit }} </td>
                                    <td> {{ number_format($item->bankBalance,2) }} </td>
                                    <td> {{ $item->created_at }} </td>


                                </tr>
                            @endforeach
                        @else
                            <tr> No Data Found </tr>
                        @endif
                    </tbody>
                </table>
                {{ $bankDetails->links() }}
            </div>

        </div>

        <script>
            $(document).ready(function() {
                $('#myTable').DataTable({
                    "paging": false,
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

    </section>

@endsection

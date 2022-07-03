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

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href=https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css>
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>



<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/plug-ins/1.10.20/filtering/row-based/range_dates.js"></script>



    <div class="container">

        <div class="pt-2">
            <table id="myTable" class="display" width="100%">
                <thead class="thead-dark">
                    <tr>
                        <th>Payment ID</th>
                        <th>Payment Date</th>
                        <th>Payment Method</th>
                        <th>Invoice ID</th>
                        <th>Invoice Date</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Days</th>
                        <th>Com %</th>
                        <th>Commission</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($commissions))
                        @foreach ($commissions as $com)
                            <tr>
                                <td> {{ $com->payment_id }}</td>
                                <td> {{ $com->payment_date }} </td>
                                <td> {{ $com->payment_method }} </td>
                                <td> {{ $com->invoice_id }} </td>
                                <td> {{ $com->invoice_date }} </td>
                                <td> {{ $com->customer_name }} </td>
                                <td> {{ $com->amount }} </td>
                                <td> {{ $com->days }} </td>
                                <td> {{ $com->commission_percentage }} </td>
                                <td> {{ $com->commission }} </td>

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

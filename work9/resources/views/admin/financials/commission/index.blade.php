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
                        <th>Owner</th>
                        <th>Month</th>
                        <th>Year</th>
                        <th>Status</th>
                        <th>Commission</th>
                        <th>Paid</th>
                        <th>Return Cheque</th>
                        <th>Invoice Due Amount</th>
                        <th>Updated Date</th>
                        @can('director-only')
                            <th>Action</th>
                        @endcan

                    </tr>
                </thead>
                <tbody>
                    @if (count($commissions))
                        @foreach ($commissions as $com)
                            <tr>
                                <td> {{ $com->owner_name }}</td>
                                <td> {{ $com->month }} </td>
                                <td> {{ $com->year }} </td>
                                <td> {{ $com->status }} </td>
                                <td> {{ $com->totalCommission }} </td>
                                <td> {{ $com->paidCommission }} </td>
                                <td> {{ $com->returnChequeCommission }} </td>
                                <td> {{ $com->invoiceDueAmount }} </td>
                                <td> {{ $com->updated_at }} </td>
                                @can('director-only')
                                    <td>

                                        <a href="{{ route('commission.edit', $com->id) }}" class="far fa-edit"></a>
                                        <a href="{{ route('commission.show', $com->id) }}" class="far fa-eye"></a>

                                    </td>
                                @endcan
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

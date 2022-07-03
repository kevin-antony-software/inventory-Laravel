@extends('dashboard')
@section('content')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<div class="container-fluid pt-2">
    <div class="pt-2">
        <table id="myTable" class="display" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th>Job ID</th>
                    <th>Customer</th>
                    <th>Closed Time</th>
                    <th>Issue</th>
                    <th>Serial Number</th>
                    <th>Component ID</th>
                    <th>Component Name</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                @if (count($jobDetails))
                    @foreach ($jobDetails as $item)
                        <tr>
                            <td> {{ $item->id }} </td>
                            <td> {{ $item->customer_name }} </td>
                            <td> {{ $item->jobClosedTime }}</td>
                            <td> {{ $item->issue }} </td>
                            <td> {{ $item->serialNum }} </td>
                            <td> {{ $item->component_id }} </td>
                            <td> {{ $item->component_name }}</td>
                            <td> {{ $item->qty }} </td>
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
                [0, "asc"]
            ],
            "lengthMenu": [
                [25, 50, -1],
                [25, 50, "All"]
            ],
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
        });
    });
</script>

@endsection

@extends('dashboard')
@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>

    <section class="content">
        <div class="container-fluid pt-2">
            <div>
                <a href="{{ route('componentPurchase.create') }}" class="btn btn-primary"> Add new Purchase</a>
            </div>
            <div class="pt-2">
                <table id="myTable" class="display" width="100%">

                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Component ID</th>
                            <th>Component Name</th>
                            <th>Qty</th>
                            <th>Date</th>
                            <th>Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($purchases))
                            @foreach ($purchases as $purchase)
                                <tr>
                                    <td> {{ $purchase->id }}</td>
                                    <td> {{ $purchase->component_id }}</td>
                                    <td> {{ $purchase->component_name }}</td>
                                    <td> {{ $purchase->qty }}</td>
                                    <td> {{ $purchase->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @can('director-only')
                                            <a href="{{ route('componentPurchase.edit', $purchase->id) }}" class="far fa-edit"></a>
                                        @endcan
                                    </td>
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
    </section>
@endsection

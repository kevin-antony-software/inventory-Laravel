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
            <div class="pt-2">
                <table id="myTable" class="display" width="100%">

                    <thead class="thead-dark">
                        <tr>

                            <th style="width:10%;"> ID</th>
                            <th style="width:10%;"> Product ID </th>
                            <th style="width:20%;"> Product Name </th>
                            @can('managers-only')
                                <th style="width:10%;"> Price </th>
                            @endcan
                            <th style="width:10%;"> Category </th>
                            <th style="width:10%;"> Warehouse </th>
                            <th style="width:15%;"> Qty </th>


                        </tr>
                    </thead>
                    <tbody>
                        @if (count($inventory))
                            @foreach ($inventory as $i)
                                <tr>
                                    <td> {{ $i->id }}</td>
                                    <td> {{ $i->product_id }} </td>
                                    <td> {{ $i->product_name }} </td>
                                    @can('managers-only')
                                        <td> {{ $i->price }} </td>
                                    @endcan
                                    <td> {{ $i->category_name }} </td>
                                    <td> {{ $i->warehouse_name }} </td>
                                    <td> {{ $i->qty }} </td>

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

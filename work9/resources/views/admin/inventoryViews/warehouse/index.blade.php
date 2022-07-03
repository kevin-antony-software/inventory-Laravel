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
            <div class="row">
                <div class="col-md-6">

                    <a href="{{ route('warehouse.create') }}" class="btn btn-primary ">Add new Warehouse</a>

                </div>
            </div>
            <div class="pt-2">
                <table id="myTable" class="display" width="100%">

                    <thead class="thead-dark">
                        <tr>
                            <th style="width:10%;">ID</th>
                            <th style="width:20%;">Warehouse Name </th>
                            <th style="width:20%;">Actions </th>

                        </tr>
                    </thead>
                    <tbody>
                        @if (count($warehouses))
                            @foreach ($warehouses as $warehouse)
                                <tr>
                                    <td> {{ $warehouse->id }}</td>
                                    <td> {{ $warehouse->warehouse_name }} </td>
                                    <td><a href="{{ route('warehouse.edit', $warehouse->id) }}" class="far fa-edit fa-fw"
                                            style="margin-right: 10px;"></a>
                                        <a href="javascript:void(0)" onclick="$(this).parent().find('form').submit()"
                                            class="far fa-trash-alt fa-fw"></a>
                                        <form action="{{ route('warehouse.destroy', $warehouse->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                        </form>
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

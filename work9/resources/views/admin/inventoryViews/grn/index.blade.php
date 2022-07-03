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
                @can('managers-only')
                <div class="col-md-6">
                    <a href="{{ route('grn.create') }}" class="btn btn-primary ">Add new GRN</a>
                </div>
                @endcan

            </div>
            <div class="pt-2">
                <table id="myTable" class="display" width="100%">

                    <thead class="thead-dark">
                        <tr>
                            <th style="width:10%;">ID</th>
                            <th style="width:20%;">From Warehouse </th>
                            <th style="width:20%;">To Warehouse </th>
                            <th style="width:20%;">User </th>
                            <th style="width:20%;">Status </th>
                            <th style="width:20%;">Date </th>
                            @can('managers-only')
                                <th>Action</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($grn))
                            @foreach ($grn as $g)
                                <tr>
                                    <td> {{ $g->id }}</td>
                                    <td> {{ $g->FromWarehouse_name }} </td>
                                    <td> {{ $g->ToWarehouse_name }} </td>
                                    <td> {{ $g->user_name }} </td>
                                    <td> {{ $g->status }} </td>
                                    <td>{{ Carbon\Carbon::parse($g->created_at)->format('Y-m-d') }}</td>
                                    
                                    
                                        <td>
                                            <a href="{{ route('grn.show', $g->id) }}" class="fas fa-eye"></a>
                                            @can('managers-only')
                                            <a href="{{ route('grn.generatePDF', $g->id) }}" class="fas fa-print"></a>
                                            @can('director-only')
                                                @if ($g->status != 'Received')
                                                    <a href="{{ route('grn.edit', $g->id) }}" class="far fa-edit"></a>
                                                    <a href="javascript:void(0)" onclick="$(this).parent().find('form').submit()"
                                                        class="far fa-trash-alt"></a>
                                                    <form action="{{ route('grn.destroy', $g->id) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endif
                                            @endcan
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
        <div class="float-right">
            {{ $grn->links() }}
        </div>



        <script>
            $(document).ready(function() {
                $('#myTable').DataTable({
                    "order": [
                        [0, "desc"]
                    ],
                    "bPaginate": false,
                    //   dom: 'Bfrtip',
                    //     buttons: [
                    //         'copy', 'csv', 'excel', 'pdf', 'print'
                    //     ]
                });
            });
        </script>

    </section>
@endsection

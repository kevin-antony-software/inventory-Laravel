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

                    <a href="{{ route('commission.index') }}" class="btn btn-primary ">Back to Index</a>

                </div>
            </div>
            <div class="pt-2">
                <table id="myTable" class="display" width="100%">

                    <thead class="thead-dark">
                        <tr>
                            <th style="width:10%;">Year</th>
                            <th style="width:10%;">Month</th>
                            <th style="width:10%;">Commission Amount</th>
                            <th style="width:10%;">Invoice Amount</th>
                            <th style="width:10%;">Due Amount</th>


                        </tr>
                    </thead>
                    <tbody>
                        @if (count($datas))
                            @foreach ($datas as $data)
                                <tr>
                                    <td> {{ $data->year }}</td>
                                    <td> {{ $data->month }} </td>
                                    <td> {{ $data->comValue }} </td>
                                    <td> {{ $data->invoiceValue }} </td>
                                    <td> {{ $data->dueAmount }} </td>


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

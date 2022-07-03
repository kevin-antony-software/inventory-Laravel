@extends('dashboard')
@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>

        <div class="container pt-2">
            @can('managers-only')
            <div>
                <a href="{{ route('issues.create') }}" class="btn btn-primary"> Add new issue</a>
            </div>
            @endcan

            <div class="pt-2">
                <table id="myTable" class="display" width="100%">

                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Issue </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($issues))
                            @foreach ($issues as $issue)
                                <tr>
                                    <td> {{ $issue->id }}</td>
                                    <td> {{ $issue->issue }} </td>

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
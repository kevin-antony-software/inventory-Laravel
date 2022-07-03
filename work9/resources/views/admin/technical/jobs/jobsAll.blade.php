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
                        <th>JobID</th>
                        <th>View Job</th>
                        <th>Customer </th>
                        <th>Serial Number </th>
                        <th>Previous </th>
                        <th>Duration </th>
                        <th>Warranty </th>
                        <th>Status </th>
                        <th>Issue </th>
                        <th>Start Time </th>
                        <th>End Time </th>
                        <th>Job Closed User </th>
                        <th>Model </th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($jobs))
                        @foreach ($jobs as $job)
                            <tr>
                                <td> {{ $job->id }}</td>
                                <td> <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-primary">view</a></td>
                                <td> {{ $job->customer_name }} </td>
                                <td> {{ $job->serialNum }} </td>
                                <td> {{ $job->repairTimes }} </td>
                                <td> {{ $job->duration }} </td>
                                <td> {{ $job->warranty }} </td>
                                <td> {{ $job->jobStatus }}</td>
                                <td> {{ $job->issue }}</td>
                                <td> {{ $job->jobStartTime }}</td>
                                <td> {{ $job->jobClosedTime }}</td>
                                <td> {{ $job->jobClosedUser_name }}</td>
                                <td> {{ $job->model }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>No Data Found</tr>
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
                "bPaginate": false,
            });
        });
    </script>
@endsection

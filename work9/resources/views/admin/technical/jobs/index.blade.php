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
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('jobs.create') }}" class="btn btn-primary ">Add new Job</a>
            </div>
        </div>
        <div class="pt-2">
            <table id="myTable" class="display" width="100%">
                <thead class="thead-dark">
                    <tr>
                        <th width=5%>JobID</th>
                        <th>Action</th>
                        <th>View Job</th>
                        <th>Customer </th>
                        <th>Serial Number </th>
                        <th>Previous </th>
                        <th>Duration </th>
                        <th width=15%>Warranty </th>
                        <th>Status </th>
                        <th>Issue </th>
                        <th width=15%>Start Time </th>
                        <th>Model </th>
                        {{-- <th>Mach Type</th> --}}
                        <th>Sold Date</th>
                        @can('director-only')
                            <th>Action</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @if (count($jobs))
                        @foreach ($jobs as $job)
                            <tr>
                                <td> {{ $job->id }}</td>
                                <td>
                                    @if ($job->jobStatus == 'Job-Created')
                                        <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-sm btn-primary p-1">Start Job</a>
                                    @elseif($job->jobStatus == 'jobStarted')
                                        <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-sm btn-primary m-1 p-1">Close </a>
                                        <a href="{{ route('jobs.suspend', $job->id) }}" class="btn btn-sm btn-primary m-1 p-1">Suspend
                                            </a>
                                        <a href="{{ route('jobs.estimate', $job->id) }}" class="btn btn-sm btn-primary m-1 p-1">Estimate
                                            </a>
                                    @elseif($job->jobStatus == 'estimated')
                                        <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-sm btn-primary">Close Job</a>
                                    @elseif($job->jobStatus == 'suspended')
                                        <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-sm btn-primary">Restart</a>
                                    @elseif($job->jobStatus == 'jobClosed')
                                        <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-sm btn-primary">Upload
                                            Images</a>
                                    @elseif($job->jobStatus == 'imageUploaded')
                                        <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-sm btn-primary">Deliver</a>
                                    @endif
                                </td>
                                <td>
                                    {{-- @if ($job->jobStatus == 'imageUploaded' || $job->jobStatus == 'Delivered') --}}
                                    <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-primary">view</a>
                                    {{-- @endif --}}
                                </td>
                                <td> {{ $job->customer_name }} </td>
                                <td> {{ $job->serialNum }} </td>
                                <td> {{ $job->repairTimes }} </td>
                                <td> {{ $job->duration }} </td>
                                <td>
                                    <div class="row">
                                        @can('director-only')
                                            <a href="javascript:void(0)" onclick="$(this).parent().find('form').submit()">
                                                <div class="form-group form-check">
                                                    <input type="checkbox" class="form-check-input" id="exampleCheck1"
                                                        @if ($job->warranty == 'withWarranty') @checked(true) @endif>
                                                    <label class="form-check-label" for="exampleCheck1"></label>
                                                </div>
                                            </a>
                                            <form action="{{ route('jobs.changeWarranty', $job->id) }}" method="post">
                                                @csrf
                                            </form>
                                        @endcan
                                        {{ $job->warranty }}
                                    </div>

                                </td>
                                <td> {{ $job->jobStatus }}</td>
                                <td> {{ $job->issue }}</td>
                                <td> {{ $job->jobStartTime }}</td>
                                <td> {{ $job->model }}</td>
                                {{-- <td> {{ $job->machineType }}</td> --}}
                                <td> {{ $job->soldDate }} test</td>

                                @can('director-only')
                                    <td>
                                        <a href="javascript:void(0)" onclick="$(this).parent().find('form').submit()"
                                            class="far fa-trash-alt"></a>
                                        <form action="{{ route('jobs.destroy', $job->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    @else
                        <tr>No Data Found</tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="container">
        {{-- <div class="float-right"> --}}
            {{ $jobs->links() }}
        {{-- </div> --}}
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

@extends('dashboard')
@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <div class="container-fluid pt-2">
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('CourierPickup.create') }}" class="btn btn-primary ">Add new PickUp</a>
            </div>
        </div>
        <div class="pt-2">
            <table id="myTable" class="display" width="100%">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Customer Name </th>
                        <th>Model</th>
                        <th>Warranty</th>
                        <th>Status</th>
                        <th>Date </th>
                        <th>Action </th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($Courierpickups))
                        @foreach ($Courierpickups as $Courierpickup)
                            <tr>
                                <td> {{ $Courierpickup->id }}</td>
                                <td> {{ $Courierpickup->courier_customer_name }} </td>
                                <td> {{ $Courierpickup->model }} </td>
                                <td> {{ $Courierpickup->warranty }} </td>
                                <td> {{ $Courierpickup->status }} </td>
                                <td> {{ $Courierpickup->created_at }} </td>
                                @if ($Courierpickup->status == 'pending')
                                    <td>
                                        <div class="row">
                                            <form action="{{ route('CourierPickup.update', $Courierpickup->id) }}"
                                                method="post">
                                                @csrf
                                                @method('PUT')
                                                <input type="number" name="COPICKID" id="COPICKID"
                                                    value="{{ $Courierpickup->id }}" hidden>
                                                <button class="btn btn-primary p-2 m-2" value="submit">Receive</button>
                                            </form>
                                            <a class="btn btn-danger p-2 m-2"
                                                href="{{ route('CourierPickup.edit', $Courierpickup->id) }}">Send
                                                Reminder</a>
                                        </div>
                                    </td>
                                @else
                                    <td>Received</td>
                                @endif

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

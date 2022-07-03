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
                    <a href="{{ route('CourierCustomer.create') }}" class="btn btn-primary ">Add new Customer</a>
                </div>
            </div>
            <div class="pt-2">
                <table id="myTable" class="display" width="100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Customer Name </th>
                            <th>Address</th>
                            <th>Mobile</th>
                            <th>Phone</th>
                            <th>Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($CourierCustomers))
                            @foreach ($CourierCustomers as $CourierCustomer)
                                <tr>
                                    <td> {{ $CourierCustomer->id }}</td>
                                    <td> {{ $CourierCustomer->courier_customer_name }} </td>
                                    <td> {{ $CourierCustomer->address }} </td>
                                    <td> {{ $CourierCustomer->mobile }} </td>
                                    <td> {{ $CourierCustomer->phone }} </td>
                                    <td>
                                        <a href="{{ route('CourierCustomer.edit', $CourierCustomer->id) }}"
                                            class="far fa-edit"></a>
                                        <a href="javascript:void(0)" onclick="$(this).parent().find('form').submit()"
                                            class="far fa-trash-alt"></a>
                                        <form action="{{ route('CourierCustomer.destroy', $CourierCustomer->id) }}"
                                            method="post">
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
@endsection

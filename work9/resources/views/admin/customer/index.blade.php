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

    <section class="content">
        <div class="container-fluid pt-2">
            <div class="row">
                <div class="col-md-6">

                    <a href="{{ route('customer.create') }}" class="btn btn-primary ">Add new Customer</a>

                </div>
            </div>
            <div class="pt-2">
                <table id="myTable" class="display" width="100%">

                    <thead class="thead-dark">
                        <tr>
                            <th style="width:10%;">ID</th>
                            <th style="width:20%;">Customer Name </th>
                            <th style="width:10%;">Mobile
                            <th style="width:10%;">Cus Type </th>
                            <th style="width:10%;">Credit Limit </th>
                            <th style="width:10%;">Cheque Limit </th>
                            <th style="width:10%;">City </th>
                            <th style="width:10%;">Owner </th>
                            <th style="width:20%;">Action </th>

                        </tr>
                    </thead>
                    <tbody>
                        @if (count($customers))
                            @foreach ($customers as $customer)
                                <tr>
                                    <td> {{ $customer->id }}</td>
                                    <td> {{ $customer->customer_name }} </td>
                                    <td> {{ $customer->mobile }} </td>
                                    <td> {{ $customer->customer_type }} </td>
                                    <td> {{ $customer->creditLimit }} </td>
                                    <td> {{ $customer->chequeLimit }} </td>
                                    <td> {{ $customer->city }} </td>
                                    <td> {{ $customer->owner_name }} </td>

                                    <td>
                                        <a href="{{ route('customer.show', $customer->id) }}" class="fas fa-eye"></a>
                                        <a href="{{ route('customer.edit', $customer->id) }}" class="far fa-edit"></a>

                                        <a href="javascript:void(0)" onclick="$(this).parent().find('form').submit()"
                                            class="far fa-trash-alt"></a>
                                        <form action="{{ route('customer.destroy', $customer->id) }}" method="post">
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

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

                    <a href="{{ route('payment.create') }}" class="btn btn-primary ">Add new Payment</a>

                </div>
            </div>
            <div class="pt-2">
                <table id="myTable" class="display" width="100%">

                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Customer Name </th>
                            <th>User </th>
                            <th>Method </th>
                            <th>Amount </th>
                            <th>Allocated </th>
                            <th>Balance </th>
                            <th>Status </th>
                            <th>Action </th>
                            <th>Receive</th>
                            <th width=20%>Link Invoice</th>

                        </tr>
                    </thead>
                    <tbody>
                        @if (count($payments))
                            @foreach ($payments as $payment)
                                <tr>
                                    <td> {{ $payment->id }}</td>
                                    <td> {{ $payment->customer_name }}</td>
                                    <td> {{ $payment->user_name }}</td>
                                    <td> {{ $payment->method }} </td>
                                    <td> {{ $payment->totalAmount }} </td>
                                    <td> {{ $payment->allocatedToInvoice }} </td>
                                    <td> {{ $payment->balanceToAllocate }} </td>
                                    <td> {{ $payment->status }} </td>
                                    <td>
                                        <a href="{{ route('payment.show', $payment->id) }}" class="fas fa-eye"></a>
                                        <a href="{{ route('payment.print', $payment->id) }}" class="fas fa-print"></a>
                                        @if ($payment->status == 'with sales')
                                            <a href="{{ route('payment.edit', $payment->id) }}"
                                                class="far fa-edit"></a>
                                        @endif
                                        <a href="javascript:void(0)" onclick="$(this).parent().find('form').submit()"
                                            class="far fa-trash-alt"></a>
                                        <form action="{{ route('payment.destroy', $payment->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')

                                        </form>
                                    </td>
                                    <td>
                                        @if ($payment->status == 'with sales')
                                            @can('director-only')
                                                <a href="javascript:void(0)"
                                                    onclick="$(this).parent().find('form').submit()">Receive</a>
                                                <form action="{{ route('paymentReceive.update', $payment->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('PUT')
                                                </form>
                                            @endcan
                                        @endif
                                    </td>
                                    <td>
                                        @if ($payment->balanceToAllocate > 0)
                                            <a class="btn btn-primary btn-sm" href="{{ route('LinkInvoice.link', $payment->id) }}" role="button">Link Invoice</a>
                                            <a class="btn btn-success btn-sm" href="" role="button">Link Repair Job</a>
                                        @else
                                            nothing to link
                                        @endif

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

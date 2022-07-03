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
                    <a href="{{ route('invoice.create') }}" class="btn btn-primary ">Create New Invoice</a>
                </div>
            </div>
            <div class="pt-2">
                <table id="myTable" class="display" width="100%">

                    <thead class="thead-dark">
                        <tr>
                            <th style="width:10%;">ID</th>
                            <th>Customer </th>
                            <th>Total Amount </th>
                            <th>Due Amount </th>
                            <th>Credit Days </th>
                            <th>Invoice Date </th>
                            <th width=15%>User </th>
                            <th>Status </th>
                            <th width=28%>Actions</th>

                        </tr>
                    </thead>
                    <tbody>
                        @if (count($invoices))
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <td> {{ $invoice->id }}</td>
                                    <td> {{ $invoice->customer_name }} </td>
                                    <td> {{ $invoice->total }}</td>
                                    <td> {{ $invoice->dueAmount }}</td>
                                    <td>
                                        @if ($invoice->status == 'not paid')
                                            <?php
                                            $today = date('d-m-Y');
                                            $cday = $invoice->created_at->format('d-m-Y');
                                            $dt1 = strtotime($today);
                                            $dt2 = strtotime($cday);
                                            $diff = $dt1 - $dt2;
                                            echo $diff / (24 * 60 * 60);
                                            ?>
                                        @else
                                            NA
                                        @endif

                                    </td>
                                    <td> {{ $invoice->created_at->format('d/m/Y') }}</td>
                                    <td> {{ $invoice->user_name }}</td>
                                    <td> {{ $invoice->status }}</td>
                                    <td>
                                        <style>
                                            .alinks a {

                                                padding-top: 10px;
                                                padding-bottom: 10px;
                                                width: 50px;
                                                margin-top: 3px;
                                                margin-left: 3px;
                                                margin-bottom: 3px;
                                                margin-right: 3px;
                                                height: auto;
                                                text-align: center;
                                                align-content: center;
                                                align-items: center;
                                                border: 1px solid #99ff66;
                                                border-radius: 10%;
                                            }

                                        </style>

                                        <div class="alinks">
                                            <a href="{{ route('invoice.show', $invoice->id) }}"
                                                class="fas fa-eye"></a>
                                            <a href="{{ route('invoice.print', $invoice->id) }}"
                                                class="fas fa-print"></a>
                                            <a href="{{ route('invoice.printVAT', $invoice->id) }}"
                                                class="fas fa-print" style="color: red; font-size: .8rem;">
                                                VAT </a>

                                            @can('director-only')
                                                <a href="{{ route('invoice.edit', $invoice->id) }}"
                                                    class="far fa-edit"></a>
                                                <a href="javascript:void(0)" onclick="$(this).parent().find('form').submit()"
                                                    class="far fa-trash-alt"></a>
                                                <form action="{{ route('invoice.destroy', $invoice->id) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endcan
                                        </div>
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
        <div class="float-right">
            {{ $invoices->links() }}
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

    </section>

@endsection

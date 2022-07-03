@extends('dashboard')
@section('content')


    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>

    <div class="container pt-2">
        <div class="row">
            <div class="col-12">
                <a href="{{ route('InvoicePayment.index') }}" class="btn btn-info"><strong> Back to Find</strong></a>
            </div>
        </div>
    </div>

    <div class="container-fluid pt-2">

        <div class="pt-2">
            <table id="myTable" class="display" width="100%">

                <thead class="thead-dark">
                    <tr>
                        @if ($type == 'Invoice')
                            <th>Invoice ID</th>
                        @else
                            <th>Job ID</th>
                        @endif
                        <th>Payment ID</th>
                        <th>Amount for this Invoice/Job</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($payments))
                        @foreach ($payments as $payment)
                            <tr>
                                @if ($type == 'Invoice')
                                    <td>
                                        <a href="{{ route('invoice.show', $payment->invoice_id) }}">{{ $payment->invoice_id }}</a>
                                    </td>
                                @else
                                    <td>
                                        <a href="{{ route('jobs.show', $payment->job_id) }}">{{ $payment->job_id }}</a>
                                    </td>
                                @endif
                                <td>
                                    <a href="{{ route('payment.show', $payment->payment_id) }}">{{ $payment->payment_id }}</a>
                                </td>
                                <td> {{ $payment->amount }} </td>
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

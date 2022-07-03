@extends('dashboard')
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="container">
        <a href="{{ route('payment.index') }}" class="btn btn-primary float-right" style="margin-top: 5px;">Back to Index
        </a>
    </div>

    <div class="container">
        <table class="table table-hover table-bordered" style="margin-top: 5px;">
            <thead class="thead-dark">
                <tr style="text-align: center;">
                    <th width=50%>item</th>
                    <th width=50%>value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Customer Name</th>
                    <td>{{ $payment->customer_name }}</td>
                </tr>
                <tr>
                    <th>User Name</th>
                    <td>{{ $payment->user_name }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $payment->status }}</td>
                </tr>
                <tr>
                    <th>Method</th>
                    <td>{{ $payment->method }}</td>
                </tr>
                <tr>
                    <th>Total Amount</th>
                    <td>{{ $payment->totalAmount }}</td>
                </tr>
                <tr>
                    <th>Allocated to Invoice</th>
                    <td>{{ $payment->allocatedToInvoice }}</td>
                </tr>
                <tr>
                    <th>Balance to Allocate</th>
                    <td>{{ $payment->balanceToAllocate }}</td>
                </tr>
                @if ($payment->method == 'BankTransfer')
                    <tr>
                        <th>Bank Transfer</th>
                        <td>{{ $payment->bank_name }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        @if ($payment->method == 'Cheque')

                <h1 style="text-align: center;">Cheque Details</h1>
         
            <table class="table">
                <thead>
                    <tr>
                        <th width=20%>Bank</th>
                        <th width=20%>Branch</th>
                        <th width=20%>Cheque No</th>
                        <th width=20%>Amount</th>
                        <th width=20%>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cheques as $c)
                        <tr>
                            <th>{{ $c->bank }}</th>
                            <td>{{ $c->branch }}</td>
                            <td>{{ $c->number }}</td>
                            <td>{{ $c->amount }}</td>
                            <td>{{ $c->chequeDate }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif


    </div>
@endsection

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
        <div class="alert alert-primary">

            Total Balance Amount to Link : {{ $payment->balance }}

        </div>
        <form method="POST" action="{{ route('payment.update', $payment->id) }}" onsubmit="return validateForm()">
            @csrf @method('PUT')

            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <style>
                            .pre-scrollable {
                                max-height: 540px;
                                overflow-y: scroll;
                            }

                            td {
                                min-width: 125px;
                            }

                        </style>
                        <div class="pre-scrollable">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Invoice ID</th>
                                            <th>Due Amount</th>
                                            <th>Paid Amount from this Payment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($x = 1; $x < 15; $x++)
                                            <tr>
                                                <td><input type="number" name="invoiceID{!! $x !!}"
                                                        id="invoiceID{!! $x !!}" class="form-control"
                                                        value="{{ old('invoiceID' . $x) }}"></td>
                                                <td><input type="number" step="0.01" name="dueAmount{!! $x !!}"
                                                        id="dueAmount{!! $x !!}" class="form-control"
                                                        value="{{ old('dueAmount' . $x) }}"></td>
                                                <td><input type="number" step="0.01"
                                                        name="paidAmount{!! $x !!}"
                                                        id="paidAmount{!! $x !!}" class="form-control"
                                                        value="{{ old('paidAmount' . $x) }}"></td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-block btn-primary" type="submit">Link Payment</button>
        </form>
    </div>

    <script>
        function validateForm() {

            var Balance = {{ $payment->balance }};
            var payAmount;
            var invoiceID;
            var paidAmount = 0;
            var i;
            var TotalLinkedValue = 0;

            for (i = 1; i < 15; i++) {

                payAmount = "paidAmount" + i;
                InvdueAmount = "dueAmount" + i;
                invoiceID = "invoiceID" + i;
                if (document.getElementById(invoiceID).value) {
                    if (document.getElementById(payAmount).value < 1) {
                        alert("paid amount cant be less than 1");
                        return false;
                    }
                    if (!document.getElementById(InvdueAmount).value) {
                        alert("enter valid invoice ID");
                        return false;
                    }
                    if (Number(document.getElementById(InvdueAmount).value) < Number(document.getElementById(payAmount)
                            .value)) {
                        alert("Invoice due amount cant be less than paid amount for Invoice - " + document.getElementById(
                            invoiceID).value);
                        return false;
                    }
                }
                paidAmount = Number(document.getElementById(payAmount).value);
                TotalLinkedValue = paidAmount + TotalLinkedValue;
            }

            if (TotalLinkedValue > Balance) {
                alert("Total paid amount cant be more than payment Balance amount");
                return false;
            }
            return true;
        }
    </script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    <script type='text/javascript'>
        $(window).load(function() {

            plist = [
                    @foreach ($invoices as $inv)
                        {label: "{!! $inv->id !!}", dueAmount: "{!! $inv->dueAmount !!}"},
                    @endforeach
                ],
                @for ($i = 1; $i < 15; $i++)
                    $('#invoiceID{!! $i !!}').autocomplete({
                    source: plist,
                    minLength: 2,
                
                    select: function( event, ui ) {
                    event.preventDefault();
                    $('#invoiceID{!! $i !!}').val(ui.item.label);
                    this.value = ui.item.label;
                    $('#dueAmount{!! $i !!}').val(ui.item.dueAmount);
                    $('#paidAmount{!! $i !!}').val(0);
                
                    }
                
                    });
                @endfor
        });
    </script>
@endsection

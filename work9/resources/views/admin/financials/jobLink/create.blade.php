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
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif
    <form action="{{ route('LinkJob.store') }}" method="post" onsubmit="return validateForm()">
        @csrf
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
                                        <th>Job ID</th>
                                        <th>Due Amount</th>
                                        <th>Paid Amount from this Payment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($x = 1; $x <= 15; $x++)
                                        <tr>
                                            <td><input type="number" name="jobID{!! $x !!}"
                                                    id="jobID{!! $x !!}" class="form-control"
                                                    onchange="checkDuplicate({!! $x !!})"></td>
                                            <td><input type="number" step="0.01" name="dueAmount{!! $x !!}"
                                                    id="dueAmount{!! $x !!}" class="form-control"></td>
                                            <td><input type="number" step="0.01" name="paidAmount{!! $x !!}"
                                                    id="paidAmount{!! $x !!}" class="form-control"></td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container pt-5">
            <div class="form-group">
                <div class="row">
                    <input type="text" id="paymentID" name="paymentID" value="{{ $payment->id }}" hidden>
                    <div class="col-6">
                        <a class="btn btn-block btn-danger" href=""> Cancel</a>
                    </div>
                    <div class="col-6">
                        <input onclick="save()" type="submit" class="btn btn-block btn-info " value="Save">
                    </div>
                </div>
            </div>
        </div>
    </form>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type='text/javascript'>
        $(window).load(function() {
            plist = [
                    @foreach ($jobs as $job)
                        {label: "{!! $job->id !!}", dueAmount: "{!! $job->dueAmount !!}"},
                    @endforeach
                ],
                @for ($i = 1; $i <= 15; $i++)
                    $('#jobID{!! $i !!}').autocomplete({
                    source: plist,
                    minLength: 2,
                    select: function( event, ui ) {
                    event.preventDefault();
                    $('#jobID{!! $i !!}').val(ui.item.label);
                    this.value = ui.item.label;
                    $('#dueAmount{!! $i !!}').val(ui.item.dueAmount);
                    $('#paidAmount{!! $i !!}').val(0);
                    }
                    });
                @endfor
        });
    </script>
    <script>
        function checkDuplicate(x) {
            var OriginaljobID = "jobID" + x;
            var DueAmount;
            var paidAmount;
            var tempInv;
            for (var j = 1; j <= 15; j++) {
                tempInv = "jobID" + j;
                if (j != x) {
                    if (document.getElementById(OriginaljobID).value == document.getElementById(tempInv).value) {
                        alert("duplicate job ID");
                        DueAmount = "dueAmount" + x;
                        paidAmount = "paidAmount" + x;
                        document.getElementById(OriginaljobID).value = '';
                        document.getElementById(DueAmount).value = '';
                        document.getElementById(paidAmount).value = '';
                        break;
                    }
                }
            }
        }
    </script>
    <script>
        function validateForm() {
            var payAmount;
            var jobID;
            var InvdueAmount;
            var paidAmount = 0;
            var Balance = {{ $payment->balanceToAllocate }};
            for (var i = 1; i <= 15; i++) {
                payAmount = "paidAmount" + i;
                InvdueAmount = "dueAmount" + i;
                jobID = "jobID" + i;
                if (document.getElementById(jobID).value) {
                    if (Number(document.getElementById(payAmount).value) < 1) {
                        alert("paid amount cant be less than 1");
                        return false;
                    }
                    if (!document.getElementById(InvdueAmount).value) {
                        alert("enter valid job ID");
                        return false;
                    }
                    if (Number(document.getElementById(payAmount).value) > Number(document.getElementById(InvdueAmount).value)){
                        alert("paid amount cant be higher than due Amount");
                        return false;
                    }
                }
                paidAmount += Number(document.getElementById(payAmount).value)
            }
            if (paidAmount > Balance) {
                alert("Total paid amount cant be more than payment Balance amount");
                return false;
            }
            return true;
        }
    </script>
@endsection

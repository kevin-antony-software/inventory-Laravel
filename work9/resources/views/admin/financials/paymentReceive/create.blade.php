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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .ui-autocomplete {
            max-height: 100px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        * html .ui-autocomplete {
            height: 100px;
        }

    </style>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function() {
            var cusJqery = {!! json_encode($customers->toArray()) !!};
            var cusName = [];
            for (var ckj = 0; ckj < cusJqery.length; ckj++) {
                cusName.push(cusJqery[ckj].customer_name);
            }

            $("#customer_name").autocomplete({
                source: cusName
            });
        });
    </script>



    <div class="container">
        <form method="POST" action="{{ route('payment.store') }}" onsubmit="return validateForm()">
            @csrf

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label" for="Method">Payment Method</label>
                <div class="col-sm-10">
                    <select class="form-control" name="Method" id="Method">
                        <option value="Cash">Cash</option>
                        <option value="Cheque">Cheque</option>
                        <option value="BankTransfer">Bank Transfer</option>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label" for="TotalAmount">Total Amount</label>
                <div class="col-sm-10">
                    <input class="form-control" step=".01" type="number" name="TotalAmount" id="TotalAmount">
                </div>
            </div>


            <div class="mb-3 row">
                <label for="customer_name" class="col-sm-2 col-form-label">Customer </label>
                <div class="col-sm-10">
                    <input id="customer_name" name="customer_name" class="form-control"
                        value="{{ old('customer_name') }}" required>
                </div>
            </div>

            <div id="chequePayment" style="display:none;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <style>
                                .pre-scrollable {
                                    max-height: 540px;
                                    overflow-y: scroll;
                                    /* overflow-x: scroll; */

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
                                                <th>Cheque No</th>
                                                <th>Bank No</th>
                                                <th>Branch No</th>
                                                <th>Amount</th>
                                                <th>Cheque Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 1; $i < 20; $i++)
                                                <tr>
                                                    <td><input type="number" name="chequeNo{!! $i !!}"
                                                            id="chequeNo{!! $i !!}" class="form-control"
                                                            onchange="return myFunction1({{ $i }})">
                                                    </td>
                                                    <td><input type="number" name="bankNo{!! $i !!}"
                                                            id="bankNo{!! $i !!}" class="form-control"></td>
                                                    <td><input type="number" name="branchNo{!! $i !!}"
                                                            id="branchNo{!! $i !!}" class="form-control">
                                                    </td>
                                                    <td><input step=".01" type="number"
                                                            name="chequeAmount{!! $i !!}"
                                                            id="chequeAmount{!! $i !!}" class="form-control"
                                                            onchange="return calculateTotal()"></td>
                                                    <td><input type="date" name="chequeDate{!! $i !!}"
                                                            id="chequeDate{!! $i !!}" class="form-control">
                                                    </td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div id="bankTransfer" style="display:none;">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label" for="bank">Bank</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="bank" id="bank">

                            @foreach ($banks as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>
            </div>

            <button class="btn btn-block btn-primary" type="submit">Create New Payment</button>
        </form>
    </div>
    <script>
        function myFunction1(i) {

            var baNo = "bankNo" + i;
            var braNo = "branchNo" + i;
            var cheAmount = "chequeAmount" + i;
            var cheDate = "chequeDate" + i;
            document.getElementById(baNo).required = true;
            document.getElementById(braNo).required = true;
            document.getElementById(cheAmount).required = true;
            document.getElementById(cheDate).required = true;
        }
    </script>


    <script>
        document.getElementById("Method").addEventListener("change", myFunction);

        function myFunction() {
            if (document.getElementById("Method").value == "Cheque") {
                document.getElementById("chequePayment").style.display = "block";
                document.getElementById("bankTransfer").style.display = "none";
                document.getElementById("TotalAmount").readOnly = true;
                document.getElementById("TotalAmount").value = 0;

            } else if (document.getElementById("Method").value == "BankTransfer") {
                document.getElementById("chequePayment").style.display = "none";
                document.getElementById("bankTransfer").style.display = "block";
                document.getElementById("TotalAmount").readOnly = false;
                document.getElementById("TotalAmount").value = 0;

            } else {
                document.getElementById("chequePayment").style.display = "none";
                document.getElementById("bankTransfer").style.display = "none";
                document.getElementById("TotalAmount").readOnly = false;
                document.getElementById("TotalAmount").value = 0;
            }

        }
    </script>

    <script>
        function calculateTotal() {

            var chequeAmount;
            var totalcheques = 0;
            var i;
            var temp;
            var tempAmount;
            for (i = 1; i < 20; i++) {
                chequeAmount = "chequeAmount" + i;
                if (document.getElementById(chequeAmount).value > 0) {
                    temp = document.getElementById(chequeAmount).value;
                    tempAmount = parseFloat(temp);
                    totalcheques = totalcheques + tempAmount;

                }
                document.getElementById("TotalAmount").value = totalcheques;
            }
        }
    </script>

    <script>
        function validateForm() {
            if (document.getElementById("Method").value == "Cheque") {
                var chequeAmount;
                var i;
                var TotalChequesValue = 0;
                var tempAmount;
                for (i = 1; i < 20; i++) {
                    chequeAmount = "chequeAmount" + i;
                    if (document.getElementById(chequeAmount).value > 0) {
                        tempAmount = parseDouble(document.getElementById(chequeAmount).value);
                        TotalChequesValue = TotalChequesValue + tempAmount;
                    }

                }

                if (document.getElementById("TotalAmount").value != TotalChequesValue) {
                    alert("cheque values doesnt match total value");
                    return false;
                } else {
                    return true;
                }
            }

        }
    </script>
@endsection

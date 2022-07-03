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
    <script type='text/javascript'>
        $(window).load(function() {
            plist = [
                    @foreach ($invoices as $invoice)
                        {
                            label: "{!! $invoice->id !!}",
                            dueAmount: "{!! $invoice->dueAmount !!}",
                            TotalAmount: "{!! $invoice->total !!}",
                            customer_name: "{!! $invoice->customer_name !!}",
                        },
                    @endforeach
                ],

                $('#invoiceID').autocomplete({
                    source: plist,
                    minLength: 2,
                    select: function(event, ui) {
                        event.preventDefault();
                        $('#invoiceID').val(ui.item.label);
                        this.value = ui.item.label;
                        $('#TotalAmount').val(ui.item.TotalAmount);
                        $('#dueAmount').val(ui.item.dueAmount);
                        $('#customer_name').val(ui.item.customer_name);

                    }
                });
        });
    </script>

    <script type='text/javascript'>
        $(window).load(function() {
            jlist = [
                    @foreach ($jobs as $job)
                        {
                            label: "{!! $job->id !!}",
                            dueAmount: "{!! $job->dueAmount !!}",
                            TotalAmount: "{!! $job->finalTotal !!}",
                            customer_name: "{!! $job->customer_name !!}",
                        },
                    @endforeach
                ],

                $('#jobID').autocomplete({
                    source: jlist,
                    minLength: 2,
                    select: function(event, ui) {
                        event.preventDefault();
                        $('#jobID').val(ui.item.label);
                        this.value = ui.item.label;
                        $('#TotalAmount').val(ui.item.TotalAmount);
                        $('#dueAmount').val(ui.item.dueAmount);
                        $('#customer_name').val(ui.item.customer_name);

                    }
                });
        });
    </script>

    <div class="container">
        <form method="POST" action="{{ route('writeoff.store') }}">
            @csrf
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label" for="invoiceType">Invoice or Repair Job?</label>
                <div class="col-sm-10">
                    <select class="form-control" name="invoiceType" id="invoiceType">
                        <option value="Inventory Invoice">Inventory Invoice</option>
                        <option value="Repair Job">Repair Job</option>
                    </select>
                </div>
            </div>
            <button class="btn btn-block btn-primary" id="selectType">Select Type</button>

            <div id="invoice-section" style="display: none;">
                <div class="mb-3 pt-2 row">
                    <label class="col-sm-2 col-form-label" for="invoiceID">Invoice ID</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="number" name="invoiceID" id="invoiceID" value="">
                    </div>
                </div>
            </div>

            <div id="job-section" style="display: none;">
                <div class="mb-3 pt-2 row">
                    <label class="col-sm-2 col-form-label" for="jobID">Job ID</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="number" name="jobID" id="jobID" value="">
                    </div>
                </div>
            </div>


            <div id="main-container" style="display: none;">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label" for="TotalAmount">Total Amount</label>
                    <div class="col-sm-10">
                        <input class="form-control" step=".01" type="number" name="TotalAmount" id="TotalAmount" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label" for="dueAmount">Due Amount</label>
                    <div class="col-sm-10">
                        <input class="form-control" step=".01" type="number" name="dueAmount" id="dueAmount" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="customer_name" class="col-sm-2 col-form-label">Customer </label>
                    <div class="col-sm-10">
                        <input id="customer_name" name="customer_name" class="form-control" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label" for="writeoffAmount">Write-off Amount</label>
                    <div class="col-sm-10">
                        <input class="form-control" step=".01" type="number" name="writeoffAmount" id="writeoffAmount"
                            required>
                    </div>
                </div>

                <button class="btn btn-block btn-danger" type="submit">Write Off</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById("selectType").addEventListener("click", myFunction);

        function myFunction() {
            if (document.getElementById("invoiceType").value == "Inventory Invoice") {
                document.getElementById("invoice-section").style.display = 'block';
                document.getElementById("job-section").style.display = 'none';
                document.getElementById("main-container").style.display = 'block';
                document.getElementById("invoiceID").required = true;
            } else {
                document.getElementById("job-section").style.display = 'block';
                document.getElementById("invoice-section").style.display = 'none';
                document.getElementById("main-container").style.display = 'block';
                document.getElementById("jobID").required = true;
            }
        }
    </script>
@endsection

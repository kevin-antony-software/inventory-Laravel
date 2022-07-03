@extends('dashboard')
@section('content')
@if (session()->has('error'))
<div class="alert alert-danger">
    {{ session()->get('error') }}
</div>
@endif
    <form method="POST" action="{{ route('InvoicePayment.store') }}">
        @csrf
        <div class="container p-2">
            <div class="d-flex justify-content-around">
                <div class="mb-2">
                    <div class="row">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="inv">
                            <label class="form-check-label" for="flexRadioDefault1">
                                Invoice Payment Report
                            </label>
                        </div>
                        <div class="form-check ml-5">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" value="job">
                            <label class="form-check-label" for="flexRadioDefault2">
                                Repair Job Payment Report
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mb-2">
            <div class="d-flex justify-content-around">
                <div id="invoiceReport" style="display: none;">
                    <label for="invoiceID" class="form-label">Choose the invoice</label>
                    <input class="form-control" list="invoiceList" name="invoiceID" id="invoiceID">
                    <datalist id="invoiceList">
                        <option value="Select Invoice">
                            @foreach ($invoices as $invoice)
                        <option value="{{ $invoice->id }}">
                            @endforeach
                    </datalist>
                </div>

                <div id="jobReport" style="display: none;">
                    <label for="jobID" class="form-label">Choose the Job</label>
                    <input class="form-control" list="jobList" name="jobID" id="jobID">
                    <datalist id="jobList">
                        <option value="Select Job">
                            @foreach ($jobs as $job)
                        <option value="{{ $job->id }}">
                            @endforeach
                    </datalist>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="col-lg-12">
                <div class="form-group">
                    <input type="submit" class="btn btn-block btn-info form-control" value="Find" id="buttonSub"disabled>
                </div>
            </div>
        </div>

    </form>
    </div>

    <script>
        document.getElementById("flexRadioDefault1").addEventListener("click", displayDate);
        document.getElementById("flexRadioDefault2").addEventListener("click", displayDate1);

        function displayDate() {

            if (document.getElementById('flexRadioDefault1').checked) {
                var x = document.getElementById("invoiceReport");
                x.style.display = "block";
                var y = document.getElementById("jobReport");
                y.style.display = "none";
                document.getElementById("buttonSub").disabled = false;
            }
        }

        function displayDate1() {

            if (document.getElementById('flexRadioDefault2').checked) {
                var x = document.getElementById("invoiceReport");
                x.style.display = "none";
                var y = document.getElementById("jobReport");
                y.style.display = "block";
                document.getElementById("buttonSub").disabled = false;
            }
        }
    </script>
@endsection

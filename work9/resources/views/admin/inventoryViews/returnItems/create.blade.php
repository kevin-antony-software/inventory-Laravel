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
    <div class="container-fluid mt-2">
        <form method="POST" action="{{ route('returnItems.store') }}" onsubmit="return validateForm()">
            @csrf
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label" for="customer">Customer</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="customer" id="customer" list="customerList"
                        autocomplete="off" onchange="showInvoices(this.value)" required>
                    <datalist id="customerList">
                        <option value="">Choose From customers</option>
                        @if (count($customers))
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                            @endforeach
                        @else
                            <option value="no invoice">no Customer</option>
                        @endif
                    </datalist>
                </div>

            </div>
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Invoice ID</label>
                <div class="col-sm-10">
                    <select class="form-control" name="InvoiceID" id="InvoiceID" onclick="showInvoiceDetails(this.value)"
                        required>
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div id="bankTransfer">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label" for="Warehouse">Warehouse</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="Warehouse" id="Warehouse">
                            @if (count($warehouses))
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->warehouse_name }}</option>
                                @endforeach
                            @else
                                <option value="no invoice">no Warehouse</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            {{ csrf_field() }}
            <div id="invoiceDetails"></div>

        </form>
    </div>


    <script>
        function validateForm() {
            return true;
        }
    </script>

    <script>
        function showInvoices(str) {
            if (str.length == 0) {
                document.getElementById("txtHint").innerHTML = "";
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("InvoiceID").innerHTML = this.responseText;
                    }
                };
                var url = '{{ route('returnItems.fetch', ':id') }}';
                url = url.replace(':id', str);
                xmlhttp.open("GET", url, true);
                xmlhttp.send();
            }
        }

        function showInvoiceDetails(invID) {
            if (invID.length == 0) {
                document.getElementById("invoiceDetails").innerHTML = "";
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("invoiceDetails").innerHTML = this.responseText;
                    }
                };
                var url = '{{ route('returnItems.giveInvoiceDetails', ':id') }}';
                url = url.replace(':id', invID);
                xmlhttp.open("GET", url, true);
                xmlhttp.send();
            }
        }
    </script>

@endsection

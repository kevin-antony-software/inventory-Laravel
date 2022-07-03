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
    <form action="{{ route('CourierPacking.store') }}" method="post" onsubmit="return validateForm()">
        @csrf
        <div class="container">
            <div class="pre-scrollable" style="max-height: 75vh">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th width=20%>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($x = 1; $x <= 20; $x++)
                                <tr>
                                    <td>
                                        <input list="customers" name="customer{{ $x }}"
                                            id="customer{{ $x }}" class="form-control">
                                        <datalist id="customers">
                                            @if ($customers)
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->courier_customer_name }}">
                                                @endforeach
                                            @else
                                                <option value="No data">
                                            @endif
                                        </datalist>
                                    </td>
                                    <td><input type="number" name="Qty{{ $x }}" id="Qty{{ $x }}"
                                            class="form-control"></td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="container pt-5">
            <div class="form-group">
                <button type="submit" value="Submit" class="btn btn-primary btn-lg btn-block">Print</button>
            </div>
        </div>

    </form>
    <script>
        function validateForm() {
            for (var i = 1; i < 21; i++) {
                qty = "Qty" + i;
                ItemName = "customer" + i;
                if (document.getElementById(ItemName).value != "") {
                    if (document.getElementById(qty).value == "") {
                        alert("Qty cant be blank with customer");
                        return false;
                    }
                }
            }
            return true;
        }
    </script>
@endsection

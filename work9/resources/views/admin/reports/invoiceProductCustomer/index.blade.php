@extends('dashboard')
@section('content')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
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
            var prodJqery = {!! json_encode($products->toArray()) !!};
            var cusName = [];
            var prod = [];
            for (var ckj = 0; ckj < cusJqery.length; ckj++) {
                cusName.push(cusJqery[ckj].customer_name);
            }

            for (var ckjp = 0; ckjp < prodJqery.length; ckjp++) {
                prod.push(prodJqery[ckjp].product_name);
            }

            $("#customer_name").autocomplete({
                source: cusName
            });

            $("#product_name").autocomplete({
                source: prod
            });

        });
    </script>
    <div class="container-fluid">
        <form method="POST" action="{{ route('InvoiceProductCustomer.store') }}">
            @csrf
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="customer_name" class="col-md-3">Customer: </label>
                        <input id="customer_name" name="customer_name" class="form-control"
                            value="{{ old('customer_name') }}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="product_name" class="col-md-3">Product: </label>
                        <input id="product_name" name="product_name" class="form-control"
                            value="{{ old('product_name') }}">
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <input type="submit" class="btn btn-block btn-info form-control" value="Search">
                </div>
            </div>
        </form>
    </div>
@endsection

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
    <div class="container-fluid">
        <form method="POST" action="{{ route('invoice.store') }}" onsubmit="return validateForm()">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="customer_name" class="col-md-3">Customer: </label>
                        <input id="customer_name" name="customer_name" class="form-control"
                            value="{{ old('customer_name') }}" required>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-md-3">Warehouse</label>
                        <select name="warehouse_id" id="warehouse_id" class="form-control">
                            @foreach ($warehouses as $w)
                                <option value="{{ $w->warehouse_id }}"
                                    {{ collect(old('warehouse_id'))->contains($w->warehouse_id) ? 'selected' : '' }}>
                                    {{ $w->warehouse_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <!--   end of ware house & customer selection -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pre-scrollable">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="8%" style="min-width: 100px;">Item No</th>
                                            <th width="40%" style="min-width: 350px;">Item Name</th>
                                            <th width="10%" style="min-width: 100px;">Price</th>
                                            <th width="10%" style="min-width: 100px;">Discount %</th>
                                            <th width="10%" style="min-width: 100px;">Available</th>
                                            <th width="10%" style="min-width: 100px;">Quantity</th>
                                            <th width="15%" style="min-width: 100px;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 1; $i < 21; $i++)
                                            <tr>
                                                <td><input type="text" name="itemNo{!! $i !!}"
                                                        id="itemNo{!! $i !!}" class="form-control"
                                                        autocomplete="off" value="{{ old('itemNo' . $i) }}"
                                                        readonly="true"></td>
                                                <td><input type="text" name="itemName{!! $i !!}"
                                                        id="itemName{!! $i !!}" class="form-control"
                                                        autocomplete="off" value="{{ old('itemName' . $i) }}"></td>
                                                <td><input type="text" name="price{!! $i !!}"
                                                        id="price{!! $i !!}" class="form-control changesNo"
                                                        autocomplete="off" ondrop="return false;" onpaste="return false;"
                                                        readonly value="{{ old('price' . $i) }}"></td>
                                                <td><input type="number" name="dprice{!! $i !!}"
                                                        id="dprice{!! $i !!}" class="form-control changesNo"
                                                        autocomplete="off" ondrop="return false;"
                                                        value="{{ old('dprice' . $i) }}" step=".01"></td>
                                                <td><input type="text" name="aquantity{!! $i !!}"
                                                        id="aquantity{!! $i !!}"
                                                        class="form-control changesNo" autocomplete="off"
                                                        ondrop="return false;" onpaste="return false;"
                                                        value="{{ old('aquantity' . $i) }}" readonly></td>
                                                <td><input type="number" name="quantity{!! $i !!}"
                                                        id="quantity{!! $i !!}" class="form-control changesNo"
                                                        autocomplete="off" ondrop="return false;" onpaste="return false;"
                                                        accesskey="a" value="{{ old('quantity' . $i) }}" step=".01"></td>
                                                <td><input type="number" name="total{!! $i !!}"
                                                        id="total{!! $i !!}"
                                                        class="form-control totalLinePrice" autocomplete="off"
                                                        ondrop="return false;" onpaste="return false;"
                                                        value="{{ old('total' . $i) }}" step=".01"></td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-2">
                <div class="col-lg-6 form-group">
                    <label for="totalAftertax">Total - Rs</label><br>
                    <input type="number" class="form-control" id="totalAftertax" name="totalAftertax" value=""
                        readonly="true">
                </div>
            </div>
            <div class="container pt-2">
                <div class="form-group">
                    <a class="btn btn-block btn-danger" href="{{ route('invoice.index') }}"> @lang('cancel')</a>
                    <button class="btn btn-block btn-primary" id="submitButton" type="submit">Save New Invoice</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        function validateForm() {

            if (document.getElementById("customer_name").value == "") {
                alert("customer cant be blank");
                return false;
            }
            if (document.getElementById("totalAftertax").value == "") {
                alert("Final Total cant be blank");
                return false;
            }


            var itemNo;
            var ItemName;
            var dprice;
            var aquantity;
            var quantity;
            var total;

            for (var i = 1; i < 21; i++) {
                itemNo = "itemNo" + i;
                ItemName = "itemName" + i;
                dprice = "dprice" + i;
                aquantity = "aquantity" + i;
                quantity = "quantity" + i;
                total = "total" + i;

                if (document.getElementById(itemNo).value != "") {
                    
                    if (document.getElementById(dprice).value == "") {
                        alert("discount cant be empty");
                        return false;
                    }
                    if (parseInt(document.getElementById(aquantity).value) < parseInt(document.getElementById(quantity)
                            .value)) {
                        alert("qty cant be higher than invetory - " + document.getElementById(ItemName).value);
                        return false;
                    }
                    if (parseInt(document.getElementById(total).value) == 0 || document.getElementById(total).value == "") {
                        alert("Total cant be zero");
                        return false;
                    }
                }
            }
            myButton = document.getElementById("submitButton");
            myButton.disabled = true;
            return true;
        }
    </script>
    <script>
        var sites = {!! json_encode($inventories->toArray()) !!};

        for (var tk = 1; tk < 21; tk++) {

            var n = tk.toString();
            var disprice = "dprice" + n;
            document.getElementById(disprice).addEventListener("keydown", myFunctionL);

            function myFunctionL() {

                for (var tks = 1; tks < 21; tks++) {
                    var ns = tks.toString();
                    var itemqqL = "itemNo" + ns;
                    var aqu = "aquantity" + ns;
                    var productVal = document.getElementById(itemqqL).value;
                    var avaVal = document.getElementById(aqu).value;
                    var e = document.getElementById("warehouse_id");
                    var warehouseID = e.value;
                    for (var pk = 0; pk < sites.length; pk++) {

                        if (sites[pk].product_id == productVal && sites[pk].warehouse_id == warehouseID) {

                            document.getElementById(aqu).value = sites[pk].qty;

                        }
                    }
                }
            }
        }
    </script>
    <script>
        @for ($t = 1; $t < 21; $t++)
            document.getElementById("itemName{!! $t !!}").addEventListener("keyup", myFunctionR);
        
            function myFunctionR() {
            document.getElementById("dprice{!! $t !!}").required;
            }
        @endfor
    </script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script type='text/javascript'>
        $(window).load(function() {

            plist = [
                    @foreach ($product as $p)
                        {label: "{!! $p->product_name !!}", productCode: "{!! $p->id !!}", price: "{!! $p->price !!}"},
                    @endforeach
                ],

                @for ($i = 1; $i < 21; $i++)
                    //$(function() {
                    $('#itemName{!! $i !!}').autocomplete({
                    source: plist,
                    minLength: 2,
                
                    select: function( event, ui ) {
                    event.preventDefault();
                    $('#itemName{!! $i !!}').val(ui.item.label);
                    this.value = ui.item.label;
                    $('#itemNo{!! $i !!}').val(ui.item.productCode);
                    $('#price{!! $i !!}').val(ui.item.price);
                    $('#quantity{!! $i !!}').val(0);
                
                    }
                
                    });
                @endfor

        });
    </script>


    <script>
        $(window).load(function() {

            @for ($i = 1; $i < 21; $i++)
                $( "#quantity{!! $i !!}" ).keyup(function() {
            
                if ($('#dprice{!! $i !!}').val() > 0){
                $('#total{!! $i !!}').val($('#quantity{!! $i !!}').val() *
                $('#price{!! $i !!}').val() * (1-($('#dprice{!! $i !!}').val()/100)));
                } else {
                $('#total{!! $i !!}').val($('#quantity{!! $i !!}').val() *
                $('#price{!! $i !!}').val() );
                }
            
                var temp = 0;
                var inta = 0;
                var intab = 0;
            
                @for ($x = 1; $x < 21; $x++)
                    inta = ($('#total{!! $x !!}').val());
                    intab = Number(inta);
                    temp = intab + temp;
                @endfor
            
                $('#totalAftertax').val(temp);
            
                });
            @endfor
        });
    </script>
    <script>
        $(window).load(function() {

            @for ($i = 1; $i < 21; $i++)
                $( "#dprice{!! $i !!}" ).keyup(function() {
                $('#total{!! $i !!}').val($('#quantity{!! $i !!}').val() *
                $('#price{!! $i !!}').val() * (1-($('#dprice{!! $i !!}').val()/100)));
            
                var temp = 0;
                var inta = 0;
                var intab = 0;
            
                @for ($x = 1; $x < 21; $x++)
                    inta = ($('#total{!! $x !!}').val());
                    intab = Number(inta);
                    temp = intab + temp;
                @endfor
            
                $('#totalAftertax').val(temp);
            
                });
            @endfor
        });
    </script>
@endsection

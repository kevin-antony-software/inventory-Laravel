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


    <div class="container-fluid">
        <form method="POST" action="{{ route('grn.store') }}" onsubmit="return validateForm()">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-6">From Warehouse</label>
                        <SELECT name="Fromwarehouse_id" id="Fromwarehouse_id" class="form-control">
                            <option value="">Choose warehouse</option>
                            @foreach ($warehouses as $Fw)
                                <option value="{{ $Fw->id }}">{{ $Fw->warehouse_name }}</option>
                            @endforeach
                        </SELECT>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-6">To Warehouse</label>
                        <select name="Towarehouse_id" id="Towarehouse_id" class="form-control">
                            <option value="">Choose Warehouse</option>
                            @foreach ($warehouses as $w)
                                <option value="{{ $w->id }}">{{ $w->warehouse_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <!--   end of ware house & customer selection -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="pre-scrollable">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="10%" style="min-width: 100px;">Item No</th>
                                            <th width="60%" style="min-width: 350px;">Item Name</th>
                                            <th width="15%" style="min-width: 100px;">Available</th>
                                            <th width="15%" style="min-width: 100px;">Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 1; $i < 21; $i++)
                                            <tr>
                                                <td><input type="text" name="itemNo{!! $i !!}"
                                                        id="itemNo{!! $i !!}" class="form-control"
                                                        autocomplete="off" value="{{ old('itemNo' . $i) }}"></td>
                                                <td><input type="text" name="itemName{!! $i !!}"
                                                        id="itemName{!! $i !!}" class="form-control"
                                                        autocomplete="off" value="{{ old('itemName' . $i) }}"></td>
                                                <td><input type="number" name="aquantity{!! $i !!}"
                                                        id="aquantity{!! $i !!}" class="form-control changesNo"
                                                        autocomplete="off" value="{{ old('aquantity' . $i) }}"></td>
                                                <td><input type="number" name="quantity{!! $i !!}"
                                                        id="quantity{!! $i !!}" class="form-control"
                                                        autocomplete="off" value="{{ old('quantity' . $i) }}"></td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container pt-2">
                <div class="row">
                    <div class="col-6">
                        <a class="btn btn-danger btn-block" href="{{ route('grn.index') }}"> @lang('Cancel')</a>
                    </div>
                    <div class="col-6">
                        <input type="submit" class="btn btn-info btn-block" value="Save">
                    </div>
                </div>
            </div>


        </form>
    </div>
    <script>
        function validateForm() {
            if (document.getElementById("Fromwarehouse_id").value == ""){
                alert("From warehouse cant be blank");
                return false;
            }
            if (document.getElementById("Towarehouse_id").value == ""){
                alert("To warehouse cant be blank");
                return false;
            }
            var availableQty;
                var GRNQty;
                var ItemName;
                var itemNameVar;
                var AQty;
                var Qty;
                var ItemCode;
            for (var i = 1; i < 21; i++) {
                availableQty = "aquantity" + i;
                GRNQty = "quantity" + i;
                ItemName = "itemName" + i;
                ItemCode = "itemNo" + i;
                AQty = parseInt(document.getElementById(availableQty).value);
                Qty = parseInt(document.getElementById(GRNQty).value);
                itemNameVar = document.getElementById(ItemName);
                if (itemNameVar && itemNameVar.value) {
                    if (AQty < Qty) {
                        alert("item " + itemNameVar.value + " not sufficient");
                        return false;
                    }
                    if (Qty == 0){
                        alert("product cant have no Qty");
                        return false;
                    }
                    if (document.getElementById(ItemCode).value == ""){
                        alert("product code cant be empty");
                        return false;
                    }
                }
            }
            return true;
        }
    </script>
    <script>
        var sites = {!! json_encode($inventories->toArray()) !!};
        for (var tk = 1; tk < 21; tk++) {

            var n = tk.toString();
            var disprice = "itemName" + n;
            document.getElementById(disprice).addEventListener("change", myFunctionL);

            function myFunctionL() {
                for (var tks = 1; tks < 21; tks++) {
                    var ns = tks.toString();
                    var itemqqL = "itemNo" + ns;
                    var aqu = "aquantity" + ns;
                    var productVal = document.getElementById(itemqqL).value;
                    var avaVal = document.getElementById(aqu).value;
                    var e = document.getElementById("Fromwarehouse_id");
                    var strUser = e.options[e.selectedIndex].value;

                    for (var pk = 0; pk < sites.length; pk++) {
                        if (sites[pk].product_id == productVal && sites[pk].warehouse_id == strUser) {

                            document.getElementById(aqu).value = sites[pk].qty;
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script type='text/javascript'>
        $(window).load(function() {

            plist = [
                    @foreach ($products as $p)
                        {label: "{!! $p->product_name !!}", productCode: "{!! $p->id !!}" },
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
                    $('#quantity{!! $i !!}').val(0);
                
                    }
                
                    });
                
                
                    //});
                @endfor

        });
    </script>

@endsection

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
        <form method="POST" action="{{ route('purchase.store') }}">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label class="col-md-3">Warehouse</label>
                        <select name="warehouse_id" class="form-control ">
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
                    <div class="col-lg-12">
                        <div class="pre-scrollable" style="max-height: 70vh">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="10%">Item No</th>
                                            <th width="40%">Item Name</th>
                                            <th width="10%">Quantity</th>
                                            <th width="10%">USD COST</th>
                                            <th width="10%">USD RATE</th>
                                            <th width="10%">DFP</th>
                                            <th width="10%">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 1; $i < 21; $i++)
                                            <tr>
                                                <td><input type="text" name="itemNo_{!! $i !!}"
                                                        id="itemNo_{!! $i !!}" class="form-control"
                                                        autocomplete="off"></td>
                                                <td><input type="text" name="itemName_{!! $i !!}"
                                                        id="itemName_{!! $i !!}" class="form-control"
                                                        autocomplete="off"></td>
                                                <td><input type="text" name="quantity_{!! $i !!}"
                                                        id="quantity_{!! $i !!}" class="form-control"
                                                        autocomplete="off"></td>
                                                <td><input type="text" name="USDcost_{!! $i !!}"
                                                        id="USDcost_{!! $i !!}" class="form-control"
                                                        autocomplete="off"></td>
                                                <td><input type="text" name="USDrate_{!! $i !!}"
                                                        id="USDrate_{!! $i !!}" class="form-control"
                                                        autocomplete="off"></td>
                                                <td><input type="number" step='0.01' name="DFP_{!! $i !!}"
                                                        id="DFP_{!! $i !!}" class="form-control"
                                                        autocomplete="off"></td>
                                                <td><input type="text" name="price_{!! $i !!}"
                                                        id="price_{!! $i !!}" class="form-control"
                                                        autocomplete="off"></td>
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
    <div class="container pt-2">
        <div class="row">
            <div class="col-6">
                <a class="btn btn-danger btn-block" href="{{ route('purchase.index') }}"> @lang('Cancel')</a>
            </div>
            <div class="col-6">
                <input type="submit" class="btn btn-info btn-block" value="Save">
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
                    @foreach ($products as $p)
                        {label: "{!! $p->product_name !!}", productCode: "{!! $p->id !!}", price: "{!! $p->price !!}",
                        USDcost: "{!! $p->USDcost !!}"
                        , USDrate: "{!! $p->ExchangeUSDRate !!}", DutyFreightPercentage: "{!! $p->DFP !!}"
                    
                    
                        },
                    @endforeach
                ],

                @for ($i = 1; $i < 21; $i++)
                    $('#itemName_{!! $i !!}').autocomplete({
                    source: plist,
                    minLength: 2,
                
                    select: function( event, ui )
                    {
                    event.preventDefault();
                    $('#itemName_{!! $i !!}').val(ui.item.label);
                    this.value = ui.item.label;
                    $('#itemNo_{!! $i !!}').val(ui.item.productCode);
                    $('#USDcost_{!! $i !!}').val(ui.item.USDcost);
                    $('#USDrate_{!! $i !!}').val(ui.item.USDrate);
                    $('#DFP_{!! $i !!}').val(ui.item.DutyFreightPercentage);
                    $('#price_{!! $i !!}').val(ui.item.price);
                    $('#quantity_{!! $i !!}').val(0);
                    }
                
                    });
                @endfor

        });
    </script>
@endsection

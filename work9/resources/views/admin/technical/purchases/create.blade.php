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
        <form method="POST" action="{{ route('componentPurchase.store') }}" onsubmit="return validateForm()">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="row">
                <div class="col-lg-12">
                    <style>
                        .pre-scrollable {
                            max-height: 540px;
                            overflow-y: scroll;
                        }

                    </style>
                    <div class="pre-scrollable">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="10%">Item No</th>
                                        <th width="50%">Item Name</th>
                                        <th width="20%">Quantity</th>
                                        <th width="20%">Category</th>
                                        <th  >Category ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 1; $i < 31; $i++)
                                        <tr>
                                            <td><input type="text" name="itemNo_{!! $i !!}"
                                                    id="itemNo_{!! $i !!}" class="form-control"
                                                    autocomplete="off" readonly></td>
                                            <td><input type="text" name="itemName_{!! $i !!}"
                                                    id="itemName_{!! $i !!}" class="form-control"></td>
                                            <td><input type="number" name="quantity_{!! $i !!}"
                                                    id="quantity_{!! $i !!}" class="form-control changesNo"
                                                    value="">
                                            </td>
                                            <td><input type="text" name="category_{!! $i !!}"
                                                    id="category_{!! $i !!}" class="form-control" readonly>
                                            </td>
                                            <td ><input type="text" name="category_ID{!! $i !!}"
                                                id="category_ID{!! $i !!}" class="form-control" readonly>
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

    <div class="form-group pt-2">
        
        <input type="submit" class="btn btn-block btn-primary" value="Save">
    </div>

    </form>
    <script>
        function validateForm() {
            var itemNo;
            var ItemName;
            var quantity;
            var category;


            for (var i = 1; i < 31; i++) {
                itemNo = "itemNo_" + i;
                ItemName = "itemName_" + i;
                quantity = "quantity_" + i;
                category = "category_" + i;


                if (document.getElementById(itemNo).value != "") {
                    if (document.getElementById(quantity).value == "") {
                        alert("qty cant be empty");
                        return false;
                    }
                    if (document.getElementById(ItemName).value == "") {
                        alert("Item Name cant be empty");
                        return false;
                    }
                }
            }

            return true;
        }
    </script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script type='text/javascript'>
        $(window).load(function() {

            plist = [
                    @foreach ($components as $p)
                        {label: "{!! $p->component_name !!}", productCode: "{!! $p->id !!}", 
                        category: "{!! $p->category_name !!}", categoryID: "{!! $p->category_id !!}"},
                    @endforeach
                ],

                @for ($i = 1; $i < 31; $i++)
                    $('#itemName_{!! $i !!}').autocomplete({
                    source: plist,
                    minLength: 2,
                
                    select: function( event, ui )
                    {
                    event.preventDefault();
                    $('#itemName_{!! $i !!}').val(ui.item.label);
                    this.value = ui.item.label;
                    $('#itemNo_{!! $i !!}').val(ui.item.productCode);
                    $('#category_{!! $i !!}').val(ui.item.category);
                    $('#category_ID{!! $i !!}').val(ui.item.categoryID);
                    $('#quantity_{!! $i !!}').val(0);
                    }
                
                    });
                @endfor

        });
    </script>


@endsection

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
        <form method="POST" action="{{ route('jobs.update', $job->id) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @method('PUT')

            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <h3> Components Added </h3>
                        </div>
                        <div class="pre-scrollable">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="10%">Item No</th>
                                            <th width="20%">Item Price</th>
                                            <th width="60%">Item Name</th>
                                            <th width="10%">Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 1; $i < 30; $i++)
                                            <tr>
                                                <td><input type="text" name="itemNo_{!! $i !!}" readonly
                                                        id="itemNo_{!! $i !!}" class="form-control"
                                                        autocomplete="off"></td>
                                                <td><input type="number" name="itemPrice_{!! $i !!}" readonly
                                                        id="itemPrice_{!! $i !!}" class="form-control"
                                                        autocomplete="off"></td>
                                                <td><input type="text" name="itemName_{!! $i !!}"
                                                        id="itemName_{!! $i !!}" class="form-control"
                                                        autocomplete="off"></td>
                                                <td><input type="text" name="quantity_{!! $i !!}"
                                                        id="quantity_{!! $i !!}" class="form-control changesNo"
                                                        autocomplete="off" ondrop="return false;" onpaste="return false;"
                                                        accesskey="a" value="">
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
    <div class="container-fluid pt-5">
        <h3> Charges </h3>
        <div class="form-group">
            <label for="repairCharges">Repair Charges</label>
            <input class="form-control" type="number" id="repairCharges" name="repairCharges" value=0>
        </div>
        <div class="form-group">
            <label for="totalCharges">Total Charges</label>
            <input class="form-control" type="number" id="totalCharges" name="totalCharges" value=0>
        </div>
        <div class="form-group">
            <label for="discount">Discount</label>
            <input class="form-control" type="number" id="discount" name="discount" value=0>
        </div>
        <div class="form-group">
            <label for="finalTotal">Final Total</label>
            <input class="form-control" type="number" id="finalTotal" name="finalTotal" value=0>
        </div>
    </div>
    <div class="container-fluid pt-5">
        <h3> Issue </h3>
        <textarea class="form-control" type="textarea" id="issue" name="issue" rows="5" cols="30"></textarea>
    </div>
    <div class="container pt-5">
        <div class="form-group">
            <a class="btn btn-danger" href="{{ route('jobs.index') }}"> @lang('Cancel')</a>
            <input type="submit" class="btn btn-info " value="Estimate Save">
        </div>
    </div>
    </form>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script type='text/javascript'>
        $(window).load(function() {

            plist = [
                    @foreach ($components as $p)
                        {label: "{!! $p->component_name !!}", productCode: "{!! $p->id !!}", productPrice:
                        "{!! $p->price !!}"},
                    @endforeach
                ],

                @for ($i = 1; $i < 30; $i++)
                    $('#itemName_{!! $i !!}').autocomplete({
                    source: plist,
                    minLength: 2,
                
                    select: function( event, ui )
                    {
                    event.preventDefault();
                    $('#itemName_{!! $i !!}').val(ui.item.label);
                    this.value = ui.item.label;
                    $('#itemNo_{!! $i !!}').val(ui.item.productCode);
                    $('#itemPrice_{!! $i !!}').val(ui.item.productPrice);
                    $('#quantity_{!! $i !!}').val(0);
                    }
                
                    });
                @endfor
        });
    </script>

    <script type='text/javascript'>
        var totalComponentcharges = 0;

        @for ($t = 1; $t < 30; $t++)
            $( "#quantity_{!! $t !!}" ).keyup(function() {
        
            var temp = 0;
            var inta = 0;
            var intab = 0;
        
            @for ($x = 1; $x < 30; $x++)
                inta = $('#itemPrice_{!! $x !!}').val() * $('#quantity_{!! $x !!}').val();
                intab = Number(inta);
                temp = intab + temp;
                totalComponentcharges = intab + totalComponentcharges;
                console.log(temp);
            @endfor
            var dis = Number($('#discount').val());
            $('#totalCharges').val(temp + Number($("#repairCharges").val()));
            $('#finalTotal').val(temp - dis + Number($("#repairCharges").val()));
        
            });
        @endfor

        $("#repairCharges").keyup(function() {
            var totalComponentcharges1 = 0;
            var temp1 = 0;
            var inta1 = 0;
            var intab1 = 0;
            @for ($xy = 1; $xy < 30; $xy++)
                inta1 = $('#itemPrice_{!! $xy !!}').val() * $('#quantity_{!! $xy !!}').val();
                intab1 = Number(inta1);
                temp1 = intab1 + temp1;
                totalComponentcharges1 = intab1 + totalComponentcharges1;
                console.log(temp1);
            @endfor

            $('#totalCharges').val(totalComponentcharges1 + Number($("#repairCharges").val()));
            $('#finalTotal').val(Number($('#totalCharges').val()) - Number($('#discount').val()));

        });
        $("#discount").keyup(function() {
            $('#finalTotal').val(Number($('#totalCharges').val()) - Number($('#discount').val()));
        });
    </script>
@endsection
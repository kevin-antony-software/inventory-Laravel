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
    <form method="POST" action="{{ route('purchase.update', $purchase->id) }}">
@csrf @method('PUT')
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-md-3">Warehouse</label>
                    <select name="warehouse_id" class="form-control">

                        <option value="{{ $purchase->warehouse_ID }}">{{ $purchase->warehouse->warehouse_name }}</option>

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
                                    <th width="10%" >Item No</th>
                                    <th width="50%" >Item Name</th>
                                    <th width="20%" >Quantity</th>
                                    <th width="20%" >Actual Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                            @foreach($purchaseDetails as $p)
                                <tr>
<td><input type="number" name="itemNo_{!! $i !!}" id="itemNo_{!! $i !!}" class="form-control" value="{{ $p->product_id }}" ></td>
<td><input type="text" name="itemName_{!! $i !!}" id="itemName_{!! $i !!}" class="form-control" value="{{ $p->product_name }}" readonly="readonly"></td>
<td><input type="number" name="quantity_{!! $i !!}" id="quantity_{!! $i !!}" class="form-control" value="{{ $p->qty }}" readonly="readonly"> </td>
<td><input type="number" name="Aquantity_{!! $i !!}" id="Aquantity_{!! $i !!}" class="form-control" value="{{ $p->qty }}"> </td>
                                </tr>
                                <?php $i = $i + 1; ?>
                                @endforeach
                            </tbody>
                        </table>
                    </div> 
                </div>
            </div>   
        </div>
    </div>
</div>
<div class="container pt-5">
        <div class="form-group">
            <a class="btn btn-danger btn-block" href="{{ route('purchase.index') }}">  @lang('Cancel')</a>
            <input type="submit" class="btn btn-info btn-block" value="Received Qty">
        </div>
</div>
    </form>

@endsection
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
        <form method="POST" action="{{ route('grn.update', $grn->id) }}">
            @csrf @method('PUT')
            <!--   end of ware house & customer selection -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pre-scrollable">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="10%">Item No</th>
                                            <th width="50%">Item Name</th>
                                            <th width="20%">Quantity</th>
                                            <th width="20%">Actual Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($GRNDetails as $GRNDetail)
                                            <tr>
                                                <td><input type="text" name="itemNo_{!! $i !!}"
                                                        id="itemNo_{!! $i !!}" class="form-control"
                                                        value="{{ $GRNDetail->product_id }}" readonly="readonly"></td>
                                                <td><input type="text" name="itemName_{!! $i !!}"
                                                        id="itemName_{!! $i !!}" class="form-control"
                                                        value="{{ $GRNDetail->product_name }}" readonly="readonly"></td>
                                                <td><input type="number" name="quantity_{!! $i !!}"
                                                        id="quantity_{!! $i !!}" class="form-control"
                                                        value="{{ $GRNDetail->qty }}" readonly="readonly"> </td>
                                                <td><input type="number" name="Aquantity_{!! $i !!}"
                                                        id="Aquantity_{!! $i !!}" class="form-control"
                                                        value="{{ $GRNDetail->qty }}"> </td>
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
            <a class="btn btn-danger btn-block" href="{{ route('grn.index') }}"> @lang('Cancel')</a>
            <input type="submit" class="btn btn-info btn-block" value="Received Qty">
        </div>
    </div>
    </form>

@endsection

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
        <a href="{{ route('grn.index') }}" class="btn btn-primary float-right" style="margin-top: 5px;">Back to Index </a>
    </div>
    <div class="container-fluid pt-5">
        <div class="container-fluid row center">
            <div class="col-4 border border-info">
                <h3 style="text-align: center;">From : </h3>
                <h3 style="text-align: center;">{{ $grn->FromWarehouse_name }} </h3>
            </div>
            <div class="col-4 border border-info">
                <h3 style="text-align: center;">To : </h3>
                <h3 style="text-align: center;"> {{ $grn->ToWarehouse_name }} </h3>
            </div>
            <div class="col-4 border border-info">
                <h3 style="text-align: center;">Date :  </h3>
                <h3 style="text-align: center;">{{ Carbon\Carbon::parse($grn->created_at)->format('Y-m-d') }} </h3>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <table class="table table-hover table-bordered table-sm" style="margin-top: 5px;">
            <thead class="thead-dark">
                <tr style="text-align: center;">
                    <th scope="col">Product Name</th>
                    <th scope="col">Qty</th>
                </tr>
            </thead>
            @if (count($grnDetails))
                @foreach ($grnDetails as $item)
                    <tbody>
                        <tr style="text-align: center;">
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->qty }}</td>
                        </tr>
                @endforeach
            @else
                <tr> No Data Found </tr>
            @endif
            </tbody>
        </table>
    </div>
@endsection

@extends('dashboard')
@section('content')
<div class="container">
    <form method="POST" action="{{ route('InventoryReport.store') }}">
        @csrf
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="col-md-3">Warehouse</label>
                    <select name="warehouse_id" id="warehouse_id" class="form-control">
                        <option value="Total">Total</option>
                        <option value="All">All</option>
                        @foreach ($warehouses as $w)
                            <option value="{{ $w->id }}">{{ $w->warehouse_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="col-md-3">Category</label>
                    <select name="category_id" id="category_id" class="form-control">
                        <option value="All">All</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-block btn-primary">Select</button>
    </form>
</div>
@endsection

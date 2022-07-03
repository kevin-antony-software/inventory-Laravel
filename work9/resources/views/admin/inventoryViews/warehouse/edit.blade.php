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
<div class="container">
    <form method="POST" action="{{ route('warehouse.update', $warehouse->id) }}">
    @csrf
    @method('PUT')
        <div class="form-group">
            <label for="warehouse_name">Warehouse Name</label>
            <input type="text" class="form-control" id="warehouse_name" name="warehouse_name" aria-describedby="name" value="{{ $warehouse->warehouse_name }}" required>
        </div>
    @error('warehouse_name')
        <div class="text-danger">{{ $message }}</div>
    @enderror
        <button class="btn btn-block btn-primary" type="submit">Update Warehouse</button>
    </form>
</div>
@endsection
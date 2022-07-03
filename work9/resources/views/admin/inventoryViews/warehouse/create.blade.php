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
    <form method="POST" action="{{ route('warehouse.store') }}">
    @csrf
        <div class="form-group">
            <label for="name">Warehouse Name</label>
            <input type="text" class="form-control" id="name" name="name" aria-describedby="name" value="{{ old('name') }}" required>
        </div>
    @error('name')
        <div class="text-danger">{{ $message }}</div>
    @enderror
        <button class="btn btn-block btn-primary" type="submit">Create New Warehouse</button>
    </form>
</div>
@endsection
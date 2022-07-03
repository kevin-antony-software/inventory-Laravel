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
        <form method="POST" action="{{ route('machineModel.store') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">

                <label for="model_name">Model Name</label>
                <input class="form-control" type="text" id="model_name" name="model_name" required>
            </div>
            @error('model_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror



            <div class="form-group">

                <label for="category_name">Category</label>
                <input class="form-control" type="text" id="category_name" name="category_name" required>
            </div>
            @error('category_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror


            <button type="submit" class="btn btn-block btn-primary form-control">Save</button>
        </form>
    </div>
@endsection

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
    <form method="POST" action="{{ route('category.update', $category->id) }}">
    @csrf
    @method('PUT')
        <div class="form-group">
            <label for="category_name">Category Name</label>
            <input type="text" class="form-control" id="category_name" name="category_name"  value="{{ $category->name }}" required>
        </div>
    @error('category_name')
        <div class="text-danger">{{ $message }}</div>
    @enderror
        <button class="btn btn-block btn-primary" type="submit">Update Category</button>
    </form>
</div>
@endsection
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
        <form method="POST" action="{{ route('component.update', $component->id) }}">
            @csrf
            @method('PUT')


            <div class="form-group">
                <label for="component_name">Component Name</label>
                <input class="form-control" type="text" id="component_name" name="component_name"
                    value="{{ $component->component_name }}" required>
                @error('component_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="col-md-3">Category</label>
                <select name="category_id" id="category_id" class="form-control">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @if ($category->id == $component->category_id) @selected(true) @endif>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="cost">Cost</label>
                <input class="form-control" type="number" id="cost" name="cost" value="{{ $component->cost }}" required>
                @error('cost')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input class="form-control" type="number" id="price" name="price" value="{{ $component->price }}"
                    required>
                @error('price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-block btn-primary" type="submit">Update Component</button>
        </form>
    </div>
@endsection

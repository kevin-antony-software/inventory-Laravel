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

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">

    <style>
        .ui-autocomplete {
            max-height: 100px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        * html .ui-autocomplete {
            height: 100px;
        }

    </style>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    <script>
        $(function() {
            var cusJqery = {!! json_encode($categories->toArray()) !!};
            var cusName = [];
            for (var ckj = 0; ckj < cusJqery.length; ckj++) {
                cusName.push(cusJqery[ckj].name);
            }
            console.log(cusName);
            $("#category_name").autocomplete({
                source: cusName
            });
        });
    </script>

    <div class="container">
        <h2 style="text-align: center;">Component Creation Form</h2>
        <form method="POST" action="{{ route('component.store') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
                <label for="component_name">Component Name</label>
                <input class="form-control" type="text" id="component_name" name="component_name" required>
                @error('component_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>


            <div class="form-group">
              <label class="col-md-3">Category</label>
              <select name="category_id" id="category_id" class="form-control">
                  @foreach ($categories as $category)
                      <option value="{{ $category->id }}"
                          {{ collect(old('category_id'))->contains($category->id) ? 'selected' : '' }}>
                          {{ $category->name }}</option>
                  @endforeach
              </select>
          </div>

            <div class="form-group">
                <label for="cost">Cost</label>
                <input class="form-control" type="number" id="cost" name="cost" required>
                @error('cost')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input class="form-control" type="number" id="price" name="price" required>
                @error('price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>


            <button type="submit" class="btn btn-block btn-primary form-control">Save</button>
        </form>
    </div>

@endsection

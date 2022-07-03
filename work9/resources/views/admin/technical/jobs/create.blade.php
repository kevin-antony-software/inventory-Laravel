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
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
            var cusJqery = {!! json_encode($customers) !!};
            var cusName = [];
            for (var ckj = 0; ckj < cusJqery.length; ckj++) {
                cusName.push(cusJqery[ckj].customer_name);
            }

            $("#customer_name").autocomplete({
                source: cusName
            });
        });
    </script>
    <script>
        $(function() {
            var models = {!! json_encode($models->toArray()) !!};
            var modelsArray = [];
            for (var ckj = 0; ckj < models.length; ckj++) {
                modelsArray.push(models[ckj].name);
            }

            $("#model").autocomplete({
                source: modelsArray
            });
        });
    </script>
    <div class="container">

        <h2>Job Creation Form</h2>
        <form method="POST" action="{{ route('jobs.store') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">

                <label for="customer_name">Customer </label>
                <input id="customer_name" name="customer_name" class="form-control" value="{{ old('customer_name') }}">


            </div>

            <div class="form-group">
                <label for="serialNumber">Serial Number</label>
                <input type="text" class="form-control" id="serialNumber" name="serialNumber"
                    aria-describedby="serialNumber" value="{{ old('serialNumber') }}" required>
                @error('serialNumber')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <script>
            $(function() {
                $('#serialNumber').on('keypress', function(e) {
                    if (e.which == 32){
                        alert('Space Detected');
                        return false;
                    }
                });
        });
            </script>


            <div class="form-group">
                <label for="soldDate">Sold Date</label>
                <input type="date" class="form-control" id="soldDate" name="soldDate" aria-describedby="soldDate"
                    value="{{ old('soldDate') }}">
                @error('soldDate')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="model">Model</label>
                <input type="text" class="form-control" id="model" name="model" aria-describedby="model"
                    value="{{ old('model') }}" required>
                @error('model')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="promptIn">PromptIn Or How did we receive</label>
                <input type="text" class="form-control" id="promptIn" name="promptIn" aria-describedby="promptIn"
                    value="{{ old('promptIn') }}" required>
                @error('promptIn')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="machine">Machine Type</label>
                <select class="form-control" id="machine" name="machine">
                    <option>Welding Machine</option>
                    <option>Power Tool</option>
                    <option>Air Compressor</option>
                    <option>CNC</option>
                </select>
                @error('machine')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="warranty" id="inlineRadio1" value="withWarranty">
                    <label class="form-check-label" for="inlineRadio1">With Warranty</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="warranty" id="inlineRadio2" value="withoutWarranty">
                    <label class="form-check-label" for="inlineRadio2">Without Warranty</label>
                </div>
            </div>


            <button class="btn btn-primary form-control" type="submit">Create New Job</button>

        </form>
    </div>

@endsection

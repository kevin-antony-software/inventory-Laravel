@extends('dashboard')
@section('content')
    <div class="container">
        <form method="POST" action="{{ route('RepairModels.store') }}">
            @csrf
            <div class="row">
                <label class="form-group" for="Model">Select Model</label>
                <select class="form-control" id="Model" name="Model">
                    <option selected>Open this select menu</option>
                    @foreach ($models as $model)
                        <option value="{{ $model->name }}">{{ $model->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group pt-2">
                <input type="submit" class="btn btn-block btn-info" value="Search">
            </div>
        </form>
    </div>

    <div class="container">
        <form method="POST" action="{{ route('RepairModels.store') }}">
            @csrf
            <div class="row">
                <label class="form-group" for="Issue">Select Issue</label>
                <select class="form-control" id="Issue" name="Issue">
                    <option selected>Open this select menu</option>
                    @foreach ($issues as $issue)
                        <option value="{{ $issue->issue }}">{{ $issue->issue }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group pt-2">
                <input type="submit" class="btn btn-block btn-info" value="Search">
            </div>
        </form>
    </div>



@endsection

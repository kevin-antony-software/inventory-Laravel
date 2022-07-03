@extends('dashboard')
@section('content')
    <div class="container">
        <form method="POST" action="{{ route('chequeReport.store') }}">
            @csrf
            <div class="row">
                <label class="form-group" for="status">Select Cheque Status</label>
                <select class="form-control" id="status" name="status">
                    <option selected>Open this select menu</option>
                    <option value="Returned">Returned</option>
                    @can('director-only')
                        <option value="Passed">Passed</option>
                        <option value="Pending">Pending</option>
                        <option value="All">All</option>
                    @endcan
                </select>
            </div>
            <div class="form-group pt-2">
                <input type="submit" class="btn btn-block btn-info" value="Search">
            </div>
        </form>
    </div>
@endsection

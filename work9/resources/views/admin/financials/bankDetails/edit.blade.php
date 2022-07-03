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
    <form method="POST" action="{{ route('bank.update', $bank->id) }}">
    @csrf @method('PUT')

        <div class="form-group">
            <label for="name">Bank Name</label>
            <input type="text" class="form-control" id="name" name="name" aria-describedby="name" value="{{ $bank->name }}" required>
        </div>
    @error('name')
        <div class="text-danger">{{ $message }}</div>
    @enderror

    <div class="form-group">
            <label for="branch">Branch</label>
            <input type="text" class="form-control" id="branch" name="branch" aria-describedby="branch" value="{{ $bank->branch }}">
        </div>
    @error('branch')
        <div class="text-danger">{{ $message }}</div>
    @enderror

    <div class="form-group">
            <label for="accountNum">Account Number</label>
            <input type="number" class="form-control" id="accountNum" name="accountNum" aria-describedby="accountNum" value="{{ $bank->accountNum }}">
        </div>
    @error('accountNum')
        <div class="text-danger">{{ $message }}</div>
    @enderror

    <div class="form-group">
            <label for="balance">Balance</label>
            <input type="number" step = "0.01" class="form-control" id="balance" name="balance" aria-describedby="balance" value="{{ $bank->balance }}">
        </div>
    @error('balance')
        <div class="text-danger">{{ $message }}</div>
    @enderror



        <button class="btn btn-block btn-primary" type="submit">Update bank</button>
    </form>
</div>
@endsection
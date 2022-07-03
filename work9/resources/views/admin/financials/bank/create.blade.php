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
<form method="POST" action="{{ route('bank.store') }}">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
   
  <div class="form-group">
    <label for="Bankname">Name</label>
    <input type="text" class="form-control" id="Bankname" name="Bankname" aria-describedby="Bankname" value="{{ old('Bankname') }}" required>
    @error('Bankname')
    <div class="text-danger">{{ $message }}</div>
  @enderror
  </div>
  
  <div class="form-group">
    <label for="accountNum">account Number</label>
    <input type="number" class="form-control" id="accountNum" name="accountNum" aria-describedby="accountNum" value="{{ old('accountNum') }}">
    @error('accountNum')
    <div class="text-danger">{{ $message }}</div>
  @enderror
  </div>

  <div class="form-group">
    <label for="branch">Branch</label>
    <input type="text" class="form-control" id="branch" name="branch" aria-describedby="branch" value="{{ old('branch') }}" required>
    @error('branch')
    <div class="text-danger">{{ $message }}</div>
  @enderror
  </div>
  <div class="form-group">
    <label for="balance">Account balance</label>
    <input type="number" class="form-control" id="balance" name="balance" aria-describedby="balance" value="{{ old('balance') }}">
    @error('balance')
    <div class="text-danger">{{ $message }}</div>
  @enderror
  </div>


    <button class="btn btn-primary" type="submit">Create Bank</button>

</form>
</div>

@endsection
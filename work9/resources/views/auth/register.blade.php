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

<div class="container-fluid">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label for="name"  class="form-label" >Name</label>
                <input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            <div class="mb-3">
                <label for="email"  class="form-label" >Email</label>
                <input id="email" class="form-control" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="mb-3">
                <label for="password"  class="form-label" >Password</label>
                <input id="password" class="form-control" type="password" name="password" required />
            </div>

            <div class="mb-3">
                <label for="password_confirmation"  class="form-label" >Confirm Password</label>
                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required />
            </div>

            <button class="btn btn-block btn-primary" type="submit">New User</button>
        </form>
</div>
@endsection

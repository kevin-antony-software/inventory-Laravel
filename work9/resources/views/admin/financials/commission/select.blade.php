
@extends('dashboard')
@section('content')

<div class="container-fluid">
    <form method="POST" action="{{ route('commission.result') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="col-lg-12">
                <div class="form-group">
                  <label class="col-md-3">User</label>
                    <select name="user_id" id="user_id" class="form-control">
                       
                        @foreach($users as $w)

                        <option value="{{ $w->user->id }}" >{{ $w->user->name }}</option>
 
                        @endforeach
                    </select>
                </div>
            </div>
                    <button class="btn btn-block btn-primary" type="submit">view Summary</button>
    </form>
</div>

@endsection
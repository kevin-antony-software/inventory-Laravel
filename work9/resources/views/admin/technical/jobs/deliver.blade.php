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
    <form method="POST" action="{{ route('jobs.update', $job->id) }}" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        @method('PUT')


        <div class="form-group">

            <label for="promptOut">prompt Out</label>
            <input class="form-control" type="text" id="promptOut" name="promptOut" required>
        </div>
        <div class="form-group">
    
            <label for="comment">Comment</label>
            <input class="form-control" type="text" id="comment" name="comment">
        </div>


            <div class="col-md-6">
                <button type="submit" class="btn btn-success">Send</button>
            </div>


    </form>

</div>

@endsection
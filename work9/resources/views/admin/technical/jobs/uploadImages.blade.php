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


        <div class="row">
  
            <div class="col-md-6">
                <input type="file" name="image1" id = "image1" class="form-control">
            </div>
            <div class="col-md-6">
                <input type="file" name="image2" id = "image2"class="form-control">
            </div>
            <div class="col-md-6">
                <input type="file" name="image3" id = "image3" class="form-control">
            </div>
            <div class="col-md-6">
                <input type="file" name="image4" id = "image4" class="form-control">
            </div>
            <div class="col-md-6">
                <input type="file" name="image5" id = "image5" class="form-control">
            </div>


            <div class="col-md-6">
                <button type="submit" class="btn btn-success">Upload</button>
            </div>

        </div>

    </form>

</div>



    
@endsection
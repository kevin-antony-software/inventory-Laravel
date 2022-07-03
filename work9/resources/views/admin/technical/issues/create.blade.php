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
    <form method="POST" action="{{ route('issues.store') }}">
        @csrf
        <div class="container">
            <div class="form-group">
                <label for="issue">Create a common issue</label>
                <input type="text" class="form-control" id="issue" name="issue" aria-describedby="issue"
                    value="{{ old('issue') }}" required>
                @error('issue')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button class="btn btn-primary form-control" type="submit">Create New Issue</button>
        </div>
    </form>
    </div>
@endsection

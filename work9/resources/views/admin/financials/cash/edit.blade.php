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
        <form method="POST" action="{{ route('cash.update', $cash->id) }}" onsubmit="return validateForm()">
            @csrf @method('PUT')

            <div class="form-group">
                <label for="currentCash">Old Cash Balance in the System</label>
                <input type="number" class="form-control" id="currentCash" name="currentCash"
                    value="{{ $cash->balance }}" disabled>
            </div>

            <div class="form-group">
                <label for="newCash">new Correct Cash Amount</label>
                <input type="number" class="form-control" id="newCash" name="newCash" value="">
            </div>
            @error('newCash')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            <button class="btn btn-block btn-primary" type="submit">Update Cash</button>
        </form>
    </div>
    <script>
        function validateForm() {
            if (document.getElementById("newCash").value == "") {
                alert("New Cash cant be blank");
                return false;
            }
        }
    </script>
@endsection

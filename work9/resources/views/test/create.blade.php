

<form method="POST" action="{{ route('request.baba', 2) }}">
    @csrf

    <input type="text" id = "test" name = "test">
    <button type="submit">Save</button>

</form>
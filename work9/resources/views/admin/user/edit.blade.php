@extends('dashboard')
@section('content')
    <a class="btn btn-success float-right" href="{{ route('user.index') }}" role="button">Back to Index</a>
    <div class="container pt-5">

        <h6><strong>User ID - {{ $user->id }} </strong></h6>
        <h6><strong>User Name - {{ $user->name }} </strong></h6>

    </div>

    <div class="container">
        <form method="POST" action="{{ route('user.update', $user->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group pt-2">
                <label for="position">Choose a Position</label>
                <select id="position" name="position" class="form-control" :value="{{ $user->position }}">
                    <option @if ($user->position == 'admin') {{ 'selected="selected"' }} @endif value="admin"> admin
                    </option>
                    <option @if ($user->position == 'director') {{ 'selected="selected"' }} @endif value="director">
                        director</option>
                    <option @if ($user->position == 'manager') {{ 'selected="selected"' }} @endif value="manager">manager
                    </option>
                    <option @if ($user->position == 'technical-executive') {{ 'selected="selected"' }} @endif
                        value="technical-executive">technical-executive</option>
                    <option @if ($user->position == 'sales-executive') {{ 'selected="selected"' }} @endif value="sales-executive">
                        sales-executive</option>
                    <option @if ($user->position == 'store-keeper') {{ 'selected="selected"' }} @endif value="store-keeper">store-keeper
                    </option>
                    <option @if ($user->position == 'banker') {{ 'selected="selected"' }} @endif value="banker">banker
                    </option>
                    <option @if ($user->position == 'supplier') {{ 'selected="selected"' }} @endif value="supplier">
                        supplier</option>
                </select>
            </div>
            <label for="warehousesforUser" class="form-label">Choose Warehouses</label>
            <div class="form-group" id="warehousesforUser">
            <?php $i = 1; ?>
            @foreach ($warehouses as $WH)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{ $WH->id }}" name="warehouse[]"
                        id="warehouse_{{ $i }}" selected @if (in_array($WH->id, $selectedWarehouses)) checked="checked" @endif>
                    <label class="form-check-label" for="warehouse_{{ $i }}">
                        {{ $WH->warehouse_name }}
                    </label>
                </div>
                <?php $i = $i + 1; ?>
            @endforeach
            </div>





            <button class="btn btn-block btn-primary" type="submit">Edit User</button>
        </form>
    </div>
@endsection

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
        <form method="POST" action="{{ route('CourierCustomer.update', $CourierCustomer->id) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label for="customer_name">Customer Name</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name"
                    value="{{ $CourierCustomer->courier_customer_name }}" required>
                @error('customer_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ $CourierCustomer->address }}"
                    required>
                @error('address')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="mobile">mobile Number</label>
                <input type="number" class="form-control" id="mobile" name="mobile" value="{{ $CourierCustomer->mobile }}" required>
                @error('mobile')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="phone">phone Number</label>
                <input type="number" class="form-control" id="phone" name="phone" value="{{ $CourierCustomer->phone }}">
                @error('phone')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
    </div>

    <button class="btn btn-block btn-primary" type="submit">Update Courier Customer</button>
    </form>
    </div>

@endsection

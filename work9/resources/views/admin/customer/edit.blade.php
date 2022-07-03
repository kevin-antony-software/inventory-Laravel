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
        <form method="POST" action="{{ route('customer.update', $customer->id) }}">
            @csrf @method('PUT')

            <div class="form-group">
                <label for="customer_type">Customer Type</label>
                <select id="customer_type" name="customer_type" class="form-control" required>
                    <option value="repair customer" {{ $customer->customer_type == 'repair customer' ? 'selected' : '' }}>
                        Repair Customer</option>
                    <option value="factory" {{ $customer->customer_type == 'factory' ? 'selected' : '' }}>Factory</option>
                    @can('director-only')
                        <option value="dealer" {{ $customer->customer_type == 'dealer' ? 'selected' : '' }}>Dealer</option>
                    @endcan
                </select>
            </div>
            @error('customer_type')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="customer_name">Customer Name</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name"
                    value="{{ $customer->customer_name }}" required>
                @error('customer_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ $customer->address }}"
                    required>
                @error('address')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="city">City</label>
                <input type="text" class="form-control" id="city" name="city" aria-describedby="city"
                    value="{{ $customer->city }}" required>
                @error('city')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="mobile">mobile Number</label>
                <input type="number" class="form-control" id="mobile" name="mobile" aria-describedby="mobile"
                    value="{{ $customer->mobile }}" required>
                @error('mobile')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div id="dealer-section" style="display: none;">
                <div class="form-group">
                    <label for="credit_limit">Credit limit</label>
                    <input type="number" class="form-control" id="credit_limit" name="credit_limit"
                        value="{{ $customer->creditLimit }}">
                    @error('credit_limit')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="cheque_limit">Cheque limit</label>
                    <input type="number" class="form-control" id="cheque_limit" name="cheque_limit"
                        value="{{ $customer->chequeLimit }}">
                    @error('cheque_limit')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                
                <div class="form-group">
                    <label for="owner_id">Customer Owner</label>
                    <select id="owner_id" name="owner_id" class="form-control">
                        <option value="">Choose Owner</option>
                        @foreach ($users as $owner)
                            <option value="{{ $owner->id }}"
                                {{ $customer->owner_ID == $owner->id ? 'selected' : '' }}>{{ $owner->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('owner_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                <div class="form-group">
                    <label for="email">email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $customer->email }}">
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">phone Number</label>
                    <input type="number" class="form-control" id="phone" name="phone" value="{{ $customer->phone }}">
                    @error('phone')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="BRnumber">BR number</label>
                    <input type="text" class="form-control" id="BRnumber" name="BRnumber"
                        value="{{ $customer->BRnumber }}">
                    @error('BRnumber')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="VATnumber">VAT Number</label>
                    <input type="text" class="form-control" id="VATnumber" name="VATnumber"
                        value="{{ $customer->VATnumber }}">
                    @error('VATnumber')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <button class="btn btn-block btn-primary" type="submit">Edit Customer</button>
        </form>
    </div>

    <script>
        document.getElementById("customer_type").addEventListener("change", checkType);
        window.onload = function() {
            checkType();
        };

        function checkType() {
            if (document.getElementById("customer_type").value == 'dealer') {
                document.getElementById("dealer-section").style.display = "block"
            } else {
                document.getElementById("dealer-section").style.display = "none"
            }
        }
    </script>
@endsection

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
        <form method="POST" action="{{ route('commission.update', $commission->id) }}">
            @csrf @method('PUT')

            <div class="form-group">
                <label for="owner_name">Owner</label>
                <input type="text" class="form-control" id="owner_name" name="owner_name" aria-describedby="owner_name"
                    value="{{ $commission->owner_name }}" readonly>
            </div>
            @error('owner_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="month">Month</label>
                <input type="text" class="form-control" id="month" name="month" aria-describedby="month"
                    value="{{ $commission->month }}" readonly>
            </div>
            @error('month')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="year">Year</label>
                <input type="text" class="form-control" id="year" name="year" aria-describedby="year"
                    value="{{ $commission->year }}" readonly>
            </div>
            @error('year')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="invoiceDueAmount">invoice Due Amount</label>
                <input type="text" class="form-control" id="invoiceDueAmount" name="invoiceDueAmount"
                    aria-describedby="invoiceDueAmount" value="{{ $commission->invoiceDueAmount }}" readonly>
            </div>
            @error('invoiceDueAmount')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="totalCommission">Suggested Commission</label>
                <input type="text" class="form-control" id="totalCommission" name="totalCommission"
                    aria-describedby="totalCommission" value="{{ $commission->totalCommission }}" required>
            </div>
            @error('totalCommission')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="returnChequeCommission">Commission deduction due to old Return Cheques</label>
                <input type="text" class="form-control" id="returnChequeCommission" name="returnChequeCommission"
                    aria-describedby="returnChequeCommission" value="{{ $commission->returnChequeCommission }}" required>
            </div>
            @error('returnChequeCommission')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="paidCommission">Paid Commission</label>
                <input type="text" class="form-control" id="paidCommission" name="paidCommission"
                    aria-describedby="paidCommission" value="{{ $commission->paidCommission }}" required>
            </div>
            @error('paidCommission')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <button class="btn btn-block btn-primary" type="submit">Update Commission</button>
        </form>
    </div>
@endsection

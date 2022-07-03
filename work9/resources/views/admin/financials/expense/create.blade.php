@extends('dashboard')
@section('content')

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif
    @if (session()->has('message'))
        <div class="alert alert-danger">
            {{ session()->get('message') }}
        </div>
    @endif

    <div class="container">
        <form method="POST" action="{{ route('expense.store') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
                <label for="to">To</label>
                <input type="text" class="form-control" id="to" name="to" aria-describedby="to" value="{{ old('to') }}"
                    required>
                @error('to')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="actualDate">Actual Date</label>
                <input type="date" class="form-control" id="actualDate" name="actualDate" value="{{ $today }}">
                @error('actualDate')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <SELECT name="category" id="category" class="form-control">
                    <option value="">Choose category</option>
                    <option value="Transport - Lodging"
                        {{ collect(old('category'))->contains('Transport - Lodging') ? 'selected' : '' }}>Transport -
                        Lodging</option>
                    <option value="Transport - Fuel"
                        {{ collect(old('category'))->contains('Transport - Fuel') ? 'selected' : '' }}>Transport - Fuel
                    </option>
                    <option value="Transport - Others"
                        {{ collect(old('category'))->contains('Transport - Others') ? 'selected' : '' }}>Transport -
                        Others</option>
                    <option value="Marketing" {{ collect(old('category'))->contains('Marketing') ? 'selected' : '' }}>
                        Marketing</option>
                    <option value="Repair" {{ collect(old('category'))->contains('Repair') ? 'selected' : '' }}>Repair
                    </option>
                    <option value="Prompt" {{ collect(old('category'))->contains('Prompt') ? 'selected' : '' }}>Prompt
                    </option>
                    <option value="Office" {{ collect(old('category'))->contains('Office') ? 'selected' : '' }}>Office
                    </option>
                    <option value="Stationary" {{ collect(old('category'))->contains('Stationary') ? 'selected' : '' }}>
                        Stationary</option>
                    <option value="MISC" {{ collect(old('category'))->contains('MISC') ? 'selected' : '' }}>MISC</option>
                    @can('director-only')
                        <option value="Bank Lease" {{ collect(old('category'))->contains('Bank Lease') ? 'selected' : '' }}>
                            Bank Lease</option>
                        <option value="Bank Interest"
                            {{ collect(old('category'))->contains('Bank Interest') ? 'selected' : '' }}>Bank Interest
                        </option>
                        <option value="Salary" {{ collect(old('category'))->contains('Salary') ? 'selected' : '' }}>Salary
                        </option>
                        <option value="Bank Expense" {{ collect(old('category'))->contains('Bank Expense') ? 'selected' : '' }}>Bank Expense
                        </option>
                        <option value="Commission" {{ collect(old('category'))->contains('Commission') ? 'selected' : '' }}>Commission
                        </option>
                        <option value="Bank Loan" {{ collect(old('category'))->contains('Bank Loan') ? 'selected' : '' }}>Bank Loan
                        </option>
                        <option value="EPF" {{ collect(old('category'))->contains('EPF') ? 'selected' : '' }}>EPF
                        </option>
                        <option value="ETF" {{ collect(old('category'))->contains('ETF') ? 'selected' : '' }}>ETF
                        </option>
                        <option value="Accounting" {{ collect(old('category'))->contains('Accounting') ? 'selected' : '' }}>
                            Accounting</option>
                        <option value="BadDebt" {{ collect(old('category'))->contains('BadDebt') ? 'selected' : '' }}>BadDebt
                        </option>
                        <option value="DirectorExpense"
                            {{ collect(old('category'))->contains('DirectorExpense') ? 'selected' : '' }}>DirectorExpense
                        </option>
                        <option value="NewBuilding"
                            {{ collect(old('category'))->contains('NewBuilding') ? 'selected' : '' }}>NewBuilding</option>
                        <option value="COGS" {{ collect(old('category'))->contains('COGS') ? 'selected' : '' }}>COGS</option>
                    @endcan

                </SELECT>
                @error('category')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="method">Method</label>
                <SELECT name="method" id="method" class="form-control">

                    <option value="cash">Cash</option>
                    @can('director-only')
                        <option value="Bank Transfer">Bank Transfer</option>
                    @endcan
                </SELECT>
            </div>
            @can('director-only')
                <div class="form-group" id="bankA" hidden>
                    <label for="bank">Bank</label>
                    <SELECT name="bank" id="bank" class="form-control">
                        <option value="">Choose bank</option>
                        @foreach ($banks as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                        @endforeach


                    </SELECT>
                </div>
            @endcan


            <div class="form-group">
                <label for="amount">Amount </label>
                <input type="number" class="form-control" id="amount" name="amount" aria-describedby="amount"
                    value="{{ old('amount') }}">
                @error('amount')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" class="form-control" id="description" name="description" aria-describedby="description"
                    value="{{ old('description') }}" required>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>


            <button class="btn btn-block btn-primary" type="submit">Save</button>

        </form>
    </div>

    <script>
        document.getElementById("method").addEventListener("change", myFunctionL);

        function myFunctionL() {

            if (document.getElementById("method").value == "Bank Transfer") {
                document.getElementById("bankA").hidden = false;

            } else {
                document.getElementById("bankA").hidden = true;
            }

        }
    </script>

@endsection

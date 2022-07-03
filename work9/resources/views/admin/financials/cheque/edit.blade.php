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
        <form method="POST" action="{{ route('cheque.update', $cheque->id) }}">
            @csrf @method('PUT')
            <style>
                .pre-scrollable {
                    max-height: 540px;
                    overflow-y: scroll;
                    overflow-x: scroll;
                }
                td {
                    min-width: 90px;
                }
            </style>
            <div class="pre-scrollable">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th width=40%>Item</th>
                            <th width=35%>Old</th>
                            <th width=25%>New</th>
                        </tr>
                        <tr>
                            <td>Cheque No</td>
                            <td>{{ $cheque->number }}</td>
                            <td><input type="number" name="NewChequeNo" id="NewChequeNo" class="form-control" value="{{ $cheque->number }}"></td>
                        </tr>
                        <tr>
                            <td>Branch</td>
                            <td>{{ $cheque->branch }}</td>
                            <td><input type="number" name="NewBranchNo" id="NewBranchNo" class="form-control" value="{{ $cheque->branch }}"></td>
                        </tr>
                        <tr>
                            <td>Bank</td>
                            <td>{{ $cheque->bank }}</td>
                            <td><input type="number" name="NewBankNo" id="NewBankNo" class="form-control" value="{{ $cheque->bank }}"></td>
                        </tr>
                        <tr>
                            <td>Cheque Date</td>
                            <td>{{ $cheque->chequeDate }}</td>
                            <td><input type="date" name="newChequeDate" id="newChequeDate" class="form-control" value="{{ $cheque->chequeDate }}"></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="form-group">
                <a class="btn btn-danger" href="{{ route('cheque.index') }}"> Cancel</a>
                <input onclick="save()" type="submit" class="btn btn-info " value="Save">
            </div>
        </form>
    </div>
@endsection

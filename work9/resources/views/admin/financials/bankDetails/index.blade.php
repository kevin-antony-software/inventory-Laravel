@extends('dashboard')
@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>

    <section class="content">
        <div class="container-fluid pt-2">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('bank.create') }}" class="btn btn-block btn-primary ">Add new Bank</a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('bank.transfer') }}" class="btn btn-block btn-primary ">Bank Transfer</a>
                </div>
            </div>
            <div class="pt-2">
                <table id="myTable" class="display" width="100%">

                    <thead class="thead-dark">
                        <tr>
                            <th style="width:10%;">ID</th>
                            <th style="width:20%;">Bank Name </th>
                            <th style="width:20%;">Bank Account Number </th>
                            <th style="width:20%;">Branch </th>
                            <th style="width:20%;">Balance </th>
                            <th style="width:20%;">Action </th>

                        </tr>
                    </thead>
                    <tbody>
                        @if (count($banks))
                            @foreach ($banks as $bank)
                                <tr>
                                    <td> {{ $bank->id }}</td>
                                    <td> {{ $bank->name }} </td>
                                    <td> {{ $bank->accountNum }} </td>
                                    <td> {{ $bank->branch }} </td>
                                    <td> {{ number_format($bank->balance,2) }} </td>

                                    <td>
                                        <a href="{{ route('bank.show', $bank->id) }}" class="fas fa-eye"></a>
                                        <a href="{{ route('bank.edit', $bank->id) }}" class="far fa-edit"></a>

                                        <a href="javascript:void(0)" onclick="$(this).parent().find('form').submit()"
                                            class="far fa-trash-alt"></a>
                                        <form action="{{ route('bank.destroy', $bank->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')

                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr> No Data Found </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>

        <script>
            $(document).ready(function() {
                $('#myTable').DataTable({
                    "order": [
                        [0, "desc"]
                    ],
                    "lengthMenu": [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ]
                });
            });
        </script>

    </section>

@endsection
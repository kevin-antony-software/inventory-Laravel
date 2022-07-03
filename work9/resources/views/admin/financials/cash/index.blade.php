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
                <div class="col-6">
                    <a href="{{ route('cash.create') }}" class="btn btn-block btn-primary ">Cash Withraw/Deposite from Bank</a>
                </div>
                @if (count($cash))
                <div class="col-6">
                    <a href="{{ route('cash.edit', $cash[count($cash) - 1]->id) }}" class="btn btn-block btn-warning ">Adjust Cash Balance</a>
                </div>
                @endif
            </div>
            <div class="pt-2">
                <table id="myTable" class="display" width="100%">

                    <thead class="thead-dark">
                        <tr>
                            <th style="width:10%;">ID</th>
                            <th style="width:20%;">Payment ID </th>
                            <th style="width:20%;">Expense ID </th>
                            <th style="width:20%;">Amount </th>
                            <th style="width:20%;">Balance </th>
                            <th style="width:20%;">Category </th>
                            <th style="width:20%;">Date </th>
                            <th style="width:20%;">Action </th>

                        </tr>
                    </thead>
                    <tbody>
                        @if (count($cash))
                            @foreach ($cash as $c)
                                <tr>
                                    <td> {{ $c->id }}</td>
                                    <td> {{ $c->payment_id }} </td>
                                    <td> {{ $c->expense_id }} </td>
                                    <td> {{ number_format($c->amount,2) }} </td>
                                    <td> {{ number_format($c->balance,2) }} </td>
                                    <td> {{ $c->category }} </td>
                                    <td> {{ Carbon\Carbon::parse($c->created_at)->format('Y-m-d') }} </td>

                                    <td>
                                        <a href="{{ route('cash.show', $c->id) }}" class="fas fa-eye"></a>
                                        <a href="{{ route('cash.edit', $c->id) }}" class="far fa-edit"></a>

                                        <a href="javascript:void(0)" onclick="$(this).parent().find('form').submit()"
                                            class="far fa-trash-alt"></a>
                                        <form action="{{ route('cash.destroy', $c->id) }}" method="post">
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

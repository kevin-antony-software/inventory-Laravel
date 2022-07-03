@extends('dashboard')
@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    <section class="content">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
            integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href=https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css>
        <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js">
        </script>



        <script type="text/javascript" charset="utf8"
                src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js">
        </script>
        <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
        </script>
        <script type="text/javascript" charset="utf8"
                src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js">
        </script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js">
        </script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js">
        </script>
        <script type="text/javascript" charset="utf8"
                src="https://cdn.datatables.net/plug-ins/1.10.20/filtering/row-based/range_dates.js"></script>



        <div class="container-fluid pt-2">
            <div class="row">
                <div class="col-md-6">

                    <a href="{{ route('expense.create') }}" class="btn btn-primary ">Add new Expense</a>

                </div>
            </div>
            <div class="pt-2">
                <table id="myTable" class="display" width="100%">

                    <thead class="thead-dark">
                        <tr>

                            <th width=10%>ID</th>
                            <th width=20%>To</th>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>User</th>
                            <th>Description</th>


                        </tr>
                    </thead>
                    <tbody>
                        @if (count($expenses))
                            @foreach ($expenses as $expense)
                                <tr>
                                    <td>{!! $expense->id !!}</td>
                                    <td>{!! $expense->to !!}</td>
                                    <td>{!! $expense->actualDate !!}</td>
                                    <td>{!! $expense->category !!}</td>
                                    <td>{!! $expense->method !!}</td>
                                    <td>{!! $expense->amount !!}</td>
                                    <td>{!! $expense->user_name !!}</td>
                                    <td>{!! $expense->description !!}</td>
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
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "paging": false,
                });
            });
        </script>




    </section>




@endsection

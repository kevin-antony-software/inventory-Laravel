@extends('dashboard')
@section('content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>


    <section class="content">
        <div class="container-fluid pt-2">
            <div class="row">
                <div class="col-md-6">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary ">Add new User</a>
                    @endif
                </div>
            </div>

            <div class="pt-2">
                <table id="myTable" class="display" width="100%">

                    <thead class="thead-dark">
                        <tr>
                            <th style="width:10%;">ID</th>
                            <th style="width:20%;">User Name </th>
                            <th style="width:30%;"> Email </th>
                            <th style="width:20%;"> Position </th>
                            <th style="width:20%;">Actions </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($users))
                            @foreach ($users as $user)
                                <tr>
                                    <td> {{ $user->id }}</td>
                                    <td> {{ $user->name }} </td>
                                    <td> {{ $user->email }} </td>
                                    <td> {{ $user->position }} </td>
                                    <td><a href="{{ route('user.edit', $user->id) }}" class="far fa-edit fa-fw"
                                            style="margin-right: 10px;"></a>
                                        <a href="{{ route('user.changePassword', $user->id) }}" class="fa-solid fa-lock"
                                            style="margin-right: 10px;"></a>
                                        <a href="javascript:void(0)" onclick="$(this).parent().find('form').submit()"
                                            class="far fa-trash-alt fa-fw"></a>
                                        <form action="{{ route('user.destroy', $user->id) }}" method="post">
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

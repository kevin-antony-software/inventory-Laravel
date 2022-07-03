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
            <div>
                <a href="{{ route('component.create') }}" class="btn btn-primary"> Add new Component</a>
            </div>
            <div class="pt-2">
                <table id="myTable" class="display" width="100%">

                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name </th>
                            <th>Category </th>
                            <th>Cost </th>
                            <th>Price </th>
                            <th>Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($components))
                            @foreach ($components as $component)
                                <tr>
                                    <td> {{ $component->id }}</td>
                                    <td> {{ $component->component_name }} </td>
                                    <td> {{ $component->category_name }}</td>
                                    <td> {{ $component->cost }} </td>
                                    <td> {{ $component->price }} </td>
                                    <td>
                                        <a href="{{ route('component.edit', $component->id) }}" class="far fa-edit"></a>
                                        <a href="javascript:void(0)" onclick="$(this).parent().find('form').submit()"
                                            class="far fa-trash-alt"></a>
                                        <form action="{{ route('component.destroy', $component->id) }}" method="post">
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
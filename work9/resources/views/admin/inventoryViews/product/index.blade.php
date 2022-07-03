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

                    <a href="{{ route('product.create') }}" class="btn btn-primary ">Add new Product</a>

                </div>
            </div>
            <div class="pt-2">
                <table id="myTable" class="display" width="100%">

                    <thead class="thead-dark">
                        <tr>
                            <th style="width:10%;"> ID</th>
                            <th style="width:15%;"> Name </th>
                            <th style="width:10%;"> Category ID </th>
                            <th style="width:10%;"> Category Name </th>
                            @can('director-only')
                            <th style="width:10%;"> USD </th>
                            <th style="width:10%;"> USD Rate </th>
                            <th style="width:15%;"> Initial Cost </th>
                            <th style="width:10%;"> DFC </th>
                            <th style="width:10%;"> Final Cost </th>
                            @endcan
                            <th style="width:10%;"> Price </th>
                            @can('director-only')
                                <th style="width:10%;"> Edit/Delete </th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($products))
                            @foreach ($products as $product)
                                <tr>
                                    <td> {{ $product->id }}</td>
                                    <td> {{ $product->product_name }} </td>
                                    <td> {{ $product->category_id }} </td>
                                    <td> {{ $product->category_name }} </td>
                                    @can('director-only')
                                        <td> {{ $product->USDcost }} </td>
                                        <td> {{ $product->ExchangeUSDRate }} </td>
                                        <td> {{ $product->firstCost }} </td>
                                        <td> {{ $product->DFP }} </td>
                                        <td> {{ $product->totalcost }} </td>
                                    @endcan
                                    <td> {{ $product->price }} </td>
                                    @can('director-only')
                                    <td>
                                        <a href="{{ route('product.edit', $product->id) }}" class="far fa-edit"></a>
                                        <a href="javascript:void(0)" onclick="$(this).parent().find('form').submit()"
                                            class="far fa-trash-alt"></a>
                                        <form action="{{ route('product.destroy', $product->id) }}" method="post">
                                            @csrf @method('DELETE')
                                        </form>
                                    </td>
                                    @endcan
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

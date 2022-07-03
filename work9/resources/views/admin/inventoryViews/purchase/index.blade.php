@extends('dashboard')
@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <div class="container-fluid pt-2">
        <div class="row">
            @can('director-only')
                <div class="col-md-6">
                    <a href="{{ route('purchase.create') }}" class="btn btn-primary ">Add new Purchase</a>
                </div>
            @endcan
        </div>
        <div class="pt-2">
            <table id="myTable" class="display" width="100%">
                <thead class="thead-dark">
                    <tr>
                        <th style="width:10%;">ID</th>
                        <th style="width:20%;">Warehouse </th>
                        @can('director-only')
                            <th style="width:20%;">Total USD </th>
                            <th style="width:20%;">Total Cost </th>
                            <th style="width:20%;">Total Price </th>
                        @endcan
                        <th style="width:20%;">User </th>
                        <th style="width:20%;">Status </th>
                        <th style="width:20%;">Date </th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($purchases))
                        @foreach ($purchases as $purchase)
                            <tr>
                                <td> {{ $purchase->id }}</td>
                                <td> {{ $purchase->warehouse_name }} </td>
                                @can('director-only')
                                    <td> {{ $purchase->total_USD }} </td>
                                    <td> {{ $purchase->total_cost }} </td>
                                    <td> {{ $purchase->total_price }} </td>
                                @endcan
                                <td> {{ $purchase->user_name }} </td>
                                <td> {{ $purchase->status }} </td>
                                <td>{!! $purchase->created_at->format('d/m/Y') !!}</td>
                                <td>
                                    <a href="{{ route('purchase.show', $purchase->id) }}" class="fas fa-eye"></a>
                                    <a href="{{ route('purchase.generatePDF', $purchase->id) }}"
                                        class="fas fa-print"></a>
                                    @if ($purchase->status != 'Received')
                                        <a href="{{ route('purchase.edit', $purchase->id) }}" class="far fa-edit"></a>
                                        @can('director-only')
                                            <a href="javascript:void(0)" onclick="$(this).parent().find('form').submit()"
                                                class="far fa-trash-alt"></a>
                                            <form action="{{ route('purchase.destroy', $purchase->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endcan
                                    @endif

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
    <div class="float-right">
        {{ $purchases->links() }}
    </div>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "bPaginate": false,
            });
        });
    </script>
@endsection

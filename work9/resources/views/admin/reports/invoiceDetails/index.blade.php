@extends('dashboard')
@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
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
        This page took {{ (microtime(true) - LARAVEL_START) }} seconds to render
        <table id="myTable" class="display" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th>Invoice ID</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Price After</th>
                    <th>Sub total</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @if (count($invoiceDetails))
                    @foreach ($invoiceDetails as $invoiceDetail)
                        <tr>
                            <td>{!! $invoiceDetail->invoice_id !!}</td>
                            <td>{!! $invoiceDetail->customer_name !!}</td>
                            <td>{!! $invoiceDetail->product_name !!}</td>
                            <td>{!! $invoiceDetail->qty !!}</td>
                            <td>{!! $invoiceDetail->price !!}</td>
                            <td>{!! $invoiceDetail->discountPercentage !!}</td>
                            <td>{!! $invoiceDetail->priceAfterDiscount !!}</td>
                            <td>{!! $invoiceDetail->subtotal_price !!}</td>
                            <td>{!! $invoiceDetail->created_at !!}</td>
                        </tr>
                    @endforeach
                @else
                    <tr> No Data Found </tr>
                @endif
            </tbody>
        </table>
        
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
    
@endsection

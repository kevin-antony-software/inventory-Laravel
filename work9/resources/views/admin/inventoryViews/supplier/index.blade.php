@extends('dashboard')
@section('content')
@if(session()->has('message'))
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
                            
                                <a href="{{ route('supplier.create') }}" class="btn btn-primary ">Add new supplier</a>
                            
                            </div>
                </div> 
        <div class="pt-2">
	      	   <table id="myTable" class="display" width="100%">
          
                    <thead class="thead-dark">
                        <tr>
                                <th style = "width:10%;">ID</th>
                                <th style = "width:20%;">Supplier Name </th>
                                <th style = "width:20%;">Action </th>

                          </tr>
                      </thead>
                      <tbody>
                      @if(count($suppliers))
                            @foreach($suppliers as $supplier)
                            <tr>
                                <td> {{ $supplier->id }}</td>
                                <td> {{ $supplier->company }} </td>
                                <td> 
                                <a href="{{ route('supplier.show',$supplier->id) }}" class="fas fa-eye"></a>
                                <a href="{{ route('supplier.edit',$supplier->id) }}" class="far fa-edit"></a>

                                <a href="javascript:void(0)" onclick="$(this).parent().find('form').submit()" class="far fa-trash-alt"></a>
                                <form action="{{ route('supplier.destroy',$supplier->id) }}" method="post">
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
$(document).ready( function () {
    $('#myTable').DataTable({
      "order": [[ 0, "desc" ]],
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
} );
        </script>

</section>

@endsection
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
                            
                                <a href="{{ route('payment.create') }}" class="btn btn-primary ">Add new Payment</a>
                            
                            </div>
                </div> 
        <div class="pt-2">
	      	   <table id="myTable" class="display" width="100%">
          
                    <thead class="thead-dark">
                        <tr>
                                <th style = "width:10%;">ID</th>
                                <th style = "width:20%;">Customer Name </th>
                                <th style = "width:20%;">User </th>
                                <th style = "width:20%;">Method </th>
                                <th style = "width:20%;">Amount </th>
                                <th style = "width:20%;">Allocated </th>
                                <th style = "width:20%;">Balance </th>
                                <th style = "width:20%;">Status </th>
                                <th style = "width:20%;">Action </th>

                          </tr>
                      </thead>
                      <tbody>
                      @if(count($payments))
                            @foreach($payments as $payment)
                            <tr>
                                <td> {{ $payment->id }}</td>
                                <td> {{ $payment->customer_name }}</td>
                                <td> {{ $payment->user_name }}</td>
                                <td> {{ $payment->method }} </td>
                                <td> {{ $payment->totalAmount }} </td>
                                <td> {{ $payment->allocatedToInvoice }} </td>
                                <td> {{ $payment->balanceToAllocate }} </td>
                                <td> {{ $payment->status }} </td>
                                <td> 
                                <a href="{{ route('payment.show',$payment->id) }}" class="fas fa-eye"></a>
                                <a href="{{ route('payment.print', $payment->id) }}"
                                    class="fas fa-print"></a>
                                <a href="{{ route('payment.edit',$payment->id) }}" class="far fa-edit"></a>

                                <a href="javascript:void(0)" onclick="$(this).parent().find('form').submit()" class="far fa-trash-alt"></a>
                                <form action="{{ route('payment.destroy',$payment->id) }}" method="post">
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
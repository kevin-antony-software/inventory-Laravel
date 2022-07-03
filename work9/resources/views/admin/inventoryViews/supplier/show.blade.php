@extends('dashboard')
@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="container">

    <a href="{{ route('supplier.index') }}" class="btn btn-primary float-right" style="margin-top: 5px;">Back to Index </a>

</div>
<style>
    th {
  width: 20%;
  
}
td{
    width: 50%;
}
</style>
<div class="container">
    <table class="table table-hover table-bordered" style="margin-top: 5px;">
            <thead class="thead-dark">
                <tr style="text-align: center;">
                    <th scope="col">item</th>
                    <th scope="col">value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">Company Name</th>
                    <td>{{ $supplier->company }}</td>

                </tr>
                <tr>
                    <th scope="row">Country</th>
                    <td>{{ $supplier->country }}</td>

                </tr>
                <tr>
                    <th scope="row">Address</th>
                    <td>{{ $supplier->address }}</td>

                </tr>
                <tr>
                    <th scope="row">Mobile</th>
                    <td>{{ $supplier->mobile }}</td>

                </tr>
                <tr>
                    <th scope="row">Email</th>
                    <td>{{ $supplier->email }}</td>

                </tr>
                <tr>
                    <th scope="row">Phone</th>
                    <td>{{ $supplier->phone }}</td>

                </tr>
            </tbody>
            </table>
 
</div>
@endsection
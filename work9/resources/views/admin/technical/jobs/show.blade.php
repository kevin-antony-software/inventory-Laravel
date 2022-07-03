@extends('dashboard')
@section('content')
    <div class="row p-2 ">
        <div class="col-6">
            <a href="{{ route('jobs.print', $job->id) }}" class="btn btn-block btn-primary">PRINT</a>
        </div>
        <div class="col-6">
            <a href="{{ route('jobs.printDetail', $job->id) }}" class="btn btn-block btn-primary">PRINT DETAILS</a>
        </div>
    </div>
    <div class="container">
        <table class="table">
            <tr>
                <td> Customer Name : </td>
                <td> {{ $job->customer_name }} </td>
            </tr>
            <tr>
                <td> Model : </td>
                <td> {{ $job->model }} </td>
            </tr>
            <tr>
                <td> Serial Number : </td>
                <td> {{ $job->serialNum }}</td>
            </tr>
            <tr>
                <td>Prompt In : </td>
                <td> {{ $job->promptIn }}</td>
            </tr>
            <tr>
                <td>Prompt Out : </td>
                <td> {{ $job->promptOut }}</td>
            </tr>
            <tr>
                <td>Final Total amount : </td>
                <td> {{ $job->finalTotal }}</td>
            </tr>
            <tr>
                <td>Issue of the Machine : </td>
                <td> {{ $job->issue }}</td>
            </tr>
            <tr>
                <td>Job Closed User : </td>
                <td> {{ $job->jobClosedUser_name }}</td>
            </tr>
            <tr>
                <td>Job Start time : </td>
                <td> {{ $job->jobStartTime }}</td>
            </tr>
            <tr>
                <td>Job Closed time : </td>
                <td> {{ $job->jobClosedTime }}</td>
            </tr>
            <tr>
                <td>Time Taken : </td>
                <td> {{ $job->duration }} mins</td>
            </tr>
            <tr>
                <td style="text-align: center"><strong>Components added </strong> </td>
                <td style="text-align: center"> <strong>Qty </strong> </td>
            </tr>
            @foreach ($componentsAdded as $c)
                <tr>
                    <td style="text-align: center"> {{ $c->component_name }} </td>
                    <td style="text-align: center"> {{ $c->qty }}pcs</td>
                </tr>
            @endforeach
            <tr>
                <td>Comments : </td>
                <td> {{ $job->comment }}</td>
            </tr>
            <tr>
                <td>Delivered Date : </td>
                <td> {{ $job->deliveredDate }}</td>
            </tr>
        </table>

        {{-- //this is local host --}}

        @isset($image->imagepath1)
            <div class="col-6">
                <img src="{{URL::asset('images/' . $image->imagepath1)}}" />
            </div>
        @endisset
        @isset($image->imagepath2)
            <div class="col-6">
                <img src="{{URL::asset('images/' . $image->imagepath2)}}" />
            </div>
        @endisset
        @isset($image->imagepath3)
            <div class="col-6">
                <img src="{{URL::asset('images/' . $image->imagepath3)}}" />
            </div>
        @endisset
        @isset($image->imagepath4)
            <div class="col-6">
                <img src="{{ asset('images/' . $image->imagepath4) }}" />
            </div>
        @endisset
        @isset($image->imagepath5)
            <div class="col-6">
                <img src="{{ asset('images/' . $image->imagepath5) }}" />
            </div>
        @endisset

        {{-- //this is for the server --}}
        {{-- <div class="col-md-auto">
            <img style="width:100%;" src="{{ asset('/public/images/'.$image->imagepath1) }}" alt="">
        </div>

        <div class="col-md-auto">
            <img style="width:100%;" src="{{ asset('/public/images/'.$image->imagepath2) }}" alt="">
        </div>

        <div class="col-md-auto">
            <img style="width:100%;" src="{{ asset('/public/images/'.$image->imagepath3) }}" alt="">
        </div> 

        <div class="col-md-auto">
            <img style="width:100%;" src="{{ asset('/public/images/'.$image->imagepath4) }}" alt="">
        </div>

        <div class="col-md-auto">
            <img style="width:100%;" src="{{ asset('/public/images/'.$image->imagepath5) }}" alt="">
        </div> --}}

    </div>
@endsection

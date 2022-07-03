<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Repair Invoice</title>
    {{-- <link rel="stylesheet" href="css/letterhead.css">
    <link rel="stylesheet" href="css/letterhead-details.css"> --}}
    <link rel="stylesheet" href="{{ public_path('css/letterhead.css') }}">
    <link rel="stylesheet" href="{{ public_path('css/letterhead-details.css') }}">
</head>

<body>
    <div class="grid-container">
        {{-- <div class="logo-img">
            <img src="kandk_logo.jpg" alt="logo">
        </div> --}}
        <div class="logo-img">
            <img src="{{ public_path('kandk_logo.jpg') }}" alt="logo">
        </div>

        <div class="company-name">
            <h1>K & K INTERNATIONAL LANKA PVT LTD</h1>
        </div>
    </div>
    <div class="address-details">
        <h1>(Sole Agent of RETOP Welding in Sri Lanka)</h1>
        <h5>No 9, 5th Lane, Borupana Road, Ratmalana</h5>
        <h5>Email: info@weld.lk Website: www.weld.lk Tel: +94112637473 </h5>
    </div>
    <div class="main-details">
        <div class="main">
            <table>
                <tr>
                    <th style="text-align: left;">Job ID</th>
                    <td>: {{ $job->id }}</td>
                </tr>
                <tr>
                    <th style="text-align: left;">Customer</th>
                    <td>: {{ $job->customer_name }}</td>
                </tr>
                <tr>
                    <th style="text-align: left;">Address</th>
                    <td>: {{ $customer->address }}</td>
                </tr>
            </table>
        </div>
        <div class="secondary">
            <table>
                <tr>
                    <th style="text-align: left;">Date</th>
                    <td>: {{ Carbon\Carbon::parse($job->created_at)->format('Y-m-d') }} </td>
                </tr>
                <tr>
                    <th style="text-align: left;">User</th>
                    <td>: {{ $job->jobClosedUser_name }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="headline">
        <h2>REPAIR INVOICE </h2>
    </div>
    <table class="table" width=100% id="table-repair">
        <thead>
            <tr>
                <th width=70%>Description</th>
                <th width=30%>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr style="height: 5px;">
                <td style="text-align: left;">
                    <p style="padding-left: 50px;">Repair Charger for the machine with <br>
                        Serial Number : {{ $job->serialNum }} <br>
                        Model : {{ $job->model }} </p>
                </td>
                <td style="text-align: center;">Rs. {{ $job->finalTotal }}/=</td>
            </tr>
        </tbody>
    </table>

    <div class="container">
        <h2 style="text-align: center;">Thank You For Your Business!</h2>
        <h5> Received name : ...........................................................
            Company stamp : ............................................................ <br><br>
            Signature : ....................................................................
            Date : ...........................</h5>
        <h6 style="margin-top: -5px; text-align: left;">Cheques to be drawn in favour of K & K International Lanka
            Pvt
            Ltd. Crossed A/C Payee Only. <strong>Number of Credit .......... Days</strong></h6>
        <h6 style="margin-top: -15px; text-align: left;">Account Name : K & K INTERNATIONAL LANKA PVT LTD</h6>
        <h6 style="margin-top: -15px; text-align: left;">Bank Account Numbers: </h6>
        <h6 style="margin-top: -15px; padding-left: 20px; text-align: left;">Seylan Bank - Mount Lavinia -
            003000653670001 </h6>
        <h6 style="margin-top: -20px; padding-left: 20px; text-align: left;">Nations Trust Bank - Mount Lavinia -
            100520004774 </h6>
        <h6 style="margin-top: -20px; padding-left: 20px; text-align: left;">Hatton National - Bank Ratmalana -
            208010004809 </h6>
    </div>

    <div class="prompt-address">
        <table id='prompt' width = 100%>
            <tr>
                <th width=50%> To </th>
                <th width=50%>From</th>
            </tr>
            <tr>
                <td>
                    {{ $customer->customer_name }} <br>
                    {{ $customer->address }} <br>
                    {{ $customer->mobile }} <br>
                    {{ $customer->phone }} <br>
                </td>
                <td>
                    K & K INTERNATIONAL LANKA PVT LTD <br>
                    No 9, 5th Lane, Borupana Road, Ratmalana <br>
                    0777696922<br>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>

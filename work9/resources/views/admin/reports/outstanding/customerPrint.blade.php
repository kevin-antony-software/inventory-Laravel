<html>

<head>
    <title>Document</title>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        #footer {
            position: static;
            left: 0;
            bottom: 155;
            width: 100%;
            color: black;
            text-align: center;
        }

        div.absolute {
            position: absolute;
            top: 180px;
            right: 0;
            width: 200px;
            height: 100px;
        }

        .clearfix {
            overflow: auto;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        img {
    width: 75px;
}

    </style>
</head>

<body>
    <div class="clearfix">

        <img src="kandk_logo.jpg" alt="logo"
            style="float:left; vertical-align:middle; padding-bottom: 20px; padding-top: 30px;">
        <h1
            style="text-align: center; width: 88%; font-size:180%; background: rgba(4, 184, 255, 0.301); padding-bottom: 20px; padding-top: 20px;float: right;">
            K & K INTERNATIONAL LANKA PVT LTD</h1>
    </div>


    <h3 style="font-style: italic; text-align: center; margin-top: -15px;">(Sole Agent of RETOP Welding in Sri Lanka)
    </h3>
    <h5 style="text-align: center; margin-top: -15px;">No 9, 5th Lane, Borupana Road, Ratmalana </h5>
    <h5 style="text-align: center; margin-top: -16px;">Email: info@weld.lk Website: www.weld.lk Tel: +94112637473 </h5>

    <h1 style="text-align: center; text-decoration: underline;"> Outstanding Summary </h1>

    <h3> Dear {{ $customer_name }}</h3>
    <p> You have current outstanding payment of Rs <strong> {{ $total }}/= </strong> With k and k
        International lanka pvt ltd. <br>
        Below is the breakdown of the outstanding report. </p>

    <h4> Please note credit period is 90 days </h4>

    Please contact us if there is any discrepancy in the Outstanding details. <br>
    <table>
        <tr>
            <th>Type</th>
            <th>ID</th>
            <th>Customer Name</th>
            <th>Due Amount</th>
            <th>Invoice Date</th>
            <th>Days </th>
        </tr>
        @foreach ($outstanding as $S)
        <tr>
            <td> {{ $S['type'] }} </td>
            <td> {{ $S['id'] }} </td>
            <td> {{ $S['customer_name'] }}</td>
            <td> {{ $S['dueAmount'] }}</td>
            <td> {{ $S['Date'] }} </td>
            <td> {{ $S['days'] }}</td>
        </tr>
        @endforeach

    </table>
    <br>

    <div id="footer">

        <h2 style="text-align: center; line-height: 70%;">Thank You For Your Business!</h2>
        <h4 style="margin-top: -1px; text-align: left; line-height: 50%;">Account Name : K & K INTERNATIONAL LANKA PVT
            LTD</h4>
        <h4 style="margin-top: -0px; text-align: left;">Bank Account Numbers: </h4>
        <h4 style="margin-top: -0px; padding-left: 20px; text-align: left; line-height: 50%;">Seylan Bank Mount Lavinia
            003000653670001 </h4>
        <h4 style="margin-top: -0px; padding-left: 20px; text-align: left; line-height: 50%;">Nations Trust Bank Mount
            Lavinia 100520004774 </h4>
        <h4 style="margin-top: -0px; padding-left: 20px; text-align: left; line-height: 50%;">Hatton National Bank
            Ratmalana 208010004809 </h4>
    </div>
</body>

</html>

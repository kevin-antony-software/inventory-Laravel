<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
 #customers {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid rgb(0, 0, 0);

}

/* #customers tr:nth-child(even){background-color: #f2f2f2;} */

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: center;
  background-color: rgb(114, 114, 114);
  color: rgb(0, 0, 0);
}
#footer {
       position: fixed;
       left: 0;
       bottom: 100;
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
    
       <img src="kandk_logo.jpg" alt="logo" style="float:left; vertical-align:middle; padding-bottom: 20px; padding-top: 30px;">
<h1 style="text-align: center; width: 88%; font-size:180%; background: rgba(4, 184, 255, 0.301); padding-bottom: 20px; padding-top: 20px;float: right;">K & K INTERNATIONAL LANKA PVT LTD</h1>
    </div>

<h3 style="font-style: italic; text-align: center; margin-top: -15px;">(Sole Agent of RETOP Welding in Sri Lanka)</h3>
<h5 style="text-align: center; margin-top: -15px;" >No 9, 5th Lane, Borupana Road, Ratmalana </h5>
<h5 style="text-align: center; margin-top: -16px;">Email: info@weld.lk  Website: www.weld.lk  Tel: +94112637473 </h5>

<div class="relative">
    <table id="headTable" >
        <tr>
            <th style="text-align: left;">Payment ID:</th>
            <td>{{ $payment->id }}</td>
        </tr>
        <tr>
            <th style="text-align: left;">Customer:</th>
            <td>{{ $payment->customer_name }}</td>
        </tr>
        <tr>
            <th style="text-align: left;">City:</th>
            <td>{{ $city }}</td>
        </tr>

        
    </table>
</div>

        <div class="absolute">
            <table>
            <tr>
                <th style="text-align: left;">Date:</th>
                <td>{{ Carbon\Carbon::parse($payment->created_at)->format('Y-m-d') }}
                    
                </td>
            </tr>
            <tr>
                <th style="text-align: left;">User:</th>
                <td>{{ $payment->user_name }}</td>
            </tr>

            
        </table>
        </div>
 
    <h2 style="text-align: center; text-decoration: underline" > Payment Receipt </h2>
    
This is to confirm recipt payment of Rs {{ $payment->totalAmount }} to K & K International Lanka Pvt Ltd
from Customer {{ $payment->customer_name }} with method {{ $payment->method }}




        <div id="footer">
            
            <h2 style="text-align: center; line-height: 50%;">Thank You For Your Business!</h2>
    </div>

</body>
</html>
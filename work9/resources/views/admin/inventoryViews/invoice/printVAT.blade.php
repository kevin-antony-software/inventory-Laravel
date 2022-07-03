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
  width: 250px;
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
    
       <img src="{{ public_path('kandk_logo.jpg') }}" alt="logo" style="float:left; vertical-align:middle; padding-bottom: 20px; padding-top: 30px;">
<h1 style="text-align: center; width: 88%; font-size:180%; background: rgba(4, 184, 255, 0.301); padding-bottom: 20px; padding-top: 20px;float: right;">K & K INTERNATIONAL LANKA PVT LTD</h1>
    </div>

<h3 style="font-style: italic; text-align: center; margin-top: -15px;">(Sole Agent of RETOP Welding in Sri Lanka)</h3>
<h5 style="text-align: center; margin-top: -15px;" >No 9, 5th Lane, Borupana Road, Ratmalana </h5>
<h5 style="text-align: center; margin-top: -16px;">Email: info@weld.lk  Website: www.weld.lk  Tel: +94112637473 </h5>

<div class="relative">
    <table id="headTable" >
        <tr>
            <th style="text-align: left;">Invoice ID:</th>
            <td>{{ $invoice->id }}</td>
        </tr>
        <tr>
            <th style="text-align: left;">Customer:</th>
            <td>{{ $invoice->customer_name }}</td>
        </tr>
        <tr>
            <th style="text-align: left;">City:</th>
            <td>{{ $city }}</td>
        </tr>
        <tr>
            <th style="text-align: left;">Customer VAT:</th>
            <td>{{ $customerVATID }}</td>
        </tr>

        
    </table>
</div>

        <div class="absolute">
            <table>
            <tr>
                <th style="text-align: left;">Date:</th>
                <td>{{ Carbon\Carbon::parse($invoice->created_at)->format('Y-m-d') }}
                                    </td>
            </tr>
            <tr>
                <th style="text-align: left;">User:</th>
                <td>{{ $invoice->user_name }}</td>
            </tr>
            <tr>
                <th style="text-align: left;">VAT Number:</th>
                <td>114258300-7000</td>
            </tr>

            
        </table>
        </div>
 
    <h2 style="text-align: center; text-decoration: underline" > TAX INVOICE </h2>
    

    <table class="table" width=100% id="customers">
        <thead>
            <tr style="font-size:70%;">
                <th width=7%>Code</th>
                <th width=39%>Product Name</th>
                <th width=10%>Price Rs.</th>
                <th width=7%>Qty</th>
                <th width=11%>discount %</th>
                <th width=13%>Price A/D Rs.</th>
                <th width=13%>Sub Total Rs.</th>
            </tr>
            </thead>
            <tbody>
                @foreach($invoiceDetails as $row)
                <tr style="font-size:70%; height: 5px;" >
                        <td style="text-align: center; ">{{ $row->product_id }}</td>
                        <td style="text-align: center; ">{{ $row->product_name }}</td>
                        <td style="text-align: center; ">{{ $row->price }}</td>
                        <td style="text-align: center;">{{ $row->qty }}</td>
                        <td style="text-align: center;">{{ $row->discountPercentage }}</td>
                        <td style="text-align: center;">{{ $row->priceAfterDiscount }}</td>
                        <td style="text-align: center;">{{ $row->subtotal_price }}</td>
                        
                </tr>
                @endforeach
            <tr>
                    <td style="border: none;">&nbsp;</td>
                    <td style="border: none;">&nbsp;</td>
                    <td style="border: none;">&nbsp;</td>
                    <td style="border: none;">&nbsp;</td>
                    <td style="border: none;">&nbsp;</td>
                    <td style="text-align: center;"><b>Total</b></td>
                    <td style="text-align: center;"><b>{{ $invoice->total }}</b></td>
            </tr>

                                       
        </tbody>
    </table>

    <div id="vatSection">
        <table>
            <tr>
                <td>Value of Supply</td>
                <td> - Rs. </td>
                <td style="text-align : right;">{{ $invoice->total - $invoice->vatAmount}}</td>
            </tr>
            <tr>
                <td>VAT 12%</td>
                <td> - Rs. </td>
                <td style="text-align : right;">{{ $invoice->vatAmount }}</td>
            </tr>
            <tr>
                <td>Grand Total</td>
                <td> - Rs. </td>
                <td style = "border-bottom-style:double; border-top-style:solid; text-align : right;">{{ $invoice->total }}</td>
            </tr>
        </table>
    </div>

        <div id="footer">
            
            <h2 style="text-align: center; line-height: 50%;">Thank You For Your Business!</h2>
            <h5 style="line-height: 50%; text-align: left;"> Received Person Name : ................................ Customer Stamp : ..............................Signature : ...................... Date : ................ </h5>

            <h6 style="margin-top: -5px; text-align: left; line-height: 50%;">Cheques to be drawn in favour of K & K International Lanka Pvt Ltd. Crossed A/C Payee Only. <strong>Number of Credit ............. Days</strong></h6> 
        
            <h6 style="margin-top: -15px; text-align: left; line-height: 50%;">Account Name : K & K INTERNATIONAL LANKA PVT LTD</h6>
            <h6 style="margin-top: -15px; text-align: left;">Bank Account Numbers:  </h6>
            <h6 style="margin-top: -15px; padding-left: 20px; text-align: left; line-height: 50%;">Seylan Bank Mount Lavinia 003000653670001 </h6>
            <h6 style="margin-top: -20px; padding-left: 20px; text-align: left; line-height: 50%;">Nations Trust Bank Mount Lavinia 100520004774 </h6>
            <h6 style="margin-top: -20px; padding-left: 20px; text-align: left; line-height: 50%;">Hatton National Bank Ratmalana 208010004809 </h6>
        </div>

</body>
</html>
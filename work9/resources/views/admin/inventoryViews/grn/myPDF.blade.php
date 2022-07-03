<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        table {
            font-family: arial, sans-serif;
            width: 100%;
            border-collapse: collapse;
        }
        td,
        th {
            border: 1px solid black;
            text-align: left;
            padding: 8px;
        }
        td{
            font-size: 14px;
        }
    </style>
</head>
<body>
    <p>GRN ID - {{ $grn->id }} </p>
    <p>GRN Date - {{ Carbon\Carbon::parse($grn->created_at)->format('Y-m-d') }}</p>
    <p>From WAREHOUSE - {{ $grn->FromWarehouse_name }} </p>
    <p>To WAREHOUSE - {{ $grn->ToWarehouse_name }} </p>

    <table>
        <tr>
            <th style="text-align: center;">Item</th>
            <th style="text-align: center;">Qty</th>
        </tr>
        @foreach ($GRNDetails as $p)
            <tr>
                <td style="text-align: center;">{{ $p->product_name }}</td>
                <td style="text-align: center;">{{ $p->qty }}</td>
            </tr>
        @endforeach


    </table>

</body>

</html>

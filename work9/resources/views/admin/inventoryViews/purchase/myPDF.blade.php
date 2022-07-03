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
        }

        td,
        th {
            border: 1px solid black;
            text-align: left;
            padding: 8px;
        }

    </style>
</head>

<body>

    <p>Purchase Order - {{ $purchaseDetails[0]->purchase_id }} </p>
    <p>Purchase Date - {{ Carbon\Carbon::parse($purchaseDetails[0]->created_at)->format('Y-m-d') }}</p>

    <table>
        <tr>
            <th style="text-align: center;">Item</th>
            <th style="text-align: center;">Qty</th>
        </tr>
        @foreach ($purchaseDetails as $p)
            <tr>
                <td style="text-align: center;">{{ $p->product_name }}</td>
                <td style="text-align: center;">{{ $p->qty }}</td>
            </tr>
        @endforeach


    </table>

</body>

</html>

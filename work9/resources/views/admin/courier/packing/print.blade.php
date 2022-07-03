<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Packing List</title>
    <style>
        table,
        tr,
        td {
            border: 1px black solid;
            border-collapse: true;
        }

    </style>
</head>

<body>
    <table>
        <tbody>
            @foreach ($customerList as $item)
                <tr>
                    <td style="width: 350px;">To : <br>
                        {{ $item->courier_customer_name }} <br>
                        {{ $item->address }} <br>
                        {{ $item->mobile }} <br>
                        {{ $item->phone }}
                    </td>
                    <td style="width: 350px;"> From : <br>
                        K & K International Lanka Pvt Ltd <br>
                        No 9, 5th Lane, Borupana Road, <br>
                        Ratmalana <br>
                        0777696922
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

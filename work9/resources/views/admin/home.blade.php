@extends('dashboard')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"
        integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

    @can('managers-only')
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ number_format($invoices, 2) }}</h3>
                            <p>Total Sales</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-bar-chart"></i>
                        </div>
                        <a href="{{ route('invoice.index') }}" class="small-box-footer">Invoices <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($unpaid, 2) }}</h3>
                            <p>Total Outstanding</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <a href="{{ route('invoice.index') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($payments, 2) }}</h3>
                            <p>Total Payments</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <a href="{{ route('customer.index') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $products }}</h3>
                            <p>Number of Products</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <a href="{{ route('product.index') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- ./col -->
        </div>
    @endcan
    @can('director-only')
        <div class="container">
            <div class="row justify-content-center">
                <table style="width:50%" class="table table-striped table-hover table-bordered table-sm">
                    <tr>
                        <th>Item</th>
                        <th>Amount</th>
                    </tr>
                    <tr>
                        <td>Cheques in Hand</td>
                        <td> {{ number_format($cheques, 2) }} </td>
                    </tr>
                    <tr>
                        <td>Return Cheques in Hand</td>
                        <td> {{ number_format($ReturnCheques, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Cash in Hand</td>
                        <td> {{ number_format($cashBalance->balance, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Bank available Total</td>
                        <td> {{ number_format($bankAvailableTotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Inventory Total After 40%</td>
                        <td>{{ number_format($inventory, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Collections Total Invoice</td>
                        <td>{{ number_format($InvoiceTotalDue, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Collections Total Repair</td>
                        <td>{{ number_format($JobTotalDue, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Working Assets</td>
                        <td>{{ number_format($TotalAssets, 2) }}</td>
                    </tr>
                    <tr>
                        <td>This Month Net Cash Flow</td>
                        <td>{{ number_format($netCashFlow, 2) }}</td>
                    </tr>
                    <tr>
                        <td>This Month Expenses</td>
                        <td>{{ number_format($expenses, 2) }}</td>
                    </tr>
                    <tr>
                        <td>This Month Cost of Goods Sold</td>
                        <td>{{ number_format($COGS, 2) }}</td>
                    </tr>
                </table>

            </div>
        </div>
    @endcan
    @can('managers-only')
        <div class="container-md pt-5">
            <canvas id="myChart2" width="200"></canvas>
        </div>

        <div class="container-md pt-5">

            <canvas id="myChart3" width="200"></canvas>

        </div>
    @endcan


    <script>
        var sites = {!! json_encode($x) !!};
        var sitesY = {!! json_encode($y) !!};
        var sitesdue = {!! json_encode($due) !!};
        var x = [];
        var y = [];
        var z = [];
        for (var pk = 0; pk < sites.length; pk++) {
            x.push(sites[pk]);
            y.push(sitesY[pk]);
            z.push(sitesdue[pk]);
        }

        var ctx = document.getElementById('myChart2').getContext('2d');
        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'bar',

            // The data for our dataset
            data: {
                labels: x,
                datasets: [{
                        label: 'Sales',
                        backgroundColor: 'rgb(230, 184, 0)',
                        borderColor: 'rgb(230, 184, 0)',
                        data: y,
                    },
                    {
                        label: 'due Amount',
                        backgroundColor: 'rgb(110, 90, 40)',
                        borderColor: 'rgb(110, 90, 40)',
                        data: z,
                    }
                ]
            },

            // Configuration options go here
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function(value, index, values) {
                                if (parseInt(value) >= 1000) {
                                    return 'Rs ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g,
                                        ",");
                                } else {
                                    return 'Rs ' + value;
                                }
                            }
                        }
                    }]
                }
            }
        });
    </script>


    <script>
        var sites = {!! json_encode($xp) !!};
        var sitesY = {!! json_encode($yp) !!};

        var x = [];
        var y = [];
        var z = [];
        for (var pk = 0; pk < sites.length; pk++) {
            x.push(sites[pk]);
            y.push(sitesY[pk]);

        }

        var ctx = document.getElementById('myChart3').getContext('2d');
        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'line',

            // The data for our dataset
            data: {
                labels: x,
                datasets: [{
                    label: 'payments',
                    backgroundColor: null,
                    borderColor: 'rgb(55, 84, 150)',
                    data: y,
                }]
            },

            // Configuration options go here
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function(value, index, values) {
                                if (parseInt(value) >= 1000) {
                                    return 'Rs ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g,
                                        ",");
                                } else {
                                    return 'Rs ' + value;
                                }
                            }
                        }
                    }]
                }
            }
        });
    </script>
    <div class="container">
        <div class="row justify-content-center">
            <table style="width:50%" class="table table-striped table-hover table-bordered table-sm">
                <tr>
                    <th>User Name</th>
                    <th>Avg Repair Time</th>
                </tr>

                @foreach ($Performance as $x => $x_value)
                    <tr>
                        <td>{{ $x }}</td>
                        <td> {{ $x_value }} </td>
                    </tr>
                @endforeach
            </table>

        </div>
    </div>
@endsection

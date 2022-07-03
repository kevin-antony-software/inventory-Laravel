<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>K & K System</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <p class="nav-link"> <strong>{{ auth()->user()->name }}</strong></p>

                </li>

            </ul>


        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item menu-open">

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-circle"></i>
                                <p>
                                    Inventory
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('managers-only')
                                    <li class="nav-item">
                                        <a href="{{ route('warehouse.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Warehouse</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('category.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Category</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('product.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Product</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('inventory.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Inventory</p>
                                        </a>
                                    </li>
                                @endcan
                                @can('store-keeper-only')
                                    <li class="nav-item">
                                        <a href="{{ route('purchase.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Purchase</p>
                                        </a>
                                    </li>
                                @endcan
                                @can('store-keeper-only')
                                    <li class="nav-item">
                                        <a href="{{ route('grn.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>GRN</p>
                                        </a>
                                    </li>
                                @endcan


                                @can('managers-only')
                                    <li class="nav-item">
                                        <a href="{{ route('invoice.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Invoice</p>
                                        </a>
                                    </li>
                                @endcan
                                @can('director-only')
                                    <li class="nav-item">
                                        <a href="{{ route('returnItems.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Return Items</p>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-circle"></i>
                                <p>
                                    Financial
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('director-only')
                                    <li class="nav-item">
                                        <a href="{{ route('bank.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Bank</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('cash.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Cash</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('cheque.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Cheques</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('writeoff.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Writeoff</p>
                                        </a>
                                    </li>
                                @endcan
                                @can('managers-only')
                                    <li class="nav-item">
                                        <a href="{{ route('payment.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon text-success"></i>
                                            <p class="text-success">Payment</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('expense.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Expenses</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('commission.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Commission</p>
                                        </a>
                                    </li>
                                @endcan

                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-circle"></i>
                                <p>
                                    Technical
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('managers-only')
                                    <li class="nav-item">
                                        <a href="{{ route('ComponentCategory.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Component Category</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('component.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Component</p>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ route('machineModel.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Machine Model</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('componentPurchase.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Component Purchase</p>
                                        </a>
                                    </li>
                                @endcan
                                <li class="nav-item">
                                    <a href="{{ route('stocks.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Component Stock</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('jobs.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-success"></i>
                                        <p class="text-success">Repair Jobs</p>
                                    </a>
                                </li>
                                @can('tech-executive-only')
                                    <li class="nav-item">
                                        <a href="{{ route('issues.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Repair Issues</p>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-circle"></i>
                                <p>Reports<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('managers-only')
                                    <li class="nav-item">
                                        <a href="{{ route('InvoiceProductCustomer.index') }}" class="nav-link">
                                            <p>Invoice Product-customer</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('InventoryReport.index') }}" class="nav-link">
                                            <p>Inventory Report</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('Outstanding.index') }}" class="nav-link">
                                            <p>Outstanding Report</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('chequeReport.index') }}" class="nav-link">
                                            <p>Cheque Report</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('chequeCustomerReport.index') }}" class="nav-link">
                                            <p>Cheque-Customer Report</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('invoice.invoiceSummary') }}" class="nav-link">
                                            <p>Invoice summary</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('jobs.jobSummary') }}" class="nav-link">
                                            <p>All Repair Jobs</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('invoiceDetails.index') }}" class="nav-link">
                                            <p>Invoice Details Report</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('InvoicePayment.index') }}" class="nav-link">
                                            <p>Invoice Payment Report</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('RepairModels.index') }}" class="nav-link">
                                            <p>Repair Models Report</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('AllRepairJobs.index') }}" class="nav-link">
                                            <p>All Repair Jobs</p>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-circle"></i>
                                <p>Courier<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('CourierCustomer.index') }}" class="nav-link">
                                        <p>Courier-customer</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('CourierPickup.index') }}" class="nav-link">
                                        <p>Courier Pickup</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('CourierPacking.create') }}" class="nav-link">
                                        <p>Courier Packing</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @can('managers-only')
                            <li class="nav-item">
                                <a href="{{ route('customer.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-circle"></i>
                                    <p>Customer</p>
                                </a>
                            </li>
                        @endcan

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-circle"></i>
                                <p>
                                    Admin
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('director-only')
                                    <li class="nav-item">
                                        <a href="{{ route('user.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>User</p>
                                        </a>
                                    </li>
                                @endcan
                                @can('managers-only')
                                    <li class="nav-item">
                                        <a href="{{ route('home.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Home</p>
                                        </a>
                                    </li>
                                @endcan


                            </ul>
                        </li>


                        </li>
                        @can('managers-only')
                        <li class="nav-item">
                            <a href="{{ route('invoice.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon text-success"></i>
                                <p class="text-success">Create Invoice</p>
                            </a>
                        </li>
                        @endcan
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Logout<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                    document.getElementById('logout-form').submit();">
                                        <i class="nav-icon far fa-circle text-danger"></i>
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
        <div class="content-wrapper">
            @yield('content')
        </div>

    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->

    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>

    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>

</body>

</html>

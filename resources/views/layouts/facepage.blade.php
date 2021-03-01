<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sisy - Facepage</title>
    <link rel="stylesheet" href="{{ asset('css/admin-lte.css') }}">
</head>
<body class="layout-top-nav" style="height: auto;">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container">
                <a href="javascript:void(0)" class="navbar-brand">
                    <img src="{{ asset('images/logo/logo.png') }}" alt="AdminLTE Logo" class="brand-image img-fluid " style="opacity: 0.8;" />
                    {{-- <span class="brand-text font-weight-light">AdminLTE 3</span> --}}
                </a>

                <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="index3.html" class="nav-link @yield('active-home')">
                                Home
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a href="#" class="nav-link">Contact</a>
                        </li> --}}
                        {{-- <li class="nav-item dropdown">
                            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Dropdown</a>
                            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                                <li><a href="#" class="dropdown-item">Some action </a></li>
                                <li><a href="#" class="dropdown-item">Some other action</a></li>

                                <li class="dropdown-divider"></li>

                                <!-- Level two dropdown-->
                                <li class="dropdown-submenu dropdown-hover">
                                    <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Hover for action</a>
                                    <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                                        <li>
                                            <a tabindex="-1" href="#" class="dropdown-item">level 2</a>
                                        </li>

                                        <!-- Level three dropdown-->
                                        <li class="dropdown-submenu">
                                            <a id="dropdownSubMenu3" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">level 2</a>
                                            <ul aria-labelledby="dropdownSubMenu3" class="dropdown-menu border-0 shadow">
                                                <li><a href="#" class="dropdown-item">3rd level</a></li>
                                                <li><a href="#" class="dropdown-item">3rd level</a></li>
                                            </ul>
                                        </li>
                                        <!-- End Level three -->

                                        <li><a href="#" class="dropdown-item">level 2</a></li>
                                        <li><a href="#" class="dropdown-item">level 2</a></li>
                                    </ul>
                                </li>
                                <!-- End Level two -->
                            </ul>
                        </li> --}}
                    </ul>
                </div>

                <!-- Right navbar links -->
                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">

                    <!-- User Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            <i class="fas fa-user"></i>&nbsp;&nbsp;Hello, {{ Auth::user()->employee->fullname}}
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <span class="dropdown-header">User Menu</span>
                            <div class="dropdown-divider"></div>
                            {{-- <a href="#" class="dropdown-item">
                                <i class="fas fa-envelope mr-2"></i> 4 new messages
                                <span class="float-right text-muted text-sm">3 mins</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-users mr-2"></i> 8 friend requests
                                <span class="float-right text-muted text-sm">12 hours</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-file mr-2"></i> 3 new reports
                                <span class="float-right text-muted text-sm">2 days</span>
                            </a> --}}
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer">
                                <i class="fas fa-door-open"></i> <b>Logout</b>
                            </a>
                        </div>
                    </li>

                </ul>
            </div>
        </nav>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="min-height: 584px;">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2 mt-3">
                        <div class="col-sm-12">
                            <h1 class="m-0 text-center">
                                @yield('title-content')
                            </h1>
                        </div>

                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                @yield('content')
                <!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->


        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                Version 4.0.1 | Developed By <a href="https://www.instagram.com/nest_nm">Nesta Maulana</a>
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2019 <a href="https://www.nutrifood.co.id">PT. Nutrifood Indonesia</a>.</strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    @if ($message = Session::get('success'))
        <div class="success" data-flashdata="{{ $message }}"></div>
    @endif

    @if ($message = Session::get('error'))
        <div class="error" data-flashdata="{{ $message }}"></div>
    @endif

    @if ($message = Session::get('infonya'))
        <div class="infonya" data-flashdata="{{ $message }}"></div>
    @endif
    <script src="{{ mix('js/admin-lte.js') }}"></script>
    <script>
        $(document).ready(function ()
        {
            const error_mess = $('.error').data('flashdata');
            if (error_mess)
            {
                Swal.fire({
                    title: 'Proses Gagal!',
                    text: error_mess,
                    icon: 'error'
                })
            }
            const success_mess = $('.success_mess').data('flashdata');
            if (success_mess) {
                Swal.fire({
                    title: 'Proses Gagal!',
                    text: success,
                    icon: 'success'
                })
            }

            const info_mess = $('.info').data('flashdata');
            if(info_mess){
                Swal.fire({
                    title: 'Proses Gagal!',
                    text: info_mess,
                    icon: 'info'
                })
            }
        });

    </script>
    <style>
        .tb_button {
            padding: 1px;
            cursor: pointer;
            border-right: 1px solid #8b8b8b;
            border-left: 1px solid #fff;
            border-bottom: 1px solid #fff;
        }
        .tb_button.hover {
            borer: 2px outset #def;
            background-color: #f8f8f8 !important;
        }
        .ws_toolbar {
            z-index: 100000;
        }
        .ws_toolbar .ws_tb_btn {
            cursor: pointer;
            border: 1px solid #555;
            padding: 3px;
        }
        .tb_highlight {
            background-color: yellow;
        }
        .tb_hide {
            visibility: hidden;
        }
        .ws_toolbar img {
            padding: 2px;
            margin: 0px;
        }
    </style>
</body>

</html>

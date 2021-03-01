<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Sisy - {{ Session::get('application') }}</title>
        <link rel="stylesheet" href="{{ asset('css/admin-lte.css') }}" media="all" >
		<meta name="csrf-token" content="{{ csrf_token() }}" />
        <style>
            .margin-for-button
            {
                margin: 5px;
                font-size: 1.0625em;
            }
            .bg-1
            {
                background-color: #a6e6ff;
            }

            .bg-2
            {
                background-color: #00b6db;
            }

            .bg-3
            {
                background-color: #e8e805;
            }
            .bg-3-1
            {
                background-color: #bbe805;
            }
            .bg-4
            {
                background-color: #a1ef19;
            }

            .bg-5
            {
                background-color: #19ef5d;
            }

            .bg-6
            {
                background-color: #ef1919;
            }
        </style>
    </head>
    <body class="sidebar-mini sidebar-collapse" style="height: auto;">
        <div id="loading-bar-overlay"  class="loading-bar hidden">
			<div id="text">
				<img src="{{ asset('images/icon/loading.gif') }}"  width="300px" >
			</div>
		</div>
        <div class="wrapper">
            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="index3.html" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="#" class="nav-link">Contact</a>
                    </li>
                </ul>


                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
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
            </nav>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="javascript:void(0)" class="brand-link">
                    <img src="{{ asset('images/logo/logo-kecil.png') }}" alt="sisy-logo" class="brand-image img-responsive" style="opacity: 0.8;max-height: 44px;
                    margin-left: 0px;" />
                    <span class="brand-text font-weight-light"> {{ Session::get('application') }} </span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar user panel (optional) -->
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="{{ asset('images/icon/user.png') }}" class="img-circle elevation-2" alt="User Image" />
                        </div>
                        <div class="info">
                            <a href="javascript:void(0)" class="d-block">
                                {{ Auth::user()->employee->fullname }}
                            </a>
                        </div>
                    </div>

                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <?php
                                $menu =  App\Helpers\GenerateMenu::generate(Session::get('application'));
                                print $menu;
                            ?>
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper" style="min-height: 654px;">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h4 class="m-0">{{Session::get('menu_name')}}</h4>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('credential_access.home-page') }}">Sisy</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ Session::get('application') }}</li>
                                </ol>
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
                    <div class="container-fluid">
                        @yield('content')
                    </div>
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
            <div id="sidebar-overlay"></div>
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
        <!-- Modal -->
        <div class="modal modal-dark modal-outline fade" id="modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog" id="modal-size">
                <div class="modal-content">
                    <div class="modal-header bg-dark">
                        <h5 class="modal-title" id="modal-title">Modal title</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" onclick="resetModalSize()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                </div>
            </div>
        </div>
        @yield('pop-up-plugin')
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
            .hidden
            {
                display: none;
            }
            #loading-bar-overlay {
                position: fixed;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0,0,0,0.5);
                z-index: 9999;
                cursor: pointer;
            }

            #text{
                position: absolute;
                top: 50%;
                left: 50%;
                font-size: 50px;
                color: white;
                transform: translate(-50%,-50%);
                -ms-transform: translate(-50%,-50%);
            }
        </style>

        @yield('custom-plugin')


        <script>
            function resetModalSize()
            {
                $('#modal-size').removeAttr('class');
                $('#modal-size').addClass('modal-dialog');
                $('.modal-body').empty();
            }
            $(document).ready(function() {
                $('.select2').select2({
                        'theme':'bootstrap4'
                });
            });
        </script>
    </body>

</html>

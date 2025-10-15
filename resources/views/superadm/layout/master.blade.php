<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ asset('asset/theamoriginalalf/images/logo_bg1.ico') }}">
    <title>Alf Engineering</title>
    <link href="{{ asset('asset/css/style.css') }}" rel="stylesheet">
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    


    <link href="{{ asset('asset/css/colors/blue.css') }}" id="theme" rel="stylesheet">
    <link href="{{ asset('asset/css/satish.css') }}" id="theme" rel="stylesheet">
    <script src="{{ asset('asset/plugins/jquery/jquery-3.6.0.min.js') }}"></script>
</head>

<body class="fix-header fix-sidebar card-no-border">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                stroke-miterlimit="10" />
        </svg>
    </div>
    <div id="main-wrapper">
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <div class="navbar-header">
                    <a class="navbar-brand" href="">

                        
                            <img src="{{ asset('asset/images/background/alf.png') }}" class="light-logo" alt="homepage"
                                style="    height: 50px;" />
                    </a>
                </div>
                <div class="navbar-collapse">
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <li class="nav-item"> <a
                                class="nav-link nav-toggler d-block d-md-none text-muted waves-effect waves-dark"
                                href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item"> <a
                                class="nav-link sidebartoggler d-none d-md-block text-muted waves-effect waves-dark"
                                href="javascript:void(0)"><i class="ti-menu"></i></a> </li>


                    </ul>
                    <ul class="navbar-nav my-lg-0 d-flex justify-content-center align-items-center">
                        <li class="fnt-size">{{ session('email_id') }}</li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="#"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img
                                    src="{{ asset('asset/images/users/1.jpg') }}" alt="user"
                                    class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right scale-up">
                                <ul class="dropdown-user">                                    
                                    {{-- <li><a href="{{ route('change-password') }}"><i class="fa fa-key"></i> Change Password</a></li> --}}
                                    <li>
                                        @if(session('role') == 'admin')
                                            <a href="{{ route('admin.change-password') }}"><i class="fa fa-key"></i> Change Password</a>
                                        @else
                                            <a href="{{ route('employee.change-password') }}"><i class="fa fa-key"></i> Change Password</a>
                                        @endif
                                    </li>
                              {{-- <li><a href="{{ route('logout') }}"><i class="fa fa-power-off"></i> Logout</a></li> --}}
                                    <li>
                                        @if(session('role') == 'admin')
                                            <a href="{{ route('admin.logout') }}"><i class="fa fa-power-off"></i> Logout</a>
                                        @else
                                            <a href="{{ route('emp.logout') }}"><i class="fa fa-power-off"></i> Logout</a>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="#"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i></i></a>
                            <div class="dropdown-menu dropdown-menu-right scale-up"> <a class="dropdown-item"
                                    href="#"><i class="flag-icon flag-icon-in"></i> India</a> <a
                                    class="dropdown-item" href="#"><i class="flag-icon flag-icon-fr"></i>
                                    French</a> <a class="dropdown-item" href="#"><i
                                        class="flag-icon flag-icon-cn"></i> China</a> <a class="dropdown-item"
                                    href="#"><i class="flag-icon flag-icon-de"></i> Dutch</a> </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <aside class="left-sidebar">
            <div class="scroll-sidebar">
                <div class="user-profile" style="margin-top: 28px;"></div>
                @if (session('role') == 'admin')
                    @include('superadm.layout.super-menu')
                @else
                    @include('superadm.layout.emp-menu')
                @endif


              



            </div>

        </aside>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row page-titles">
                    <div class="col-md-5 col-12 align-self-right ">
                        <?php
                        // <ol class="breadcrumb">
                        //   <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        //   <li class="breadcrumb-item active">Icon</li>
                        // </ol>
                        ?>
                    </div>

                </div>

                @yield('content')
                @include('toast')
                @include('superadm.layout.footer')

<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from www.wrappixel.com/demos/admin-templates/material-pro/material/icon-flag.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:21:11 GMT -->

<head>


    <!-- CSRF Token -->


    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('asset/images/favicon.png') }}">
    <title>Alf Engineering Template - Bootstrap 4 Admin Template</title>
    <!-- <link rel="canonical" href="https://www.wrappixel.com/templates/materialpro/" /> -->
    <!-- Custom CSS -->
    <!-- Custom CSS -->
    <link href="{{ asset('asset/css/style.css') }}" rel="stylesheet">

    <!-- <link href="{{ asset('asset/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet"> -->


    <!-- You can change the theme colors from here -->
    <link href="{{ asset('asset/css/colors/blue.css') }}" id="theme" rel="stylesheet">
    <link href="{{ asset('asset/css/satish.css') }}" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    <script src="{{ asset('asset/plugins/jquery/jquery-3.6.0.min.js') }}"></script>
</head>

<body class="fix-header fix-sidebar card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                stroke-miterlimit="10" />
        </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="">
                        
                        <!-- Logo text --><span>
                            <img src="{{ asset('asset/images/background/alf.jpg') }}" class="light-logo"
                                alt="homepage" /></span>
                    </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- This is  -->
                        <li class="nav-item"> <a
                                class="nav-link nav-toggler d-block d-md-none text-muted waves-effect waves-dark"
                                href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item"> <a
                                class="nav-link sidebartoggler d-none d-md-block text-muted waves-effect waves-dark"
                                href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                       
                        
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                       
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="#"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img
                                    src="{{ asset('asset/images/users/1.jpg') }}" alt="user"
                                    class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right scale-up">
                                <ul class="dropdown-user">
                                    <li>
                                        <div class="dw-user-box">
                                            <div class="u-img"><img src="{{ asset('asset/images/users/1.jpg') }}"
                                                    alt="user"></div>
                                            <div class="u-text">
                                                <h4>Steave Jobs</h4>
                                                <p class="text-muted"><a
                                                        href="https://www.wrappixel.com/cdn-cgi/l/email-protection"
                                                        class="__cf_email__"
                                                        data-cfemail="dfa9beadaab19fb8b2beb6b3f1bcb0b2">[email&#160;protected]</a>
                                                </p><a href="profile.html"
                                                    class="btn btn-rounded btn-danger btn-sm">View
                                                    Profile</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#"><i class="ti-user"></i> My Profile</a></li>
                                    <li><a href="#"><i class="ti-wallet"></i> My Balance</a></li>
                                    <li><a href="#"><i class="ti-email"></i> Inbox</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#"><i class="ti-settings"></i> Account Setting</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- Language -->
                        <!-- ============================================================== -->
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
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- User profile -->
                <div class="user-profile"
                    style="background: url('{{ asset('asset/images/background/user-info.jpg') }}'); no-repeat;">
                    <!-- User profile image -->
                    <div class="profile-img"> <img src="{{ asset('asset/images/background/alf.jpg') }}"
                            alt="user" /> </div>
                   
                </div>
                <!-- End User profile text-->
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">

                        <li> <a href="{{ route('dashboard') }}"><i class="mdi mdi-gauge"></i><span>Dashboard
                                </span></a>
                        </li>

                        <!--    <li> <a href="index.html"><i class="mdi mdi-gauge"></i><span >System Setup </span></a>
                        </li> -->


                        <li> <a href="{{ route('roles.list') }}"><i class="mdi mdi-gauge"></i><span>
                                    Role</span></a>
                        </li>

                        <li> <a href="{{ route('designations.list') }}"><i class="mdi mdi-gauge"></i><span>
                                    Designations</span></a>
                        </li>

                        <li> <a href="{{ route('plantmaster.list') }}"><i class="mdi mdi-gauge"></i><span>
                                    Plant</span></a>
                        </li>

                        <li> <a href="{{ route('projects.list') }}"><i class="mdi mdi-gauge"></i><span>
                                    Projects</span></a>
                        </li>

                        <li> <a href="{{ route('departments.list') }}"><i class="mdi mdi-gauge"></i><span>
                                    Departments</span></a>
                        </li>
                        <li> <a href="{{ route('employees.list') }}"><i class="mdi mdi-gauge"></i><span>
                                    Employees</span></a>
                        </li>

                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
            <!-- Bottom points-->
            <div class="sidebar-footer">
                <!-- item--><a href="#" class="link" data-toggle="tooltip" title="Settings"><i
                        class="ti-settings"></i></a>
                <!-- item--><a href="#" class="link" data-toggle="tooltip" title="Email"><i
                        class="mdi mdi-gmail"></i></a>
                <!-- item--><a href="{{ route('logout') }}" class="link" data-toggle="tooltip" title="Logout"><i
                        class="mdi mdi-power"></i></a>
            </div>
            <!-- End Bottom points-->
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
                @extends('toast')
                @extends('superadm.layout.footer')

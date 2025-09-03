@extends('superadm.layout.master')

@section('content')
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <!-- Roles Card -->
                        <div class="col-lg-3 col-md-6 mb-4">
                            <a href="{{ route('roles.list') }}" class="text-decoration-none text-dark">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div
                                                class="round round-lg text-white d-inline-block text-center rounded-circle bg-info">
                                                <i class="ti-wallet"></i>
                                            </div>
                                            <div class="ml-2 align-self-center">
                                                <h3 class="mb-0 font-weight-light">
                                                    Roles {{ $allRoles }}
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Designations Card -->
                        <div class="col-lg-3 col-md-6 mb-4">
                            <a href="{{ route('designations.list') }}" class="text-decoration-none text-dark">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div
                                                class="round round-lg text-white d-inline-block text-center rounded-circle bg-info">
                                                <i class="ti-wallet"></i>
                                            </div>
                                            <div class="ml-2 align-self-center">
                                                <h3 class="mb-0 font-weight-light">
                                                    Designations {{ $allDesignations }}
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Plants Card -->
                        <div class="col-lg-3 col-md-6 mb-4">
                            <a href="{{ route('plantmaster.list') }}" class="text-decoration-none text-dark">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div
                                                class="round round-lg text-white d-inline-block text-center rounded-circle bg-info">
                                                <i class="ti-wallet"></i>
                                            </div>
                                            <div class="ml-2 align-self-center">
                                                <h3 class="mb-0 font-weight-light">
                                                    Plants {{ $allPlants }}
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>


                          <!-- Plants Card -->
                        <div class="col-lg-3 col-md-6 mb-4">
                            <a href="{{ route('projects.list') }}" class="text-decoration-none text-dark">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div
                                                class="round round-lg text-white d-inline-block text-center rounded-circle bg-info">
                                                <i class="ti-wallet"></i>
                                            </div>
                                            <div class="ml-2 align-self-center">
                                                <h3 class="mb-0 font-weight-light">
                                                    Projects {{ $allProjects }}
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>


                          <!-- Plants Card -->
                        <div class="col-lg-3 col-md-6 mb-4">
                            <a href="{{ route('departments.list') }}" class="text-decoration-none text-dark">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div
                                                class="round round-lg text-white d-inline-block text-center rounded-circle bg-info">
                                                <i class="ti-wallet"></i>
                                            </div>
                                            <div class="ml-2 align-self-center">
                                                <h3 class="mb-0 font-weight-light">
                                                    Departments {{ $allDepartments }}
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>


                          <!-- Plants Card -->
                        <div class="col-lg-3 col-md-6 mb-4">
                            <a href="{{ route('employees.list') }}" class="text-decoration-none text-dark">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div
                                                class="round round-lg text-white d-inline-block text-center rounded-circle bg-info">
                                                <i class="ti-wallet"></i>
                                            </div>
                                            <div class="ml-2 align-self-center">
                                                <h3 class="mb-0 font-weight-light">
                                                    Employees {{ $allEmployees }}
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End of Page Content -->
    <!-- ============================================================== -->
@endsection

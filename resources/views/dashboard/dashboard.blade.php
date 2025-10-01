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
                                              <i class="mdi mdi-account-key mdi-36px icon-padding"></i>
                                            </div>
                                            <div class="ml-2 align-self-center">
                                                <h3 class="mb-0 font-weight-light">
                                                    Roles <strong>{{ $allRoles }}</strong>
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
                                                <i class="mdi mdi-id-card mdi-36px icon-padding"></i>
                                            </div>
                                            <div class="ml-2 align-self-center">
                                                <h3 class="mb-0 font-weight-light">
                                                    Designations <strong>{{ $allDesignations }}</strong>
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
                                               <i class="mdi mdi-factory mdi-36px icon-padding"></i>
                                            </div>
                                            <div class="ml-2 align-self-center">
                                                <h3 class="mb-0 font-weight-light">
                                                    Plants <strong>{{ $allPlants }}</strong>
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
                                                <i class="mdi mdi-clipboard-text mdi-36px icon-padding"></i>
                                            </div>
                                            <div class="ml-2 align-self-center">
                                                <h3 class="mb-0 font-weight-light">
                                                    Projects <strong>{{ $allProjects }}</strong>
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
                                               <i class="mdi mdi-domain mdi-36px icon-padding"></i>
                                            </div>
                                            <div class="ml-2 align-self-center">
                                                <h3 class="mb-0 font-weight-light">
                                                    Departments <strong>{{ $allDepartments }}</strong>
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
                                                <i class="mdi mdi-account-group mdi-36px icon-padding"></i>
                                            </div>
                                            <div class="ml-2 align-self-center">
                                                <h3 class="mb-0 font-weight-light">
                                                    Employees <strong>{{ $allEmployees }}</strong>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Employee Types Card -->
                        <div class="col-lg-3 col-md-6 mb-4">
                            <a href="{{ route('employee-types.list') }}" class="text-decoration-none text-dark">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div class="round round-lg text-white d-inline-block text-center rounded-circle bg-info">
                                                <i class="mdi mdi-account-tie mdi-36px icon-padding"></i>
                                            </div>
                                            <div class="ml-2 align-self-center">
                                                <h3 class="mb-0 font-weight-light">
                                                    Employee Types <strong>{{ $allEmployeeTypes }}</strong>
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

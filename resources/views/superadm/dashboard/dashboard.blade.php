@extends('superadm.layout.master')
@section('content')
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">


                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('roles.list') }}" class="text-decoration-none text-dark">

                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div
                                            class="round round-lg text-white d-inline-block text-center rounded-circle bg-info">
                                            <i class="ti-wallet"></i>
                                        </div>
                                        <div class="ml-2 align-self-center">
                                            <h3 class="mb-0 font-weight-light">Roles {{ $allRoles }}</h3>
                                            <h5 class="text-muted mb-0"></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>

                         <a href="{{ route('roles.list') }}" class="text-decoration-none text-dark">

                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div
                                            class="round round-lg text-white d-inline-block text-center rounded-circle bg-info">
                                            <i class="ti-wallet"></i>
                                        </div>
                                        <div class="ml-2 align-self-center">
                                            <h3 class="mb-0 font-weight-light">Designations {{ $allDesignations}}</h3>
                                            <h5 class="text-muted mb-0"></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>


                         <a href="{{ route('plantmaster.list') }}" class="text-decoration-none text-dark">

                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div
                                            class="round round-lg text-white d-inline-block text-center rounded-circle bg-info">
                                            <i class="ti-wallet"></i>
                                        </div>
                                        <div class="ml-2 align-self-center">
                                            <h3 class="mb-0 font-weight-light">Plants {{ $allPlants}}</h3>
                                            <h5 class="text-muted mb-0"></h5>
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

    <!-- ============================================================== -->
    <!-- End of Page Content -->
    <!-- ============================================================== -->
@endsection

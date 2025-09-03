@extends('superadm.layout.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <!-- Roles Card -->
                        @foreach ($projects as $project)
                            <div class="col-lg-3 col-md-6 mb-4">

                                <a href="{{ $project->project_url }}?pant_no={{$project->plant_id}}&emp_code={{$project->employee_code}}" class="text-decoration-none text-dark">
                                    <div class="card shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex flex-row">
                                                <div
                                                    class="round round-lg text-white d-inline-block text-center rounded-circle bg-info">
                                                    <i class="ti-wallet"></i>
                                                </div>
                                                <div class="ml-2 align-self-center">
                                                    <h3 class="mb-0 font-weight-light">
                                                        {{ $project->project_name}}
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                            </div>
                        @endforeach

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

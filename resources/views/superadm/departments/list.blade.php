@extends('superadm.layout.master')

@section('content')
<style>
    .dropdown-item.active, .dropdown-item-custom:active {
        background-color: #952419;
    }
    #global-loader {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(255,255,255,0.7);
        z-index: 9999;
    }
    #global-loader i {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        font-size: 3rem;
        color: #f0ad4e;
    }
    .blink-btn {
        animation: blink 1s infinite;
    }
    @keyframes blink {
        50% { opacity: 0.3; }
    }
</style>

<div id="global-loader">
    <i class="fa fa-spinner fa-spin"></i>
</div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{-- <div class="col-md-4">
                        <form method="GET" action="{{ route('departments.list') }}">
                            <select name="plant_id" class="form-control" onchange="this.form.submit()">
                                <option value="">-- Select Plant --</option>
                                @foreach($plants as $p)
                                    <option value="{{ $p->id }}" 
                                        {{ $selectedPlant == $p->id ? 'selected' : '' }}>
                                        {{ $p->plant_code }} - {{ $p->plant_name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div> --}}

                    <div class="mb-3 d-flex">
                        <form method="POST" action="{{ route('departments.filter') }}" class="mr-auto">
                            @csrf
                            <select name="plant_id" class="form-control" onchange="this.form.submit()">
                                <option value="">-- Select Plant --</option>
                                @foreach($plants as $p)
                                    <option value="{{ $p->id }}" {{ $selectedPlant == $p->id ? 'selected' : '' }}>
                                        {{ $p->plant_code }} - {{ $p->plant_name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>

                        <div class="btn-group mr-2">
                            <button type="button" class="btn btn-warning btn-add dropdown-toggle" data-toggle="dropdown">
                                Export
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item dropdown-item-custom export-btn" data-type="excel" href="#">Excel</a>
                                <a class="dropdown-item dropdown-item-custom export-btn" data-type="pdf" href="#">PDF</a>
                            </div>
                        </div>

                        <a href="{{ route('departments.create') }}" class="btn btn-warning btn-add">Add Department</a>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" id="success-alert">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" id="error-alert" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped datatables">
                            <thead>
                                <tr>

                                    <th>Sr.No.</th>
                                    <th>Plant Name</th>
                                    <th>Department Code</th>
                                    <th>Department Name</th>
                                    <th>Department Short Name</th>
                                    <th>Send Data</th>
                                    <th>Created By</th>
                                    <th>Created Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataAll as $key => $data)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        {{-- <td> {{ $data->plant_name }}</td> --}}
                                        <td>{{ $data->plant_code ?? '-' }} - {{ $data->plant_name }}</td>
                                        <td>{{ $data->department_code }}</td>
                                        <td>{{ $data->department_name }}</td>
                                        <td>{{ $data->department_short_name }}</td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-sm btn-warning open-department-send-modal {{ $data->send_api == 0 ? 'blink-btn' : '' }}"
                                                data-id="{{ $data->id }}"
                                                data-plant-id="{{ $data->plant_id }}"
                                                data-plant-code="{{ $data->plant_code }}"
                                                data-plant-name="{{ $data->plant_name }}"
                                                data-department-code="{{ $data->department_code }}"
                                                data-department-name="{{ $data->department_name }}"
                                                data-department-short-name="{{ $data->department_short_name }}"
                                                data-old-projects="{{ $data->send_api_project_id ?? '' }}">
                                                <i class="mdi mdi-upload"></i> Send Data
                                            </button>
                                        </td>
                                        <td>{{ $data->created_by ?? '-' }}</td>
                                        <td>
                                            {{ $data->created_at 
                                                ? $data->created_at->setTimezone('Asia/Kolkata')->format('d-m-Y h:i:s A') 
                                                : '-' }}
                                        </td>
                                        <td>
                                            <form action="{{ route('departments.updatestatus') }}" method="POST"
                                                class="d-inline-block delete-form">
                                                @csrf
                                                <label class="switch">
                                                    <input type="checkbox" class="toggle-status"
                                                        data-id="{{ base64_encode($data->id) }}"
                                                        {{ $data->is_active == '1' ? 'checked' : '' }}>
                                                    <span class="slider"></span>
                                                </label>

                                                <input type="hidden" name="id"
                                                    value="{{ base64_encode($data->id) }}">
                                            </form>
                                        </td>
                                         <td class="d-flex">
                                            <a href="{{ route('departments.edit', base64_encode($data->id)) }}" 
                                            class="btn btn-sm btn-primary mr-2" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            title="Edit">
                                            <i class="mdi mdi-square-edit-outline icon-medium"></i>
                                            </a>
                                            <form action="{{ route('departments.delete') }}" method="POST" class="d-inline-block delete-form">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ base64_encode($data->id) }}">
                                                <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        title="Delete">
                                                    <i class="mdi mdi-trash-can-outline icon-medium"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sendDepartmentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-white">Send Department Data to API</h5>
                    <button class="close" data-bs-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sr</th>
                                <th>Plant</th>
                                <th>Department</th>
                                <th>Select Projects</th>
                            </tr>
                        </thead>
                        <tbody id="departmentProjectAssignBody"></tbody>
                    </table>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-success" id="sendDepartmentApiBtn">Send to API</button>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- =================== SCRIPT =================== --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-multiselect/dist/js/bootstrap-multiselect.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-multiselect/dist/css/bootstrap-multiselect.css">

<script>
$(document).on("click", ".open-department-send-modal", function () {

    $("#sendDepartmentApiBtn").prop("disabled", true);

    let id = $(this).data("id");
    let departmentName = $(this).data("department-name");
    let plantCode = $(this).data("plant-code");
    let plantName = $(this).data("plant-name");
    let oldProjects = ($(this).data("old-projects") || "").toString().split(",").filter(Boolean);

    $("#departmentProjectAssignBody").html("<tr><td colspan='3'>Loading...</td></tr>");
    $("#sendDepartmentModal").modal("show");

    $.get("{{ url('/get-all-projects') }}", function(projects){

        let options = "";
        projects.forEach(p => {
            options += `<option value="${p.id}">${p.project_name}</option>`;
        });

        $("#departmentProjectAssignBody").html(`
            <tr>
                <td>1</td>
                <td>${plantCode ?? ''} - ${plantName ?? ''}</td>
                <td>${departmentName}</td>
                <td>
                    <select class="form-control department-project-select" multiple>
                        ${options}
                    </select>
                </td>
            </tr>
        `);

        $(".department-project-select").multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
            buttonWidth: '100%',
            maxHeight: 300,
            onChange: function(){
                $("#sendDepartmentApiBtn").prop(
                    "disabled",
                    !$(".department-project-select").val()
                );
            }
        });

        $(".department-project-select")
            .val(oldProjects)
            .multiselect("refresh");

        if(oldProjects.length){
            $("#sendDepartmentApiBtn").prop("disabled", false);
        }

        $("#sendDepartmentApiBtn").data("id", id);
    });
});
</script>

<script>
$("#sendDepartmentApiBtn").click(function(){

    let id = $(this).data("id");
    let projects = $(".department-project-select").val();

    if(!projects || projects.length === 0){
        Swal.fire("Select at least one project!");
        return;
    }

    Swal.fire({
        title: "Are you sure?",
        text: "Send department data to selected projects?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, send it!"
    }).then(res => {

        if(res.isConfirmed){
            $("#global-loader").fadeIn(100);

            $.ajax({
                url: "{{ route('departments.sendApi') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    projects: projects
                },
                success: function(resp){
                    $("#global-loader").fadeOut(300);

                    if(resp.status){
                        Swal.fire("Success!", resp.message, "success")
                            .then(()=> location.reload());
                    } else {
                        Swal.fire("Error!", resp.message, "error");
                    }
                },
                error: function(){
                    $("#global-loader").fadeOut(300);
                    Swal.fire("Error!", "Something went wrong", "error");
                }
            });
        }
    });
});
</script>



    <script>
    $(document).on("change", ".toggle-status", function(e) {
        e.preventDefault();

        let checkbox = $(this);
        let id = checkbox.data("id");
        let is_active = checkbox.is(":checked") ? 1 : 0;

        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to change the status?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, change it!",
            cancelButtonText: "No, cancel"
        }).then((result) => {
            if(result.isConfirmed){
                
                $("#global-loader").fadeIn(100); // ← ADD THIS

                $.ajax({
                    url: "{{ route('departments.updatestatus') }}",
                    type: "POST",
                    data: {_token: "{{ csrf_token() }}", id: id, is_active: is_active},
                    success: function(res){
                        $("#global-loader").fadeOut(300); // ← ADD THIS

                        if(res.status){
                            Swal.fire('Success!', res.message, 'success');
                        } else {
                            Swal.fire('Error!', res.message, 'error');
                            checkbox.prop("checked", !is_active);
                        }
                    },
                    error: function(xhr){
                        $("#global-loader").fadeOut(300); // ← ADD THIS
                        Swal.fire('Error!', xhr.responseJSON?.message || 'Something went wrong', 'error');
                        checkbox.prop("checked", !is_active);
                    }
                });
            } else {
                checkbox.prop("checked", !checkbox.is(":checked"));
            }
        });
    });
    </script>

<script>
$(document).on("click", ".delete-btn", function (e) {
    e.preventDefault();

    let button = $(this);
    let form = button.closest("form");

    Swal.fire({
        title: "Are you sure?",
        text: "This record will be deleted!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel"
    }).then((result) => {
        if (result.isConfirmed) {

            $("#global-loader").fadeIn(100); 

            form.submit();
        }
    });
});
</script>


    <script>
        // Delegated event for delete buttons
        $(document).on("click", ".delete-btn", function (e) {
            e.preventDefault();

            let button = $(this);
            let form = button.closest("form");

            Swal.fire({
                title: "Are you sure?",
                text: "This record will be deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

    </script>



@endsection

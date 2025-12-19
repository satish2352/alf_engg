@extends('superadm.layout.master')

@section('content')

<style>

    #global-loader {
        display: none;
        position: fixed;
        top: 0; 
        left: 0;
        width: 100%; 
        height: 100%;
        background: rgba(255, 255, 255, 0.7);
        z-index: 9999;
        text-align: center;
    }
    #global-loader i {
        position: absolute;
        top: 50%;
        left: 50%;
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

                    <div class="mb-3 d-flex justify-content-end">
                        <a href="{{ route('roles.create') }}" class="btn btn-add">Add Role</a>
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
                                    <th>Role Name</th>
                                    <th>Short Description</th>
                                    <th>Send Data</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $key => $role)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $role->role }}</td>
                                    <td>{{ $role->short_description }}</td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-sm btn-warning open-role-send-modal {{ $role->send_api == 0 ? 'blink-btn' : '' }}"
                                            data-id="{{ $role->id }}"
                                            data-role="{{ $role->role }}"
                                            data-desc="{{ $role->short_description }}"
                                            data-old-projects="{{ $role->send_api_project_id ?? '' }}"
                                        >
                                            <i class="mdi mdi-upload"></i> Send Data
                                        </button>
                                    </td>
                                    <td>
                                        @if ($role->id != 0)
                                            <form action="{{ route('roles.updatestatus') }}" method="POST"
                                                class="d-inline-block delete-form">
                                                @csrf
                                                <label class="switch">
                                                    <input type="checkbox" class="toggle-status"
                                                        data-id="{{ base64_encode($role->id) }}"
                                                        {{ $role->is_active == '1' ? 'checked' : '' }}>
                                                    <span class="slider"></span>
                                                </label>
                                                <input type="hidden" name="id" value="{{ base64_encode($role->id) }}">
                                            </form>
                                        @else
                                            <span>Active</span> {{-- or whatever you want --}}
                                        @endif
                                    </td>

                                    <td>
                                        @if ($role->id != 0)
                                            <a href="{{ route('roles.edit', base64_encode($role->id)) }}" 
                                                class="btn btn-sm btn-primary" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                title="Edit">
                                                <i class="mdi mdi-square-edit-outline icon-medium"></i>
                                            </a>

                                            <form action="{{ route('roles.delete') }}" method="POST" class="d-inline-block delete-form">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ base64_encode($role->id) }}">
                                                <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        title="Delete">
                                                    <i class="mdi mdi-trash-can-outline icon-medium"></i>
                                                </button>
                                            </form>
                                        @else
                                            {{-- <button class="btn btn-sm btn-secondary" disabled>Protected</button> --}}
                                                        {{-- Disabled Edit Icon --}}
                                            <button class="btn btn-sm btn-secondary" disabled style="pointer-events: none; opacity: 0.5;">
                                                <i class="mdi mdi-square-edit-outline icon-medium"></i>
                                            </button>

                                            {{-- Disabled Delete Icon --}}
                                            <button class="btn btn-sm btn-secondary" disabled style="pointer-events: none; opacity: 0.5;">
                                                <i class="mdi mdi-trash-can-outline icon-medium"></i>
                                            </button>
                                        @endif
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

    <div class="modal fade" id="sendRoleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header bg-warning">
                    <h5 class="modal-title" style="color:white">Send Role Data to API</h5>
                    <button class="close" data-bs-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Role Name</th>
                                <th>Select Projects</th>
                            </tr>
                        </thead>
                        <tbody id="roleProjectAssignBody"></tbody>
                    </table>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-success" id="sendRoleApiBtn">Send to API</button>
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
        $(document).on("click", ".open-role-send-modal", function () {

            $("#sendRoleApiBtn").prop("disabled", true);

            let id = $(this).data("id");
            let role = $(this).data("role");
            let desc = $(this).data("desc");

            let rawOldProjects = $(this).attr("data-old-projects") || "";
            let oldProjects = rawOldProjects.split(",").filter(Boolean);

            $("#roleProjectAssignBody").html("<tr><td colspan='3'>Loading...</td></tr>");
            $("#sendRoleModal").modal("show");

            $.ajax({
                url: "{{ url('/get-all-projects') }}",
                type: "GET",
                success: function(projects){

                    let options = "";
                    projects.forEach(p=>{
                        options += `<option value="${p.id}">${p.project_name}</option>`;
                    });

                    let row = `
                        <tr>
                            <td>1</td>
                            <td>${role}</td>
                            <td>
                                <select class="form-control role-project-select" multiple>
                                    ${options}
                                </select>
                            </td>
                        </tr>
                    `;

                    $("#roleProjectAssignBody").html(row);

                    $(".role-project-select").multiselect({
                        includeSelectAllOption: true,
                        enableFiltering: true,
                        buttonWidth: '100%',
                        maxHeight: 300,
                        onChange: function(){
                            let selected = $(".role-project-select").val();
                            $("#sendRoleApiBtn").prop("disabled", !selected);
                        }
                    });

                    $(".role-project-select").val(oldProjects);
                    $(".role-project-select").multiselect("refresh");

                    if(oldProjects.length > 0){
                        $("#sendRoleApiBtn").prop("disabled", false);
                    }

                    $("#sendRoleApiBtn").data("id", id);
                }
            });

        });

        $("#sendRoleApiBtn").click(function(){

            let roleId = $(this).data("id");
            let selectedProjects = $(".role-project-select").val();

            if(!selectedProjects){
                Swal.fire("Select at least one project!");
                return;
            }

            Swal.fire({
                title: "Are you sure?",
                text: "Send role data to selected projects?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, send it!",
                cancelButtonText: "No, cancel"
            }).then(res=>{

                if(res.isConfirmed){

                    // START LOADER
                    $("#global-loader").fadeIn(100);

                    $.ajax({
                        url: "{{ route('roles.sendApi') }}",
                        type: "POST",
                        data:{
                            _token: "{{ csrf_token() }}",
                            id: roleId,
                            projects: selectedProjects
                        },
                        success: function(response){

                            // STOP LOADER
                            $("#global-loader").fadeOut(300);

                            if(response.status){
                                Swal.fire("Success!", response.message, "success")
                                .then(()=> location.reload());
                            }else{
                                Swal.fire("Error!", response.message, "error");
                            }
                        },
                        error: function(){

                            // STOP LOADER
                            $("#global-loader").fadeOut(300);

                            Swal.fire("Error!", "Something went wrong!", "error");
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
            let form = checkbox.closest("form");
            let id = checkbox.data("id");
            let is_active = checkbox.is(":checked") ? 1 : 0;

            // Show SweetAlert confirmation
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
                if (result.isConfirmed) {
                    // START LOADER
                    $("#global-loader").fadeIn(100);
                    $.ajax({
                        url: "{{ route('roles.updatestatus') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            is_active: is_active
                        },
                        success: function(response) {
                            // STOP LOADER
                            $("#global-loader").fadeOut(300);
                            if (response.status) {
                                Swal.fire('Success!', response.message, 'success');
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                                checkbox.prop("checked", !is_active); // revert
                            }
                        },
                        error: function(xhr) {
                            // STOP LOADER
                            $("#global-loader").fadeOut(300);
                            Swal.fire('Error!', xhr.responseJSON?.message || 'Something went wrong', 'error');
                            checkbox.prop("checked", !is_active); // revert
                        }
                    });
                } else {
                    checkbox.prop("checked", !checkbox.is(":checked")); // revert
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

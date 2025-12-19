@extends('superadm.layout.master')

@section('content')

<style>
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

                    <div class="mb-3 d-flex justify-content-end">
                        <a href="{{ route('designations.create') }}" class="btn btn-warning btn-add">Add Designation</a>
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
                                    <th>Designation Name</th>
                                    <th>Short Description</th>
                                    <th>Send Data</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($designation as $key => $data)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $data->designation }}</td>
                                        <td>{{ $data->short_description }}</td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-sm btn-warning open-designation-send-modal {{ $data->send_api == 0 ? 'blink-btn' : '' }}"
                                                data-id="{{ $data->id }}"
                                                data-designation="{{ $data->designation }}"
                                                data-desc="{{ $data->short_description }}"
                                                data-old-projects="{{ $data->send_api_project_id ?? '' }}">
                                                <i class="mdi mdi-upload"></i> Send Data
                                            </button>
                                        </td>
                                        <td>
                                            <form action="{{ route('designations.updatestatus') }}" method="POST"
                                                class="d-inline-block delete-form">
                                                @csrf
                                                <label class="switch">
                                                    <input type="checkbox" class="toggle-status"
                                                        data-id="{{ base64_encode($data->id) }}"
                                                        {{ $data->is_active == '1' ? 'checked' : '' }}>
                                                    <span class="slider"></span>
                                                </label>


                                                <input type="hidden" name="id" value="{{ base64_encode($data->id) }}">
                                            </form>
                                        </td>
                                         <td>
                                            <a href="{{ route('designations.edit', base64_encode($data->id)) }}" 
                                            class="btn btn-sm btn-primary" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            title="Edit">
                                            <i class="mdi mdi-square-edit-outline icon-medium"></i>
                                            </a>
                                            <form action="{{ route('designations.delete') }}" method="POST" class="d-inline-block delete-form">
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

    <div class="modal fade" id="sendDesignationModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            <div class="modal-header bg-warning">
                <h5 class="modal-title text-white">Send Designation Data to API</h5>
                <button class="close" data-bs-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered">
                <thead>
                    <tr>
                    <th>Sr</th>
                    <th>Designation</th>
                    <th>Select Projects</th>
                    </tr>
                </thead>
                <tbody id="designationProjectAssignBody"></tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-success" id="sendDesignationApiBtn">Send to API</button>
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
        $(document).on("click", ".open-designation-send-modal", function () {

    $("#sendDesignationApiBtn").prop("disabled", true);

    let id = $(this).data("id");
    let designation = $(this).data("designation");
    let oldProjects = ($(this).data("old-projects") || "").toString().split(",").filter(Boolean);

    $("#designationProjectAssignBody").html("<tr><td colspan='3'>Loading...</td></tr>");
    $("#sendDesignationModal").modal("show");

    $.get("{{ url('/get-all-projects') }}", function(projects){

        let options = "";
        projects.forEach(p => {
            options += `<option value="${p.id}">${p.project_name}</option>`;
        });

        $("#designationProjectAssignBody").html(`
            <tr>
                <td>1</td>
                <td>${designation}</td>
                <td>
                    <select class="form-control designation-project-select" multiple>
                        ${options}
                    </select>
                </td>
            </tr>
        `);

        $(".designation-project-select").multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
            buttonWidth: '100%',
            maxHeight: 300,
            onChange: function(){
                $("#sendDesignationApiBtn").prop(
                    "disabled",
                    !$(".designation-project-select").val()
                );
            }
        });

        $(".designation-project-select").val(oldProjects).multiselect("refresh");

        if(oldProjects.length) {
            $("#sendDesignationApiBtn").prop("disabled", false);
        }

        $("#sendDesignationApiBtn").data("id", id);
    });
});

$("#sendDesignationApiBtn").click(function(){

    let id = $(this).data("id");
    let projects = $(".designation-project-select").val();

    Swal.fire({
        title: "Are you sure?",
        text: "Send designation data to selected projects?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, send it!"
    }).then(res => {

        if(res.isConfirmed){
            $("#global-loader").fadeIn(100);

            $.post("{{ route('designations.sendApi') }}", {
                _token: "{{ csrf_token() }}",
                id: id,
                projects: projects
            }, function(resp){

                $("#global-loader").fadeOut(300);

                if(resp.status){
                    Swal.fire("Success!", resp.message, "success")
                        .then(()=> location.reload());
                } else {
                    Swal.fire("Error!", resp.message, "error");
                }
            }).fail(()=>{
                $("#global-loader").fadeOut(300);
                Swal.fire("Error!", "Something went wrong", "error");
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
                    $.ajax({
                        url: "{{ route('designations.updatestatus') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            is_active: is_active
                        },
                        success: function(response) {
                            if (response.status) {
                                Swal.fire('Success!', response.message, 'success');
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                                checkbox.prop("checked", !is_active); // revert
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', xhr.responseJSON?.message || 'Something went wrong', 'error');
                            checkbox.prop("checked", !is_active); // revert
                        }
                    });
                } else {
                    checkbox.prop("checked", !checkbox.is(":checked")); // revert if cancelled
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

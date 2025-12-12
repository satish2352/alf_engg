@extends('superadm.layout.master')

@section('content')
<style>
    .dropdown-item.active, .dropdown-item-custom:active {
        background-color: #952419;
    }

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
                        <div class="btn-group mr-2">
                            <button type="button" class="btn btn-warning btn-add dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Export
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item dropdown-item-custom export-btn" data-type="excel" href="#">Excel</a>
                                <a class="dropdown-item dropdown-item-custom export-btn" data-type="pdf" href="#">PDF</a>
                            </div>
                        </div>

                        <a href="{{ route('plantmaster.create') }}" class="btn btn-warning btn-add">Add Plant Details</a>
                        {{-- <a id="exportExcelBtn" class="btn btn-warning btn-add" style="cursor: pointer;">Export Excel</a> --}}
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
                                    <th>Plant Code</th>
                                    <th>Plant Name</th>
                                    <th>Plant Address</th>
                                    <th>Plant City</th>
                                    <th>Plant Short Name</th>
                                    <th>Created By</th>
                                    <th>Send Data</th>
                                    <th>Created Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_all as $key => $data)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $data->plant_code }}</td>
                                        <td>{{ $data->plant_name }}</td>
                                        <td>{{ $data->address ?? '-' }}</td>
                                        <td>{{ $data->city }}</td>
                                        <td>{{ $data->plant_short_name ?? '-' }}</td>
                                        <td>{{ $data->created_by ?? '-' }}</td>
                                        <td><button type="button"
                                                class="btn btn-sm btn-warning open-send-modal {{ $data->send_api == 0 ? 'blink-btn' : '' }}"
                                                data-id="{{ $data->id }}"
                                                data-plant="{{ $data->plant_name }}"
                                                data-code="{{ $data->plant_code }}"
                                                data-old-projects="{{ $data->send_api_project_id ?? '' }}"
                                            >
                                                <i class="mdi mdi-upload"></i> Send Data
                                            </button>
                                        </td>
                                        <td>
                                            {{ $data->created_at 
                                                ? $data->created_at->setTimezone('Asia/Kolkata')->format('d-m-Y h:i:s A') 
                                                : '-' }}
                                        </td>
                                        <td>
                                            <form action="{{ route('plantmaster.updatestatus') }}" method="POST"
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
                                          <td class="d-flex">
                                            <a href="{{ route('plantmaster.edit', base64_encode($data->id)) }}" 
                                            class="btn btn-sm btn-primary mr-2" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            title="Edit">
                                            <i class="mdi mdi-square-edit-outline icon-medium"></i>
                                            </a>
                                            <form action="{{ route('plantmaster.delete') }}" method="POST" class="d-inline-block delete-form">
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

<div class="modal fade" id="sendPlantModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-warning">
                <h5 class="modal-title" style="color: #fff">Send Plant Data to API</h5>
                <button class="close" data-bs-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sr.</th>
                            <th>Plant Name</th>
                            <th>Select Projects</th>
                        </tr>
                    </thead>
                    <tbody id="projectAssignBody">
                    </tbody>
                </table>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-success" id="sendPlantApiBtn">Send to API</button>
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
$(document).on("click", ".open-send-modal", function () {
    $("#sendPlantApiBtn").prop("disabled", true); // disable initially

    let id = $(this).data("id");
    let plantName = $(this).data("plant");
    let plantcode = $(this).data("code");

        // FETCH OLD STORED PROJECT IDS
let rawOldProjects = $(this).attr("data-old-projects") || "";
let oldProjects = rawOldProjects.split(",").filter(Boolean);

    $("#projectAssignBody").html("<tr><td colspan='3'>Loading...</td></tr>");
    $("#sendPlantModal").modal("show");

    $.ajax({
        url: "{{ url('/get-all-projects') }}",
        type: "GET",
        success: function (projects) {

            let options = "";
            
            projects.forEach(p => {
                options += `<option value="${p.id}">${p.project_name}</option>`;
            });

            let row = `
                <tr>
                    <td>1</td>
                    <td>${plantName} - ${plantcode}</td>
                    <td>
                        <select class="form-control project-select" multiple>
                            ${options}
                        </select>
                    </td>
                </tr>
            `;

            $("#projectAssignBody").html(row);

            $('.project-select').multiselect({
                includeSelectAllOption: true,
                enableFiltering: true,
                buttonWidth: '100%',
                maxHeight: 300,
                onChange: function(option, checked) {
                    let selected = $('.project-select').val();
                    $("#sendPlantApiBtn").prop("disabled", !selected || selected.length === 0);
                }
            });

            // Pre-select saved projects
            $('.project-select').val(oldProjects);
            $('.project-select').multiselect("refresh");

            // Enable button if old selections exist
            if (oldProjects.length > 0) {
                $("#sendPlantApiBtn").prop("disabled", false);
            }

            $("#sendPlantApiBtn").data("id", id);
        }
    });
});

$("#sendPlantApiBtn").click(function () {

    let plantId = $(this).data("id");
    let selectedProjects = $(".project-select").val();

    if (!selectedProjects || selectedProjects.length === 0) {
        Swal.fire("Select at least one project!");
        return;
    }

    Swal.fire({
        title: "Are you sure?",
        text: "Send plant data to selected projects?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, send it!",
        cancelButtonText: "No, cancel"
    }).then(res => {

        if (res.isConfirmed) {

            // 🔥 Loader Start (Manual)
            $("#global-loader").fadeIn(100);

            $.ajax({
                url: "{{ route('plantmaster.sendApi') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: plantId,
                    projects: selectedProjects
                },
                success: function (response) {

                    $("#global-loader").fadeOut(300);

                    if (response.status) {
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success"
                        }).then(() => {
                            location.reload(); // ⬅️ OK बटणावर क्लिक झाल्यावर refresh
                        });
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: response.message,
                            icon: "error"
                        }).then(() => {
                            location.reload(); // ⬅️ येथेही OK नंतर refresh
                        });
                    }
                },
                error: function (xhr) {

                    // 🔥 Stop Loader
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

                    // 🔥 MANUAL LOADER START
                    $("#global-loader").fadeIn(100);

                    $.ajax({
                        url: "{{ route('plantmaster.updatestatus') }}",
                        type: "POST",
                        data: {_token: "{{ csrf_token() }}", id: id, is_active: is_active},
                        success: function(res){

                            // 🔥 STOP LOADER
                            $("#global-loader").fadeOut(300);

                            if(res.status){
                                Swal.fire('Success!', res.message, 'success');
                            } else {
                                Swal.fire('Error!', res.message, 'error');
                                checkbox.prop("checked", !is_active);
                            }
                        },
                        error: function(xhr){

                            // 🔥 STOP LOADER
                            $("#global-loader").fadeOut(300);

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
$(document).ready(function() {
    $('.export-btn').click(function(e){
        e.preventDefault();

        const type = $(this).data('type'); // excel or pdf
        const rows = $("table.datatables tbody tr");
        let hasData = false;

        rows.each(function() {
            const cellText = $(this).find("td:first").text().trim().toLowerCase();
            if (cellText !== "no data available" && cellText !== "no matching records found" && cellText !== "") {
                hasData = true;
                return false;
            }
        });

        if (!hasData) {
            Swal.fire({
                icon: 'warning',
                title: 'No data available!',
                text: 'There is no data in the table to export.',
                confirmButtonColor: '#3085d6'
            });
            return false;
        }

        let searchValue = $('.dataTables_filter input').val();
        let url = "{{ route('plantmaster.export') }}" + '?type=' + type;
        if (searchValue) {
            url += '&search=' + encodeURIComponent(searchValue);
        }

        window.location.href = url;
    });
});
</script>

    <script>
        // Delegated event for delete buttons
        $(document).on("click", ".delete-btn", function (e) {
            e.preventDefault();

            let form = $(this).closest("form");

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

                    // 🔥 SHOW LOADER BEFORE SUBMIT
                    $("#global-loader").fadeIn(100);

                    form.submit();
                }
            });
        });

    </script>


@endsection

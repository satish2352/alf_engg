@extends('superadm.layout.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="mb-3 d-flex justify-content-end">
                    <a href="{{ route('employee.assignments.create') }}" class="btn btn-warning btn-add">Add Employee Assignment</a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped datatables">
                        <thead>
                            <tr>
                                <th>Sr.No.</th>
                                <th>Employee</th>
                                <th>Plant</th>
                                <th>Departments</th>
                                <th>Projects</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assignments as $key => $data)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $data->employee->employee_name ?? '-' }}</td>
                                    <td>{{ $data->plant->plant_name ?? '-' }}</td>
                                    <td>{{ $data->departments_names }}</td>
                                    <td>{{ $data->projects_names }}</td>
                                    <td>
                                        <form class="d-inline-block">
                                            @csrf
                                            <label class="switch">
                                                <input type="checkbox" class="toggle-status"
                                                    data-id="{{ base64_encode($data->id) }}"
                                                    {{ $data->is_active ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </form>
                                    </td>
                                    <td>
                                        {{-- Edit Button --}}
                                        <a href="{{ route('employee.assignments.edit', base64_encode($data->id)) }}" 
                                        class="btn btn-sm btn-primary mr-1" 
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        title="Edit">
                                        <i class="mdi mdi-square-edit-outline icon-medium"></i>
                                        </a>

                                        {{-- Delete Button --}}
                                        <form action="{{ route('employee.assignments.delete') }}" method="POST" class="d-inline-block delete-form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ base64_encode($data->id) }}">
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                                    data-employee="{{ $data->employee->employee_name ?? '-' }}"
                                                    data-plant="{{ $data->plant->plant_name ?? '-' }}"
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
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('employee.assignments.updatestatus') }}",
                type: "POST",
                data: { _token: "{{ csrf_token() }}", id: id, is_active: is_active },
                success: function(response) {
                    if(response.status){
                        Swal.fire('Success!', response.message, 'success');
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                        checkbox.prop("checked", !is_active);
                    }
                },
                error: function(xhr){
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
$(document).ready(function(){

    // ---- Delete Employee Assignment ----
    $(document).on("click", ".delete-btn", function(e){
        e.preventDefault();

        let button = $(this);
        let form = button.closest("form");
        let id = form.find("input[name='id']").val();
        let employeeName = button.data("employee");
        let plantName = button.data("plant");

        Swal.fire({
            title: "Are you sure?",
            text: `Do you want to delete ${employeeName} assignment for ${plantName}?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if(result.isConfirmed){
                $.ajax({
                    url: form.attr("action"),
                    type: "POST",
                    data: form.serialize(),
                    success: function(response){
                        if(response.status){
                            Swal.fire("Deleted!", response.message, "success")
                                .then(() => location.reload()); // Reload page
                        } else {
                            Swal.fire("Error!", response.message, "error");
                        }
                    },
                    error: function(xhr){
                        Swal.fire("Error!", xhr.responseJSON?.message || "Something went wrong", "error");
                    }
                });
            }
        });
    });


    // ---- Toggle Status ----
    $(document).on("change", ".toggle-status", function(e){
        e.preventDefault();

        let checkbox = $(this);
        let previousState = !checkbox.prop("checked"); // 1st state
        let id = checkbox.data("id");
        let newState = checkbox.is(":checked") ? 1 : 0;

        let row = checkbox.closest("tr");
        let employeeName = row.find("td:nth-child(2)").text();
        let plantName = row.find("td:nth-child(3)").text();
        let statusText = newState ? "activate" : "deactivate";

        // instent state not change → 1st revert do
        checkbox.prop("checked", previousState);

        Swal.fire({
            title: "Are you sure?",
            text: `Do you want to ${statusText} ${employeeName}'s assignment for ${plantName}?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, change it!",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if(result.isConfirmed){
                $.ajax({
                    url: "{{ route('employee.assignments.updatestatus') }}",
                    type: "POST",
                    data: { _token: "{{ csrf_token() }}", id: id, is_active: newState },
                    success: function(response){
                        if(response.status){
                            // ✅ Success done then checkbox update do
                            checkbox.prop("checked", newState);
                            Swal.fire("Success!", response.message, "success");
                        } else {
                            Swal.fire("Error!", response.message, "error");
                        }
                    },
                    error: function(xhr){
                        Swal.fire("Error!", xhr.responseJSON?.message || "Something went wrong", "error");
                    }
                });
            } else {
                // ❌ Cancel → do nothing
                checkbox.prop("checked", previousState);
            }
        });
    });



});
</script>

@endsection

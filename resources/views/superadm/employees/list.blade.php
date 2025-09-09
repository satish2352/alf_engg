@extends('superadm.layout.master')

@section('content')
    <style>
        .dataTables_wrapper {
            padding-top: 10px;
            overflow: visible;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="mb-3 d-flex justify-content-end">
                        <a href="{{ route('employees.create') }}" class="btn btn-warning">Add Employee</a>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped datatables">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th> Name</th>
                                    <th>Code</th>
                                    <th>Email</th>
                                    <th>User Name</th>
                                    <th>Reporting To</th>
                                    <th>Plant Name</th>
                                    <th>Project Name</th>
                                    <th>Department Name</th>
                                    <th>Designation</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                              
                                @foreach ($employees as $key => $data)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $data->employee_name }}</td>
                                        <td>{{ $data->employee_code }}</td>
                                        <td>{{ $data->employee_email }}</td>
                                        <td>{{ $data->employee_user_name }}</td>
                                        <td>{{ $data->reporting_name ?? '-'}}</td>
                                        <td>{{ $data->plant_name ?? '-' }}</td>
                                        <td>{{ $data->project_names ?? '-' }}</td>
                                        <td>{{ $data->department_names ?? '-' }}</td>
                                        <td>{{ $data->designation ?? '-' }}</td>
                                        <td>{{ $data->role ?? '-' }}</td>
                                        <td>
                                            <form action="{{ route('employees.updatestatus') }}" method="POST"
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
                                        <td>
                                            <?php
                                            // dd( base64_encode($data->id));
                                            // die();
                                            ?>
                                            <a href="{{ route('employees.edit', $data->id) }}"
                                                class="btn btn-primary btn-sm">Edit</a>

                                            <form action="{{ route('employees.delete') }}" method="POST"
                                                class="d-inline-block delete-form">
                                                @csrf
                                                <input type="hidden" name="id"
                                                    value="{{ base64_encode($data->id) }}">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm delete-btn">Delete</button>
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
                    // Append or update hidden input with status
                    if (form.find("input[name='is_active']").length) {
                        form.find("input[name='is_active']").val(is_active);
                    } else {
                        form.append(
                            `<input type="hidden" name="is_active" value="${is_active}">`
                        );
                    }
                    form.submit(); // submit the form
                } else {
                    // If cancelled, revert checkbox back
                    checkbox.prop("checked", !checkbox.is(":checked"));
                }
            });
        });
    </script>
@endsection

@extends('superadm.layout.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Employees List</h4>

                    <div class="mb-3 d-flex justify-content-end">
                        <a href="{{ route('employees.create') }}" class="btn btn-warning">Add Employee</a>
                    </div>


                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>User Name</th>
                                <th>Plant</th>
                                <th>Role</th>
                                <td> Status</td>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                                <tr>
                                    <td>{{ $employee->employee_code }}</td>
                                    <td>{{ $employee->employee_name }}</td>
                                    <td>{{ $employee->employee_email }}</td>
                                    <td>{{ $employee->employee_user_name }}</td>
                                    <td>{{ $employee->plant->plant_name ?? '-' }}</td>
                                    <td>{{ $employee->role->role ?? '-' }}</td>

                                    <td>
                                            <form action="{{ route('employees.updatestatus') }}" method="POST"
                                                class="d-inline-block delete-form">
                                                @csrf
                                                <label class="switch">
                                                    <input type="checkbox" class="toggle-status"
                                                        data-id="{{ base64_encode($employee->id) }}"
                                                        {{ $employee->is_active == '1' ? 'checked' : '' }}>
                                                    <span class="slider"></span>
                                                </label>


                                                <input type="hidden" name="id" value="{{ base64_encode($employee->id) }}">
                                            </form>
                                        </td>

                                    <td>
                                        <a href="{{ route('employees.edit', $employee->id) }}"
                                            class="btn btn-primary btn-sm">Edit</a>
                                        <form action="{{ route('employees.delete', $employee->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                             <input type="hidden" name="id"
                                                    value="{{ base64_encode($employee->id) }}">
                                            <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('Delete this employee?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $employees->links() }} {{-- pagination --}}
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

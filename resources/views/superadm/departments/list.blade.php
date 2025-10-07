@extends('superadm.layout.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="mb-3 d-flex justify-content-end">
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
                                        <td> {{ $data->plant_name }}</td>
                                        <td>{{ $data->department_code }}</td>
                                        <td>{{ $data->department_name }}</td>
                                        <td>{{ $data->department_short_name }}</td>
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
                if(result.isConfirmed){
                    $.ajax({
                        url: "{{ route('departments.updatestatus') }}",
                        type: "POST",
                        data: {_token: "{{ csrf_token() }}", id: id, is_active: is_active},
                        success: function(res){
                            if(res.status){
                                Swal.fire('Success!', res.message, 'success');
                            } else {
                                Swal.fire('Error!', res.message, 'error');
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
@endsection
